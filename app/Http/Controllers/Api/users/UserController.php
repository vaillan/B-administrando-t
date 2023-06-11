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

class UserController extends Controller
{
    public function loadImagen(Request $request)
    {
        $query = DB::transaction(function () use ($request) {
            $user = User::with('tipoUsuario')->find($request->input('user_id'));
            //subir imagen
            if ($request->hasFile('image')) {
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
            }else {
                return response()->json(['msg' => 'Validation Error.'], Response::HTTP_NOT_ACCEPTABLE);
            }
        });
        return $query;
    }
}
