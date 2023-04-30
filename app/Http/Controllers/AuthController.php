<?php

namespace App\Http\Controllers;

use App;
use App\Models\User;
use App\Responses\ApiError;
use App\Responses\ApiResponse;
use App\Responses\ApiSuccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return ApiResponse
     */
    public function register(Request $request): ApiResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|alpha_dash:ascii|max:32',
            'email' => 'required|email|max:64',
            'password' => 'required|alpha_dash:ascii|confirmed|min:6|max:32',
        ]);

        if($validator->fails()){
            return new ApiError('invalid data', $validator->errors());
        }

        if(User::where('email', $request->email)->exists()){
            return new ApiError('user already exists', "$request->email");
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'api_token' => Str::random(60),
        ]);

        return new ApiSuccess('ok', $user);
    }
}
