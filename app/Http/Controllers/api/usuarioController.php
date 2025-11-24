<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class usuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all();

         if($usuarios->isEmpty()) {
             $data = [
                 'message' => 'No se encontraron usuarios registrados.',
                 'status' => 200
             ];
             return response()->json($data, 200);
         }

        $data = [
            'usuarios' => $usuarios,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:usuario,email',
            'password'=>'required'
        ]);

        if($validator->fails()){
            $data = [
            'message' => 'Error en la validacion de los datos.',
            'errors' => $validator->errors(),
            'status'=>400
            ];

            return response()->json($data,400);
        }
        
        $usuarios = Usuario::create([
            'name' => $request -> name,
            'email' => $request -> email,
            'password' => $request -> password
        ]);

        if(!$usuarios){
            $data = [
                'message' => 'Error al crear un usuario',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => $usuarios,
            'status' => 201
        ];

        return response() -> json($data,201);
    }
}
