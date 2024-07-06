<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte</title>


    <style>
        body {
            margin: 0;
            font-family: arial, sans-serif;
            font-family: arial, sans-serif;
        }

        .header {
            align-items: center;
            text-align: center;
            align-items: center;
            text-align: center;
            display: flex;
            justify-content: space-between;
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
            margin-bottom: 0;
            padding-bottom: 0;
        }

        h1 {
            font-size: 1.4rem;
        }

        h2 {
            font-size: 1.2rem;
        }

        p {
            margin: 0;
            font-size: 0.85rem

        h2 {
            font-size: 1.2rem;
        }

        p {
            margin: 0;
            font-size: 0.85rem
        }

        .contenido {
            margin-top: 2rem;
        .contenido {
            margin-top: 2rem;
        }

        table {
            width: 100%;
            width: 100%;
            border-collapse: collapse;
            font-size: 1rem;


        }

        th,
        td {
            border: 1px solid #3b4494;
            text-align: left;
            padding: 0.4 rem;
        }

        th {
            background: #b4c6e7;
            padding: 0.4 rem;
        }

        th {
            background: #b4c6e7;
        }

        .section-title {
            margin-top: 2rem;
            font-size: 1rem;
            font-weight: bold;
            color: #333;
        .section-title {
            margin-top: 2rem;
            font-size: 1rem;
            font-weight: bold;
            color: #333;
        }
    </style>


</head>


<body>
    <div class="header">
        <img class="left" src="{{ public_path('images/LogoBinaes.png') }}" alt="Logo">
        <div class="title">
            <h1>Biblioteca Nacional de El Salvador</h1>
            <h2>Sección de informática</h2>

        </div>
        <img class="right" src="{{ public_path('images/MCLogo.png') }}" alt="Logo">
    </div>

    @foreach ($data as $report)
        <div class="contenido">


            <table>
                <tr>
                    <th>Ubicación </th>
                    <td> {{ $report->location_id }} </td>

                    <th>Cod. Activo Fijo</th>
                    <td>{{ $report->number_active }}</td>

                    <th>Marca</th>
                    <td>{{ $report->brand }} </td>

                </tr>

                <tr>
                    <th>Tipo de equipo </th>
                    <td>{{ $report->type }} </td>

                    <th>Modelo</th>
                    <td>{{ $report->model }} </td>
                    <th>Serie</th>
                    <td>{{ $report->serial_number }}</td>

                </tr>


                <tr>

                    <th colspan="6">Detalles Técnicos</th>
                </tr>
                @foreach ($report['technicalAttributes'] as $attribute)
                    <tr>
                        <td colspan="3">{{ $attribute['technicalDescription'] }}</td>
                        <td colspan="3">{{ $attribute['attribute'] }}</td>
                    </tr>
                @endforeach

            </table>

        </div>
    @endforeach

</body>



</html>
