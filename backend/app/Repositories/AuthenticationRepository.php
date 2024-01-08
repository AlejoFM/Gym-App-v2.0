<?php
namespace App\Repositories;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticationRepository{

    protected $user;
    private User $User;

    public function __construct(User $user)
    {
        $this->User = $user;
    }

    public function saveNewUser($data){
        $user = User::create($data);

        return $user->fresh();
    }
    public function loginUser($data){
        $result = ['status' => 401, 'message' => 'Failed to authenticate'];
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {

            $user = User::find(Auth::user()->id);

            $user_token = $user->createToken('appToken')->accessToken;

            $result = ['user_data' => $user, 'user_token' => $user_token, 'status' => 200];
            return response()->json($result);
        } else {

            return response()->json($result);
        }
    }
    public function logoutUser($data){
        if (Auth::user()) {
            $data->user()->token()->revoke();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ], 200);
        }
    }
  }
