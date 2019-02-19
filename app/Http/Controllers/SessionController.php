<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\token;
use App\User;
class SessionController extends Controller
{
    private $token = "";
    public function token(){
        $i = 0;
        $t = 0;
        $id;
        $characters = "qwertyuiopasdfghjklzxcvbnm147258369";
        $characters_count = strlen($characters);

        while($i <= 11){
            $rand = mt_rand(0,33);
            $this->token = $this->token . substr($characters,$rand,1);
            $i++;
            $t++;
            if($t == 3 || $t == 6 || $t == 9 ){
                $this->token = $this->token . "-";
            }
        }
        return $this->token;
    }
    public function logout($token){
        $token_sha = hash('sha1',$token);
        token::where('token',$token_sha)->update(['revoke' => 1]);
        auth()->logout();
        return response([
            'message' => 'logout successful'
        ],200);
    }
    public function login(){
        if(auth()->attempt(request(["email","password"]))){
            $email = request('email');
            $user = Auth::user();
            $this->id = (string)$user->id;
            $tokenPlaneText = (string)$this->token();
            $this->token = hash('sha1', $tokenPlaneText);
            $revoke = 0;
            $create = token::create(['user_id' => $this->id,'token' => $this->token ,'revoke' => $revoke]);
            $token_id = $create->id;
            $user_token = User::where('email',$email)->update(["token_id" => $token_id]);
            return response([
                'token' => $tokenPlaneText
            ],200);
        } else{
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }
    }
    public function renew($token){
        $token_sha = hash('sha1',$token);
        token::where('token',$token_sha)->update(['revoke' => 1]);
        $user_id = token::where('token',$token_sha)->first()->user_id;
        $revoke = 0;
        $tokenPlaneText = (string)$this->token();
        $this->token = hash('sha1', $tokenPlaneText);
        $create = token::create(['user_id' => $user_id,'token' => $this->token ,'revoke' => $revoke]);
        $token_id = $create->id;
        $user_token = User::where('id',$user_id)->update(["token_id" => $token_id]);
        return response([
            'token' => $tokenPlaneText
        ],200);
    }
}
