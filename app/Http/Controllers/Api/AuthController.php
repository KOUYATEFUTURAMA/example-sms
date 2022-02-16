<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{   
    /**
    * @OA\Post(
    *   path="/api/auth/signup",
    *   summary="Sign up",
    *   description="Signup by name, email, password and confirme your password",
    *   operationId="authSignup",
    *   tags={"auth"},
    *  @OA\Response(
    *          response=201,
    *          description="Successful operation",
    *       ),
    *  @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *       ),
    *   @OA\RequestBody(
    *       required=true,
    *       description="User signup",
    *       @OA\JsonContent(
    *           required={"name","email","password","confirm_password"},
    *           @OA\Property(property="name", type="string", example="Ali Abdoul"),
    *           @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
    *           @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
    *@OA\Property(property="confirm_password", type="string", format="password",example="PassWord12345"),
    *       )
    *   )
    * )
    */

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        return response()->json([
            'message' => 'Your acount is successfully created !'
        ], 201);
    }

    /**
    * @OA\Post(
    *   path="/api/auth/login",
    *   summary="User login",
    *   description="Login by email, password",
    *   operationId="authLogin",
    *   tags={"auth"},
    *  @OA\Response(
    *          response=201,
    *          description="Successful operation",
    *       ),
    * @OA\Response(
    *          response=401,
    *          description="Unauthorized",
    *       ),
    *   @OA\RequestBody(
    *       required=true,
    *       description="User login",
    *       @OA\JsonContent(
    *           required={"email","password"},
    *           @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
    *           @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
    *       )
    *   )
    * )
    */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ],201);
    }

    /**
    * @OA\Get(
    *   path="/api/logout",
    *   summary="User logout",
    *   description="User logout",
    *   operationId="authLogout",
    *   tags={"auth"},
    *  @OA\Response(
    *          response=201,
    *          description="Successful operation",
    *       ),
    * @OA\Response(
    *          response=401,
    *          description="Unauthorized",
    *       )
    * )
    */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Your are successfully logged out'
        ],201);
    }
}
