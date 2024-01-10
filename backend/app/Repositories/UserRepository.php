<?php
namespace App\Repositories;
use App\Models\User;

class UserRepository{
    protected $User;
    public function __construct(User $User)
    {
        $this->User = $User;
    }
    public function getAllUsers(){
        return User::all();
    }
    public function createUser($data){

        $user = User::create($data);
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        $result = ['user_data' => $user, 'user_token' => $token, 'status' => 200];

        return $result;

    }
    public function deleteUser($user){

        $user->delete();
        return response()->json(['message' => 'User deleted successfully', 'status' => 200]);
    }
  }
