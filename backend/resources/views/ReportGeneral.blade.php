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

        .tdDetail,
        .thDetail {
            border: 1px solid #dddddd;
            text-align: center;
        }


        .contenido {
            font-size: 1.25rem;
            margin: 1rem auto;
        }

        .no-data {
            color: #304463;
            text-align: center;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }

        .page-break {
            page-break-before: always;
            /* O usa page-break-after: always; para el salto de página después del elemento */
        }
    </style>
</head>

</div>

<body>
    <div class="header">
        <div class="header-text">
            <img class="left-img" src="{{ public_path('images/LogoBinaes.png') }}" alt="Logo">
            <img class="rigth-img" src="{{ public_path('images/MCLogo.png') }}" alt="Logo">
            <h3> <b>Biblioteca Nacional de El Salvador</b> Sección de informática</h3>
        </div>
        <h2 class="header-text"> Reporte General de Equipos por Marca </h2>
    </div>
    @if (!empty($data))
        <div class="contenido">
            <div class="header-text">
                @if ($data[0]['request'] == 'TODAS LAS MARCAS' || $data[0]['request'] == '')

                @else
                <h3> <b>Equipos: </b> {{ $data[0]['request'] }}</h3>
                @endif
            </div>
            @foreach ($data as $equipment)
                <hr>
                <table>
                    <tr>
                        <th colspan="2">Fecha de Asignación:</th>
                        <td colspan="2"> {{ $equipment['assignment_date'] }}</td>

                    </tr>
                    <tr>
                        <th>Dependencia:</th>
                        <td>{{ $equipment['dependency'] }}</td>
                        <th>Ubicación:</th>
                        <td>{{ $equipment['location'] }}</td>
                    </tr>
                </table>
                <br>
                <table>
                    <tr>
                        <th>Tipo de equipo:</th>
                        <td>{{ $equipment['type'] }}</td>
                        <th>Número activo fijo:</th>
                        <td>{{ $equipment['number_active'] }}</td>

                    </tr>
                    <tr>
                        <th>Modelo:</th>
                        <td>{{ $equipment['model'] }}</td>

                        <th>Serial:</th>
                        <td>{{ $equipment['serial_number'] }}</td>

                    </tr>
                    <tr>
                        @if ($equipment['request'] == 'TODAS LAS MARCAS')
                        <th>Marca:</th>
                        <td> {{ $equipment['brand'] }}</td>
                        <th>Estado del equipo:</th>
                        <td>{{ $equipment['state'] }}</td>
                        @else
                        <th>Estado del equipo:</th>
                        <td>{{ $equipment['state'] }}</td>
                        <th></th>
                        <td></td>
                        @endif

                    </tr>
                </table>
                <div class="header-text">
                    <h4> <b>Detalles Técnicos</b></h4>
                </div>
                <hr>
                @if (!empty($equipment['technical_details']))
                    <table>
                        <tbody>
                            <tr>
                                <th class="thDetail">Componente</th>
                                <th class="thDetail">Capacidad</th>
                            </tr>
                            @foreach ($equipment['technical_details'] as $detail)
                                <tr>
                                    <td class="tdDetail">{{ $detail['component'] }}</td>
                                    <td class="tdDetail">{{ $detail['capacity'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="no-data">No hay datos disponibles.</p>
                @endif
                <div class="page-break"></div>
            @endforeach
        </div>
    @else
        <p class="no-data">No hay datos disponibles.</p>
    @endif

</body>



</html>
