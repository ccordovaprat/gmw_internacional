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
<li class="nav-item">
    <a class="nav-link btn-dark" href="/logout">Salir</a>
</li>
@endsection

@section('content')

        <h5 mt-5>Zona horaria: {{$zh->nombrezona}}</h5>
    <h6>Diferencia horaria: {{$hdif}}</h6>
    <br/>
    <p style="text-align:center">Lorem ipsum dolor sit amet consectetur adipiscing elit, purus tellus accumsan suspendisse vestibulum commodo sociis est, luctus lacus tempor volutpat netus a. Sodales lacinia odio justo consequat mus tempus maecenas ante quam, ut suscipit torquent scelerisque elementum mauris senectus gravida, integer placerat sapien arcu a facilisis taciti et. Pulvinar velit aliquet mi sollicitudin potenti sociis condimentum morbi nam, arcu bibendum tempor eget nascetur leo suscipit dis habitasse faucibus, vivamus lobortis per nullam nec vehicula parturient cras.</p>

    @if ($paciente->isNotEmpty())
    <div class="row justify-content-center m-5">
        <div class="col-auto">
            <table class="table table-striped ">
        <tr>
            <th scope="col">Id hora</th>
            <th scope="col">Paciente</th>
            <th scope="col">Hora atención</th>
            <th scope="col">Prestador</th>
            <th scope="col">Prestación</th>
        </tr>
        <tbody>
        <tr>
            @foreach($paciente as $p)
                <td>{{$p->idhora}}</td>
                <td>{{$p->paciente}}</td>
                <td>{{ date("Y-m-d H:i", strtotime($p->hora_atencion.$hdif)) }}</td>
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

