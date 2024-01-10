<?php
namespace App\Services;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserService{
    protected $UserRepository;
    public function __construct(UserRepository $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    public function getAllUsers(){
        return $this->UserRepository->getAllUsers();
    }
    public function createUser($data){
        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails())
        {
            $error = ['error_message' => $validator->errors()->all(),'status' => 422];
            return $error;
        }
        $data['password']= Hash::make($data['password']);
        $data['remember_token'] = Str::random(10);

        return $this->UserRepository->createUser($data);
    }
    public function deleteUser($userId){
        $user = User::findOrFail($userId);
        return $this->UserRepository->deleteUser($user);
    }
  }
