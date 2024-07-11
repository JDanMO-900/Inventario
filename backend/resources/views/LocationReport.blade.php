<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte</title>

    <style>
        body {
            margin: 0;
            font-family: arial, sans-serif;
        }

        .header {
            align-items: center;
            text-align: center;
            display: flex;
            justify-content: space-between;
        }

        .header img {
            padding-top: 2rem;
            position: absolute;
            top: 0;

        }

        .header img.left {
            left: 0;
            width: 4.5rem;
            height: auto;
        }

        .header img.right {
            width: 7.5rem;
            height: auto;
            right: 0;
        }

        .header .title {
            margin: 1.5rem 7.5rem 0 4.5rem;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        h1 {
            font-size: 1.4rem;
        }

        h2 {
            font-size: 1.2rem;
            margin-top: -14px;
        }

        h4 {
            color: #373A40;
        }

        p {
            margin: 0;
            font-size: 0.85rem
        }

        .contenido {
            margin-top: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        th,
        td {
            border: 1px solid #3b4494;
            text-align: left;
            padding: 0.4 rem;
        }

        th {
            background: #b4c6e7;
            text-align: center;
        }

        .no-data {
            color: #304463;
            text-align: center;
        }
    </style>

</head>


<body>


    <div class="header">
        <img class="left" src="{{ public_path('images/LogoBinaes.png') }}" alt="Logo">
        <div class="title">
            <h1>Biblioteca Nacional de El Salvador</h1>
            <h2>Sección de informática</h2>
            <h4>Reporte de inventario por Ubicación</h4>
        </div>
        <img class="right" src="{{ public_path('images/MCLogo.png') }}" alt="Logo">
    </div>

    @foreach ($data as $report)
        <div class="contenido">

            <table>
                <tr>
                    <th>Ubicación </th>
                    <th>Cod. Activo Fijo</th>
                    <th>Marca</th>
                    <th>Tipo de equipo </th>
                    <th>Modelo</th>
                    <th>Serie</th>
                </tr>

                <tr>
                    <td> {{ $report->location_id }} </td>
                    <td>{{ $report->number_active }}</td>
                    <td>{{ $report->brand }} </td>
                    <td>{{ $report->type }} </td>
                    <td>{{ $report->model }} </td>
                    <td>{{ $report->serial_number }}</td>
                </tr>

                <tr>

                    <th colspan="6">Especificaciones Técnicas</th>
                </tr>
                @if (!empty($report['technicalAttributes']))
                @foreach ($report['technicalAttributes'] as $attribute)
                    <tr>
                        <td colspan="3">{{ $attribute['technicalDescription'] }}</td>
                        <td colspan="3">{{ $attribute['attribute'] }}</td>
                    </tr>
                @endforeach
                @else
                <tr >
                    <td colspan="6" class="no-data">No hay datos disponibles</td>
                    

                </tr>
                    
                @endif
                

                <tr>

                    <th colspan="6">Detalles Técnicos</th>
                </tr>
                <tr>
                    <th>Movimiento realizado</th>
                    <td> {{ $report->type_action_id }} </td>
                    <th>Fecha de inicio</th>
                    <td>{{ $report->start_date }}</td>
                    <th>Fecha de finalización</th>
                    <td>{{ $report->end_date }} </td>
                </tr>

            </table>

        </div>
    @endforeach


</body>



</html>
