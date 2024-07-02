<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte</title>

    <style>
        body {
            margin: 0;
        }

        .header {
            padding: 1rem;
            max-width: 62.5rem;
            margin: 0 auto;
            display: flex;
            align-content: center;
        }

        .header-text {
            flex-grow: 1;
            line-height: 1.2;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            text-align: center;
            text-transform: uppercase;
        }

        .left-img {
            max-width: 14.5%;
            max-height: 14.5%;
            margin-right: 3%;
            float: left;
        }
        .rigth-img {
            max-width: 14.5%;
            max-height: 14.5%;
            margin-right: 3%;
            float: right;
        }


        h2 {
            text-align: center;
            text-transform: uppercase;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
        }

        .contenido {
            font-size: 1.25rem;
            margin: 3rem auto;
        }
    </style>
</head>
@foreach ($data as $report)

    <body>
        <div class="header">
            <div class="header-text">
                <img class="left-img" src="{{ public_path('images/LogoBinaes.png') }}" alt="Logo" >
                <img class="rigth-img" src="{{ public_path('images/MCLogo.png') }}" alt="Logo" >
                <h3> <b>Biblioteca Nacional de El Salvador</b> Sección de informática</h3>
            </div>
            <h2> Reporte General de Equipos por Marca </h2>
        </div>



       {{--  {{ $report }} --}}


        <hr>
        <div class="contenido">
            <table>
                <tr>
                    <th colspan="2">Fecha:</th>
                    <td colspan="2"> {{ $report->start_date }}</td>

                </tr>
                <tr>
                    <th colspan="2">Área:</th>
                    <td colspan="2">Servicios de Información</td>

                </tr>
                <tr>
                    <th>Dependencia:</th>
                    <td>{{ $report->dependency_id }}</td>
                    <th>Ubicación:</th>
                    <td>{{ $report->location_id }}</td>
                </tr>
            </table>
        </div>

        <div class="contenido">
            <table>
                <tr>
                    <th>Tipo de equipo:</th>
                    <td>Laptop</td>
                    <th>Número activo fijo:</th>
                    <td>{{ $report->number_internal_active }}</td>

                </tr>
                <tr>
                    <th>Modelo:</th>
                    <td>{{ $report->model }}</td>

                    <th>Serial:</th>
                    <td>{{ $report->equipment_id }}</td>

                </tr>
                <tr>
                    <th>Marca:</th>
                    <td> {{ $report->brand }}</td>
                    <th>Estado del equipo:</th>
                    <td>Buen estado</td>
                </tr>
            </table>
        </div>


    </body>
@endforeach


</html>
