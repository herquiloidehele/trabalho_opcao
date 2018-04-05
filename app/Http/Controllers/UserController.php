<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\User;
use JWTAuth;

class UserController extends ModelController
{

    public function __construct() {
        $this->object = new User();
        $this->objectName = 'user';
        $this->objectNames = 'users';
        $this->relactionships = [];
    }



    public function signup(){

    }


    public function login(Request $request){

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $credencias = $request->only(['username', 'password']);

        try{
            if(! $token = JWTAuth::attempt($credencias))
                return response()->json(['mensagem' => 'Credencias Erradas'], 401);
        }catch (JWTException $ex){
            return response()->json(['mensagem' => 'Erro ao gerar token'], 500);
        }

        $user = $this->getUserFromToken($token);

        return response()->json(['token' => $token, 'user' => $user], 200);
    }



    public function logout(){

    }


    /**
     * return the user associated with the token
     */
    public function getUserFromToken($token){
        return $this->getUserKind(JWTAuth::toUser($token));
    }


    /**
     * Return the user Kind of the user: Agricultor, Revendedor ou Cadastrador
     */
    private function getUserKind($user){
        if($user->produtor)
            return $user->produtor;
        if($user->revendedor)
            return $user->revendedor;
        if ($this->cadastrador)
            return $user->revendedor;
    }

}
