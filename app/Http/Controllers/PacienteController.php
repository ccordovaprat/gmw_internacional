<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Paciente;
use App\Hora;
use DB;
use DateTime;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class PacienteController extends Controller
{

    public function horaPaciente(Request $request)

    {
        //horas reservadas del paciente
        $paciente = DB::table('paciente')
            ->join('hora', 'paciente.id', '=', 'hora.paciente')
            ->select('hora.hora_atencion', 'hora.prestador', 'hora.prestacion', 'paciente.diff')
            ->where('paciente', '=', Auth::user()->idpaciente)
            ->get();

        //Colombia: 216.239.55.176, Mexico: 216.239.55.24-201.143.47.225 Argentina: 217.146.28.0, Chile: 190.5.56.205
        $arr_ip = geoip()->getLocation('216.239.55.176');
        $ip = $arr_ip->ip;
        $pais = $arr_ip->country;
        $ciudad = $arr_ip->city;
        $zonaHoraria = $arr_ip->timezone;
        $moneda = $arr_ip->currency;
        date_default_timezone_set($zonaHoraria);
        //$fecha= Date('Y-m-d H:i:s');
        $fecha = Date('d-m-Y');
        $hora = Date('H:i');
        $codPais = $arr_ip->iso_code;

        //se obtiene los paises de la tabla: pais en bd.
        $pais_lista = DB::table('pais')
            ->orderBy('pais', 'asc')
            ->get();

        //se obtiene las ciudades de la tabla: ciudad en bd.
        $ciudad_lista = DB::table('ciudad')
            ->orderBy('ciudad', 'asc')
            ->where('cod_pais', '=', $codPais)
            ->get();

        //se obtiene las ciudades de la tabla: ciudad en bd sin filtro país.
        $ciudad_lista_sf = DB::table('ciudad')
            ->orderBy('ciudad', 'asc')
            ->get();

        //para dejar seleccionada la ciudad se obtiene el nombre de la ciudad y el código del país.
        $idciudad = DB::table('ciudad')
            ->select('idciudad')
            ->where('ciudad', '=', $ciudad)
            ->where('cod_pais', '=', $codPais)
            ->first();

        //se inicializa idResult en 0 si es que el resultado de la consulta $idciudad es null
        $idResult=0;
        if (!empty($idciudad)) {
            $idResult = $idciudad->idciudad;
        }

         //horas reservadas del paciente
         $diferencia  = DB::table('paciente')
             ->select('diff')
             ->where('id', '=', Auth::user()->idpaciente)
             ->first();

         $diferencia = $diferencia->diff;

        //función que obtiene la ciudad en función del código del país.

         return view('horaPaciente', compact('paciente','pais_lista','ciudad_lista','pais','ciudad','zonaHoraria','fecha','hora', 'diferencia','codPais','idResult','ciudad_lista_sf'));
     }

    public function porPais($pais){

        $ciudadDep = DB::table('ciudad')->where('cod_pais', $pais)->get();

        return $ciudadDep;
    }

    public function grabarDatosPorIp(Request $request)
    {

        try {
        // Formulario confirmar datos
        if ($request->ajax()) {

            $zh_ip= $request->input('zhIp');
            $ciudad_ip= $request->input('ciudadIp');
            $ciudad_fil=str_replace('Mexico City', 'Mexico', $ciudad_ip);

            $paciente =  Paciente::find(Auth::user()->idpaciente); // Crea objeto

            //se obtiene el id de la zona horaria en función del nombre de la zona horaria.
            $idzona= DB::table('zona_horaria')
                ->select('idzona')
                ->where('nombrezona', '=',$zh_ip)
                ->first();

            //se obtiene el id de la ciudad en función del nombre de la ciudad.
            $idciudad= DB::table('ciudad')
                ->select('idciudad')
                ->where('ciudad', '=',$ciudad_fil)
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

            $horas = DB::table('paciente')
                ->join('hora', 'paciente.id', '=', 'hora.paciente')
                ->select('hora.hora_atencion', 'hora.prestador', 'hora.prestacion', 'paciente.diff')
                ->where('paciente', '=', Auth::user()->idpaciente)
                ->get();

            foreach ($horas as $hora){
                $hora->hora_atencion = date("H:i d-m-Y", strtotime($hora->hora_atencion.$paciente->diff));
            }

            $flag = true;

        }
        } catch(\Exception $e){
                $flag = false;
                $horas = [];
            }

        return response()->json([
            "flag" => $flag,
            "horas" => $horas
        ]);


        }
        // fin ajax

    public function grabarDatosGc(Request $request)
    {

        try {
            // Formulario confirmar datos
            if ($request->ajax()) {

                $ciudadGc= $request->input('ciudadGc');
                $fechaHora = $request->input('fechaGc').' '.$request->input('horaGc');
                $codPaisGc = $request->input('codPaisGc');
                $zonaHorariaGc = $request->input('zonaHorariaGc');

                $paciente =  Paciente::find(Auth::user()->idpaciente); // Crea objeto

                //se obtiene el id de la ciudad en función del nombre de la ciudad.
                $idCiudad= DB::table('ciudad')
                    ->select('idciudad')
                    ->where('ciudad', '=',$ciudadGc)
                    ->where('cod_pais', '=',$codPaisGc)
                    ->first();

                $idZona= DB::table('zona_horaria')
                    ->select('idzona')
                    ->where('nombreZona', '=',$zonaHorariaGc)
                    ->first();

                $paciente->ciudad = $idCiudad->idciudad;
                $paciente->zonaHoraria = $idZona->idzona;

                //fecha y hora de Chile
                $fechaChile= Carbon::now();
                $fechaChile= $fechaChile->format('d-m-Y H:i');

                //obtenemos la diferencia en horas (chile y la del paciente).
                $time1 = new DateTime($fechaChile);
                $time2 = new DateTime($fechaHora);
                $interval = $time1->diff($time2);

                //obtenemos el signo (- ó + y la diferencia en horas y minutos)
                $signo = $interval->format("%R");
                $horas = $interval->format("%h hour");
                $minutos = $interval->format("%i minute");
                //concatenamos el signo, horas y minutos).
                $hdif= $signo.$horas.' '.$minutos;

                $paciente->diff =  $hdif;
                $paciente->save(); // guarda los registros


                $horas = DB::table('paciente')
                    ->join('hora', 'paciente.id', '=', 'hora.paciente')
                    ->select('hora.hora_atencion', 'hora.prestador', 'hora.prestacion', 'paciente.diff')
                    ->where('paciente', '=', Auth::user()->idpaciente)
                    ->get();
                $flag = true;

                foreach ($horas as $hora){
                    $hora->hora_atencion = date("H:i d-m-Y", strtotime($hora->hora_atencion.$paciente->diff));
                }

            }
        } catch(\Exception $e){
            $flag = false;
            $horas = [];
        }

        return response()->json([
            "flag" => $flag,
            "horas" => $horas
        ]);

    }


    public function grabarDatosGeo(Request $request)
    {

        try {
            $validated = Validator::make($request->all(), [
                'codigo_pais' => 'required',
                'codigo_ciudad' => 'required',
                'fecha' => 'required'
            ], [
                'codigo_pais.required' => 'Debe seleccionar un pais',
                'codigo_ciudad.required' => 'Debe seleccionar una ciudad',
                'fecha.required' => 'Debe seleccionar una fecha',
            ]);

            if($validated->fails()){
                return response()->json([
                    "flag" => false,
                    "errors" => $validated->errors()
                ]);
            }

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

                //horas reservadas del paciente
                $horas = DB::table('paciente')
                    ->join('hora', 'paciente.id', '=', 'hora.paciente')
                    ->select('hora.hora_atencion', 'hora.prestador', 'hora.prestacion', 'paciente.diff')
                    ->where('paciente', '=', Auth::user()->idpaciente)
                    ->get();

                $flag = true;

                foreach ($horas as $hora){
                    $hora->hora_atencion = date("H:i d-m-Y", strtotime($hora->hora_atencion.$paciente->diff));
                }

                // retorno de datos via json
//            return response()->json(
//                $paciente->toArray()
//            );
            }
        } catch(\Exception $e){
            $flag = false;
            $horas = [];
        }

        return response()->json([
            "flag" => $flag,
            "horas" => $horas
        ]);


    }


    public function grabarDatosModGc(Request $request)
    {

       $fechaHora= $request->input('fechaModGc');
       $codPais= $request->input('codigo_pais_Gc');
       $nomCiudad= $request->input('codigo_ciudad_Gc');

        try {
            $validated = Validator::make($request->all(), [
                'codigo_pais_Gc' => 'required',
                'codigo_ciudad_Gc' => 'required',
                'fechaModGc' => 'required'
            ], [
                'codigo_pais_Gc.required' => 'Debe seleccionar un pais',
                'codigo_ciudad_Gc.required' => 'Debe seleccionar una ciudad',
                'fechaModGc.required' => 'Debe seleccionar una fecha',
            ]);

            if($validated->fails()){
                return response()->json([
                    "flag" => false,
                    "errors" => $validated->errors()
                ]);
            }

            // Formulario modificar datos
            if ($request->ajax()) {

                //fecha y hora de Chile
                $fechaChile= Carbon::now();
                $fechaChile= $fechaChile->format('d-m-Y H:i');

                //obtenemos la diferencia en horas (chile y la del paciente).
                $time1 = new DateTime($fechaChile);
                $time2 = new DateTime($fechaHora);
                $interval = $time1->diff($time2);

                //obtenemos el signo (- ó + y la diferencia en horas y minutos)
                $signo = $interval->format("%R");
                $horas = $interval->format("%h hour");
                $minutos = $interval->format("%i minute");

                //concatenamos el signo, horas y minutos).
                $hdif= $signo.$horas.' '.$minutos;

                //obtengo id ciudad

                $idciudad = DB::table('ciudad')
                    ->select('idciudad')
                    ->where('cod_pais', '=', $codPais)
                    ->where('ciudad', '=', $nomCiudad)
                    ->first();

                $paciente =  Paciente::find(Auth::user()->idpaciente);
                $paciente->ciudad = $idciudad->idciudad;
                $paciente->diff =  $hdif;

                $paciente->save(); // guarda el registro

                //horas reservadas del paciente
                $horas = DB::table('paciente')
                    ->join('hora', 'paciente.id', '=', 'hora.paciente')
                    ->select('hora.hora_atencion', 'hora.prestador', 'hora.prestacion', 'paciente.diff')
                    ->where('paciente', '=', Auth::user()->idpaciente)
                    ->get();

                $flag = true;

                foreach ($horas as $hora){
                    $hora->hora_atencion = date("H:i d-m-Y", strtotime($hora->hora_atencion.$paciente->diff));
                }

                // retorno de datos via json
//            return response()->json(
//                $paciente->toArray()
//            );
            }
        } catch(\Exception $e){
            $flag = false;
            $horas = [];
            echo $e;
        }

        return response()->json([
            "flag" => $flag,
            "horas" => $horas
        ]);


    }



}
