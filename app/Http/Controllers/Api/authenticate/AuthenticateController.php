<?php

namespace App\Http\Controllers\Api\authenticate;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class AuthenticateController extends Controller
{
    protected $tipo_usuario_id;

    public function __construct()
    {
        $this->tipo_usuario_id = 2;
    }
    /**
     * Register API
     * 
     * @param Illuminate\Contracts\Routing\ResponseFactory::json
     */
    public function signUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'unique:users|email|required',
            'device_name' => 'required',
            'password' => 'required',
            'confirmed_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'Validation Error.', 'params' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE);
        }

        $registerData = $request->all();
        $registerData['password'] = bcrypt($registerData['password']);
        $registerData['created_by'] = 1;
        $registerData['updated_by'] = 1;
        $registerData['tipo_usuario_id'] = $this->tipo_usuario_id;
        User::create($registerData);
        return response()->json(['msg' => 'Register has been successfully.']);
    }

    /**
     * Login API
     * 
     * @param Illuminate\Contracts\Routing\ResponseFactory::json
     */
    public function logIn(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::with('tipoUsuario')->where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->update(['device_name' => $request->input('device_name')]);

        $token = $user->createToken($request->device_name)->plainTextToken;

        $authData = [
            'token' => $token,
            'user' => $user
        ];

        return response()->json(['items' => $authData, 'type' => 'object']);
    }

    /**
     * Logout API
     * 
     * @param Illuminate\Contracts\Routing\ResponseFactory::json
     */
    public function logOut(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->delete();
        $response = ['message' => 'You have been successfully logged out!'];
        return response()->json($response, 200);
    }
}
