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
    <!--Modal para confirmar datos de geolocalización-->
    <div class="modal fade" id="modalConfirmar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <form class="form-horizontal" method="POST" action="{{ route('hora.grabarPorIp') }}" name="formGuardarPorIp" id="formGuardarPorIp">
            {{ csrf_field() }}
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4>Por favor confirmanos si los siguientes datos son correctos:</h4>
                    <div class="container mt-3">
                        <div class="row">
                            <div class="col mt-3">
                                <h6>País: {{ $pais ?? '' }}</h6>
                                <input id="paisIp" name="paisIp" type="hidden" value="{{$pais ?? ''}}">
                            </div>
                            <div class="col mt-3">
                                <h6>Ciudad: {{ $ciudad ?? '' }}</h6>
                                <input id="ciudadIp" name="ciudadIp" type="hidden" value="{{$ciudad ?? ''}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mt-3">
                                <h6>Fecha: {{ $fecha ?? '' }}</h6>
                                <input id="fechaIp" name="fechaIp" type="hidden" value="{{ $fecha ?? '' }}">
                            </div>
                            <div class="col mt-3">
                                <h6>Hora: {{ $hora ?? '' }}</h6>
                                <input id="zhIp" name="zhIp" type="hidden" value="{{  $zonaHoraria ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnGuardarPorIP">
                            Registrar
                        </button>
{{--                        <a data-dismiss="modal" class="btn btn-primary" onclick="confirmarDatos()">Confirmar</a>--}}
                        <a href="#modalModificar" data-dismiss="modal" class="btn btn-danger"
                           onclick="modificarDatos()">Modificar</a>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>

    <!--Modal para modificar datos de geolocalización-->
    <div class="modal fade" id="modalModificar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <form class="form-horizontal" method="POST" action="{{ route('hora.grabar') }}" name="formGuardarMod" id="formGuardarMod">
            {{ csrf_field() }}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4>Por favor modifica los datos que no son correctos:</h4>
                    <div class="container mt-3">
                        <div class="row">
                            <div class="col-md-5">
                                Pais:
                                <select id="codigo_pais" name="codigo_pais" class="form-control" required="required">
                                    <option value="">Seleccione país</option>
                                    @foreach ($pais_lista as $p)
                                        {{--                                            <option value="{{ $p->codigo}}" {{ ($p->codigo == old('codigo_pais',$codPais))?'selected':'' }} >{{ $p->pais }}</option>--}}
                                        <option value="{{ $p->codigo}}">{{ $p->pais }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                Ciudad:
                                <select id="codigo_ciudad" name="codigo_ciudad" class="form-control" required="required">
                                    <option value="">Seleccione ciudad</option>
                                    {{--                                        @foreach ($ciudad_lista as $c)--}}
                                    {{--                                            <option value="{{ $c->idciudad}}">{{ $c->ciudad }}</option>--}}
                                    {{--                                        @endforeach--}}
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mt-3">
                                Fecha y hora:
                                <input type="datetime-local" id="fecha"
                                       name="fecha" value="" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
{{--                    <a data-dismiss="modal" class="btn btn-primary" onclick="guardarDatosMod()">Guardar</a>--}}
                    <button type="submit" class="btn btn-primary" id="btnGuardarMod">
                        Registrar
                    </button>
                    <a data-dismiss="modal" class="btn btn-danger" onclick="cancelar()">Cancelar</a>
                </div>
            </div>
        </div>
        </form>
    </div>

    <br/>
    <p style="text-align:center">Lorem ipsum dolor sit amet consectetur adipiscing elit, purus tellus accumsan suspendisse vestibulum commodo sociis est, luctus lacus tempor volutpat netus a. Sodales lacinia odio justo consequat mus tempus maecenas ante quam, ut suscipit torquent scelerisque elementum mauris senectus gravida, integer placerat sapien arcu a facilisis taciti et. Pulvinar velit aliquet mi sollicitudin potenti sociis condimentum morbi nam, arcu bibendum tempor eget nascetur leo suscipit dis habitasse faucibus, vivamus lobortis per nullam nec vehicula parturient cras.</p>

    @if ($paciente->isNotEmpty())
    <div class="row justify-content-center m-5">
        <div class="col-auto">
            <table class="table table-striped ">
        <tr>
            <th scope="col">Hora atención</th>
            <th scope="col">Prestador</th>
            <th scope="col">Prestación</th>
        </tr>
        <tbody>
        <tr>
            @foreach($paciente as $p)
                <td>{{ date("Y-m-d H:i", strtotime($p->hora_atencion.$p->diff)) }}</td>
                <td>{{$p->prestador}}</td>
                <td>{{$p->prestacion}}</td>
        </tr>
        @endforeach

        </tbody>
    </table>
            <div/>
            <div/>

        @else
    <p>No hay horas reservadas para el paciente</p>
@endif
@endsection
@section('js')
    <script>
                //Carga el modal: "modalConfirmar" al cargar la página.
                function confirmarDatos() {
                $("#modalConfirmar").modal("show");
                };

                //Carga el modal: "modificarDatos" al hacer clic en modificar.
                function modificarDatos() {
                $("#modalConfirmar").modal("hide");
                $("#modalModificar").modal("show");

                };

                //Obtiene la ciudad
                $(document).ready(function(){
                    $("#codigo_pais").change(function(){
                        var pais = $(this).val();

                        $.get('/hora/'+pais, function(data){
                            console.log(data);

                            var ciudad_select = '<option value="">Seleccione ciudad</option>';

                            for (var i=0; i<data.length;i++)
                                ciudad_select+='<option value="'+data[i].idciudad+'">'+data[i].ciudad+'</option>';

                            $("#codigo_ciudad").html(ciudad_select);
                        });
                    });
                });

                //actualizar información formulario datos por ip geolocalización
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
                                //alert(data['apellidoPaterno']);
                                $("#modalConfirmar").modal("hide");
                                location.reload();

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
                                //alert(data['apellidoPaterno']);
                                $("#modalModificar").modal("hide");
                                location.reload();
                            }
                        });

                    });

                });

                //valida si el usuario a agregado datos de georeferencia para solicitarlos en el inicio
                //de sesión.
                $(document).ready(function () {
                    var diff = @json($diferencia);
                    if (diff == null)
                    {
                        $("#modalConfirmar").modal("show");
                    }
                });

    </script>
@endsection

