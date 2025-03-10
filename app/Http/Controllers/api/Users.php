<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class Users extends Controller
{

//=============================User=====================

    public function getUserByNik ($nik){
        $datas = DB::table('tb_karyawan')->select('nama','email')->where('nik',$nik)->where('status_karyawan','!=','Off')->first();
        if ($datas){
            return [
                'datas' => $datas,
                'success' => true,
            ];
        } else {
            return [
                'success' => false,
                'message' => 'NIK tidak ditemukan .',
            ];
        }
    }

    public function userlist(Request $request){
        // dd($request->all());
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $users = User::with('departments','roles')->where(function ($q) use ($search) {
            $q
                ->where('name', 'like', '%' . $search . '%')
                ->orWhere('nik', 'like', '%' . $search . '%');
        })
        ->skip($start)
        ->take($length)
        ->get();

        $count = User::where(function ($q) use ($search) {
            $q
                ->where('name', 'like', '%' . $search . '%')
                ->orWhere('nik', 'like', '%' . $search . '%');
        })->count();

        // dd($users);
        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $users,
        ];
    }

    public function createuser(Request $request){
        
        $check = User::where('nik',$request->nik)->orWhere('name',$request->nama)->count();

        if($check > 0){
            return [
                "success" => false,
                "message" => "Data sudah ada!",
            ];
        }
        $status = "on";
        if (!$request->exists('status')) {
            $status = "off";
        }
        $create = User::create([
            "name"=> $request->nama,
            "email" => $request->email,
            "nik" => date('His'),
            "password" => Hash::make($request->password),
            "setatus" => $status,
        ]);

        if(!$create->id){
            return [
                "success" => false,
                "message" => "Simpan data gagal !",
            ];
        }
        
        $create->departments()->attach($request->department);
        $create->roles()->attach($request->role);
   
        return [
            "success" => true,
            "message" => "Berhasil create Permission",
        ];
    }

    public function del_user($id){
        $del = User::destroy($id);

        if(!$del){
            return [
                "success" => false,
                "message" => "Hapus data gagal !",
            ];
        }
        return [
            "success" => true,
            "message" => "Hapus data berhasil",
        ];
    }

    public function edit_user(Request $request){
        $user = User::find($request->id_user);
        if ($user === null) {
            return [
                "success" => false,
                "message" => "Edit data gagal!",
            ];
        }
        $status = "on";
        if (!$request->exists('status')) {
            $status = "off";
        }
        $user->name = $request->nama;
        $user->email = $request->email;
        //$user->nik = $request->nik;
        $user->setatus = $status;
        //$user->setatus = $request->setatus;
        if ($user->isDirty()) {
            $user->save();
            
        }

        $user->departments()->sync($request->department);
        $user->roles()->sync($request->role);
        return [
            "success" => true,
            "message" => "Edit data berhasil",
        ];
    }

    public function resetpassword(Request $request){
        $user = User::find($request->id);
        if ($user === null) {
            return [
                "success" => false,
                "message" => "Edit data gagal!",
            ];
        }

        $user->password = Hash::make($request->password);
        $user->save();
        return [
            "success" => true,
            "message" => "Edit data berhasil",
        ];
    }

    public function update_pass(Request $request){
        $user = $request->user();
        $old = $request->passlama;
        if ($request->newpass1 != $request->newpass2) {
            return [
                "success" => false,
                "message" => "Password baru tidak sama!",
            ];
        }
        if (strlen($request->newpass1) < 6) {
            return [
                "success" => false,
                "message" => "Password kurang dari 6 karakter!",
            ];
        }
        if (Hash::check($old,$user->password)) {
           $user->password = Hash::make($request->newpass1);
           $user->save();
            return [
                "success" => true,
                "message" => "Update Password berhasil",
            ];
        }
        return [
            "success" => false,
            "message" => "Update password gagal!",
        ];

    }

    public function n_info_upd(Request $request){
       if ($request->user()->flag == 0) {
        $last_info_upd = DB::table('tb_info_update')
                ->select('created_at', 'deskripsi')
                ->orderBy('created_at', 'desc')
                ->first();
            return [
                'message' => 'flag = 0 ',
                'success' => true,
                'deskripsi' => $last_info_upd->deskripsi,
            ];
        } else {
            return [
                'message' => 'flag = 1 ',
                'success' => false,
            ];
        }
    }

    public function c_info_upd(Request $request){
        $request->user()->flag = 1;
        $request->user()->save();
        return [
            'message' => 'flag = 1',
            'success' => true,
        ];
    }

