<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthenticationController extends Controller
{
    protected $authenticationService;
    public function __construct(AuthenticationService $authenticationService){
        $this->authenticationService = $authenticationService;
    }

    public function register (Request $request) {

        $result = ['status' => 200];
        $data = $request->toArray();

        try{
            $result['data'] = $this->authenticationService->registerNewUser($data);
        }catch (\Exception $e){
            return ['status' => 500, 'error' => $e->getMessage()];
        }
        $token = $result['data']->createToken('Laravel Password Grant Client')->accessToken;
        return response()->json(['user' => $result['data'], 'token' => $token, 'status' => $result['status']]);
    }
    public function login(Request $request)
    {
        $result = ['status' => 200, 'data' => "Logged in succesfully"];

        $data = $request;
        try {
            return $this->authenticationService->loginUser($data);
        }catch (\Exception $e) {
            $result['error'] = $e->getMessage();
            return response()->json(['result' => $result, 'status' => $result['error']]);

        }
    }

    public function logout(Request $request)
    {
        $data = $request;
        return $this->authenticationService->logoutUser($data);
    }
}
