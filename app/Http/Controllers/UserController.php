<?php

namespace App\Http\Controllers;


use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    private $user;
    public $role;
    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = $this->role->all();
        $users = User::with('roles')->latest();
        if ($request->ajax()) {
            $data = User::latest()->get();
//            $data = User::with('roles')->latest();

            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" onclick="editUser('.$row->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger deleteUser"><i class="ti-trash"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Reset" class="btn btn-secondary reset2FAUser"><i class="mdi mdi-lock-reset"></i></a>';

                    return $btn;
                })
                ->addColumn('2fa', function($row){
                    $googleAuthenticator = new \PHPGangsta_GoogleAuthenticator();
                    $secretCode = $row->secret_code;
                    if($secretCode == null){
                        return '<span class="badge badge-danger">Chưa kích hoạt 2FA</span>';
                    }else{
                        return $googleAuthenticator->getCode($secretCode);
                    }
                })

                ->rawColumns(['action','2fa'])
                ->make(true);
        }

        return view('user.index',compact(['users','roles']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request  $request)
    {

        if (! Gate::allows('user-add')) {
            abort(403);
        }

        $rules = [
            'name' =>'unique:users,name,',
            'email' =>'unique:users,email',
            'password'=>'required'
        ];
        $message = [
            'namea.unique'=>'Tên người dùng đã tồn tại',
            'email.unique'=>'Email đã tồn tại',
            'password.required' => 'Mật khẩu không để trống.'
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $user = $this->user->create([
            'name' => $request->name,
            'email'=> $request->email,
            'part_time'=> $request->part_time ? 1:0,
            'password' =>bcrypt($request->password),

        ]);
        $roleIds = $request->role_id;
        $user->roles()->attach($roleIds);
        DB::commit();
        return response()->json(['success'=>'Thêm mới thành công']);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message :' . $exception->getMessage() . '--- Line: ' . $exception->getLine());

        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//
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
        $user = User::find($id);
        $roleOfUser = $user->roles;
        return response()->json([$user,$roleOfUser]);
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

        $id = $request->user_id;
        $rules = [
            'name' =>'unique:users,name,'.$id.',id',
            'email' =>'unique:users,email,'.$id.',id',
        ];
        $message = [
            'name.unique'=>'Tên người dùng đã tồn tại',
            'email.unique'=>'Email đã tồn tại',
        ];
//
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            if($request->password){
                $this->user->find($id)->update([
                    'name' => $request->name,
                    'email'=> $request->email,
                    'part_time'=> $request->part_time ? 1:0,
                    'password' =>bcrypt($request->password),
                ]);
            }
            $this->user->find($id)->update([
                'name' => $request->name,
                'email'=> $request->email,
                'part_time'=> $request->part_time ? 1:0,
            ]);
            $roleIds = $request->role_id;
            $user = $this->user->find($id);
            $user->roles()->sync($roleIds);
            DB::commit();
            return response()->json(['success'=>'Cập nhật thành công']);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message :' . $exception->getMessage() . '--- Line: ' . $exception->getLine());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {

        User::find($id)->delete();
        return response()->json(['success'=>'Xóa người dùng.']);
    }

    public function reset2fa($id)
    {
        $user= User::find($id);
        $user->secret_code = null;
        $user->save();
        return response()->json(['success'=>'Reset 2FA thành công.']);
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }

}
