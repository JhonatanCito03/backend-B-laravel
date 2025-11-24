<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


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
            'password' => bcrypt($request->password)
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

    public function validateCredentials(Request $request)
    {
        //Validate email and password
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'message' => 'Datos invalidos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        //email finding

        $usuarios = Usuario::where('email', $request->email)->first();

        if(!$usuarios) 
        {
            return response()->json([
                'valid' => false,
                'message' => 'El correo no existe',
                'status' => 400
            ], 404);
        }

        //password validate after validating email
        if(!Hash::check($request->password, $usuarios->password)){
            return response()->json([
                'valid' => false,
                'message' => 'La contraseña es incorrecta',
                'status' => 401
            ], 401);
        }

        //Validate credentials
        return response()->json([
            'valid'=> true,
            'message' => 'Credenciales validas',
            'usuario' => [
                'id' => $usuarios->id,
                'name' => $usuarios->name,
                'email' => $usuarios->email
            ],
            'status' => 200
        ], 200);
    }
}
