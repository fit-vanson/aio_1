<?php

namespace App\Http\Controllers;


use App\Models\CategoryTemplate;
use App\Models\TemplatePreview;
use App\Models\TemplateTextPr;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;



class TemplateTextPrController extends Controller
{
    public function index(){
        $categoyTemplate =  CategoryTemplate::latest('id')->where('category_template_parent',0)->get();
        $categoyTemplateChild =  CategoryTemplate::latest('id')->where('category_template_parent','<>',0)->get();
        return view('template-text-preview.index',compact([
            'categoyTemplate','categoyTemplateChild']));
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
        $totalRecords = TemplateTextPr::select('count(*) as allcount')->count();
        $totalRecordswithFilter = TemplateTextPr::select('count(*) as allcount')
            ->where('tt_name', 'like', '%' . $searchValue . '%')
            ->orWhere('tt_file', 'like', '%' . $searchValue . '%')
            ->count();


        // Get records, also we have included search filter as well
        $records = TemplateTextPr::orderBy($columnName, $columnSortOrder)
            ->with('CategoryTemplate')
            ->where('tt_name', 'like', '%' . $searchValue . '%')
            ->orWhere('tt_file', 'like', '%' . $searchValue . '%')
            ->orWhere('tt_text_1', 'like', '%' . $searchValue . '%')
            ->orWhere('tt_text_2', 'like', '%' . $searchValue . '%')
            ->orWhere('tt_text_3', 'like', '%' . $searchValue . '%')
            ->orWhere('tt_text_4', 'like', '%' . $searchValue . '%')
            ->orWhere('tt_text_5', 'like', '%' . $searchValue . '%')
            ->orWhere('tt_text_6', 'like', '%' . $searchValue . '%')
            ->orWhere('tt_text_7', 'like', '%' . $searchValue . '%')
            ->orWhere('tt_text_8', 'like', '%' . $searchValue . '%')
            ->orwhereHas('CategoryTemplate', function ($q) use ($searchValue) {
                $q->where('category_template_name', 'like', '%' . $searchValue . '%');
            })
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="editTemplateTextPreview('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteTemplateTextPreview"><i class="ti-trash"></i></a>';
            $logo = "<img class='rounded mx-auto d-block'  height='150px' src='/file-manager/TemplateTextPreview/logo/$record->tt_logo'>";

            $data_arr[] = array(
                "tt_logo" => $logo,
                "tt_name" => $record->tt_name,
                "tt_file" =>  "<a href='/file-manager/TemplateTextPreview/".$record->tt_file."' target='_blank'>$record->tt_file</a>",
                "tt_category" => $record->CategoryTemplate ? $record->CategoryTemplate->category_template_name : 'null',
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
            'tt_name' =>'unique:template_text_prs,tt_name',
//            'tt_file' => 'required|mimes:zip,rar',
//            'logo' => 'required',

        ];
        $message = [
            'tt_name.unique'=>'Tên đã tồn tại',
            'tt_file.mimes'=>'Định dạng file: *.zip',
//            'tt_file.required'=>'File không để trống',
//            'logo.required'=>'Logo không để trống',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new TemplateTextPr();
        $data['tt_name'] = $request->tt_name;
        $data['tt_category'] = $request->category_template_child;
        $data['tt_text_1'] = $request->tt_text_1 ? $request->tt_text_1 : '';
        $data['tt_text_2'] = $request->tt_text_2 ? $request->tt_text_2 : '';
        $data['tt_text_3'] = $request->tt_text_3 ? $request->tt_text_3 : '';
        $data['tt_text_4'] = $request->tt_text_4 ? $request->tt_text_4 : '';
        $data['tt_text_5'] = $request->tt_text_5 ? $request->tt_text_5 : '';
        $data['tt_text_6'] = $request->tt_text_6 ? $request->tt_text_6 : '';
        $data['tt_text_7'] = $request->tt_text_7 ? $request->tt_text_7 : '';
        $data['tt_text_8'] = $request->tt_text_8 ? $request->tt_text_8 : '';

        if($request->tt_file){
            $destinationPath = public_path('file-manager/TemplateTextPreview/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file = $request->tt_file;
            $extension = $file->getClientOriginalExtension();
            $tt_file = $request->tt_name.'.'.$extension;
            $data['tt_file'] = $tt_file;
            $file->move($destinationPath, $tt_file);
        }else{
            $data['tt_file'] = 'null';
        }

        $destinationPathLogo = public_path('file-manager/TemplateTextPreview/logo/');
        if (!file_exists($destinationPathLogo)) {
            mkdir($destinationPathLogo, 0777, true);
        }
        if($request->logo){

            $file = $request->logo;
            $extension = $file->getClientOriginalExtension();
            $tt_logo = $request->tt_name.time().'.'.$extension;
            $data['tt_logo'] = $tt_logo;
            $file->move($destinationPathLogo, $tt_logo);
        }else{
            $srcfile = public_path('img/text_demo.png');
            copy($srcfile, $destinationPathLogo.$request->tt_name.time().'.png');
            $data['tt_logo'] = $request->tt_name.time().'.png';
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
        $temp = TemplateTextPr::with('CategoryTemplate')->find($id);
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
        $id = $request->tt_id;
        $rules = [
            'tt_name' =>'unique:template_text_prs,tt_name,'.$id.',id',
            'tt_file' => 'mimes:zip,rar',
        ];
        $message = [
            'tt_name.unique'=>'Tên đã tồn tại',
            'tt_file.mimes'=>'Định dạng file: *.zip',
        ];

        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = TemplateTextPr::find($id);
        $destinationPath = public_path('file-manager/TemplateTextPreview/');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $destinationPathLogo = public_path('file-manager/TemplateTextPreview/logo/');
        if (!file_exists($destinationPathLogo)) {
            mkdir($destinationPathLogo, 0777, true);
        }
        if($request->tt_file ){
            $path_Remove = public_path('file-manager/TemplateTextPreview/') . $data->tt_file;
            if (file_exists($path_Remove)) {
                unlink($path_Remove);
            }
            $file = $request->tt_file;
            $extension = $file->getClientOriginalExtension();
            $tt_file = $request->tt_name.'.'.$extension;
            $data['tt_file'] = $tt_file;
            $file->move($destinationPath, $tt_file);
        }
        if($request->logo ){
            $path_Remove = public_path('file-manager/TemplateTextPreview/logo/') . $data->tt_logo;
            if (file_exists($path_Remove)) {
                unlink($path_Remove);
            }
            $logo = $request->logo;
            $extension = $logo->getClientOriginalExtension();
            $tt_logo = $request->tt_name.time().'.'.$extension;
            $data['tt_logo'] = $tt_logo;
            $logo->move($destinationPathLogo, $tt_logo);
        }
        if($data->tt_name != $request->tt_name){
            $file = pathinfo($destinationPath.$data->tt_file);
            $logo =  pathinfo($destinationPathLogo.$data->tt_logo);
            rename($destinationPath.$data->tt_file, $destinationPath.$request->tt_name.'.'.$file['extension']);
            rename($destinationPathLogo.$data->tt_logo, $destinationPathLogo.$request->tt_name.time().'.'.$logo['extension']);
            $data['tt_file'] = $request->tt_name.'.'.$file['extension'];
            $data['tt_logo'] = $request->tt_name.time().'.'.$logo['extension'];
        }

        $data->tt_name = $request->tt_name;
        $data->tt_category = $request->category_template_child;
        $data->tt_text_1 = $request->tt_text_1 ? $request->tt_text_1 : '';
        $data->tt_text_2 = $request->tt_text_2 ? $request->tt_text_2 : '';
        $data->tt_text_3 = $request->tt_text_3 ? $request->tt_text_3 : '';
        $data->tt_text_4 = $request->tt_text_4 ? $request->tt_text_4 : '';
        $data->tt_text_5 = $request->tt_text_5 ? $request->tt_text_5 : '';
        $data->tt_text_6 = $request->tt_text_6 ? $request->tt_text_6 : '';
        $data->tt_text_7 = $request->tt_text_7 ? $request->tt_text_7 : '';
        $data->tt_text_8 = $request->tt_text_8 ? $request->tt_text_8 : '';
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
        $data = TemplateTextPr::find($id);
        $path_Remove = public_path('file-manager/TemplateTextPreview/') . $data->tt_file;
        if (file_exists($path_Remove)) {
            unlink($path_Remove);
        }
        $path_Remove_logo = public_path('file-manager/TemplateTextPreview/logo/') . $data->tt_logo;
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
