<?php

namespace App\Http\Controllers\Api\users;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\egresos\Gasto;
use App\Models\reglas\ReglaAplicadaPresupuesto;
use App\Models\ingresos\Ingreso;
use File;
use Storage;
use Validator;

class UserController extends Controller
{
    public function loadImagen(Request $request)
    {
        $query = DB::transaction(function () use ($request) {

            // $validator = Validator::make($request->all(), [
            //     'image' => 'required|image',
            // ]);

            // if ($validator->fails()) {
            //     return response()->json(['msg' => 'Validation Error.', 'params' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE);
            // }

            $user = User::with('tipoUsuario')->find(Auth::id());
            //subir imagen
            if ($request->hasFile('image')) {
                if (!empty($user->image)) {
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
            return response()->json(['msg' => 'Validation Error.', 'params' => ['img' => 'La imagen es requerida']], Response::HTTP_NOT_ACCEPTABLE);
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

    public function updateDatosUsuario(Request $request)
    {
        $query = DB::transaction(function () use ($request) {
            $user = User::with('tipoUsuario')->find($request->input('user_id'));
            $id = $user->id;
            $validator = Validator::make($request->all(), [
                'user_id' => ['required'],
                'name' => ['required', 'string'],
                'last_name' => ['required', 'string'],
                'email' => ['required', 'string', 'unique:users,email,' . $id],
            ]);

            if ($validator->fails()) {
                return response()->json(['msg' => 'Validation Error.', 'params' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE);
            }
            $updatedDatosUsuario = collect($request->all())->filter(function ($item) {
                return $item != null;
            })->toArray();

            if ($request->has('password') && !empty($request->input('password'))) {
                $updatedDatosUsuario['password'] = bcrypt($request->input('password'));
            }

            $user->update($updatedDatosUsuario);

            return response()->json(['type' => 'object', 'items' => ['msg' => 'Datos actualizados correctamente', 'user' => $user]]);
        });
        return $query;
    }

    public function deleteCuenta($user_id)
    {
        $query = DB::transaction(function () use ($user_id) {
            $user = User::find($user_id);
            $gatos = Gasto::with(['periodo', 'gastoReporte'])->where('created_by', $user->id)->get();
            $reglas = ReglaAplicadaPresupuesto::where('created_by', $user->id)->get();
            $ingresos = Ingreso::with(['periodo', 'presupuesto'])->where('usuario_id', $user->id)->get();

            foreach ($gatos as $gasto) {
                $gasto->periodo->forceDelete();
                $gasto->gastoReporte->forceDelete();
                $gasto->forceDelete();
            }

            foreach ($reglas as $regla) {
                $regla->forceDelete();
            }

            foreach ($ingresos as $ingreso) {
                $ingreso->periodo->forceDelete();
                $ingreso->presupuesto->forceDelete();
                $ingreso->forceDelete();
            }
            $user->updated_at = date('Y-m-d H:i:m');
            $user->deleted_at = date('Y-m-d H:i:m');
            $user->email = $user->email . '_deleted_at_' . $user->deleted_at;
            $user->save();
            $user->tokens()->delete();
            return response()->json(['type' => 'object', 'items' => ['msg' => 'Cuenta cancelada']]);
        });
        return $query;
    }
}
