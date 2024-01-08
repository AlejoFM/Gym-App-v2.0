<?php
namespace App\Services;
use App\Models\User;
use App\Repositories\AuthenticationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthenticationService{
    protected $AuthenticationRepository;
    public function __construct(AuthenticationRepository $AuthenticationRepository)
    {
        $this->AuthenticationRepository = $AuthenticationRepository;
    }

    public function registerNewUser($data){
        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $data['password']= Hash::make($data['password']);
        $data['remember_token'] = Str::random(10);

        $result = $this->AuthenticationRepository->saveNewUser($data);

        return $result;
    }
    public function loginUser($data){
        return $this->AuthenticationRepository->loginUser($data);
    }
    public function logoutUser($data){
        return $this->AuthenticationRepository->logoutUser($data);
    }
  }
