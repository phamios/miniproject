<?php

namespace App\Http\Controllers\Api\V1;

use App\Cores\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use Hash;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Post(
     *      path="/api/v1/auth/login",
     *      summary="Sign in",
     *      description="Login by email, password",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass user credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="email", type="email", example="super@laramongo.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password123"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Wrong credentials response",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={}),
     *          )
     *      )
     * )
     */
    public function login(User $user, LoginRequest $request)
    {
        $findUser = $user->firstWhere('email', $request->email);

        if (!$findUser) {
            return $this->responseJson('error', 'Unauthorized. Email not found', '', 401);
        }

        if (!Auth::attempt($request->validated())) {
            return $this->responseJson('error', 'Unauthorized.', '', 401);
        }

        $token = $findUser->createToken('authToken');
        $data = [
            'accessToken' => $token->plainTextToken,
            'user' => new UserResource($findUser)
        ];
        return $this->responseJson('success', 'Login success', $data);
    }

    /**
    * @OA\Post(
    *       path="/api/v1/auth/logout",
    *       summary="Log user out ",
    *       description="Endpoint to log current user out",
    *       tags={"Auth"},
    *       security={
    *           {"token": {}}
    *       },
    *       @OA\Response(
    *           response=200,
    *           description="OK"
    *       ),
    *       @OA\Response(
    *           response=401,
    *           description="Unauthorized"
    *       ),
    * )
    */
    public function logout()
    {
        if (auth()->user()) {
            // FIXME : Fixing Revoke the token that was used to authenticate the current request
            // $revoke = auth()->user()->currentAccessToken()->delete();

            /**Use below code if you want to log current user out in all devices */
            $revoke = auth()->user()->tokens()->delete();
            if ($revoke) {
                return $this->responseJson('success', 'Logout');
            }
            return $this->responseJson('error', 'Logout');
        }
        return $this->responseJson('error', 'Unauthorized.', '', 401);
    }

     /**
    * @OA\Post(
    *       path="/api/v1/auth/signup",
    *       summary="Register",
    *       description="Endpoint to log current user out",
    *       tags={"Auth"},
    *      @OA\RequestBody(
    *          required=true,
    *          description="User Signup",
    *          @OA\JsonContent(
    *              @OA\Property(property="name", type="string", example="kevin"),
    *              @OA\Property(property="email", type="email", example="super@laramongo.com"),
    *              @OA\Property(property="password", type="string", format="password", example="password123"),
    *              @OA\Property(property="password_confirmation", type="string", format="password")
    *          ),
    *      ),
    *       @OA\Response(
    *          response=201,
    *          description="Register Successfully",
    *          @OA\JsonContent()
    *       ),
    *       @OA\Response(
    *           response=200,
    *           description="Register Successfully"
    *       ),
    *      @OA\Response(
    *          response=422,
    *          description="Unprocessable Entity",
    *          @OA\JsonContent()
    *       ),
    *       @OA\Response(
    *           response=401,
    *           description="Error handling"
    *       ),
    * )
    */
    public function signup(RegisterRequest $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $success['token'] =  $user->createToken('authToken')->accessToken;
        $success['name'] =  $user->name;
        return response()->json(['success' => $success]);
    }
}
