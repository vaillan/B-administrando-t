<?php

namespace App\Http\Controllers\Api\code_check;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\reset_password\ResetCodePassword;
use Symfony\Component\HttpFoundation\Response;
use Validator;

class CodeCheckController extends Controller
{
    /**
     * Check if the code is exist and vaild one (Setp 2)
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Contracts\Routing\ResponseFactory::json
     */
    public function checkCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|exists:reset_code_passwords',
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'Validation Error.', 'params' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE);
        }

        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        if ($passwordReset->isExpire()) {
            return response()->json(['msg' => 'Code is expire'], Response::HTTP_PRECONDITION_FAILED);
        }

        return response()->json(['code' => $passwordReset->code, 'msg' => 'Code is valid']);
    }
}
