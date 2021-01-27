<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Paciente;
use App\Hora;
use DB;
use DateTime;
use Auth;
use Carbon\Carbon;

class PacienteController extends Controller
{

    public function horaPaciente(Request $request)

     {
         //horas reservadas del paciente
         $paciente = DB::table('paciente')
             ->join('hora', 'paciente.id', '=', 'hora.paciente')
             ->select('hora.hora_atencion','hora.prestador','hora.prestacion','paciente.diff')
             ->where('paciente', '=', Auth::user()->idpaciente)
             ->get();

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

         //horas reservadas del paciente
         $diferencia  = DB::table('paciente')
             ->select('diff')
             ->where('id', '=', Auth::user()->idpaciente)
             ->first();

         $diferencia = $diferencia->diff;

        //función que obtiene la ciudad en función del código del país.

         return view('horaPaciente', compact('paciente','pais_lista','ciudad_lista','pais','ciudad','zonaHoraria','fecha','hora', 'diferencia'));
     }

    public function porPais($pais){

        $ciudadDep = DB::table('ciudad')->where('cod_pais', $pais)->get();

        return $ciudadDep;
    }

    public function grabarDatosPorIp(Request $request)
    {

        // Formulario modificar datos
        if ($request->ajax()) {

            $zh_ip= $request->input('zhIp');
            $ciudad_ip= $request->input('ciudadIp');


            $paciente =  Paciente::find(Auth::user()->idpaciente); // Crea objeto

            //se obtiene el id de la zona horaria en función del nombre de la zona horaria.
            $idzona= DB::table('zona_horaria')
                ->select('idzona')
                ->where('nombrezona', '=',$zh_ip)
                ->first();

            //se obtiene el id de la ciudad en función del nombre de la ciudad.
            $idciudad= DB::table('ciudad')
                ->select('idciudad')
                ->where('ciudad', '=',$ciudad_ip)
                ->first();

            $paciente->zonaHoraria = $idzona->idzona;
            $paciente->ciudad = $idciudad->idciudad;

            //hora de chile
            date_default_timezone_set('America/Santiago');
            $fechaCh= Date('d-m-Y H:i');

            //hora del paciente
            date_default_timezone_set($request->input('zhIp'));
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

            $paciente->diff =  $hdif;
            $paciente->save(); // guarda los registros
        }
        // fin ajax
    }

    public function grabarDatosGeo(Request $request)
    {

        // Formulario modificar datos
        if ($request->ajax()) {

            //fecha y hora de Chile
            $fechaChile= Carbon::now();
            $fechaChile= $fechaChile->format('d-m-Y H:i');

            //obtenemos la diferencia en horas (chile y la del paciente).
            $time1 = new DateTime($fechaChile);
            $time2 = new DateTime($request->input('fecha'));
            $interval = $time1->diff($time2);

            //obtenemos el signo (- ó + y la diferencia en horas y minutos)
            $signo = $interval->format("%R");
            $horas = $interval->format("%h hour");
            $minutos = $interval->format("%i minute");

            //concatenamos el signo, horas y minutos).
            $hdif= $signo.$horas.' '.$minutos;

            $paciente =  Paciente::find(Auth::user()->idpaciente); // Crea objeto
            $paciente->ciudad = $request->input('codigo_ciudad');
            $paciente->diff =  $hdif;

            $paciente->save(); // guarda el registro

            // retorno de datos via json
//            return response()->json(
//                $paciente->toArray()
//            );
        }
        // fin ajax
    }

}
