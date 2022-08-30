<?php

namespace App\Http\Controllers;

use App\Models\Dev;
use App\Models\ProjectModel;
use App\Models\Template;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class TemplateController extends Controller
{
    public function index(){
        return view('template.index');
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
        $totalRecords = Template::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Template::select('count(*) as allcount')
            ->where('template', 'like', '%' . $searchValue . '%')
            ->orwhere('template_name', 'like', '%' . $searchValue . '%')
            ->orWhere('ver_build', 'like', '%' . $searchValue . '%')
            ->orWhere('Chplay_category', 'like', '%' . $searchValue . '%')
            ->orWhere('Amazon_category', 'like', '%' . $searchValue . '%')
            ->orWhere('Samsung_category', 'like', '%' . $searchValue . '%')
            ->orWhere('Xiaomi_category', 'like', '%' . $searchValue . '%')
            ->orWhere('Oppo_category', 'like', '%' . $searchValue . '%')
            ->orWhere('Vivo_category', 'like', '%' . $searchValue . '%')
            ->orWhere('Huawei_category', 'like', '%' . $searchValue . '%')
            ->count();


        // Get records, also we have included search filter as well
        $records = Template::orderBy($columnName, $columnSortOrder)
            ->where('template', 'like', '%' . $searchValue . '%')
//            ->where('template', 'TA002')
            ->orwhere('template_name', 'like', '%' . $searchValue . '%')
            ->orWhere('ver_build', 'like', '%' . $searchValue . '%')
            ->orWhere('Chplay_category', 'like', '%' . $searchValue . '%')
            ->orWhere('Amazon_category', 'like', '%' . $searchValue . '%')
            ->orWhere('Samsung_category', 'like', '%' . $searchValue . '%')
            ->orWhere('Xiaomi_category', 'like', '%' . $searchValue . '%')
            ->orWhere('Oppo_category', 'like', '%' . $searchValue . '%')
            ->orWhere('Vivo_category', 'like', '%' . $searchValue . '%')
            ->orWhere('Huawei_category', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->with('project')
            ->get();


        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="editTemplate('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Check" class="btn btn-info checkDataTemplate"><i class="ti-file"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteTemplate"><i class="ti-trash"></i></a>';


            $template = '<p style="margin: 0"><b>'.$record->template_name.'</b></p>
                            <a href="/project?q=template&id='.$record->id.'"> <span>'.$record->template.' - ('.count($record->project).')</span></a>
                            <p class="text-muted" style="margin: 0">'.$record->ver_build.'</p>';


            if($record['script_copy'] !== Null){
                $script_copy = "<span style='color:green;'> Script_copy</span> - ";
            } else {
                $script_copy = "<span style='color:red;'> Script_copy</span> - ";
            }
            if($record['script_img'] !== Null){
                $script_img = "<span style='color:green;'> IMG</span> - ";
            } else {
                $script_img = "<span style='color:red;'> IMG</span> - ";
            }
            if($record['script_svg2xml'] !== Null){
                $script_svg2xml = "<span style='color:green;'> svg2xml</span> - ";
            } else {
                $script_svg2xml = "<span style='color:red;'> svg2xml</span> - ";
            }
            if($record['script_file'] !== Null){
                $script_file = "<span style='color:green;'> File</span>";
            } else {
                $script_file = "<span style='color:red;'> File</span>";
            }
            $script = $script_copy . $script_img. $script_svg2xml .$script_file;

            $ads = json_decode($record->ads,true);

            if(isset($ads['ads_id'])){
                $ads_id = "<span style='color:green;'> Id</span> - ";
            }else{
                $ads_id = "<span style='color:red;'> Id</span> - ";
            }

            if(isset($ads['ads_banner'])){
                $ads_banner = "<span style='color:green;'> Banner</span> - ";
            }else{
                $ads_banner = "<span style='color:red;'> Banner</span> - ";
            }

            if(isset($ads['ads_inter'])){
                $ads_inter = "<span style='color:green;'> Inter</span> - ";
            }else{
                $ads_inter = "<span style='color:red;'> Inter</span> - ";
            }

            if(isset($ads['ads_reward'])){
                $ads_reward = "<span style='color:green;'> Reward</span> - ";
            }else{
                $ads_reward = "<span style='color:red;'> Reward</span> - ";
            }

            if(isset($ads['ads_native'])){
                $ads_native = "<span style='color:green;'> Native</span> - ";
            }else{
                $ads_native = "<span style='color:red;'> Native</span> - ";
            }

            if(isset($ads['ads_open'])){
                $ads_open = "<span style='color:green;'> Open</span> - ";
            }else{
                $ads_open = "<span style='color:red;'> Open</span> - ";
            }

            if(isset($ads['ads_start'])){
                $ads_start = "<span style='color:green;'> Start</span>";
            }else{
                $ads_start = "<span style='color:red;'> Start</span>";
            }
            $ads ='<br>'.$ads_id.$ads_banner.$ads_inter.$ads_reward.$ads_native.$ads_open.$ads_start;


            if($record->convert_aab != 0){
                $convert_aab = '<br>'. "<span style='color:green;'> Aab</span>";
            }else{
                $convert_aab = '<br>'. "<span style='color:red;'> Aab</span>";
            }

            if($record->startus == 0){
                $startus = '<br>'. "<span style='color:green;'> Status</span>";
            }else{
                $startus = '<br>'. "<span style='color:red;'> Status</span>";
            }

            if($record->time_create == 0 ){
                $time_create =   null;
            }else{
                $time_create =  date( 'd/m/Y',$record->time_create);
            }

            if($record->time_update == 0 ){
                $time_update =   null;
            }else{
                $time_update =  date( 'd/m/Y',$record->time_update);
            }

            if($record->time_get == 0 ){
                $time_get =   null;
            }else{
                $time_get =  date( 'd/m/Y',$record->time_get);
            }
            if(isset($record->Chplay_category)){
                $Chplay_category = 'CH Play: '.$record->Chplay_category;
            }else{
                $Chplay_category ='';
            }

            if(isset($record->Amazon_category)){
                $Amazon_category = 'Amazon: '.$record->Amazon_category;
            }else{
                $Amazon_category ='';
            }

            if(isset($record->Samsung_category)){
                $Samsung_category = 'Samsung: '.$record->Samsung_category;
            }else{
                $Samsung_category ='';
            }

            if(isset($record->Xiaomi_category)){
                $Xiaomi_category = 'Xiaomi: '.$record->Xiaomi_category;
            }else{
                $Xiaomi_category ='';
            }

            if(isset($record->Oppo_category)){
                $Oppo_category = 'Oppo: '.$record->Oppo_category;
            }else{
                $Oppo_category ='';
            }

            if(isset($record->Vivo_category)){
                $Vivo_category = 'Vivo: '.$record->Vivo_category;
            }else{
                $Vivo_category ='';
            }

            if(isset($record->Huawei_category)){
                $Huawei_category = 'Huawei: '.$record->Huawei_category;
            }else{
                $Huawei_category ='';
            }


            if ($record->link_chplay !== null){
                $link= "<a  target= _blank href='$record->link_chplay'>Link</a>";
            }
            else{
                $link = null;
            }

            if(isset($record->template_logo)){
                if (isset($record->link_store_vietmmo)){
                    $logo = "<a href='".$record->link_store_vietmmo."' target='_blank'>  <img class='rounded mx-auto d-block'  width='100px'  height='100px'  src='../uploads/template/$record->template/thumbnail/$record->template_logo'></a>";
                }else{
                    $logo = "<img class='rounded mx-auto d-block'  width='100px'  height='100px'  src='../uploads/template/$record->template/thumbnail/$record->template_logo'>";
                }
            }else{
                $logo = '<img class="rounded mx-auto d-block" width="100px" height="100px" src="assets\images\logo-sm.png">';
            }

//            $template_apk  = $record->template_apk ?  " <a href='/file-manager/ProjectApk/$record->template_apk' target='_blank' </a>" : '1111';
            $template_apk   = $record->template_apk ?  '<a href="/file-manager/download?disk=File%20Manager&path=TemplateApk%2F/'.$record->template_apk.'" class="badge badge-success" style="font-size: 12px">APK</a>' : '<span  class="badge badge-danger" style="font-size: 12px">APK</span>';
            $template_data  = $record->template_data ? '<a href="/file-manager/TemplateData/'.$record->template_data.'" class="badge badge-success" style="font-size: 12px">Data</a>' : '<span  class="badge badge-danger" style="font-size: 12px">Data</span>';



            $data_arr[] = array(
                "logo" => $logo,
                "template" => $template. '<br>'.$link.$template_apk.'  ' .$template_data ,
                "category"=>$Chplay_category.'<br>'.$Amazon_category.'<br>'.$Samsung_category.'<br>'.$Xiaomi_category.'<br>'.$Oppo_category.'<br>'.$Vivo_category.'<br>'.$Huawei_category,
                "script" => $script.$ads.$convert_aab.$startus.'<br>Package: '.$record->package,
                "time_create"=> $time_create,
                "time_update"=> $time_update,
                "time_get"=> $time_get,
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
            'template' =>'unique:ngocphandang_template,template',
            'template_data' => 'mimes:zip',
            'template_apk' => 'mimes:zip,apk'

        ];
        $message = [
            'template.unique'=>'Tên Template đã tồn tại',
            'template_data.mimes'=>'Template Data: *.zip',
            'template_apk.mimes'=>' Template APK: *.apk',
        ];


        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }

        $ads = [
            'ads_id' => $request->Check_ads_id,
            'ads_banner' => $request->Check_ads_banner,
            'ads_inter' => $request->Check_ads_inter,
            'ads_reward' => $request->Check_ads_reward,
            'ads_native' => $request->Check_ads_native,
            'ads_open' => $request->Check_ads_open,
            'ads_start' => $request->Check_ads_start,
            'ads_banner_huawei' => $request->Check_ads_banner_huawei,
            'ads_inter_huawei' => $request->Check_ads_inter_huawei,
            'ads_reward_huawei' => $request->Check_ads_reward_huawei,
            'ads_native_huawei' => $request->Check_ads_native_huawei,
            'ads_splash_huawei' => $request->Check_ads_splash_huawei,
            'ads_roll_huawei' => $request->Check_ads_roll_huawei,

        ];
        $ads =  json_encode($ads);
        $data = new Template();
        $data['template'] = $request->template;
        $data['template_name'] = $request->template_name;
        $data['ver_build'] = $request->ver_build;
        $data['script_copy'] = $request->script_copy;
        $data['script_img'] = $request->script_img;
        $data['script_svg2xml'] = $request->script_svg2xml;
        $data['script_file'] = $request->script_file;
        $data['permissions'] = $request->permissions;
        $data['policy1'] = $request->policy1;
        $data['policy2'] = $request->policy2;
        $data['time_create'] =  time();
        $data['time_update'] = time();
        $data['time_get'] = time();
        $data['note'] = $request->note;
        $data['ads'] = $ads;
        $data['package'] = $request->package;
        $data['link'] = $request->link;
        $data['convert_aab'] = $request->convert_aab;
        $data['startus'] = $request->startus;
        $data['link_store_vietmmo'] = $request->link_store_vietmmo;
        $data['Chplay_category'] =  $request->Chplay_category;
        $data['Amazon_category'] =  $request->Amazon_category;
        $data['Samsung_category'] =  $request->Samsung_category;
        $data['Xiaomi_category'] =  $request->Xiaomi_category;
        $data['Oppo_category'] =  $request->Oppo_category;
        $data['Vivo_category'] =  $request->Vivo_category;
        $data['Huawei_category'] =  $request->Huawei_category;

        if(isset($request->logo)){
            $image = $request->file('logo');
            $data['template_logo'] = 'logo_'.time().'.'.$image->extension();
            $destinationPath = public_path('uploads/template/'.$request->template.'/thumbnail/');
            $img = Image::make($image->path());
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 777, true);
            }
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.$data['template_logo']);
            $destinationPath = public_path('uploads/template/'.$request->template);
            $image->move($destinationPath, $data['template_logo']);
        }

        if($request->template_apk){
            $destinationPath = public_path('file-manager/TemplateApk/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file = $request->template_apk;
            $extension = $file->getClientOriginalExtension();
            $file_name_apk = $request->template.'.'.$extension;
            $data['template_apk'] = $file_name_apk;
            $file->move($destinationPath, $file_name_apk);
        }
        if($request->template_data){
            $destinationPath = public_path('file-manager/TemplateData/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file = $request->template_data;
            $extension = $file->getClientOriginalExtension();
            $file_name_data = $request->template.'.'.$extension;
            $data['template_data'] = $file_name_data;
            $file->move($destinationPath, $file_name_data);
        }
        $data->save();
        $allTemp  = Template::latest('id')->get();
        return response()->json([
            'success'=>'Thêm mới thành công',
            'temp' => $allTemp

        ]);
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
        $temp = Template::find($id);
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
        $id = $request->template_id;
        $rules = [
            'template' =>'unique:ngocphandang_template,template,'.$id.',id',
            'template_data' => 'mimes:zip',
            'template_apk' => 'mimes:zip,apk'
        ];
        $message = [
            'template.unique'=>'Tên template đã tồn tại',
            'template_data.mimes'=>'Template Data: *.zip',
            'template_apk.mimes'=>' Template APK: *.apk',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $ads = [
            'ads_id' => $request->Check_ads_id,
            'ads_banner' => $request->Check_ads_banner,
            'ads_inter' => $request->Check_ads_inter,
            'ads_reward' => $request->Check_ads_reward,
            'ads_native' => $request->Check_ads_native,
            'ads_open' => $request->Check_ads_open,
            'ads_start' => $request->Check_ads_start,
            'ads_banner_huawei' => $request->Check_ads_banner_huawei,
            'ads_inter_huawei' => $request->Check_ads_inter_huawei,
            'ads_reward_huawei' => $request->Check_ads_reward_huawei,
            'ads_native_huawei' => $request->Check_ads_native_huawei,
            'ads_splash_huawei' => $request->Check_ads_splash_huawei,
            'ads_roll_huawei' => $request->Check_ads_roll_huawei,
        ];

        $ads =  json_encode($ads);
        $data = Template::find($id);

        $data->ver_build = $request->ver_build;
        $data->script_copy = $request->script_copy;
        $data->script_img= $request->script_img;
        $data->script_svg2xml = $request->script_svg2xml;
        $data->script_file = $request->script_file;
        $data->permissions = $request->permissions;
        $data->policy1 = $request->policy1;
        $data->policy2 = $request->policy2;
        $data->time_update = time();
        $data->note = $request->note;
        $data->ads = $ads;
        $data->package = $request->package;
        $data->link = $request->link;
        $data->convert_aab = $request->convert_aab;
        $data->startus = $request->startus;
        $data->link_store_vietmmo = $request->link_store_vietmmo;
        $data->Chplay_category =  $request->Chplay_category;
        $data->Amazon_category =  $request->Amazon_category;
        $data->Samsung_category =  $request->Samsung_category;
        $data->Xiaomi_category =  $request->Xiaomi_category;
        $data->Oppo_category =  $request->Oppo_category;
        $data->Vivo_category =  $request->Vivo_category;
        $data->Huawei_category =  $request->Huawei_category;

        if($data->template_logo){
            if($data->template <> $request->template){
                $dir = (public_path('uploads/template/'));
                rename($dir.$data->template, $dir.$request->template);
            }
        }
        if($request->logo){
            $image = $request->file('logo');
            $data['template_logo'] = 'logo_'.time().'.'.$image->extension();
            $destinationPath = public_path('uploads/template/'.$request->template.'/thumbnail/');
            $img = Image::make($image->path());
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 777, true);
            }
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.$data['template_logo']);
            $destinationPath = public_path('uploads/template/'.$request->template);
            $image->move($destinationPath, $data['template_logo']);
        }

