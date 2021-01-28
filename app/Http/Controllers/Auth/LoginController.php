<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Illuminate\Http\Request;
use Hash;
use Auth;
use DB;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //función que utiliza el paquete torann/geoip para obtener la localización del paciente en función a su dirección IP.


    public function login(Request $request)

    {
        //Validación de credenciales de paciente
        $credentials = $this->validate(request(), [
          'rut' => 'required',
         'password' => 'required'
        ], [
            'rut.required' => 'Ingrese documento',
            'password.required' => 'Ingrese contraseña'

        ]);

        //Se valida las credenciales (rut y password) y si son correctas se redirige a ruta hora.
        if (Auth::attempt($credentials)) {

        //redirección a hora
          return redirect()->route('hora');

        }

        //if (!Auth::attempt($credentials)) {
            return redirect()->route('login');
        //}

    }
    //función para salir de página principal y limpiar variables de sesión del paciente.
    public function logout()

    {
        Auth::logout();
        return redirect()->route('login');
    }

}
