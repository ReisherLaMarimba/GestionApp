<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Documento Aceptación y Entendimiento</title>
    <style>
        body {
            font-family: sans-serif;
            line-height: 1.4;
            margin: 0;
            padding: 10px ;
            font-size: 12px;
            background: #ffffff;
        }

        .container {
            width: 100%;
            background: #ffff;
            /*margin: 0 auto;*/
            /*padding: 24px 32px;*/
            /*border: 1px solid #cbd4e2;*/
        }

        .logo {
            text-align: center;
            margin-bottom: 10px;
        }

        h1, h2, h6 {
            margin: 5px 0;
        }

        h6 {
            text-align: center;
            font-size: 12px;
        }

        .divider {
            border-top: 1px solid #333;
            margin: 10px 0 18px 0;
        }

        .user-info {
            width: 100%;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .column {
            width: 49%;
            float: left;
        }

        .item_info table {
            border: 1px solid #dededf;
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .item_info th {
            background-color: #008BCB;
            color: #ffffff;
            padding: 4px;
            border: 1px solid #dededf;
        }

        .item_info td {
            border: 1px solid #dededf;
            padding: 4px;
        }

        .acceptance-section {
            border: 1px dashed #008bcb;
            background: #eef7fd;
            margin: 20px 0;

            padding: 15px;
        }

        .acceptance-section h2 {
            color: #008BCB;
            font-size: 16px;
            text-align: center;
        }

        .acceptance-section p {
            margin: 5px 0;
            font-size: 13px;
        }

        .form-line {
            display: inline-block;
            border-bottom: 1px solid #444;
            height: 14px;
            vertical-align: bottom;
            margin: 0 5px;
        }

        .longer { width: 200px; }
        .short { width: 60px; }

        .centered-text {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }

        .signatures {
            width: 100%;
            margin-top: 0px;
        }

        .signature-block {
            width: 32%;
            float: left;
            text-align: center;
            font-size: 10px;
            margin: 0 1%;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin: 20px 0 5px auto;
            width: 80%;
        }

        .footer {
            text-align: center;
            font-size: 9px;
            margin-top: 0px;

            page-break-inside: avoid;
            page-break-before: auto;
            page-break-after: auto;

            display: block; /* ensures it acts as a block to respect page-break rules */
        }

        .page-number {
            text-align: right;
            font-size: 10px;
            font-weight: bold;
            margin-top: 0px;
        }

        .no-break {
            page-break-inside: avoid;
            page-break-before: auto;
            page-break-after: auto;
        }

        @page {
            margin: 1cm;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="logo">
        <img src="{{ $imagePath }}" alt="Logo de la Empresa" width="100" />
    </div>

    <h6>DOCUMENTO ACEPTACION Y ENTENDIMIENTO – RESPONSIVA DE ACTIVO</h6>
    <div class="divider"></div>

    <h2 style="font-size: 10px;">{{$user->name}}</h2>
    <div class="user-info clearfix">
        <div class="column">
            <p><strong>CMID:</strong> {{$user->emp_number}}</p>
            <p><strong>Cédula:</strong> {{$user->Cedula}}</p>
            <p><strong>Fecha de ingreso:</strong> {{$user->Hire_date}}</p>
            <p><strong>Coordinador de tecnología:</strong> Branklyn Torres</p>
            <p><strong>Aprobado por:</strong> Jamel Rodriguez</p>
        </div>
        <div class="column">
            <p><strong>Puesto:</strong> {{$user->task->name}}</p>
            <p><strong>Site:</strong> DR-SD1-RMT</p>
            <p><strong>Supervisor:</strong> {{$user->superior}}</p>
            <p><strong>Gerente:</strong> Jamel Rodriguez</p>
        </div>
    </div>

    <div class="item_info">
        <table>
            <thead>
            <tr>
                <th colspan="2">Información sobre el equipo</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Monitor:
                    @if(isset($itemsAssignedToUser['Monitor']))
                        @foreach($itemsAssignedToUser['Monitor'] as $item)
                            <div>{{ $item->name . ' - ' . $item->item_code }}</div>
                        @endforeach
                    @endif
                </td>
                <td>Headset:
                    @if(isset($itemsAssignedToUser['Headsets']))
                        @foreach($itemsAssignedToUser['Headsets'] as $item)
                            <div>{{ $item->name  . ' - ' . $item->item_code }}</div>
                        @endforeach
                    @endif
                </td>
            </tr>
            <tr>
                <td>CPU:
                    @if(isset($itemsAssignedToUser['CPU']))
                        @foreach($itemsAssignedToUser['CPU'] as $item)
                            <div>{{ $item->name       . ' - ' . $item->item_code }}</div>
                        @endforeach
                    @endif
                </td>
                <td>Extras:
                    @if(isset($Additionals))
                        @foreach($Additionals as $item)
                            <div>{{ $item->name       . ' - ' . $item->item_code }}</div>
                        @endforeach
                        @endif
                </td>
            </tr>
            <tr>
                <td>KEYBOARD:
                    @if(isset($itemsAssignedToUser['Keyboards']))
                        @foreach($itemsAssignedToUser['Keyboards'] as $item)
                            <div>{{ $item->name       . ' - ' . $item->item_code }}</div>
                        @endforeach
                    @endif
                </td>
                <td>Valor:
                    {{-- Add financial value or similar info if you want --}}
                </td>
            </tr>
            <tr>
                <td>MOUSE:
                    @if(isset($itemsAssignedToUser['Mouses']))
                        @foreach($itemsAssignedToUser['Mouses'] as $item)
                            <div>{{ $item->name       . ' - ' . $item->item_code }}</div>
                        @endforeach
                    @endif
                </td>
                <td></td>
            </tr>
            </tbody>

        </table>
    </div>

    <div class="acceptance-section">
        <h2>Aceptación y Responsiva del Equipo</h2>
        <p>
            Se hace entrega de una computadora desktop con CPU, teclado, mouse y headset en condiciones usadas y funcionales.
        </p>
        <p>
            Yo, <span class="form-line longer"></span> número de empleado <span class="form-line short"></span>,
            titular de la cédula de identidad y electoral número <span class="form-line short"></span> –
            <span class="form-line short"></span> – <span class="form-line short"></span>, hoy, en este día
            <span class="form-line short"></span> del mes de <span class="form-line longer"></span> del año
            <span class="form-line short"></span>, de manera libre y voluntaria, en pleno uso de mis facultades,
            acepto y reconozco expresamente que el equipo/activo indicado más arriba es propiedad de la empresa
            <strong>OPES SRL</strong> y que se me ha asignado para el desarrollo de mis funciones como empleado de la empresa,
            por lo que me comprometo a resguardarlo y darle uso con fines estrictamente laborales.
        </p>
        <p>
            De igual manera acepto y reconozco que en caso de daño, pérdida, robo o modificación no autorizada a este equipo,
            <strong>OPES SRL</strong> queda autorizada a descontarme el costo de reparación o el costo total del activo.
            Con mi firma a continuación, confirmo que he leído y revisado toda la información contenida en este documento
            y certifico que es correcta.
        </p>
        <div class="centered-text">
            Complete los espacios en blanco, firme y entregue el documento al coordinador tecnológico.
        </div>
    </div>

    <div class="signatures clearfix">
        <div class="signature-block">
            <div class="signature-line"></div>
            <p><strong>Branklyn Torres</strong></p>
            <p>Coordinador Tecnología</p>
        </div>
        <div class="signature-block">
            <div class="signature-line"></div>
            <p><strong>{{ $user->name }}</strong></p>
            <p>Colaborador</p>
        </div>
        <div class="signature-block">
            <div class="signature-line"></div>
            <p><strong>Jamel Rodriguez</strong></p>
            <p>Gerente del Área</p>
        </div>
    </div>

    <div class="page-number">Página 1 de 1</div>

    <div class="footer">
        <p>© 2025 OPES SRL, RNC 1-31-96035-9 | Confidencial</p>
        <p>
            Este documento contiene información confidencial y privada para uso exclusivo de la(s) persona(s) a quien(es) se dirige.
        </p>
    </div>
</div>
</body>
</html>
