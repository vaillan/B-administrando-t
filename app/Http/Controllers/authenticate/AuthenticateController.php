<?php

namespace App\Http\Controllers\authenticate;

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
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'unique:users|email|required',
            'password' => 'required',
            'confirmed_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'Validation Error.', 'errors' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE);
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
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('administrando-t')->plainTextToken;
            $success['name'] =  $user->name;
            return response()->json(['autenticado' => $success, 'msg' => 'User login successfully.']);
        } else {
            return response()->json(['msg' => 'Unauthorised.'], Response::HTTP_UNAUTHORIZED);
        }
    }
}
