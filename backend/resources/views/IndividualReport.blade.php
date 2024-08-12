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
@php
    $lastTrheeReoports = $data->take(5);
@endphp

@foreach ($lastTrheeReoports as $report)
    <body>
        <div class="header">
            <img class="left" src="{{ public_path('images/LogoBinaes.png') }}" alt="Logo">
            <div class="title">
                <h1>Biblioteca Nacional de El Salvador</h1>
                <h2>Sección de informática</h2>
                <h4>Información del equipo</h4>
            </div>
            <img class="right" src="{{ public_path('images/MCLogo.png') }}" alt="Logo">
        </div>

        <div class="contenido">
            <table>
                <tr>
                    <th colspan="4" style="text-align: center">Detalles Generales del Equipo</th>
                </tr>
                <tr>
                    <th colspan="2">Tipo de equipo</th>
                    <td colspan="2">{{ $report->type }}</td>
                </tr>
                <tr>
                    <th colspan="2">Modelo</th>
                    <td colspan="2">{{ $report->model }}</td>
                </tr>
                <tr>
                    <th colspan="2">Código de Activo Fijo</th>
                    <td colspan="2">{{ $report->number_active }}</td>
                </tr>
                <tr>
                    <th colspan="2">Número de Serie</th>
                    <td colspan="2">{{ $report->serial_number }} </td>
                </tr>
            </table>
        </div>

        <div class="contenido">
            <table>
                <tr>
                    <th colspan="2" style="text-align: center">Características Técnicas</th>
                </tr>
                @if (!empty($report->technicalAttributes))
                    @foreach ($report['technicalAttributes'] as $attribute)
                        <tr>
                            <td>{{ $attribute['technicalDescription'] }}</td>
                            <td>{{ $attribute['attribute'] }}</td>
                        </tr>
                    @endforeach
                @else 
                <td colspan="2" class="no-data">No hay datos disponibles</td>
                @endif
            </table>
        </div>


        <div class="contenido">
            <table>
                <table>
                    <tr>
                        <th colspan="2" style="text-align: center">Responsable y Ubicación</th>
                    </tr>
                    <tr>
                        <th>Nombre</th>
                        @if (!empty($report->users && $report->availability==0))
                            <td>{{ $report->users }}</td>
                        @else
                            <td class="no-data">No hay datos disponibles</td>
                        @endif
                    </tr>

                    <tr>
                        <th>Ubicación</th>
                        @if (!empty($report->location && $report->availability==0))
                            <td>{{ $report->location }}</td>
                        @else
                            <td class="no-data">No hay datos disponibles</td>
                        @endif

                    </tr>
                    <tr>
                        <th>Fecha de Asignación o Traslado</th>
                        @if (!empty($report->start_date && $report->availability==0))
                        <td>{{ $report->start_date }}</td>
                        @else
                            <td class="no-data">No hay datos disponibles</td>
                        @endif
                    </tr>



                </table>
        </div>



        <div class="contenido">
            <p><b>Observaciones: </b> {{ $report->description }}</p>
        </div>

        <div class="contenido">
            <p>
                <b>CLÁUSULA DE COMPROMISO:</b> Declaro mi conocimiento y compromiso de mantener en un lugar seguro y
                en
                óptimas
                condiciones de uso el equipo y los accesorios propiedad de la BIBLIOTECA NACIONAL DE EL SALVADOR,
                identificados en este documento, prohibiendo su utilización a cualquier otra persona.

                Asimismo, reconozco que cualquier daño causado por un uso inadecuado o una conservación deficiente
                hasta
                la fecha de su devolución será de mi entera responsabilidad, incluyendo los costos asociados a las
                reparaciones necesarias. En caso de robo o hurto, me comprometo a informar de inmediato a la Sección
                de
                Informática. La omisión de esta notificación conllevará la obligación de cubrir los costos
                correspondientes a la restitución de los aparatos y accesorios.

                Además, declaro haber recibido en buen estado el equipo y los accesorios detallados, y reconozco que
                están bajo mi custodia. Autorizo a la BIBLIOTECA NACIONAL DE EL SALVADOR a descontar de mi salario
                la
                suma equivalente a cada accesorio en caso de pérdida o daño comprobado.

                Esta disposición no aplicará en casos de fuerza mayor o pérdida por caso fortuito debidamente
                comprobados, o por la existencia de explicaciones razonables que excluyan conductas dolosas o
                negligentes por parte del Usuario. También autorizó que, en caso de terminación de mi contrato
                laboral
                por cualquier motivo y de no devolver el equipo, se descuente de mi liquidación el saldo pendiente
                por
                pérdida de los accesorios y/o el Equipo.

                La firma de este documento implica mi conformidad con el adecuado funcionamiento del equipo en el
                momento de la entrega y mi aceptación de todos los términos establecidos en esta declaración de
                responsabilidad.
            </p>
        </div>
        {{-- 
            @endforeach --}}
    </body>
@endforeach
</html>