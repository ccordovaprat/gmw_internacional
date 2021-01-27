
<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.79.0">
    <title>@yield('title') - Global Med Web</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sticky-footer-navbar/">



    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="/docs/5.0/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/5.0/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/5.0/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#7952b3">


    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="{{asset('public/css/style.css')}}">
</head>
<body class="d-flex flex-column h-100">

<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark bg-primary sticky-top ">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Global Med Web Internacional</a>
            @yield('headerdiv1')
        </div>
            @yield('headerdiv2')
    </nav>
</header>

<!-- Begin page content -->
<main role="main" class="container">
    <div class="row mt-4">
        <div class="col-12">
            @yield('content')
        </div>
    </div>
</main>

{{--<footer class="footer mt-auto py-3 bg-light">--}}
{{--    <div class="container">--}}
{{--        <span class="text-muted">Place sticky footer content here.</span>--}}
{{--    </div>--}}
{{--</footer>--}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
        crossorigin="anonymous"></script>
@yield('js')

{{--Se comenta el siguiente código que utiliza la api geocoding que obtiene datos de la ubicación--}}
{{--del paciente para ser usado en un futuro.--}}

{{--<script>--}}
{{--    function success(pos) {--}}

{{--        //función para obtener fecha--}}
{{--        var curday = function (sp) {--}}
{{--            today = new Date();--}}
{{--            var dd = today.getDate();--}}
{{--            var mm = today.getMonth() + 1; //As January is 0.--}}
{{--            var yyyy = today.getFullYear();--}}

{{--            if (dd < 10) dd = '0' + dd;--}}
{{--            if (mm < 10) mm = '0' + mm;--}}
{{--            return (mm + sp + dd + sp + yyyy);--}}
{{--        };--}}
{{--        //console.log(curday('/'));--}}
{{--        //console.log(curday('-'));--}}

{{--        //función para obtener la hora, minutos y segundos--}}
{{--        var fechaHora = new Date();--}}
{{--        var horas = fechaHora.getHours();--}}
{{--        var minutos = fechaHora.getMinutes();--}}
{{--        var segundos = fechaHora.getSeconds();--}}

{{--        if (horas < 10) {--}}
{{--            horas = '0' + horas;--}}
{{--        }--}}
{{--        if (minutos < 10) {--}}
{{--            minutos = '0' + minutos;--}}
{{--        }--}}
{{--        if (segundos < 10) {--}}
{{--            segundos = '0' + segundos;--}}
{{--        }--}}

{{--        //función para obtener zona horaria--}}
{{--        var resolvedOptions = Intl.DateTimeFormat().resolvedOptions();--}}
{{--        var zonaHoraria = resolvedOptions.timeZone;--}}

{{--        $('#fecha').val(curday('-'));--}}
{{--        $('#hora').val(horas);--}}
{{--        $('#minutos').val(minutos);--}}

{{--        var crd = pos.coords;--}}

{{--        const KEY = "AIzaSyAwSfkmMbLAYTYWzekPTF91O8X3UBEbAYk";--}}
{{--        let url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${crd.latitude},${crd.longitude}&key=${KEY}`;--}}
{{--        fetch(url)--}}
{{--            .then(response => response.json())--}}
{{--            .then(data => {--}}
{{--                console.log(data);--}}
{{--                let parts = data.results[0].address_components;--}}

{{--                parts.forEach(part => {--}}
{{--                    if (part.types.includes("country")) {--}}
{{--                        //we found "country" inside the data.results[0].address_components[x].types array--}}
{{--                        document.body.insertAdjacentHTML(--}}
{{--                            "beforeend",--}}
{{--                            ``--}}
{{--                        );--}}
{{--                    }--}}

{{--                    if (part.types.includes("administrative_area_level_3")) {--}}
{{--                        document.body.insertAdjacentHTML(--}}
{{--                            "beforeend",--}}
{{--                            ``--}}
{{--                        );--}}
{{--                        $('#ciudad').val(part.long_name);--}}
{{--                    }--}}
{{--                    $('#pais').val(part.long_name);--}}
{{--                });--}}
{{--            })--}}
{{--            .catch(err => console.warn(err.message));--}}

{{--    };--}}

{{--    function error(err) {--}}
{{--        console.warn('ERROR(' + err.code + '): ' + err.message);--}}
{{--    };--}}

{{--    navigator.geolocation.getCurrentPosition(success, error);--}}

{{--</script>--}}
</body>
</html>

