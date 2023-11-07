<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Login;
use App\Http\Requests\User\Upsert;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService){
        $this->authService = $authService;
    }

    public function signUp(Upsert $request){
        return $this->authService->signUp($request);
    }

    public function login(Login $request){
        return $this->authService->login($request);
    }

    public function logout(Request $request){
        return $this->authService->logout($request);
    }


}
