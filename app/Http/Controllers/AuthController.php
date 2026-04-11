<?php

namespace App\Http\Controllers;

use App\Models\Sentence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $fields=$request->validate([
            'email'=>['required','email'],
            'password'=>['required','string']
        ]);
        if (Auth::attempt(['email'=>$fields['email'],'password'=>$fields['password']])) {
            return redirect('/admin');

        } else {
            return redirect('/admin/login')->with("error", "Wrong Credentials");
        }
    }
    public function Logout()
    {
        Auth::logout();
        return redirect('/');
    }
    public function changePassword(Request $request)
    {
        $fields = $request->validate([
            'oldPassword' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        $user = User::first(); 
        // Check if the old password matches
        if (!Hash::check($fields['oldPassword'], $user->password)) {
            return back()->with(['oldPassword' => 'The old password is incorrect.']);
        }

        // Hash and save the new password
        $user->password = Hash::make($fields['password']);
        $user->save();

        return redirect('/admin/changePassword')->with('message', 'Password changed successfully.');
    }
    public function changeSentence(Request $request){
        $fields=$request->validate([
            'sentence'=>'required|string'
        ]);
        $sentence=Sentence::first();
        $sentence->update($fields);
        return redirect('/admin/sentence')->with('message', 'Sentence Edited Successfully');
    }
}