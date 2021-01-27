@extends('plantilla')

@section('content')

    <!--Modal para confirmar datos de geolocalización-->
    <div class="modal fade" id="modalConfirmar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Por favor confirmanos si los siguientes datos son correctos:</h4>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col mt-3">
                                <h6>País: {{ $pais ?? '' }}</h6>
                            </div>
                            <div class="col mt-3">
                                <h6>Ciudad: {{ $ciudad ?? '' }}</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mt-3">
                                <h6>Fecha: {{ $fecha ?? '' }}</h6>
                            </div>
                            <div class="col mt-3">
                                <h6>Hora: {{ $hora ?? '' }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a data-dismiss="modal" class="btn btn-primary" onclick="confirmarDatos()">Confirmar</a>
                        <a href="#modalModificar" data-dismiss="modal" class="btn btn-danger"
                           onclick="modificarDatos()">Modificar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Modal para modificar datos de geolocalización-->
    <div class="modal fade" id="modalModificar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Por favor modifica los datos que no son correctos:</h4>
                </div>
                    <div class="modal-body">
                        <div class="container">
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
                        <a data-dismiss="modal" class="btn btn-primary" onclick="guardarDatosMod()">Guardar</a>
                        <a data-dismiss="modal" class="btn btn-danger" onclick="cancelar()">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>

{{--    Login del usuario--}}
                        <div class="container mt-5">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-header">Acceso</div>

                                        <div class="card-body">
                                            <form method="POST" action="{{ route('login') }}">
                                                @csrf
                                                <input type="hidden" id="zh_paciente" name="zh_paciente" value="">
                                                <input type="hidden" id="ciudad_paciente" name="ciudad_paciente" value="{{$ciudad}}">
                                                <input type="hidden" id="pais_mod" name="pais_mod" value="">
                                                <input type="hidden" id="ciudad_mod" name="ciudad_mod" value="">
                                                <input type="hidden" id="fecha_mod" name="fecha_mod" value="">

                                                <div class="form-group row">
                                                    <label for="rut"
                                                           class="col-md-4 col-form-label text-md-right">{{ __('Documento') }}</label>

                                                    <div class="col-md-6">
                                                        <input id="rut" type="text"
                                                               class="form-control @error('rut') is-invalid @enderror"
                                                               name="rut" value="{{ old('rut') }}" required
                                                               autocomplete="rut" autofocus>

                                                        @error('rut')
                                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="password"
                                                           class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                                                    <div class="col-md-6">
                                                        <input id="password" type="password"
                                                               class="form-control @error('password') is-invalid @enderror"
                                                               name="password" required autocomplete="contraseña">

                                                        @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-md-6 offset-md-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="remember"
                                                                   id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                            <label class="form-check-label" for="remember">
                                                                {{ __('Recuerdame') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row mb-0">
                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-primary">
                                                            {{ __('Ingresar') }}
                                                        </button>

                                                        {{--
                                                        @if (Route::has('password.request'))
                                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                                {{ __('Forgot Your Password?') }}
                                                            </a>
                                                        @endif
                                                        --}}
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endsection

@section('js')
    <script>
        //Carga el modal: "modalConfirmar" al cargar la página.
        $(document).ready(function () {
            // $("#modalConfirmar").modal("show");
        });

        //Carga el modal: "modificarDatos" al hacer clic en modificar.
        function modificarDatos() {
            $("#modalConfirmar").modal("hide");
            $("#modalModificar").modal("show");

        }

        //Carga el modal: "modificarDatos" al hacer clic en modificar.
        function confirmarDatos() {
            var zh_p = @json($zonaHoraria);
            var ciudad_p = @json($ciudad);
            $('#zh_paciente').val(zh_p);
            $('#ciudad_paciente').val(ciudad_p);
            $("#modalConfirmar").modal("hide");
        }

        //Guardar datos una vez modificados por el paciente.
        function guardarDatosMod() {
            $('#pais_mod').val($('#codigo_pais').val());
            $('#ciudad_mod').val($('#codigo_ciudad').val());
            $('#fecha_mod').val($('#fecha').val());
            var fecha = $('#fecha_mod').val();
            var d = new Date(fecha);
            $('#fecha_mod').val(d.toLocaleString());
            $("#modalModificar").modal("hide");
        }

        //Salir de modal
        function cancelar() {
            $("#modalModificar").modal("hide");
        }

        //Obtiene la ciudad
        $(document).ready(function(){
            $("#codigo_pais").change(function(){
                var pais = $(this).val();

                $.get('geodatos/'+pais, function(data){
                    console.log(data);

                    var ciudad_select = '<option value="">Seleccione ciudad</option>';

                    for (var i=0; i<data.length;i++)
                        ciudad_select+='<option value="'+data[i].idciudad+'">'+data[i].ciudad+'</option>';

                    $("#codigo_ciudad").html(ciudad_select);
                });
            });
        });

    </script>
@endsection
