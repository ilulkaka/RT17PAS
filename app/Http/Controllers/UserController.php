<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function authenticate(Request $request)//: RedirectResponse
    {
        $credentials = $request->validate([
            'nik' => ['required'],
            'password' => ['required'],

        ]);

        if (Auth::attempt(['nik' => $request->nik, 'password' => $request->password, 'setatus' => 'on'])) {
            $request->session()->regenerate();
            //$request->session()->keep('name', 'test');
            //session(['key' => 'value']);
            //Session::put('name', 'test');
            //$request->session()->save();
            //dd('Login success');
            //return redirect()->intended('/dashboard');

            $user = Auth::user();
            $abilities = [];
            $i =0;
            foreach($user->roles as $role){
                foreach($role->permissions as $permit){
                    $abilities[$i]= $permit->name;
                    $i++;
                }
            }

            // foreach ($user->departments as $departement){
            //     $abilities[$i]= $departement->section;
            //     $i++;
            // }

            return [
                'token' => $user->createToken("API_TOKEN",$abilities)->plainTextToken,
                'user' => $user,
                'message' => 'Authorization Successful!',
                'success' => true,
            ];
        }

        return [
            'message' => 'Authorization failed!',
            'success' => false,
        ];
    }

    public function logout(Request $request){

        if (!Auth::check()) {
            return [
                'message' => 'Logout gagal!',
                'success' => false,
            ];
        }
        $user = Auth::user();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $user->tokens()->delete();
        return redirect('/');
        // return [
        //     'message' => 'Logout Successful!',
        //     'success' => true,
        // ];
    }

    public function sesion(Request $request){
        dd(Auth::user());
        //dd(Session::get('name'));
    }
}
