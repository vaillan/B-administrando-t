<?php

namespace App\Http\Controllers\Api\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use File;
use Storage;
use Validator;

class UserController extends Controller
{
    public function loadImagen(Request $request)
    {
        $query = DB::transaction(function () use ($request) {

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'image' => 'required|image',
            ]);

            if ($validator->fails()) {
                return response()->json(['msg' => 'Validation Error.', 'params' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE);
            }

            $user = User::with('tipoUsuario')->find($request->input('user_id'));
            //subir imagen
            if ($request->hasFile('image')) {
                if(!empty($user->image)) {
                    $this->deleteUserImage($user->id);
                }
                $image = $request->file('image');
                //asignarle un nombre unico
                $image_full = \time() . '.' . $image->extension();
                //guardarla en la carpeta storage/app/users
                Storage::disk('usersImg')->put($image_full, File::get($image));
                //setear el nombre de la imagen en el objeto user
                $user->image = $image_full;
                $imgUrl = Storage::disk('usersImg')->url($user->image);
                $user->url_image = $imgUrl;
                $user->save();
                return response()->json(['type' => 'object', 'items' => ['user' => $user, 'msg' => 'Imagen cargada correctamente']]);
            }
        });
        return $query;
    }

    public function deleteUserImage($user_id)
    {
        $query = DB::transaction(function () use ($user_id) {
            $user = User::with('tipoUsuario')->find($user_id);
            Storage::disk('usersImg')->delete($user->image);
            $user->image = null;
            $user->url_image = null;
            $user->save();
            return response()->json(['type' => 'object', 'items' => ['msg' => 'Imagen borrada correctamente', 'user' => $user]]);
        });
        return $query;
    }
}
