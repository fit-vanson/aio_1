<?php

namespace App\Http\Controllers;


use App\Http\Resources\KeystoresResource;
use App\Models\Keystore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class KeystoreController extends Controller
{
    public function index(){

        $header = [
            'title' => 'Quản lý Keystore',

            'button' => [
                'Create'            => ['id'=>'createNewKeystore','style'=>'primary'],
                'Multiple'   => ['id'=>'createMultiple','style'=>'warning'],
            ]

        ];
        return view('keystore.index')->with(compact('header'));
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
        $totalRecords = Keystore::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Keystore::select('count(*) as allcount')
            ->where('name_keystore', 'like', '%' . $searchValue . '%')
//            ->orwhere('pass_keystore', 'like', '%' . $searchValue . '%')
//            ->orWhere('aliases_keystore', 'like', '%' . $searchValue . '%')
//            ->orWhere('pass_aliases', 'like', '%' . $searchValue . '%')
            ->orWhere('SHA_256_keystore', 'like', '%' . $searchValue . '%')
            ->orWhere('SHA_1_keystore', 'like', '%' . $searchValue . '%')
            ->orWhere('note', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Keystore::orderBy($columnName, $columnSortOrder)
            ->select('id','name_keystore','pass_keystore','aliases_keystore','SHA_256_keystore','SHA_1_keystore','note')
//            ->where('name_keystore', 'k16006')
            ->where('name_keystore', 'like', '%' . $searchValue . '%')
//            ->orwhere('pass_keystore', 'like', '%' . $searchValue . '%')
//            ->orWhere('aliases_keystore', 'like', '%' . $searchValue . '%')
//            ->orWhere('pass_aliases', 'like', '%' . $searchValue . '%')
            ->orWhere('SHA_1_keystore', 'like', '%' . $searchValue . '%')
            ->orWhere('SHA_256_keystore', 'like', '%' . $searchValue . '%')
            ->orWhere('note', 'like', '%' . $searchValue . '%')
            ->with('market_project.dev')
            ->withCount('market_project')
            ->skip($start)
            ->take($rowperpage)
            ->get();
//        dd($records);

        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="editKeytore('.$record['id'].')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-id="'.$record['id'].'" class="btn btn-danger deleteKeystore"><i class="ti-trash"></i></a>';

            $devs = [];
            foreach ($record->market_project as $dev_project){
//
                $devs[$dev_project->dev_id] = @$dev_project->dev->dev_name;
            }
            $dev ='';
            foreach ($devs as $key=>$value){
                $dev .= ' <span class="badge badge-success">'.@$value.'</span> ';
            }

            $data_arr[] = array(
                "name_keystore" => '<div>'.$record['name_keystore'].'</div>',
                "id" => $record['id'],
                "market_project_count" => $record['market_project_count'],
                "pass_keystore" => '<b>Pass:</b> <span class="copyButton">'.$record['pass_keystore'].'</span><br><b>Alias: </b><span class="copyButton">'.$record['aliases_keystore'].'</span>',
                "SHA_1_keystore" => '<b class="truncate">SHA 1:</b><span class="truncate copyButton">'.$record['SHA_1_keystore'].'</span><br><b class="truncate">SHA 256:</b> <span class="truncate copyButton">'.$record['SHA_256_keystore'].'</span>',
                "dev" => '<div class="text-wrap width-400">'.$dev.'</div>',
                "note"=> $record['note'],
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
            'name_keystore' =>'unique:ngocphandang_keystores,name_keystore',
//            'keystore_file' => 'mimes:zip,jks'
        ];
        $message = [
            'name_keystore.unique'=>'Tên Keystore đã tồn tại',
//            'keystore_file.mimes'=>'Định dạng File: *.zip, *.jks',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new Keystore();
        $data['name_keystore'] = $request->name_keystore;
        $data['pass_keystore'] = $request->pass_keystore;
        $data['aliases_keystore'] = $request->aliases_keystore;
        $data['pass_aliases'] = $request->pass_aliases;
        $data['SHA_256_keystore'] = $request->SHA_256_keystore;
        $data['SHA_1_keystore'] = $request->SHA_1_keystore;
        $data['file'] = $request->keystore_file;
        $data['note'] = $request->note;

//        $destinationPath = public_path('uploads/keystore/');
//        if (!file_exists($destinationPath)) {
//            mkdir($destinationPath, 0777, true);
//        }
//        if(isset($request->keystore_file)){
//            $file = $request->file('keystore_file');
//            $extension = $file->getClientOriginalExtension();
//            $data['file'] = $request->name_keystore.'_'.time().'.'.$extension;
//            $file->move($destinationPath, $data['file']);
//        }

        $data->save();
        $allKeys  = Keystore::latest('id')->get();
        return response()->json([
            'success'=>'Thêm mới thành công',
            'keys' => $allKeys

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
        $keystore = Keystore::find($id);
        return response()->json($keystore);
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


        $id = $request->keystore_id;
        $rules = [
            'name_keystore' =>'unique:ngocphandang_keystores,name_keystore,'.$id.',id',
//            'keystore_file' => 'mimes:zip,jks'
        ];
        $message = [
            'name_keystore.unique'=>'Tên Keystore đã tồn tại',
//            'keystore_file.mimes'=>'Định dạng File: *.zip, *.jks',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }

        $data = Keystore::find($id);
//        if($data->name_keystore <> $request->name_keystore){
//            $dir = (public_path('uploads/keystore/'));
//            rename($dir.$data->file, $dir.$request->name_keystore);
//        }

        $data->name_keystore = $request->name_keystore;
        $data->pass_keystore = $request->pass_keystore;
        $data->aliases_keystore= $request->aliases_keystore;
        $data->pass_aliases= $request->pass_aliases;
        $data->SHA_256_keystore = $request->SHA_256_keystore;
        $data->SHA_1_keystore = $request->SHA_1_keystore;
        $data->file = $request->keystore_file;
        $data->note = $request->note;
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
        $keystore = Keystore::find($id);
//        if($keystore->file){
//            $path    =   public_path('uploads/keystore/').$keystore->file;
//            if(file_exists($path)){
//                unlink($path);
//            }
//        }
        $keystore->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }

    public function updateMultiple(Request $request)
    {
        $data = explode("\r\n",$request->KeystoreMultiple);
        foreach (array_filter($data) as $item){
            [$nameKeystore, $passKeystore, $Aliases, $passAliases, $sha256] = explode("|",$item);

            Keystore::updateOrCreate(
                [
                    "name_keystore" => trim($nameKeystore),
                ],
                [
                    "pass_keystore" => trim($passKeystore),
                    'aliases_keystore' => trim($Aliases),
                    'pass_aliases' => trim($passAliases),
                    'SHA_256_keystore' => trim($sha256),
                ]);
        }
        return response()->json(['success'=>'Thêm mới thành công.']);
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }

    function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }



}
