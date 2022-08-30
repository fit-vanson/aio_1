<?php

namespace App\Http\Controllers;

use App\Models\Profile;

use App\Models\ProfileCompany;
use App\Models\ProfileV2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function index()
    {
//        $ga_name = Ga::orderBy('ga_name','asc')->get();
//        $ga_dev = Ga_dev::orderBy('gmail','asc')->get();
        return view('profile.index');
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
        $totalRecords = ProfileCompany::select('count(*) as allcount')->count();
        $totalRecordswithFilter = ProfileCompany::with('profile')
            ->where('name_en', 'like', '%' . $searchValue . '%')
            ->orwhere('name_vi', 'like', '%' . $searchValue . '%')
            ->orwhere('mst', 'like', '%' . $searchValue . '%')
            ->orWhere('dia_chi', 'like', '%' . $searchValue . '%')
            ->orWhereHas('profile', function($query) use ($searchValue) {
                $query->orwhere('profile_name', 'like', '%' . $searchValue . '%')
                    ->orwhere('profile_ho_va_ten', 'like', '%' . $searchValue . '%')
                    ->orwhere('profile_cccd', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Get records, also we have included search filter as well
        $records = ProfileCompany::with('profile')
            ->orderBy($columnName, $columnSortOrder)
            ->where('name_en', 'like', '%' . $searchValue . '%')
            ->orwhere('name_vi', 'like', '%' . $searchValue . '%')
            ->orwhere('mst', 'like', '%' . $searchValue . '%')
            ->orWhere('dia_chi', 'like', '%' . $searchValue . '%')
            ->orWhereHas('profile', function($query) use ($searchValue) {
                $query->where('profile_name', 'like', '%' . $searchValue . '%')
                    ->orwhere('profile_ho_va_ten', 'like', '%' . $searchValue . '%')
                    ->orwhere('profile_cccd', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
            try {
                $btn = '';
//                $btn = ' <a href="javascript:void(0)" onclick="editProfile('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
                $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteProfile"><i class="ti-trash"></i></a>';
                $data_arr[] = array(
                    "ma_profile" => $record->profile->profile_name.' - '.$record->profile->profile_ho_va_ten. ' - '.$record->profile->profile_cccd. ' - '.$record->profile->profile_sex. ' - '.$record->profile->profile_add ,
                    "name_en" => $record->name_en. '<p class="text-muted">'.$record->name_vi .'</p>',
                    "mst" => $record->mst,
                    "dia_chi" => $record->dia_chi,
                    "ngay_thanh_lap" => $record->ngay_thanh_lap,
                    "action"=> $btn,
                );
            }catch (\Exception $exception) {
                Log::error('Message:' . $exception->getMessage() . '--- get Profile: ---' . $exception->getLine());
            }

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
            'profile_name' =>'unique:ngocphandang_profiles,profile_name',
            'logo' =>'required',

        ];
        $message = [
            'profile_name.unique'=>'Tên đã tồn tại',
            'logo.required'=>'Vui lòng chọn Logo',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }

        $data = new Profile();
        $destinationPath_file = public_path('uploads/profile/file/');
        if (!file_exists($destinationPath_file)) {
            mkdir($destinationPath_file, 0777, true);
        }
        $destinationPath_logo = public_path('uploads/profile/logo/');
        if (!file_exists($destinationPath_logo)) {
            mkdir($destinationPath_logo, 0777, true);
        }
        $data['profile_name'] = $request->profile_name;
        $data['profile_ho_ten'] = $request->profile_ho_ten;
        $data['profile_sdt'] = $request->profile_sdt;
        $data['profile_dia_chi'] = $request->profile_dia_chi;
        $data['profile_cccd'] = $request->profile_cccd;
        $data['profile_note'] = $request->profile_note;
        $data['profile_attribute'] = $request->attribute;
        $data['profile_anh_cccd'] = $request->profile_anh_cccd ? 1 :0 ;
        $data['profile_anh_bang_lai'] = $request->profile_anh_bang_lai ? 1 :0 ;
        $data['profile_anh_ngan_hang'] = $request->profile_anh_ngan_hang ? 1 :0 ;

        $img_file  = $request->logo;
        $img = Image::make($img_file);
        $extension = $img_file->getClientOriginalExtension();
        $imgName = $request->profile_name.'_'.time().'.'.$extension;
        $img->save($destinationPath_logo.$imgName);
        $data['profile_logo'] = $imgName;

        if($request->profile_file){
            $destinationPath = public_path('uploads/profile/file/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }


            $file = $request->profile_file;
            $extension = $file->getClientOriginalExtension();
            $file_name = $request->profile_name.'.'.$extension;
            $data['profile_file'] = $file_name;
            $file->move($destinationPath, $file_name);
        }

        $data->save();
        return response()->json([
            'success'=>'Thêm mới thành công',
        ]);
    }
    public function edit($id)
    {
        $data = Profile::find($id);
        return response()->json($data);

    }
    public function update(Request $request)
    {
        $id = $request->Profile_id;
        $rules = [
            'profile_name' =>'unique:ngocphandang_profiles,profile_name,'.$id.',id',
        ];
        $message = [
            'profile_name.unique'=>'Tên đã tồn tại',
        ];

        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = Profile::find($id);
        $data->profile_name = $request->profile_name;
        $data->profile_ho_ten = $request->profile_ho_ten;
        $data->profile_sdt = $request->profile_sdt;
        $data->profile_dia_chi = $request->profile_dia_chi;
        $data->profile_cccd = $request->profile_cccd;
        $data->profile_note = $request->profile_note;
        $data->profile_attribute = $request->attribute;
        $data->profile_anh_cccd = $request->profile_anh_cccd ? 1 :0 ;
        $data->profile_anh_bang_lai = $request->profile_anh_bang_lai ? 1 :0 ;
        $data->profile_anh_ngan_hang = $request->profile_anh_ngan_hang ? 1 :0 ;
        if($request->logo){
            $path_Remove =  public_path('uploads/profile/logo/').$data->profile_logo;
            if(file_exists($path_Remove)){
                unlink($path_Remove);
            }
            $destinationPath_logo = public_path('uploads/profile/logo/');
            if (!file_exists($destinationPath_logo)) {
                mkdir($destinationPath_logo, 0777, true);
            }


            $img_file  = $request->logo;
            $img = Image::make($img_file);
            $extension = $img_file->getClientOriginalExtension();
            $imgName = $request->profile_name.'_'.time().'.'.$extension;
            $img->save($destinationPath_logo.$imgName);
            $data['profile_logo'] = $imgName;
        }
        if($request->profile_file){
            if($data->profile_file){
                $path_Remove =  public_path('uploads/profile/file/').$data->profile_file;
                if(file_exists($path_Remove)){
                    unlink($path_Remove);
                }
            }
            $destinationPath = public_path('uploads/profile/file/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file = $request->profile_file;

            $extension = $file->getClientOriginalExtension();
            $file_name = $request->profile_name.'.'.$extension;
            $data->profile_file = $file_name;
            $file->move($destinationPath, $data->profile_file);
        }
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id)
    {
        $profile = ProfileCompany::find($id);
//        if($profile->profile_logo){
//            $path_image =   public_path('uploads/profile/logo/').$profile->profile_logo;
//            unlink($path_image);
//        }
//        if($profile->profile_file){
//            $path_file  =   public_path('uploads/profile/file/').$profile->profile_file;
//            unlink($path_file);
//        }
        $profile->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }
    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }


    public function create_v2(Request $request)
    {
        if($request->attribute1 ==1){
            $data = explode("\r\n",$request->profile_multiple);
            foreach ($data as $item){
                try {
                    [$name,$cccd,$ho_ten, $birth, $sex, $add, $ngay_cap] = explode('|',$item);

                    ProfileV2::updateOrCreate(
                        [
                            'profile_name' => $name
                        ],
                        [
                            'profile_ho_va_ten' => $ho_ten,
                            'profile_cccd' => $cccd,
                            'profile_ngay_cap' => $ngay_cap[0].$ngay_cap[1].'/'.$ngay_cap[2].$ngay_cap[3].'/'.$ngay_cap[4].$ngay_cap[5].$ngay_cap[6].$ngay_cap[7],
                            'profile_ngay_sinh' => $birth[0].$birth[1].'/'.$birth[2].$birth[3].'/'.$birth[4].$birth[5].$birth[6].$birth[7],
                            'profile_sex' => $sex,
                            'profile_add' => $add,
                        ]
                    );
                }catch (\Exception $exception) {
                    Log::error('Message:' . $exception->getMessage() . '--- insert Multiple Profile: ---' . $exception->getLine());
                }
            }
        }
        if($request->attribute1 == 0){
            $data = explode("\r\n",$request->profile_multiple);
            foreach ($data as $item){
                try {
                    [$name,$name_vi,$name_en, $mst, $ngay_thanh_lap, $dia_chi] = explode('|',$item);

                    $id_name = ProfileV2::where('profile_name',$name)->first();
                    try {
                        ProfileCompany::updateOrCreate(
                            [
                                'ma_profile' => $id_name->id,
                                'mst' => $mst,
                            ],
                            [
                                'name_vi' => $name_vi,
                                'name_en' => $name_en,
                                'ngay_thanh_lap' => $ngay_thanh_lap,
                                'dia_chi' => $dia_chi,
                            ]
                        );
                    }catch (\Exception $exception) {
                        Log::error('Message:' . $exception->getMessage() . '--- insert No Profile: ---' . $exception->getLine());
                    }
                }catch (\Exception $exception) {
                    Log::error('Message:' . $exception->getMessage() . '--- insert Multiple Company: ---' . $exception->getLine());
                }
            }
        }
        return response()->json([
            'success'=>'Thêm mới thành công',
        ]);
    }

    public function show(Request $request)
    {
        if (isset($request->profileID)){
            $profile = ProfileV2::with('company')->where('profile_name',$request->profileID)->first();
            if(isset($profile)){
                return response()->json([
                    'success'=>'Thành công.',
                    'profile'=> $profile,
                    ]);
            }else{
                return response()->json(['error'=>'sai ma']);
            }
        }elseif (isset($request->ID)){
            $profile = ProfileV2::with('company')->find($request->ID);
            if(isset($profile)){
                return response()->json([
                    'success'=>'Thành công.',
                    'profile'=> $profile,
                ]);
            }else{
                return response()->json(['error'=>'sai ma']);
            }
        }else{
            return view('profile.show'  );
        }


    }



}
