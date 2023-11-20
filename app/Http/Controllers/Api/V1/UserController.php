<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Update;
use App\Http\Requests\User\Upsert;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(){
        return $this->userService->resource();
    }

    public function show(User $user){
        return $this->userService->resource();
    }

    public function update(Upsert $request, string $ulid){
        return $this->userService->update($request,$ulid);
    }
}
