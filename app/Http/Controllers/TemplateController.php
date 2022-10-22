<?php

namespace App\Http\Controllers;

use App\Http\Resources\TemplateResource;
use App\Models\Dev;
use App\Models\Markets;
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
        $header = [
            'title' => 'Template',

            'button' => [
                'Create'            => ['id'=>'createNewTemplate','style'=>'primary'],
            ]

        ];
        return view('template.index')->with(compact('header'));

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
            ->orWhere('category', 'like', '%' . $searchValue . '%')
//            ->orWhere('ver_build', 'like', '%' . $searchValue . '%')
//            ->orwhereJsonContains('category', ['value' => $searchValue])
            ->count();


        // Get records, also we have included search filter as well
        $records = Template::orderBy($columnName, $columnSortOrder)
            ->where('template', 'like', '%' . $searchValue . '%')
            ->orwhere('template_name', 'like', '%' . $searchValue . '%')
            ->orWhere('ver_build', 'like', '%' . $searchValue . '%')
            ->orWhere('category', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->with('project','markets')
            ->get();


        $data_arr = array();
        foreach ($records as $record) {
            $btn =      ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editTemplate"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-info checkDataTemplate"><i class="ti-file"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteTemplate"><i class="ti-trash"></i></a>';


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
            $ads_admod = $this->array_slice_assoc($ads, ['ads_id', 'ads_banner','ads_inter','ads_reward','ads_native','ads_open']);
            $ads_start = $this->array_slice_assoc($ads, ['ads_start']);
            $ads_huawei = $this->array_slice_assoc($ads, ['ads_banner_huawei', 'ads_inter_huawei','ads_reward_huawei','ads_native_huawei','ads_splash_huawei','ads_roll_huawei']);


            if(count(array_filter($ads_admod)) != 0){
                $ads_admod_status = 'Admod: <i class="font-20 ion ion-md-checkmark-circle" style="color: green"></i>';
            }else{
                $ads_admod_status = 'Admod: <i class="font-20 ion ion-md-close-circle" style="color: red"></i>';
            }

            if(count(array_filter($ads_start)) != 0){
                $ads_start_status = 'Start: <i class="font-20 ion ion-md-checkmark-circle" style="color: green"></i>';
            }else{
                $ads_start_status = 'Start: <i class="font-20 ion ion-md-close-circle" style="color: red"></i>';
            }

            if(count(array_filter($ads_huawei)) != 0){
                $ads_huawei_status = 'Huawei: <i class="font-20 ion ion-md-checkmark-circle" style="color: green"></i>';
            }else{
                $ads_huawei_status = 'Huawei: <i class="font-20 ion ion-md-close-circle" style="color: red"></i>';
            }


            if($record->convert_aab != 0){
                $convert_aab = 'AAB: <i class="font-20 ion ion-md-checkmark-circle" style="color: green"></i>';
            }else{
                $convert_aab = 'AAB: <i class="font-20ion ion-md-close-circle" style="color: red"></i>';
            }

            if($record->status == 0){
                $status = 'Trạng thái: <i class="font-20 ion ion-md-checkmark-circle" style="color: green"></i>';
            }else{
                $status = 'Trạng thái: <i class="font-20 ion ion-md-close-circle" style="color: red"></i>';
            }

//            if($record->time_create == 0 ){
//                $time_create =   null;
//            }else{
//                $time_create =  date( 'd/m/Y',$record->time_create);
//            }
//
//            if($record->time_update == 0 ){
//                $time_update =   null;
//            }else{
//                $time_update =  date( 'd/m/Y',$record->time_update);
//            }
//
//            if($record->time_get == 0 ){
//                $time_get =   null;
//            }else{
//                $time_get =  date( 'd/m/Y',$record->time_get);
//            }

            $categories = '';

            foreach ($record->markets as $category){
                $categories .= '<p class="card-title-desc font-16"><img src="img/icon/'.$category->market_logo.'"> '.$category->pivot->value.'</p>';
            }


            if(isset($record->template_logo)){
                $logo = "<p><img class='rounded mx-auto d-block'  width='100px'  height='100px'  src='../storage/template/$record->template/$record->template_logo'></p>";

            }else{
                $logo = '<p><img class="rounded mx-auto d-block" width="100px" height="100px" src="assets\images\logo-sm.png"></p>';
            }
            $template_apk   = $record->template_apk ?  ' <a href="/storage/template/'.$record->template.'/'.$record->template_apk.'" class="badge badge-success" style="font-size: 12px">APK</a> ' : ' <span  class="badge badge-danger" style="font-size: 12px">APK</span> ';
            $template_data  = $record->template_data ? ' <a href="/storage/template/'.$record->template.'/'.$record->template_data.'" class="badge badge-success" style="font-size: 12px">Data</a> ' : ' <span  class="badge badge-danger" style="font-size: 12px">Data</span> ';

            if ($record->link !== null){
                $link= ' <a  target= _blank href="'.$record->link.'"><span  class="badge badge-success" style="font-size: 12px">Link</span></a> ';
            }
            else{
                $link = null;
            }
            $type = $record->template_type;
            switch ($type){
                case 0:
                    $type = '<span class="badge badge-secondary" style="font-size: 12px">Chưa phân loại</span>';
                    break;
                case 1:
                    $type = '<span class="badge badge-primary" style="font-size: 12px">App</span>';
                    break;
                case 2:
                    $type = '<span class="badge badge-info" style="font-size: 12px">Game</span>';
                    break;
                case 3:
                    $type = '<span class="badge badge-warning" style="font-size: 12px">Laucher & Theme	</span>';
                    break;
            }

            if($record->template_preview > 6 ){
                $preview = 6;
            }else{
                $preview = $record->template_preview;
            }
            $template_preview = '<div class="light_gallery img-list" id="light_gallery">';
            for ($i=1; $i<=$preview; $i++ ){
                $template_preview .= '<a class="img_class" style="margin:5px" href="/storage/template/'.$record->template.'/'.$i.'.jpg" title="preview '.$i.'">
                                        <img src="/storage/template/'.$record->template.'/'.$i.'.jpg" alt="preview '.$i.'" height="150">
                                    </a>';
            }

            $template_preview .='</div>';

            $data_arr[] = array(
                "id" => $record->id,
                "template_logo" => '<p class="h3 font-16"> '.$record->ver_build.' </p>'.$logo.$link .$template_apk.$template_data,
                "template" => '<span class="h3 font-16"> '.$record->template_name.' </span>'.'<p class="text-muted">'.$record->package.'</p>' .$template_preview,
                "category"=>$categories,
//                "category"=>$Chplay_category.'<br>'.$Amazon_category.'<br>'.$Samsung_category.'<br>'.$Xiaomi_category.'<br>'.$Oppo_category.'<br>'.$Vivo_category.'<br>'.$Huawei_category,
//                "script" => '<div class="text-wrap width-400">'.$script.$value_ads.$convert_aab.$status.'</div>',
                "script" => '<div class="text-wrap width-400">'.$script.'<br>'.$ads_admod_status.'<br>'.$ads_start_status.'<br>'.$ads_huawei_status.'<br>'.$convert_aab.'<br>'.$status.'</div>',
//                "time_create"=> $time_create,
//                "time_update"=> $time_update,
//                "time_get"=> $time_get,
                "template_type"=> $type,
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
            'template_files.*' =>  'mimes:zip,jpg,apk,webp',

        ];
        $message = [
            'template.unique'=>'Tên Template đã tồn tại',
            'template_files.mimes'=>'Template Data: *.zip, *.apk, *.jpg, *.webp',

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

        $categories = [];
        foreach (array_filter($request->category)  as $key=>$value){
            $categories[] = [
                'market_id' =>$key,
                'value' =>$value
            ];
        }

        $ads =  json_encode($ads);
        $data = new Template();
        $data['template'] = $request->template;
        $data['template_name'] = $request->template_name;
        $data['ver_build'] = $request->template.'_'.$request->ver_build;
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
        $data['sdk'] = $request->sdk;
        $data['convert_aab'] = $request->convert_aab;
        $data['status'] = $request->status;

        $data['category'] =  $categories;

        $path = storage_path('app/public/template/'.$request->template.'/');
        if (!file_exists($path)) {
            mkdir($path, 777, true);
        }



        if(isset($request->logo)){
            $image = $request->file('logo');
            $data['template_logo'] = 'logo.'.$image->extension();
            $img = Image::make($image->path());
            $img->resize(100, 100)
                ->save($path.$data['template_logo'],85);

        }



        if($request->template_files){
            $files= $request->template_files;
            $num_image = 0;
            foreach ($files as $file){
                $extension = $file->getClientOriginalExtension();
                switch ($extension){
                    case 'apk':
                        $file_name_apk = $data->ver_build.'.'.$extension;
                        $data['template_apk'] = $file_name_apk;
                        $file->move($path, $file_name_apk);
                        break;
                    case 'jpg'|| 'webp':
                        $fileName = $num_image+1;
                        $img = Image::make($file->path());
                        $img
                            ->resize(720, 1280)
                            ->save($path.$fileName.'.jpg',60,'jpg');
//                        $file_name_data = $fileName.'.'.$extension;
                        $data['template_preview'] = $fileName;
//                        $file->move($path, $file_name_data);
                        $num_image ++;
                        break;

                    case 'zip':
                        $file_name_data = $data->ver_build.'.'.$extension;
                        $data['template_data'] = $file_name_data;
                        $file->move($path, $file_name_data);
                        break;
                }
            }
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

        dd($request->all());
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

        if(isset(\request()->project_id)){
            $temp = Template::with(["project" => function($q){
                $q->with('markets')->where('projectid',\request()->project_id)->first();
            }])
                ->find($id);
        }else{
            $temp = Template::find($id);
        }
        return response()->json($temp->load('markets'));

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
            'template_files.*' =>  'mimes:zip,jpg,apk,webp',

        ];
        $message = [
            'template.unique'=>'Tên Template đã tồn tại',
            'template_files.mimes'=>'Template Data: *.zip, *.apk, *.jpg,*.webp',

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

        $categories = [];

        foreach (array_filter($request->category)  as $key=>$value){
            $categories[] = [
                'market_id' =>$key,
                'value' =>$value
            ];
        }



        $ads =  json_encode($ads);
        $data = Template::find($id);
        $data->ver_build = $data->template.'_'.$request->ver_build;
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
        $data->sdk = $request->sdk;
        $data->convert_aab = $request->convert_aab;
        $data->status = $request->status;
        $data->category = $categories ;

        $path = storage_path('app/public/template/'.$data->template.'/');
        if (!file_exists($path)) {
            mkdir($path, 777, true);
        }
        if($request->logo){
            $image = $request->file('logo');
            $data['template_logo'] = 'logo.'.$image->extension();
            $img = Image::make($image->path());
            $img->resize(100, 100)
                ->save($path.$data['template_logo'],85);
        }


        if($request->template_files){
            $files= $request->template_files;
            $num_image = 0;
            foreach ($files as $file){
                $extension = $file->getClientOriginalExtension();
                switch ($extension){
                    case 'apk':
                        $file_name_apk = $data->ver_build.'.'.$extension;
                        $data['template_apk'] = $file_name_apk;
                        $file->move($path, $file_name_apk);
                        break;
                    case 'jpg' || 'webp':
                        $fileName = $num_image+1;
                        $img = Image::make($file->path());
                        $img
                            ->resize(720, 1280)
//                            ->encode('jpg', 60)
                            ->save($path.$fileName.'.jpg',60,'jpg');
//                            ->save($path.$fileName,60,'jpg');
//                        $file_name_data = $fileName.'.'.$extension;
                        $data['template_preview'] = $fileName;

                        $num_image ++;
                        break;
                    case 'zip':
                        $file_name_data = $data->ver_build.'.'.$extension;
                        $data['template_data'] = $file_name_data;
                        $file->move($path, $file_name_data);
                        break;
                }
            }
        }
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
        $template = Template::find($id);
        if($template->status == 0 ){
            return response()->json(['error'=>'Template đang mở, không thể xoá.']);
        }
        elseif(count($template->project) > 0  ){
            return response()->json(['error'=>'Đang có Project sử dụng, không thể xoá.']);
        }else{
            $template->delete();
            return response()->json(['success'=>'Xóa thành công.']);
        }


//        ->delete();

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

    function array_slice_assoc($array,$keys) {
        return array_intersect_key($array,array_flip($keys));
    }




    public function convert(){
        $templates = Template::select('id','Chplay_category','Amazon_category','Samsung_category','Xiaomi_category','Oppo_category','Vivo_category','Huawei_category')->get();
        $insert = [];
        foreach ($templates as $template){
            $insert = [
                [
                    'market_id'=>1,
                    'value'=> $template->Chplay_category,
                ],
                [
                    'market_id'=>2,
                    'value'=> $template->Amazon_category,
                ],
                [
                    'market_id'=>3,
                    'value'=> $template->Samsung_category,
                ],
                [
                    'market_id'=>4,
                    'value'=> $template->Xiaomi_category,
                ],
                [
                    'market_id'=>5,
                    'value'=> $template->Oppo_category,
                ],
                [
                    'market_id'=>6,
                    'value'=> $template->Vivo_category,
                ],
                [
                    'market_id'=>7,
                    'value'=> $template->Huawei_category,
                ]
            ];

            $result=array();
            foreach($insert as $key => $value)
            {

                if(!empty($value["value"]))
                {
                    $result[]=$value;
                }
            }

            $convert = Template::find($template->id);
            $convert->update(['category'=>$result]);

        }

    }

}
