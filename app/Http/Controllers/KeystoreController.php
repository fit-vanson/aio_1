<?php

namespace App\Http\Controllers;


use App\Models\Keystore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class KeystoreController extends Controller
{
    public function index(){
        return view('keystore.index');
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
            ->orwhere('pass_keystore', 'like', '%' . $searchValue . '%')
            ->orWhere('aliases_keystore', 'like', '%' . $searchValue . '%')
            ->orWhere('pass_aliases', 'like', '%' . $searchValue . '%')
            ->orWhere('SHA_256_keystore', 'like', '%' . $searchValue . '%')
            ->orWhere('note', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Keystore::orderBy($columnName, $columnSortOrder)
            ->where('name_keystore', 'like', '%' . $searchValue . '%')
            ->orwhere('pass_keystore', 'like', '%' . $searchValue . '%')
            ->orWhere('aliases_keystore', 'like', '%' . $searchValue . '%')
            ->orWhere('pass_aliases', 'like', '%' . $searchValue . '%')
            ->orWhere('SHA_256_keystore', 'like', '%' . $searchValue . '%')
            ->orWhere('note', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->with('project',
                'project_chplay',
                'project_amazon',
                'project_samsung',
                'project_xiaomi',
                'project_oppo',
                'project_vivo',
                'project_huawei'
            )
            ->get()->toArray();
        $data_arr = array();
        foreach ($records as $record) {
            $data = array_merge(
                $record['project'],
                $record['project_chplay'],
                $record['project_amazon'],
                $record['project_samsung'],
                $record['project_xiaomi'],
                $record['project_oppo'],
                $record['project_vivo'],
                $record['project_huawei']
            );
            $btn = ' <a href="javascript:void(0)" onclick="editKeytore('.$record['id'].')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record['id'].'" data-original-title="Delete" class="btn btn-danger deleteKeystore"><i class="ti-trash"></i></a>';

            $html = '../uploads/keystore/'.$record['file'];
            $data_arr[] = array(
//                "name_keystore" => $record->name_keystore,
//                "name_keystore" => '<a href="/project?q=key_store&id='.$record->name_keystore.'"> <span>'.$record->name_keystore.' - ('.$project.')</span></a>',
                "name_keystore" => '<a href="/project?q=key_store&id='.$record['name_keystore'].'"> <span>'.$record['name_keystore'].' - ('.count($this->unique_multidim_array($data,'projectid')).')</span></a>',
                "pass_keystore" => $record['pass_keystore'],
                "aliases_keystore" => $record['aliases_keystore'],
                "SHA_256_keystore" => $record['SHA_256_keystore'],
                "pass_aliases" => $record['pass_aliases'],
                "file" => '<a href="'.$html.'">'.$record['file'].'</a>',
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
            'keystore_file' => 'mimes:zip,jks'
        ];
        $message = [
            'name_keystore.unique'=>'Tên Keystore đã tồn tại',
            'keystore_file.mimes'=>'Định dạng File: *.zip, *.jks',
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
        $data['note'] = $request->note;

        $destinationPath = public_path('uploads/keystore/');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        if(isset($request->keystore_file)){
            $file = $request->file('keystore_file');
            $extension = $file->getClientOriginalExtension();
            $data['file'] = $request->name_keystore.'_'.time().'.'.$extension;
            $file->move($destinationPath, $data['file']);
        }

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
            'keystore_file' => 'mimes:zip,jks'
        ];
        $message = [
            'name_keystore.unique'=>'Tên Keystore đã tồn tại',
            'keystore_file.mimes'=>'Định dạng File: *.zip, *.jks',
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
        if(isset($request->keystore_file)){
            if (isset($data->file)){
                $path_Remove =   public_path('uploads/keystore/').$data->file;
                if(file_exists($path_Remove)){
                    unlink($path_Remove);
                }
            }


            $file = $request->file('keystore_file');
            $extension = $file->getClientOriginalExtension();
            $data['file'] = $request->name_keystore.'_'.time().'.'.$extension;
            $destinationPath = public_path('uploads/keystore/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file->move($destinationPath, $data['file']);
        }
        $data->name_keystore = $request->name_keystore;
        $data->pass_keystore = $request->pass_keystore;
        $data->aliases_keystore= $request->aliases_keystore;
        $data->pass_aliases= $request->pass_aliases;
        $data->SHA_256_keystore = $request->SHA_256_keystore;
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
        if($keystore->file){
            $path    =   public_path('uploads/keystore/').$keystore->file;
            if(file_exists($path)){
                unlink($path);
            }
        }
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
