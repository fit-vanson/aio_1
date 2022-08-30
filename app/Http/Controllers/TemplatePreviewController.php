<?php

namespace App\Http\Controllers;

use App\Models\CategoryTemplate;
use App\Models\CategoryTemplateFrame;
use App\Models\DataProfile;
use App\Models\tbl_font;
use App\Models\Template;
use App\Models\TemplatePreview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use RarArchive;
use ZipArchive;

class TemplatePreviewController extends Controller
{
    public function index(){
//        $fonts = scandir('data/fonts');
//        array_shift($fonts);
//        array_shift($fonts);
//        $dataArr = array();
//        foreach ($fonts as $font){
//            [$name, $ex ] = explode('.',$font);
//            $dataArr[] = array(
//                'name' => $name,
//                'file' => $font
//            );
//        }
//        tbl_font::insert($dataArr);
//
//        dd($dataArr);

        $fonts = tbl_font::all();
        $categoyTemplateFrame =  CategoryTemplateFrame::latest('id')->get();
        $categoyTemplateText =  CategoryTemplate::latest('id')->where('category_template_parent',0)->get();
        $dataFiles =  DataProfile::latest('id')->get();
        return view('template-preview.index',compact([
            'categoyTemplateFrame',
            'categoyTemplateText',
            'dataFiles',
            'fonts'
        ]));
    }

