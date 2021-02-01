@extends('plantilla')
@section('title', "Hora")

@section('headerdiv1')
    <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Bienvenido: {{ Auth::user()->name }}</a>
        </li>
    </ul>
@endsection

@section('headerdiv2')
    <a class="nav-link btn-dark"  onclick="confirmarDatos()" >Geolocalización</a>
    <a class="nav-link btn-dark" href="/logout">Salir</a>
@endsection

@section('content')

    <input id="flagTipoGeo" name="flagTipoGeo" type="hidden" value="">

    <!--Modal para confirmar datos de geolocalización a través de JavaScript-->
    <div class="modal fade" id="modalPermiterGeo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4 class="mt-5">Por favor permite la geolocalización en tu navegador.</h4>
                        <div class="container mt-3">
                            <div class="row">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <!--Modal para confirmar datos de geolocalización por Gc-->
    <div class="modal fade" id="modalConfirmarGc" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <form class="form-horizontal" method="POST" action="{{ route('hora.grabarGc') }}" name="formGuardarPorGc" id="formGuardarPorGc">
            {{ csrf_field() }}
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4>Por favor confirmanos si los siguientes datos son correctos:</h4>
                        <div class="container mt-3">
                            <div class="row">
                                <div class="col mt-3">
                                    <label for="paisGc">Pais:</label>
                                    <input id="paisGc" name="paisGc" type="text" value="" class="form-control">
                                    <input id="codPaisGc" name="codPaisGc" type="hidden" value="" class="form-control">

                                </div>
                                <div class="col mt-3">
                                    <label for="ciudadGc">Ciudad:</label>
                                    <input id="ciudadGc" name="ciudadGc" type="text" value="" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mt-3">
                                    <label for="fechaGc">Fecha:</label>
                                    <input id="fechaGc" name="fechaGc" type="text" value="" class="form-control">
                                </div>
                                <div class="col mt-3">
                                    <label for="horaGc">Hora:</label>
                                    <input id="horaGc" name="horaGc" type="text" value="" class="form-control">
                                    <input id="zonaHorariaGc" name="zonaHorariaGc" type="hidden" value="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="btnGuardarPorGc">
                                Registrar
                            </button>
                            <a href="#modalModificar" id="btnModPorGc" data-dismiss="modal" class="btn btn-danger"
                               onclick="modificarDatosGc()">Modificar</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!--Modal para confirmar datos de geolocalización por IP-->
    <div class="modal fade" id="modalConfirmar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <form class="form-horizontal" method="POST" action="{{ route('hora.grabarPorIp') }}" name="formGuardarPorIp" id="formGuardarPorIp">
            {{ csrf_field() }}
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4>Por favor confirmanos si los siguientes datos son correctos:</h4>
                    <div class="container mt-3">
                        <div class="row">
                            <div class="col mt-3">
                                <label for="paisIp">Pais:</label>
                                <input id="paisIp" name="paisIp" type="text" value="{{$pais ?? ''}}" class="form-control">
                            </div>
                            <div class="col mt-3">
                                <label for="ciudadIp">Ciudad:</label>
                                <input id="ciudadIp" name="ciudadIp" type="text" value="{{$ciudad ?? ''}}" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mt-3">
                                <label for="fechaIp">Fecha:</label>
                                <input id="fechaIp" name="fechaIp" type="text" value="{{ $fecha ?? '' }}" class="form-control">
                            </div>
                            <div class="col mt-3">
                                <label for="horaIp">Hora:</label>
                                <input id="horaIp" name="horaIp" type="text" value="{{ $hora ?? '' }}" class="form-control">
                                <input id="zhIp" name="zhIp" type="hidden" value="{{  $zonaHoraria ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnGuardarPorIP">
                            Registrar
                        </button>
                        <a href="#modalModificar" data-dismiss="modal" class="btn btn-danger"
                           onclick="modificarDatos()">Modificar</a>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>

    <!--Modal para modificar datos de geolocalización por IP-->
    <div class="modal fade" id="modalModificar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <form class="form-horizontal" method="POST" action="{{ route('hora.grabar') }}" name="formGuardarMod" id="formGuardarMod">
            {{ csrf_field() }}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4>Por favor modifica los datos que no son correctos:</h4>
                    <div class="container mt-3">
                        <div class="row">
                            <div class="col-md-5">
                                Pais:
                                <select id="codigo_pais" name="codigo_pais" class="form-control required">
{{--                                    <option value="">Seleccione país</option>--}}
                                    @foreach ($pais_lista as $p)
                                        <option value="{{ $p->codigo}}" {{ ($p->codigo == old('codigo_pais',$codPais))?'selected':'' }} >{{ $p->pais }}</option>
{{--                                        <option value="{{ $p->codigo}}">{{ $p->pais }}</option>--}}
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                Ciudad:
                                <select id="codigo_ciudad" name="codigo_ciudad" class="form-control required">
{{--                                    <option value="">Seleccione ciudad</option>--}}
                                    @foreach ($ciudad_lista as $c)
                                        <option value="{{ $c->idciudad}}" {{ ($c->idciudad == old('codigo_ciudad',$idResult))?'selected':'' }}>{{ $c->ciudad }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mt-3">
                                Fecha y hora:
                                <input type="datetime-local" id="fecha"
                                       name="fecha" value="{!!date("Y-m-d", strtotime($fecha)).'T'.$hora!!}" class="required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="alert alert-danger mt-2 d-none" id="erroresModalModificar">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="submit" class="btn btn-primary" id="btnGuardarMod">
                        Registrar
                    </button>
                    <a data-dismiss="modal" class="btn btn-danger" onclick="cancelarDatos()">Cancelar</a>
                </div>
            </div>
        </div>
        </form>
    </div>

    <!--Modal para modificar datos de geolocalización por Gc-->
    <div class="modal fade" id="modalModificarGc" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <form class="form-horizontal" method="POST" action="{{ route('hora.grabarModGc') }}" name="formGuardarModGc" id="formGuardarModGc">
            {{ csrf_field() }}
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4>Por favor modifica los datos que no son correctos:</h4>
                        <div class="container mt-3">
                            <div class="row">
                                <div class="col-md-5">
                                    Pais:
                                    <select id="codigo_pais_Gc" name="codigo_pais_Gc" class="form-control required">
{{--                                        <option value="">Seleccione país</option>--}}
                                    @foreach ($pais_lista as $p)
                                            {{--                                            <option value="{{ $p->codigo}}" {{ ($p->codigo == old('codigo_pais',$codPais))?'selected':'' }} >{{ $p->pais }}</option>--}}
                                            <option value="{{ $p->codigo}}" {{ ($p->codigo == old('codigo_pais_Gc'))?'selected':'' }}>{{ $p->pais }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    Ciudad:
                                    <select id="codigo_ciudad_Gc" name="codigo_ciudad_Gc" class="form-control required">
{{--                                        @foreach ($ciudad_lista_sf as $c)--}}
{{--                                            <option value="{{ $c->idciudad}}">{{ $c->ciudad }} </option>--}}

{{--                                        @endforeach--}}
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mt-3">
                                    Fecha y hora:
                                    <input type="datetime-local" id="fechaModGc"
                                           name="fechaModGc" value="{!!date("Y-m-d", strtotime($fecha)).'T'.$hora!!}" class="required">
                                </div>
                            </div>
                            <div class="row">
                                <div class="alert alert-danger mt-2 d-none" id="erroresModalModificarGc">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary" id="btnGuardarModGc">
                            Registrar
                        </button>
                        <a data-dismiss="modal" class="btn btn-danger" onclick="cancelarDatosGc()">Cancelar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <br/>
    <p class="text-center">Lorem ipsum dolor sit amet consectetur adipiscing elit, purus tellus accumsan suspendisse vestibulum commodo sociis est, luctus lacus tempor volutpat netus a. Sodales lacinia odio justo consequat mus tempus maecenas ante quam, ut suscipit torquent scelerisque elementum mauris senectus gravida, integer placerat sapien arcu a facilisis taciti et. Pulvinar velit aliquet mi sollicitudin potenti sociis condimentum morbi nam, arcu bibendum tempor eget nascetur leo suscipit dis habitasse faucibus, vivamus lobortis per nullam nec vehicula parturient cras.</p>

    @if ($paciente->isNotEmpty())
    <div class="row justify-content-center m-5">
        <div class="col-auto">
            <table class="table table-striped ">
            <thead>
                <tr>
                    <th scope="col">Hora atención</th>
                    <th scope="col">Prestador</th>
                    <th scope="col">Prestación</th>
                </tr>
            </thead>

            <tbody>

                @foreach($paciente as $p)
                <tr>
                    <td>{{ date("H:i Y-m-d", strtotime($p->hora_atencion.$p->diff)) }}</td>
                    <td>{{$p->prestador}}</td>
                    <td>{{$p->prestacion}}</td>
                </tr>
                @endforeach

            </tbody>
        </table>

        @else
    <p>No hay horas reservadas para el paciente</p>
@endif
@endsection
@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>

                <script>
                //Carga el modal: "modalConfirmar" al cargar la página.
                function confirmarDatos() {
                    if ($('#flagTipoGeo').val() === 'OK') {
                        $("#modalConfirmarGc").modal("show");

                    } else {
                        $("#modalConfirmar").modal("show");
                    }
                };

                //Carga el modal: "modificarDatos" al hacer clic en modificar.
                function modificarDatos() {
                        $("#modalConfirmar").modal("hide");
                        $("#modalModificar").modal("show");
                };

                //Carga el modal de Gc para modificar datos
                function modificarDatosGc() {
                        $("#modalConfirmarGc").modal("hide");
                        $("#modalModificarGc").modal("show");

                }
                //función para ocultar el modal (Ip) al hacer clic en el botón cancelar
                function cancelarDatos() {
                        $("#modalModificar").modal("hide");
                };

                //función para ocultar el modal (Gc) al hacer clic en el botón cancelar
                function cancelarDatosGc() {
                    $("#modalModificarGc").modal("hide");
                };

                //Obtiene la ciudad Ip
                $(document).ready(function(){
                    $("#codigo_pais").change(function(){
                        var pais = $(this).val();

                        $.get('/hora/'+pais, function(data){
                            console.log(data);

                            var ciudad_select
                                //= '<option value="">Seleccione ciudad</option>';

                            for (var i=0; i<data.length;i++)
                                ciudad_select+='<option value="'+data[i].idciudad+'">'+data[i].ciudad+'</option>';

                            $("#codigo_ciudad").html(ciudad_select);
                        });
                    });
                });

                //Obtiene la ciudad Gc
                $(document).ready(function(){
                    $("#btnModPorGc").click(function(){
                        var pais = $('#codigo_pais_Gc').val();
                        var ciudadGc = $('#ciudadGc').val();

                        $.get('/hora/'+pais, function(data){
                            console.log(data);

                            var ciudad_select
                                //= '<option value="">Seleccione ciudad</option>';

                            for (var i=0; i<data.length;i++)
                                ciudad_select+='<option value="'+data[i].ciudad+'">'+data[i].ciudad+'</option>';

                            $("#codigo_ciudad_Gc").html(ciudad_select);
                            $("#codigo_ciudad_Gc").val(ciudadGc);
                        });
                    });
                });

                //Obtiene la ciudad Gc change
                $(document).ready(function(){
                    $("#codigo_pais_Gc").change(function(){

                        var pais = $(this).val();

                        $.get('/hora/'+pais, function(data){
                            console.log(data);

                            var ciudad_select
                                //= '<option value="">Seleccione ciudad</option>';

                            for (var i=0; i<data.length;i++)
                                ciudad_select+='<option value="'+data[i].ciudad+'">'+data[i].ciudad+'</option>';

                            $("#codigo_ciudad_Gc").html(ciudad_select);

                        });
                    });
                });
                //actualizar información formulario datos por Ip
                $(document).ready(function()
                {
                    // boton de guardar
                    $('#btnGuardarPorIP').click(function(event) {
                        event.preventDefault();
                        var dataString = $('#formGuardarPorIp').serialize(); // carga todos los campos para enviarlos
                        // AJAX
                        $.ajax({
                            type: "POST",
                            url: "{{ route('hora.grabarPorIp') }}",
                            data: dataString,
                            success: function(data) {
                                if(data['flag']){
                                    //Escondemos el modal Modificar
                                    $("#modalConfirmar").modal("hide");
                                    console.log(data);

                                    //Si el paciente tiene horas asignadas..
                                    if(data['horas'].length){
                                        $("tbody").html("");

                                        //Por cada uno de las horas, las agregamos al table
                                        data['horas'].forEach(function(hora, indice){
                                            console.log(indice, hora);
                                            let tRowHTML = "<tr>" +
                                                "<td>"+hora['hora_atencion']+"</td>" +
                                                "<td>"+hora['prestador']+"</td>" +
                                                "<td>"+hora['prestacion']+"</td>" +
                                                "</tr>";
                                            $("tbody").append(tRowHTML);
                                        })
                                    }
                                }
                            },
                            error: function(x,z,y){
                                console.warn(x);
                                console.warn(z);
                                console.warn(y);
                                alert("Lamentablemente ha ocurrido un error");
                            }
                        });

                    });

                });

                //actualizar información formulario datos por Gc
                $(document).ready(function()
                {
                    // boton de guardar
                    $('#btnGuardarPorGc').click(function(event) {
                        event.preventDefault();
                        var dataString = $('#formGuardarPorGc').serialize(); // carga todos los campos para enviarlos
                        // AJAX
                        $.ajax({
                            type: "POST",
                            url: "{{ route('hora.grabarGc') }}",
                            data: dataString,
                            success: function(data) {
                                if(data['flag']){
                                    //Escondemos el modal Modificar
                                    $("#modalConfirmarGc").modal("hide");
                                    console.log(data);

                                    //Si el paciente tiene horas asignadas..
                                    if(data['horas'].length){
                                        $("tbody").html("");

                                        //Por cada uno de las horas, las agregamos al table
                                        data['horas'].forEach(function(hora, indice){
                                            console.log(indice, hora);
                                            let tRowHTML = "<tr>" +
                                                "<td>"+hora['hora_atencion']+"</td>" +
                                                "<td>"+hora['prestador']+"</td>" +
                                                "<td>"+hora['prestacion']+"</td>" +
                                                "</tr>";
                                            $("tbody").append(tRowHTML);
                                        })
                                    }
                                }
                            },
                            error: function(x,z,y){
                                console.warn(x);
                                console.warn(z);
                                console.warn(y);
                                alert("Lamentablemente ha ocurrido un error");
                            }
                        });

                    });

                });

                //actualizar información formulario modificar datos geolocalización
                $(document).ready(function()
                {
                    // boton de guardar
                    $('#btnGuardarMod').click(function(event) {
                        event.preventDefault();
                        var dataString = $('#formGuardarMod').serialize(); // carga todos los campos para enviarlos
                        // AJAX
                        $.ajax({
                            type: "POST",
                            url: "{{ route('hora.grabar') }}",
                            data: dataString,
                            success: function(data) {
                                if(data['flag']){
                                    //Escondemos el modal Modificar
                                    $("#modalModificar").modal("hide");
                                    console.log(data);

                                    //Si el paciente tiene horas asignadas..
                                    if(data['horas'].length){
                                        $("tbody").html("");

                                        //Por cada uno de las horas, las agregamos al table
                                        data['horas'].forEach(function(hora, indice){
                                            //console.log(k, hora);
                                            let tRowHTML = "<tr>" +
                                                "<td>"+hora['hora_atencion']+"</td>" +
                                                "<td>"+hora['prestador']+"</td>" +
                                                "<td>"+hora['prestacion']+"</td>" +
                                                "</tr>";
                                            $("tbody").append(tRowHTML);
                                        })
                                    }
                                } else{
                                    if(data["errors"]){

                                        //Eliminamos la clase d-none para poder visualizar el alert de errores
                                        $("#erroresModalModificar").removeClass("d-none");
                                        //Dejamos el div solo con etiqueta ul
                                        $("#erroresModalModificar").html("<ul></ul>");

                                        // Mostramos cada uno de los errores almacenados en data.errors
                                        $.each(data.errors, function(indice, error){
                                            $("#erroresModalModificar ul").append('<li>' + error + '</li>');
                                        });
                                    }
                                }
                            },
                            error: function(x,z,y){
                                console.warn(x);
                                console.warn(z);
                                console.warn(y);
                                alert("Lamentablemente ha ocurrido un error");
                            }
                        });

                    });

                });

                //actualizar información formulario modificar datos Gc
                $(document).ready(function()
                {
                    // boton de guardar
                    $('#btnGuardarModGc').click(function(event) {
                        event.preventDefault();
                        var dataString = $('#formGuardarModGc').serialize(); // carga todos los campos para enviarlos
                        // AJAX
                        $.ajax({
                            type: "POST",
                            url: "{{ route('hora.grabarModGc') }}",
                            data: dataString,
                            success: function(data) {
                                if(data['flag']){
                                    //Escondemos el modal Modificar
                                    $("#modalModificarGc").modal("hide");
                                    console.log(data);

                                    //Si el paciente tiene horas asignadas..
                                    if(data['horas'].length){
                                        $("tbody").html("");

                                        //Por cada uno de las horas, las agregamos al table
                                        data['horas'].forEach(function(hora, indice){
                                            //console.log(k, hora);
                                            let tRowHTML = "<tr>" +
                                                "<td>"+hora['hora_atencion']+"</td>" +
                                                "<td>"+hora['prestador']+"</td>" +
                                                "<td>"+hora['prestacion']+"</td>" +
                                                "</tr>";
                                            $("tbody").append(tRowHTML);
                                        })
                                    }
                                } else{
                                    if(data["errors"]){

                                        //Eliminamos la clase d-none para poder visualizar el alert de errores
                                        $("#erroresModalModificarGc").removeClass("d-none");
                                        //Dejamos el div solo con etiqueta ul
                                        $("#erroresModalModificarGc").html("<ul></ul>");

                                        // Mostramos cada uno de los errores almacenados en data.errors
                                        $.each(data.errors, function(indice, error){
                                            $("#erroresModalModificarGc ul").append('<li>' + error + '</li>');
                                        });
                                    }
                                }
                            },
                            error: function(x,z,y){
                                console.warn(x);
                                console.warn(z);
                                console.warn(y);
                                alert("Lamentablemente ha ocurrido un error");
                            }
                        });

                    });

                });

                //Geolocalización geocoding

                //permisos denegados
                function positionDenied() {
                    console.log("Permiso denegado");

                };

                //permisos aceptados
                function revealPosition(position) {

                    //Obtiene la posición
                    //console.log(position);
                    var crd = position.coords;

                    //Api timezonedb obtiene zona horaria, fecha y hora por latitud y longitud.
                    const PASS = "ES4SAOFV1XO0";
                    let dir = `https://api.timezonedb.com/v2.1/get-time-zone?key=${PASS}&format=json&by=position&lat=${crd.latitude}&lng=${crd.longitude}`;
                    fetch(dir)
                        .then(response => response.json())
                        .then(result=> {
                            let zonaHoraria = result.zoneName;
                            let horaFecha = result.formatted;

                                var date = moment(horaFecha).format("DD-MM-YYYY");
                                var time = moment(horaFecha).format("HH:mm");

                                var dateMod = moment(horaFecha).format("YYYY-MM-DD");
                                var timeMod = moment(horaFecha).format("HH:mm");

                                //asignar valores fecha y hora a los campos del formulario.
                                $('#fechaGc').val(date);
                                $('#horaGc').val(time);
                                $('#fechaModGc').val(dateMod+'T'+timeMod);

                                //asignar valor a input zonaHorariaGc
                                $('#zonaHorariaGc').val(zonaHoraria);

                        })
                        .catch(err => console.warn(err.message));

                    //Api geocoding obtiene el pais y la ciudad por latitud y longitud.
                    const KEY = "";
                    let url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${crd.latitude},${crd.longitude}&key=${KEY}`;
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);
                            let parts = data.results[0].address_components;

                            parts.forEach(part => {
                                if (part.types.includes("country")) {
                                    //we found "country" inside the data.results[0].address_components[x].types array
                                    document.body.insertAdjacentHTML(
                                        "beforeend",
                                        ``
                                    );
                                }

                                if (part.types.includes("administrative_area_level_3")) {
                                    document.body.insertAdjacentHTML(
                                        "beforeend",
                                        ``
                                    );
                                    $('#ciudadGc').val(part.long_name);
                                }

                                $('#paisGc').val(part.long_name);
                                $('#codigo_pais_Gc').val(part.short_name);
                                $('#codPaisGc').val(part.short_name);

                            });

                        })
                        .catch(err => console.warn(err.message));

                };

                //código se ejecuta cuando usuario interactua con las opciones de permiso
                //de geolocalización
                const listenerPermissions = function (resultado){
                    if(resultado.state === "granted"){
                        //input flag con valor OK
                        $('#flagTipoGeo').val('OK');

                        var diff = @json($diferencia);

                        //si diff es distinto a null se recarga la página para que refresque los
                        //valores.
                        if (diff != null){
                            location.reload();
                        }


                        //valida si el usuario a agregado datos de georeferencia a través del campo diff de
                        // la tabla paciente para solicitarlos en el inicio.

                        //si diff es null y el flag está con valor ok despliego modal confirmar gc.
                        if (diff == null)
                        {
                            $("#modalPermiterGeo").modal("hide");
                            $("#modalConfirmarGc").modal("show");
                        }

                    }

                    if(resultado.state === "denied"){
                        //Seguir el camino de la ip
                        $('#flagTipoGeo').val('');

                        //valida si el usuario a agregado datos de georeferencia a través del campo diff de
                        // la tabla paciente para solicitarlos en el inicio.

                        //obtengo dato de la variable diff.
                        var diff = @json($diferencia);

                        //si diff es null y el flag está con valor ok despliego modal confirmar gc.
                        if (diff == null)
                        {
                            $("#modalPermiterGeo").modal("hide");
                            $("#modalConfirmar").modal("show");
                        }
                    }

                    if(resultado.state === "prompt")
                        $("#modalPermiterGeo").modal("show");
                }

                if ("geolocation" in navigator) {
                    const geoSettings = {
                        enableHighAccuracy: true,
                        maximumAge: 30000,
                        timeout: 20000
                    };

                    navigator.geolocation.getCurrentPosition(revealPosition, positionDenied, geoSettings);
                    navigator.permissions.query({name: 'geolocation'})

                        //código se ejecuta cuando usuario recarga la página
                        .then(function (result) {
                            //console.log(result);
                            if(result.state === "granted"){
                                //input flag con valor OK
                                $('#flagTipoGeo').val('OK');

                                //valida si el usuario a agregado datos de georeferencia a través del campo diff en
                                // la tabla paciente para solicitarlos en el inicio.

                                //obtengo dato de la variable diff.
                                var diff = @json($diferencia);
                                if (diff == null)
                                {
                                    $("#modalPermiterGeo").modal("hide");
                                    $("#modalConfirmarGc").modal("show");
                                }

                            }

                            if(result.state === "denied"){
                                //Seguir el camino de la ip
                                $('#flagTipoGeo').val('');

                                //valida si el usuario a agregado datos de georeferencia a través del campo diff de
                                // la tabla paciente para solicitarlos en el inicio.

                                //obtengo dato de la variable diff.
                                var diff = @json($diferencia);

                                //si diff es null y el flag está con valor ok despliego modal confirmar gc.
                                if (diff == null)
                                {
                                    $("#modalPermiterGeo").modal("hide");
                                    $("#modalConfirmar").modal("show");
                                }
                            }

                            if(result.state === "prompt")
                                $("#modalPermiterGeo").modal("show");


                            result.addEventListener("change", function () {
                                listenerPermissions(result);
                            })
                        });
                }

    </script>
@endsection

