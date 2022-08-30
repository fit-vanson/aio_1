<?php

namespace App\Http\Controllers;

use App\Jobs\DeleteFileBuild;
use App\Models\CategoryTemplate;
use App\Models\DataProfile;
use App\Models\tbl_font;
use App\Models\TemplatePreview;
use App\Models\TemplateTextPr;
use FFMpeg\Filters\Frame\FrameFilters;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use ZipArchive;


class BuildPreviewController extends Controller
{

    const COORDINATES = [
        'logoCenterX' => 0,
        'logoCenterY' => 5,
        'logoLeftX' => 5,
        'logoLeftY' => 0,
        'textCenterX' => 61,
        'textCenterY' => 37,
        'textLeftX' => 77,
        'textLeftY' => 21,
        'textOnlyX' => 0,
        'textOnlyY' => 0,
    ];

    //Định nghĩa size logo sẽ thực hiện resize
    const RESIZE_LOGO_SIZE = [
        'widthLeft' => 27,
        'heightLeft' => 27,
        'widthCenter' => 37,
        'heightCenter' => 15,
    ];

    public function index(){
        $categoyTemplate =  CategoryTemplate::latest('id')->where('category_template_parent',0)->get();
        return view('category-template.index',compact('categoyTemplate'));
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
        $totalRecords = CategoryTemplate::select('count(*) as allcount')->count();
        $totalRecordswithFilter = CategoryTemplate::select('count(*) as allcount')
            ->where('category_template_name', 'like', '%' . $searchValue . '%')
            ->count();


        // Get records, also we have included search filter as well
        $records = CategoryTemplate::orderBy($columnName, $columnSortOrder)
            ->with('parent')
//            ->select('*',DB::raw("sum( IF(tp_script_1 = '',0,1)  + IF(tp_script_2 = '',0,1)  + IF(tp_script_3 = '',0,1)  + IF(tp_script_4 = '',0,1)  + IF(tp_script_5 = '',0,1)  + IF(tp_script_6 = '',0,1)  + IF(tp_script_7 = '',0,1)  + IF(tp_script_8 = '',0,1)  ) AS sum_script") )
            ->where('category_template_name', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="editCategoryTemplate('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteCategoryTemplate"><i class="ti-trash"></i></a>';

            if($record->parent){
                $parent = $record->parent->category_template_name;
            }else{
                $parent = $record->category_template_name;
            }
            $data_arr[] = array(
                "id " => $parent ,
                "category_template_name" => $record->category_template_name,
                "category_template_parent" => $parent,
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
//dd($request->all());
        $rules = [
            'file_data' => 'mimes:zip',
        ];
        $message = [
            'file_data.mimes'=>'File *.zip',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $folder = Str::orderedUuid();
        $outData = public_path('file-manager/BuildTemplate/'.$folder.'/');
        $color_text = $request->color_text ? $request->color_text : 'Blue';


        $job =  (new  DeleteFileBuild($outData))->delay(Carbon::now()->addHours(2));
        dispatch($job);

        if($request->template_frame){
            $frame = TemplatePreview::find($request->template_frame);
        }else{
            if($request->template_frame_preview != 0){
                $frame = TemplatePreview::find($request->template_frame_preview);
            }else{
                $frame = TemplatePreview::where('tp_category',$request->category_template_frame)->inRandomOrder()->first();
            }
        }

        if ($request->template123 == 'template_availavble'){
            if($request->template_text_preview != 0){
                $text = TemplateTextPr::find($request->template_text_preview);
            }else{
                $text = TemplateTextPr::where('tt_category',$request->category_child_template_text)->inRandomOrder()->first();
            }
            $srcDataText = public_path('file-manager/TemplateTextPreview/'.$text->tt_file);
            $this->extract_file($srcDataText, $outData);
        }

        if ($request->data123 == 'data_custom'){
            $this->extract_file($request->file_data,$outData);
        }
        if ($request->data123 == 'data_availavble'){
            $dataFile = DataProfile::find($frame->tp_data);
            $srcData = public_path('file-manager/dataFile/'.$dataFile->data_file);
            $this->extract_file($srcData, $outData);
        }
        $srcDataPr = public_path('file-manager/TemplatePreview/'.$frame->tp_sc);
        $this->extract_file($srcDataPr, $outData);

        copy('data/output.png', $outData.'/output.png');
        $tempFrame = json_decode(json_encode($frame), true);

        for ($i = 1; $i<=6; $i++ ){
            if($request->template123 == 'template_custom' ){
                copy('data/text.png', $outData.'/text_'.$i.'.png');
            }elseif ($request->template123 == 'template_availavble'){
                copy($outData.$color_text .'/text_'.$i.'.png', $outData.'/text_'.$i.'.png');
            }
            foreach(preg_split("/((\r?\n)|(\r\n?))/", $tempFrame['tp_script_'.$i]) as $line){
                $tempScript= explode('|',$line);

                [$width ,$height ]= explode(':',$tempScript[2]);
                if($tempScript[1] == 'resize'){
                    Image::make($outData.$tempScript[0])->resize($width,$height)->save($outData.'/'.$tempScript[3]);
                }elseif ($tempScript[1] == 'overlay'){
                    [$tp1 ,$tp2 ]= explode('/',$tempScript[0]);
                    if($request->template123 == 'template_custom' ){
                        $fileFont = tbl_font::find($request->font_name);
                        $fileFontSmall = tbl_font::find($request->font_name_small);
                        $uploadClientName = $request->text_to[$i-1];
                        $uploadClientName1 = $request->text_nho[$i-1];
                        $size = $request->font_size;
                        $size1 = $request->font_size_small;
                        Image::make($outData.$tp1)->insert($outData.$tp2, 'top-left', $width,$height)
                        ->text($uploadClientName, 540,150, function($font) use($fileFont, $request, $size){
                            $font->file(public_path("data/fonts/$fileFont->file"));
                            $font->size($size);
                            $font->color($request->colorpicker);
                            $font->align('center');
                            $font->valign('middle');
                        })->text($uploadClientName1, 540,$size+150, function($font) use($fileFontSmall, $request, $size1){
                                $font->file(public_path("data/fonts/$fileFontSmall->file"));
                                $font->size($size1);
                                $font->color($request->colorpicker_small);
                                $font->align('center');
                                $font->valign('middle');
                        })
                            ->save($outData.'/'.$tempScript[3]);
                    }elseif ($request->template123 == 'template_availavble'){
                        Image::make($outData.$tp1)->insert($outData.$tp2, 'top-left', $width,$height)
                            ->save($outData.'/'.$tempScript[3]);
                    }
                }
            }
            Image::make($outData.'/output.png')
                ->insert($outData.'/pr_'.$i.'.jpg', 'top-left', 1080*($i-1), 0)
                ->save($outData.'/output.png');
        }


        return response()->json([
            'success'=>'Thêm mới thành công',
            'out' => $folder.'/output.png',
        ]);

    }
    public function edit($id)
    {
        $temp = CategoryTemplate::find($id);
        $cateParent =  CategoryTemplate::latest()->where('category_template_parent',0)->get();
        return response()->json([$temp,$cateParent] );
    }
    public function update(Request $request){
        $id = $request->category_template_id;
        $rules = [
            'category_template_name' =>'unique:category_templates,category_template_name,'.$id.',id',

        ];
        $message = [
            'category_template_name.unique'=>'Tên đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = CategoryTemplate::find($id);
        $data->category_template_name = $request->category_template_name;
        $data->category_template_parent = $request->category_template_parent ? $request->category_template_parent : 0;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id){
        $data = CategoryTemplate::find($id);
        if($data->category_template_parent == 0 ){
            $cateParent = CategoryTemplate::where('category_template_parent',$id)->count();
            if($cateParent ==0){
                $data->delete();
                return response()->json(['success'=>'Xóa thành công.']);
            }else{
                return response()->json(['errors'=>'Đang tồn tại cate child, không thể xoá.']);
            }

        }else{
            $data->delete();
            return response()->json(['success'=>'Xóa thành công.']);
        }
    }
    public function getCateTempParent($id){
        $cateParent = CategoryTemplate::where('category_template_parent',$id)->get();
        $cateName = CategoryTemplate::find($id);
        $textPreview = TemplateTextPr::where('tt_category',$id)->get();
        $text = TemplateTextPr::find($id);
        return response()->json([
            'success'=>'Thêm mới thành công',
            'cateParent' => $cateParent,
            'cateName' => $cateName,
            'textPreview' => $textPreview,
            'text' => $text,
        ]);

    }


    public function extract_file($file_path, $to_path = "./")
    {
//        dd($file_path);
//        $file_type = $file_path->getClientOriginalExtension();
//        if ("zip" === $file_type) {
        $xmlZip = new ZipArchive();
        if ($xmlZip->open($file_path)) {
            $xmlZip->extractTo($to_path);
            return true;
        } else {
            echo "extract fail";
            return false;
        }
//        } elseif ("rar" == $file_type) {
//
//            $archive = RarArchive::open($file_path);
//            $entries = $archive->getEntries();
//            if ($entries) {
//                foreach ($entries as $entry) {
//                    $entry->extract($to_path);
//                }
//                $archive->close();
//                return true;
//            }else{
//                echo "extract fail";
//                return false;
//            }
//        }
    }
}