    public function getIndex(Request $request)
    {

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value


        // Total records
        $totalRecords = TemplatePreview::select('count(*) as allcount')->count();
        $totalRecordswithFilter = TemplatePreview::select('count(*) as allcount')
            ->where('tp_name', 'like', '%' . $searchValue . '%')
            ->orWhere('tp_sc', 'like', '%' . $searchValue . '%')
            ->count();


        // Get records, also we have included search filter as well
        $records = TemplatePreview::orderBy($columnName, $columnSortOrder)
            ->with('CategoryTemplate')
            ->select('*',DB::raw("sum( IF(tp_script_1 = '',0,1)  + IF(tp_script_2 = '',0,1)  + IF(tp_script_3 = '',0,1)  + IF(tp_script_4 = '',0,1)  + IF(tp_script_5 = '',0,1)  + IF(tp_script_6 = '',0,1)  + IF(tp_script_7 = '',0,1)  + IF(tp_script_8 = '',0,1)  ) AS sum_script") )
            ->where('tp_name', 'like', '%' . $searchValue . '%')
            ->orWhere('tp_sc', 'like', '%' . $searchValue . '%')
            ->orwhereHas('CategoryTemplate', function ($q) use ($searchValue) {
                $q->where('category_template_frames_name', 'like', '%' . $searchValue . '%');
                    })
            ->groupBy('tp_name')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="editTemplatePreview('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteTemplatePreview"><i class="ti-trash"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" onclick="buildTemplatePreview('.$record->id.')" class="btn btn-secondary"><i class="ti-settings"></i></a>';
//            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-secondary buildTemplatePreview"><i class="ti-settings"></i></a>';
            $logo = "<img class='rounded mx-auto d-block'  height='150px' src='/file-manager/TemplatePreview/logo/$record->tp_logo'>";
            $data_arr[] = array(
                "tp_logo" => $logo,
                "tp_name" => $record->tp_name,
                "sum_script" => $record->sum_script,
                "tp_category" => $record->CategoryTemplate? $record->CategoryTemplate->category_template_frames_name :"",
                "tp_sc" => "<a href='/file-manager/TemplatePreview/".$record->tp_sc."' target='_blank'>$record->tp_sc</a>",
                "action"=> $btn,
            );
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);
    }

    public function create(Request  $request)
    {
        $rules = [
            'tp_name' =>'unique:template_previews,tp_name',
            'tp_sc' => 'required|mimes:zip,rar',

        ];
        $message = [
            'tp_name.unique'=>'Tên đã tồn tại',
            'tp_sc.mimes'=>'Định dạng file: *.zip',
            'tp_sc.required'=>'File không để trống',
//            'logo.required'=>'Logo không để trống',
        ];

        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }

        $data = new TemplatePreview();
        $data['tp_name'] = $request->tp_name;
        $data['tp_script'] = $request->tp_script ? $request->tp_script : '';
        $data['tp_script_1'] = $request->tp_script_1 ? $request->tp_script_1 : '';
        $data['tp_script_2'] = $request->tp_script_2 ? $request->tp_script_2 : '';
        $data['tp_script_3'] = $request->tp_script_3 ? $request->tp_script_3 : '';
        $data['tp_script_4'] = $request->tp_script_4 ? $request->tp_script_4 : '';
        $data['tp_script_5'] = $request->tp_script_5 ? $request->tp_script_5 : '';
        $data['tp_script_6'] = $request->tp_script_6 ? $request->tp_script_6 : '';
        $data['tp_script_7'] = $request->tp_script_7 ? $request->tp_script_7 : '';
        $data['tp_script_8'] = $request->tp_script_8 ? $request->tp_script_8 : '';
        $data['tp_black'] = $request->tp_black ? 1 : 0;
        $data['tp_blue'] = $request->tp_blue ? 1 : 0;
        $data['tp_while'] = $request->tp_while ? 1 : 0;
        $data['tp_pink'] = $request->tp_pink ? 1 : 0;
        $data['tp_yellow'] = $request->tp_yellow ? 1 : 0;
        $data['tp_category'] = $request->category_template;
        $data['tp_data'] = $request->tp_data;


        if($request->tp_sc){
            $destinationPath = public_path('file-manager/TemplatePreview/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file = $request->tp_sc;
            $extension = $file->getClientOriginalExtension();
            $tp_sc = $request->tp_name.'.'.$extension;
            $data['tp_sc'] = $tp_sc;
            $file->move($destinationPath, $tp_sc);
        }
        $destinationPathLogo = public_path('file-manager/TemplatePreview/logo/');
        if (!file_exists($destinationPathLogo)) {
            mkdir($destinationPathLogo, 0777, true);
        }
        if($request->logo){
            $file = $request->logo;
            $extension = $file->getClientOriginalExtension();
            $tp_logo = $request->tp_name.time().'.'.$extension;
            $data['tp_logo'] = $tp_logo;
            $file->move($destinationPathLogo, $tp_logo);
        }else{
            $srcfile = public_path('img/frame_demo.png');
            copy($srcfile, $destinationPathLogo.$request->tp_name.time().'.png');
            $data['tp_logo'] = $request->tp_name.time().'.png';
        }
        $data->save();
        return response()->json(['success'=>'Thêm mới thành công']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $temp = TemplatePreview::find($id);
        return response()->json($temp);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $id = $request->tp_id;
        $rules = [
            'tp_name' =>'unique:template_previews,tp_name,'.$id.',id',
            'tp_sc' => 'mimes:zip',


        ];
        $message = [
            'tp_name.unique'=>'Tên đã tồn tại',
            'tp_sc.mimes'=>'Định dạng file: *.zip',
            'tp_sc.required'=>'Trường không để trống',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = TemplatePreview::find($id);
        $data->tp_script = $request->tp_script ? $request->tp_script : '';
        $data->tp_script_1 = $request->tp_script_1 ? $request->tp_script_1 : '';
        $data->tp_script_2 = $request->tp_script_2 ? $request->tp_script_2 : '';
        $data->tp_script_3 = $request->tp_script_3 ? $request->tp_script_3 : '';
        $data->tp_script_4 = $request->tp_script_4 ? $request->tp_script_4 : '';
        $data->tp_script_5 = $request->tp_script_5 ? $request->tp_script_5 : '';
        $data->tp_script_6 = $request->tp_script_6 ? $request->tp_script_6 : '';
        $data->tp_script_7 = $request->tp_script_7 ? $request->tp_script_7 : '';
        $data->tp_script_8 = $request->tp_script_8 ? $request->tp_script_8 : '';
        $data->tp_black = $request->tp_black ? 1 : 0;
        $data->tp_blue= $request->tp_blue ? 1 : 0;
        $data->tp_while = $request->tp_while ? 1 : 0;
        $data->tp_pink = $request->tp_pink ? 1 : 0;
        $data->tp_yellow = $request->tp_yellow ? 1 : 0;
        $data->tp_category = $request->category_template;
        $data->tp_data = $request->tp_data;

        $destinationPath = public_path('file-manager/TemplatePreview/');
        $destinationPathLogo = public_path('file-manager/TemplatePreview/logo/');
        $destinationPathData = public_path('file-manager/TemplatePreview/data/');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        if (!file_exists($destinationPathLogo)) {
            mkdir($destinationPathLogo, 0777, true);
        }

        if($request->tp_sc ){
            $path_Remove = public_path('file-manager/TemplatePreview/') . $data->tp_sc;
            if (file_exists($path_Remove)) {
                unlink($path_Remove);
            }
            $file = $request->tp_sc;
            $extension = $file->getClientOriginalExtension();
            $tp_sc = $request->tp_name.'.'.$extension;
            $data['tp_sc'] = $tp_sc;
            $file->move($destinationPath, $tp_sc);
        }


        if($request->logo ){
            $path_Remove_logo = public_path('file-manager/TemplatePreview/logo/') . $data->tp_logo;
            if (file_exists($path_Remove_logo)) {
                unlink($path_Remove_logo);
            }
            $file = $request->logo;
            $extension = $file->getClientOriginalExtension();
            $logo = $request->tp_name.time().'.'.$extension;
            $data['tp_logo'] = $logo;
            $file->move($destinationPathLogo, $logo);
        }

        if($data->tp_name != $request->tp_name){
            $file= pathinfo($destinationPath.$data->tp_sc);
            $logo= pathinfo($destinationPathLogo.$data->tp_logo);
            rename($destinationPathLogo.$data->tp_logo, $destinationPathLogo.$request->tp_name.time().'.'.$logo['extension']);
            rename($destinationPath.$data->tp_sc, $destinationPath.$request->tp_name.'.'.$file['extension']);
            $data['tp_sc'] = $request->tp_name.'.'.$file['extension'];
            $data['tp_logo'] = $request->tp_name.time().'.'.$logo['extension'];

        }
        $data->tp_name = $request->tp_name;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $data = TemplatePreview::find($id);
        $path_Remove = public_path('file-manager/TemplatePreview/') . $data->tp_sc;
        if (file_exists($path_Remove)) {
            unlink($path_Remove);
        }
        $path_Remove_logo = public_path('file-manager/TemplatePreview/logo/') . $data->tp_logo;
        if (file_exists($path_Remove_logo)) {
            unlink($path_Remove_logo);
        }
        $data->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }


    public function extract_file($file_path, $to_path = "./")
    {
        $file_type = $file_path->getClientOriginalExtension();
        if ("zip" === $file_type) {
            $xmlZip = new ZipArchive();
            if ($xmlZip->open($file_path)) {
                $xmlZip->extractTo($to_path);
                return true;
            } else {
                echo "extract fail";
                return false;
            }
        } elseif ("rar" == $file_type) {

            $archive = RarArchive::open($file_path);
            $entries = $archive->getEntries();
            if ($entries) {
                foreach ($entries as $entry) {
                    $entry->extract($to_path);
                }
                $archive->close();
                return true;
            }else{
                echo "extract fail";
                return false;
            }
        }


    }
}