//====================department======================
    public function deptlist(Request $request){
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $dept = Department::where(function ($q) use ($search) {
            $q
                ->where('dept', 'like', '%' . $search . '%')
                ->orWhere('group', 'like', '%' . $search . '%')
                ->orWhere('section', 'like', '%' . $search . '%');
        })
        ->skip($start)
        ->take($length)
        ->get();

        $count = Department::where(function ($q) use ($search) {
            $q
            ->where('dept', 'like', '%' . $search . '%')
            ->orWhere('group', 'like', '%' . $search . '%')
            ->orWhere('section', 'like', '%' . $search . '%');
        })->count();


        // dd($users);
        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $dept,
        ];
    }

    public function createdept(Request $request){

        $check = Department::where('section',$request->section)->count();

        if($check > 0){
            return [
                "success" => false,
                "message" => "Data sudah ada!",
            ];
        }
        $create = Department::create([
            "dept"=> strtoupper($request->dept),
            "group"=> strtoupper($request->group),
            "section"=> strtoupper($request->section),
            "jenis"=> strtoupper($request->jenis),
        ]);

        if(!$create->id){
            return [
                "success" => false,
                "message" => "Simpan data gagal !",
            ];
        }
        return [
            "success" => true,
            "message" => "Berhasil create Department",
        ];
    }

    public function del_dept($id){
        $del = Department::destroy($id);

        if(!$del){
            return [
                "success" => false,
                "message" => "Hapus data gagal !",
            ];
        }
        return [
            "success" => true,
            "message" => "Hapus data berhasil",
        ];
    }

    public function edit_dept(Request $request){
        $dept = Department::find($request->id_dept);
        if ($dept === null) {
            return [
                "success" => false,
                "message" => "Edit data gagal!",
            ];
        }
        $dept->dept = strtoupper($request->dept);
        $dept->group = strtoupper($request->group);
        $dept->section = strtoupper($request->section);
        $dept->jenis = strtoupper($request->jenis);
        $dept->save();
        return [
            "success" => true,
            "message" => "Edit data berhasil",
        ];
    }

    public function getAllDept(Request $request){
        $dept = Department::get();
        return [
            "success"=> true,
            "data"=>$dept,
        ];
    }

//======================Role=============================
    public function rolelist(Request $request){
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $role = Role::with('permissions')->where(function ($q) use ($search) {
            $q
                ->where('name', 'like', '%' . $search . '%');
        })
        ->skip($start)
        ->take($length)
        ->get();

        $count = Role::where(function ($q) use ($search) {
            $q
                ->where('name', 'like', '%' . $search . '%');
        })->count();

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $role,
        ];
    }

    public function createrole(Request $request){
        //dd($request->all());
        $check = Role::where('name',$request->nama)->count();

        if($check > 0){
            return [
                "success" => false,
                "message" => "Data sudah ada!",
            ];
        }
        $create = Role::create([
            "name"=> strtolower($request->nama),
        ]);

        if(!$create->id){
            return [
                "success" => false,
                "message" => "Simpan data gagal !",
            ];
        }

            $create->permissions()->attach($request->permit);
   
        return [
            "success" => true,
            "message" => "Berhasil create Permission",
        ];
    }

    public function edit_role(Request $request){
        $role = Role::find($request->id_role);
        if ($role === null) {
            return [
                "success" => false,
                "message" => "Edit data gagal!",
            ];
        }
        $role->name = strtolower($request->nama);
        $role->save();
        $role->permissions()->sync($request->permit);
        return [
            "success" => true,
            "message" => "Edit data berhasil",
        ];
    }

    public function del_role($id){
        $del = Role::destroy($id);

        if(!$del){
            return [
                "success" => false,
                "message" => "Hapus data gagal !",
            ];
        }
        return [
            "success" => true,
            "message" => "Hapus data berhasil",
        ];
    }

    public function getAllRole(){
        $role = Role::get();
        return [
            "success"=> true,
            "data"=>$role,
        ];
    }

//=====================Permission=======================

    public function permissionlist(Request $request){
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $permit = Permission::where(function ($q) use ($search) {
            $q
                ->where('name', 'like', '%' . $search . '%');
        })
        ->skip($start)
        ->take($length)
        ->get();

        $count = Permission::where(function ($q) use ($search) {
            $q
                ->where('name', 'like', '%' . $search . '%');
        })->count();

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $permit,
        ];
    }

    public function getAllPermission(){
        $permit = Permission::get();
        return [
            "success"=> true,
            "data"=>$permit,
        ];
    }

    public function createpermit(Request $request){
        $check = Permission::where('name',$request->nama)->count();

        if($check > 0){
            return [
                "success" => false,
                "message" => "Data sudah ada!",
            ];
        }
        $create = Permission::create([
            "name"=> strtolower($request->nama),
        ]);

        if(!$create->id){
            return [
                "success" => false,
                "message" => "Simpan data gagal !",
            ];
        }
        return [
            "success" => true,
            "message" => "Berhasil create Permission",
        ];
    }

    public function edit_permit(Request $request){
        $permit = Permission::find($request->id_permit);
        if ($permit=== null) {
            return [
                "success" => false,
                "message" => "Edit data gagal!",
            ];
        }
        $permit->name = strtolower($request->nama);
        $permit->save();
        return [
            "success" => true,
            "message" => "Edit data berhasil",
        ];
    }

    public function del_permit($id){
        $del = Permission::destroy($id);

        if(!$del){
            return [
                "success" => false,
                "message" => "Hapus data gagal !",
            ];
        }
        return [
            "success" => true,
            "message" => "Hapus data berhasil",
        ];
    }

}
