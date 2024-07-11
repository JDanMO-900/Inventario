<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte General de Equipos</title>

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
        .tdeail{
            text-align: center;
        }

        .contenido {
            font-size: 1.25rem;
            margin: 1rem auto;
        }
    </style>
</head>

</div>

<body>
    <div class="header">
        <img class="left" src="{{ public_path('images/LogoBinaes.png') }}" alt="Logo">
        <div class="title">
            <h1>Biblioteca Nacional de El Salvador</h1>
            <h2>Sección de informática</h2>
            <h4>Reporte de inventario general</h4>
        </div>
        <img class="right" src="{{ public_path('images/MCLogo.png') }}" alt="Logo">
    </div>
    @if (!empty($data))

        <table>
            <tr>
                <th>#</th>
                <th>Tipo de equipo</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Activo Fijo</th>
                <th>Estado</th>
                <th>Ubicación</th>
                <th>Detalles técnicos</th>
            </tr>
            @foreach ($data as $key => $report)
                <tr>
                    <td class="tdeail">{{ ++$key }}</td>
                    <td>{{ $report['type'] }}</td>
                    <td class="tdeail">{{ $report['brand'] }}</td>
                    <td class="tdeail">{{ $report['model'] }}</td>
                    <td class="tdeail">{{ $report['number_active'] }}</td>
                    <td class="tdeail">{{ $report['state'] }}</td>
                    <td class="tdeail">
                        @php
                            $location = json_decode($report['location'], true);
                            echo $location['location'] ?? 'No definida';
                        @endphp
                    </td>
                    <td>{!! nl2br($report['detail']) !!}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p class="no-data">No hay datos disponibles.</p>
    @endif

</body>

</html>
