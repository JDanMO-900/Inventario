<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Equipos disponibles</title>
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
            margin-top: 1.2rem;
            text-align: justify;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1rem;
        }

        th,
        td {
            border: 1px solid #3b4494;
            text-align: left;
            padding-left: 1rem;
        }

        th {
            background: #b4c6e7;
        }

        .section-title {
            margin-top: 2rem;
            font-size: 1rem;
            font-weight: bold;
            color: #333;
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
            <h4>Equipos disponibles en bodega N6</h4>
        </div>
        <img class="right" src="{{ public_path('images/MCLogo.png') }}" alt="Logo">
    </div>

    @php
        $totales = [];
    @endphp   

    @if (!empty($data) && $data->count()) 
        <div class="section-title">Lista de equipos:</div>
            <div class="contenido">
                <table>
                    <tr>
                        <th>#</th>
                        <th>Tipo de equipo</th>
                        <th>Código de Activo Fijo</th>  
                        <th>Serie</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                    </tr>                
                    @foreach ($data as $key => $report)                    
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $report->type }}</td>
                            <td>{{ $report->number_active }}</td>
                            <td>{{ $report->serial_number }}</td>
                            <td>{{ $report->brand }}</td>
                            <td>{{ $report->model }}</td>
                        </tr> 
                        {{-- Acumular total por tipo --}}
                        @php    
                            if (isset($totales[$report->type])) {
                                $totales[$report->type]++;
                            } else {
                                $totales[$report->type] = 1;
                            }
                        @endphp
                    @endforeach
                </table>
            </div> 
        
        {{-- Mostrar los totales acumulados por tipo --}}
        <div class="section-title">Totales por tipo de equipo:</div>
        <div class="contenido">
            <table>
                <tr>
                    <th>Tipo de equipo</th>
                    <th>Cantidad</th>
                </tr>
                @foreach ($totales as $type => $total)
                    <tr>
                        <td>{{ $type }}</td>
                        <td>{{ $total }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    @else
        <p class="no-data">No hay equipos en bodega.</p>
    @endif   
</body>
</html>