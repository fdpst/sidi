<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>Factura</title>
    <style>
        * body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
        }

        /*div table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }*/
    </style>
</head>

<body>
    @php
        $subtotal = 0;
    @endphp
    <div style="border:1px solid grey;">
        <table >
            <tr>
                <td >
                    <span style="font-size:45px;font-weight:200;">Factura</span><br>
                    <span style="color:grey;">Número:</span> {{$report_pdf['nro_factura']}}<br>
                    <span style="color:grey;">Fecha: </span>{{$report_pdf['fecha']}}
                </td>

                <td style="text-align: center;">
                    <br>
                    <span style="font-weight:200;">Fidias Technology S.L.</span><br>
                    <span style="color:grey;">C/Granados 19, Bajo D.</span><br>
                    <span style="color:grey;">03160 Almoradí</span><br>
                    <span style="color:grey;">B01807064</span><br>
                    <span style="color:grey;">info@fidiaspro.com</span><br>
                    <span style="color:grey;">Telf: 865516837</span><br>
                </td>

                <td style="text-align: right;">
                    <img src="https://plataforma.fidiaspro.com/logo.jpg" height="80" width="250" style="text-align: right;">
                </td>
            </tr>
        </table>
        <table style="border:1px solid grey;width:95%;margin: 0 auto;">
            <tr>
                <td style="width:20px;">
                    <span style="color:grey; margin-left:10px;">Cliente:</span>
                </td>
                <td>
                    {{$proyecto['usuario']['nombre']}}
                </td>
            </tr>
            <tr>
                <td style="width:20px;">
                    <span style="color:grey; margin-left:10px;">Domicilio:</span>
                </td>
                <td >
                    {{$proyecto['usuario']['direccion']}}
                </td>
            </tr>
            <tr>
                <td style="width:20px;">
                    <span style="color:grey; margin-left:10px;">CIF/DNI:</span>
                </td>
                <td>
                    {{$proyecto['usuario']['cif']}}
                </td>
            </tr>
        </table>
        <table style="border:1px solid grey;width:95%;margin: 0 auto;">
            <tr>
                <td>
                    <span style="color:grey; margin-left:10px;">Descripción-notas:</span>
                </td>
            </tr>
            <tr>
                <td style="margin-left:15px;">
                </td>
            </tr>
        </table>

        <div>
            <table style="width:95%;margin: 0 auto;">
                <tr>
                    <th>DESCRIPCIÓN</th>
                    <th style="text-align: center;">CANTIDAD</th>
                    <th style="text-align: center;">PRECIO</th>
                    <th style="text-align: right;">IMPORTE</th>
                </tr>
                @foreach($report_pdf['items_factura'] as $item)
                <tr>
                    <td>{{ $item['descripcion'] }}</td>
                    <td style="text-align: center;">{{ $item['cantidad'] }}</td>
                    <td style="text-align: center;">{{ $item['precio'] }}</td>
                    <td style="text-align: right;">{{ $item['importe'] }}</td>
                    @php $subtotal = $subtotal + $item['importe'] @endphp
                </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td style="text-align: right;">
                        <div>
                            Total:
                        </div>
                        @if($report_pdf['descuento'] > 0)
                        <div>
                            Descuento:
                        </div>
                        @endif
                        <div>
                            Base Imponible:
                        </div>
                        <div>
                            I.V.A. 21%:
                        </div>
                        <div>
                            <b>Total Factura:</b> 
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <div>
                            {{$subtotal}} €
                        </div>
                        @if($report_pdf['descuento'] > 0)
                        <div>
                            {{$report_pdf['descuento']}} €
                        </div>
                        @endif
                        <div>
                            {{$subtotal - $report_pdf['descuento']/100}} €
                        </div>
                        <div>
                            {{$report_pdf['status_iva'] != 0 ? ($subtotal - ($subtotal * $report_pdf['descuento']/100)) * 0.21 : '0.00' }} €
                        </div>
                        <div>
                            <b>{{$report_pdf['total']}} € </b>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top:30px;">
            <table style="border:1px solid grey;width:95%;margin: 0 auto;">
                <tr>
                    <td>
                        <span style="color:grey;margin-left:10px;">Forma de Pago:</span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">
                        Pago mediante trasferencia bancaria<br>
                        <span style="font-weight:200;">Banco: </span>BBVA //  <span style="font-weight:200;">CUENTA: </span> ES18 0182 4466 1702 0154 1449<br>
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top:10px;margin-bottom:10px;">
            <table style="border:1px solid grey;width:95%;margin: 0 auto;">
                <tr>
                    <td style="text-align: justify;">
                        <span style="color:grey;margin-left:10px;font-size:10px;">De conformidad con el Reglamento Europeo de Protección de Datos (UE) 679/2016, le comunicamos que los datos objeto de este
                        tratamiento en la presente factura son responsabilidad de Fidias Texhnology S.L. Le informamos que los datos que nos facilita se precisan
                        para prestarle el servicio solicitado y realizar la facturación de este. Los datos se conservarán mientras se mantenga la relación comercial o
                        durante los años necesarios para cumplir con las obligaciones legales. Los datos no se cederán a terceros salvo en los casos en que exista una
                        obligación legal. Usted puede ejercer el derecho de acceso, rectificación, cancelación, supresión y portabilidad, dirigiéndose por escrito a C/
                        Granados, 19, BAJO D. Almoradí (03160) Alicante.</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