//        if($data->template_apk){
////            dd($data->template_apk.$request->template);
//            $dir_file = public_path('file-manager/TemplateApk/');
//            @rename($dir_file.$data->template_apk, $dir_file.$request->template.'.apk');
//            $data['template_apk'] = $request->template.'.apk';
//        }
        if($request->template_apk){
            if($data->template_apk){
                $path_Remove =  public_path('file-manager/TemplateApk/').$data->template_apk;
                if(file_exists($path_Remove)){
                    unlink($path_Remove);
                }
            }
            $destinationPath = public_path('file-manager/TemplateApk/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file = $request->template_apk;
            $extension = $file->getClientOriginalExtension();
            $file_name_apk = $request->template.'.'.$extension;
            $data->template_apk = $file_name_apk;
            $file->move($destinationPath, $file_name_apk);
        }

//        if($data->template_data){
//            $dir_file = public_path('file-manager/TemplateData/');
//            rename($dir_file.$data->template_data, $dir_file.$request->template.'.zip');
//            $data['template_data'] = $request->template.'.zip';
//        }
        if($request->template_data){
            if($data->template_data){
                $path_Remove =  public_path('file-manager/TemplateData/').$data->template_data;
                if(file_exists($path_Remove)){
                    unlink($path_Remove);
                }
            }
            $destinationPath = public_path('file-manager/TemplateData/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file = $request->template_data;
            $extension = $file->getClientOriginalExtension();
            $file_name_data = $request->template.'.'.$extension;
            $data->template_data = $file_name_data;
            $file->move($destinationPath, $file_name_data);
        }

        $data->template = $request->template;
        $data->template_name = $request->template_name;

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
        Template::find($id)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }

    public function upload()
    {
//        dd(Auth::id());
        return view('template.upload');
    }

}
