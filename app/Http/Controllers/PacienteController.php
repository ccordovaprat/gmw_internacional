<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Paciente;
use App\Hora;
use DB;
use DateTime;
use Auth;

class PacienteController extends Controller
{
    public function horaPaciente(Request $request)

     {
         //horas reservadas del paciente
         $paciente = DB::table('paciente')
             ->join('hora', 'paciente.id', '=', 'hora.paciente')
             ->select('hora.*', 'paciente.nombres')
             ->where('paciente', '=', Auth::user()->idpaciente)
             ->get();

         //zona horaria del paciente
         $zh = DB::table('paciente')
             ->join('zona_horaria', 'paciente.zonahoraria', '=', 'zona_horaria.idzona')
             ->select('zona_horaria.nombrezona')
             ->where('paciente.id', '=', Auth::user()->idpaciente)
             ->first();

         //hora de chile
         date_default_timezone_set('America/Santiago');
         $fechaChile= Date('d-m-Y H:i');

         //hora del paciente
         date_default_timezone_set($zh->nombrezona);
         $fechaPaciente= Date('d-m-Y H:i');

         //obtenemos la diferencia en horas (chile y la del paciente).
         $time1 = new DateTime($fechaChile);
         $time2 = new DateTime($fechaPaciente);
         $interval = $time1->diff($time2);

         //obtenemos el signo (- ó + y la diferencia en horas y minutos)
         $signo = $interval->format("%R");
         $horas = $interval->format("%h hour");
         $minutos = $interval->format("%i minute");

         //concatenamos el signo, horas y minutos).
         $hdif= $signo.$horas.' '.$minutos;

         //$minutos = $interval->format("%i min");

         return view('horaPaciente', compact('paciente','zh', 'fechaChile','fechaPaciente', 'horas','hdif'));
     }

}
