<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeolocalizacionController extends Controller
{
    public function geo(Request $request)

    {
        //Se utiliza el paquete geoip() para obtener la localizacion con el metodo getLocation()
        //Colombia: 216.239.55.176, Mexico: 216.239.55.24, Argentina: 217.146.28.0, Chile: 190.5.56.205
        $arr_ip = geoip()->getLocation('216.239.55.24');
        $ip=    $arr_ip->ip;
        $pais=   $arr_ip->country;
        $ciudad= $arr_ip->city;
        $zonaHoraria= $arr_ip->timezone;
        $moneda = $arr_ip->currency;
        date_default_timezone_set($zonaHoraria);
        //$fecha= Date('Y-m-d H:i:s');
        $fecha= Date('d-m-Y');
        $hora= Date('H:i');
        //dd($arr_ip);
        return view('geolocalizacion', compact('ip', 'pais', 'ciudad','zonaHoraria','moneda','fecha','hora'));


        //obtiene ip cliente
        //dd($request->ip());
    }
}
