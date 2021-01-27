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
    public function geoDatos(Request $request)
    {

        //Colombia: 216.239.55.176, Mexico: 216.239.55.24-201.143.47.225 Argentina: 217.146.28.0, Chile: 190.5.56.205
        $arr_ip = geoip()->getLocation('216.239.55.176');
        $ip=    $arr_ip->ip;
        $pais=   $arr_ip->country;
        $ciudad= $arr_ip->city;
        $zonaHoraria= $arr_ip->timezone;
        $moneda = $arr_ip->currency;
        date_default_timezone_set($zonaHoraria);
        //$fecha= Date('Y-m-d H:i:s');
        $fecha= Date('d-m-Y');
        $hora= Date('H:i');
        $codPais=$arr_ip->iso_code;

        //se obtiene los paises de la tabla: pais en bd.
        $pais_lista = DB::table('pais')
            ->orderBy('pais', 'asc')
            ->get();

        //se obtiene las ciudades de la tabla: ciudad en bd.
        $ciudad_lista = DB::table('ciudad')
            ->orderBy('ciudad', 'asc')
            ->get();

        return view('auth.login', compact('ip', 'pais', 'ciudad','zonaHoraria','moneda','fecha','hora', 'pais_lista', 'ciudad_lista','codPais'));

        //obtiene ip cliente
        //dd($request->ip());
    }

    //función que obtiene la ciudad en función del código del país.
    public function porPais($pais){

        $ciudadDep = DB::table('ciudad')->where('cod_pais', $pais)->get();

        return $ciudadDep;
    }

    public function login(Request $request)

    {
        //recuperar valor de input hiden zona horaria
        $zh_form = $request->input('zh_paciente');
        //recuperar valor de input hiden ciudad.
        $ciudad_form = $request->input('ciudad_paciente');
        //Si la ciudad es "Mexico City" se reemplaza con "Mexico"
        $ciudad_fil=str_replace('Mexico City', 'Mexico', $ciudad_form);
        $fecha_form = $request->input('fecha_mod');
        $ciudad_mod = $request->input('ciudad_mod');

        //Validación de credenciales de paciente
        $credentials = $this->validate(request(), [
          'rut' => 'required',
         'password' => 'required'
        ]);

        //Se valida las credenciales (rut y password) y si son correctas se redirige a ruta hora.
        if (Auth::attempt($credentials)) {

            //se obtiene el id de la zona horaria en función del nombre de la zona horaria.
           $idzona= DB::table('zona_horaria')
                ->select('idzona')
                ->where('nombrezona', '=',$zh_form)
               ->first();

           //actualiza la zonahoraria de la tabla paciente.
            if (!empty($idzona)) {
                $updateZona= DB::table('paciente')
                    ->where('id', Auth::user()->idpaciente)
                    ->update(['zonahoraria' => $idzona->idzona]);
            }

            //se obtiene el id de la ciudad en función del nombre de la ciudad.
            $idciudad= DB::table('ciudad')
                ->select('idciudad')
                ->where('ciudad', '=',$ciudad_fil)
                ->first();

            //actualiza la ciudad de la tabla paciente.
            if (!empty($idciudad)) {
                $updateCiudad= DB::table('paciente')
                    ->where('id', Auth::user()->idpaciente)
                    ->update(['ciudad' => $idciudad->idciudad]);
            }

            //Actualiza ciudad seleccionada en tabla paciente.
            if (!empty($ciudad_mod)) {
                $updateCiudadMod= DB::table('paciente')
                    ->where('id', Auth::user()->idpaciente)
                    ->update(['ciudad' => $ciudad_mod]);
            }

            $fechaChile= Carbon::now();
            $fechaChile= $fechaChile->format('d-m-Y H:i');

            //obtenemos la diferencia en horas (chile y la del paciente).
            $time1 = new DateTime($fechaChile);
            $time2 = new DateTime($fecha_form);
            $interval = $time1->diff($time2);

            //obtenemos el signo (- ó + y la diferencia en horas y minutos)
            $signo = $interval->format("%R");
            $horas = $interval->format("%h hour");
            $minutos = $interval->format("%i minute");

            //concatenamos el signo, horas y minutos).
            $hdif= $signo.$horas.' '.$minutos;

            if (!empty($fecha_form)) {
                //actualiza diferencia en horas y minutos tabla paciente
                $updateDiff = DB::table('paciente')
                    ->where('id', Auth::user()->idpaciente)
                    ->update(['diff' => $hdif]);
            }

            if (!empty($zh_form)) {

            //hora de chile
            date_default_timezone_set('America/Santiago');
            $fechaCh= Date('d-m-Y H:i');

            //hora del paciente
            date_default_timezone_set($zh_form);
            $fechaPa= Date('d-m-Y H:i');

            //obtenemos la diferencia en horas (chile y la del paciente).
            $time1 = new DateTime($fechaCh);
            $time2 = new DateTime($fechaPa);
            $interval = $time1->diff($time2);

            //obtenemos el signo (- ó + y la diferencia en horas y minutos)
            $signo = $interval->format("%R");
            $horas = $interval->format("%h hour");
            $minutos = $interval->format("%i minute");

            //concatenamos el signo, horas y minutos).
            $hdif= $signo.$horas.' '.$minutos;

            //Actualizar campo diff de paciente con datos geolocalizados
                $updateDiffApi = DB::table('paciente')
                    ->where('id', Auth::user()->idpaciente)
                    ->update(['diff' => $hdif]);
            }
        //redirección a hora
          return redirect()->route('hora');

        }

        //if (!Auth::attempt($credentials)) {
            return redirect()->route('geoDatos');
        //}

    }
    //función para salir de página principal y limpiar variables de sesión del paciente.
    public function logout()

    {
        Auth::logout();
        return redirect()->route('geoDatos');
    }

}
