<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class RegistrationController extends Controller
{
    public function Registration(){
        $this->validate(request(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $password = bcrypt(request('password'));
        $email = request('email');
        $login = User::create(['email' => $email, 'password' => $password]);
        if($login){
            return response([
                'message' => 'now you can login'
            ],200);
        }else{
            return response([
                'message' => 'oops tryt again'
            ],500);
        }
    }
}
