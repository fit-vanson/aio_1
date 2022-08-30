<?php

namespace App\Http\Controllers;

use App\Models\CategoryTemplate;
use App\Models\CategoryTemplateFrame;
use App\Models\TemplatePreview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryTemplateFrameController extends Controller
{
    public function index(){
        $categoyTemplateFrame =  CategoryTemplateFrame::latest('id')->get();
        return view('category-template-frame.index',compact('categoyTemplateFrame'));
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
        $totalRecords = CategoryTemplateFrame::select('count(*) as allcount')->count();
        $totalRecordswithFilter = CategoryTemplateFrame::select('count(*) as allcount')
            ->where('category_template_frames_name', 'like', '%' . $searchValue . '%')
            ->count();


        // Get records, also we have included search filter as well
        $records = CategoryTemplateFrame::orderBy($columnName, $columnSortOrder)
//            ->select('*',DB::raw("sum( IF(tp_script_1 = '',0,1)  + IF(tp_script_2 = '',0,1)  + IF(tp_script_3 = '',0,1)  + IF(tp_script_4 = '',0,1)  + IF(tp_script_5 = '',0,1)  + IF(tp_script_6 = '',0,1)  + IF(tp_script_7 = '',0,1)  + IF(tp_script_8 = '',0,1)  ) AS sum_script") )
            ->where('category_template_frames_name', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="editCategoryTemplateFrame('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteCategoryTemplateFrame"><i class="ti-trash"></i></a>';

            if($record->parent){
                $parent = $record->parent->category_template_name;
            }else{
                $parent = $record->category_template_name;
            }
            $data_arr[] = array(
                "id " => $record->id ,
                "category_template_frames_name" => $record->category_template_frames_name,

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
            'category_template_name' =>'unique:category_template_frames,category_template_frames_name',
        ];
        $message = [
            'category_template_name.unique'=>'Tên đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new CategoryTemplateFrame();
        $data['category_template_frames_name'] = $request->category_template_name;
        $data->save();
        $allCateTemp  = CategoryTemplateFrame::latest()->get();


        return response()->json([
            'success'=>'Thêm mới thành công',
            'cate_temp' => $allCateTemp
        ]);

    }
    public function edit($id)
    {
        $temp = CategoryTemplateFrame::find($id);

        return response()->json($temp);
    }
    public function update(Request $request){
        $id = $request->category_template_id;

        $rules = [
            'category_template_name' =>'unique:category_template_frames,category_template_frames_name,'.$id.',id',

        ];
        $message = [
            'category_template_name.unique'=>'Tên đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = CategoryTemplateFrame::find($id);
        $data->category_template_frames_name = $request->category_template_name;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id){
        $data = CategoryTemplateFrame::find($id);

        $data->delete();
        return response()->json(['success'=>'Xóa thành công.']);

    }
    public function getTemp($id){
        $tempPreview = TemplatePreview::where('tp_category',$id)->get();
        $frame = TemplatePreview::find($id);
        return response()->json([
            'success'=>'Thêm mới thành công',
            'tempPreview' => $tempPreview,
            'frame' => $frame
        ]);

    }
}
