<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ], 200); // Explicitly set 200 status code
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'user' => $this->guard()->user(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function guard()
    {
        return Auth::Guard('api');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currentPassword' => 'required',
            'newPassword' => 'required|confirmed|min:6',
        ], [
            'currentPassword.required' => 'Required field',
            'newPassword.required' => 'Required field',
            'newPassword.confirmed' => "New password and confirmation don't match",
        ]);

        $isPasswordWrong = false;

        if (!auth('api')->validate([
            'email' => Auth::user()->email,
            'password' => $request->get('currentPassword'),
        ])) {
            $isPasswordWrong = true;
        }

        if ($validator->fails() || $isPasswordWrong) {
            if ($isPasswordWrong) {
                $validator->errors()->add('currentPassword', 'Wrong password');
            }
            return response()->json(['errors' => $validator->errors()], 429);
        }

        $user = User::find(Auth::user()->id);
        $user->password = bcrypt($request->get('newPassword'));
        $user->save();

        return response()->json(['message' => 'success']);
    }
}
