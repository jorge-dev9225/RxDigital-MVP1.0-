<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Receta médica #{{ $prescription->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333333;
        }

        .page {
            padding: 20px 30px;
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .header {
            background: linear-gradient(180deg, #EFAFC1 0%, #F7D6E0 100%);
            padding: 20px;
            border-bottom: 3px solid #D98AA4;
            text-align: center;
            position: relative;
        }

        .header-logo {
            position: absolute;
            top: 12px;
            left: 18px;
        }

        .header-logo img {
            height: 150px;
            width: auto;
        }

        .header .header-line {
            font-size: 12px;
            color: #C26C85;
            margin: 0;
        }

        .header .header-line+.header-line {
            margin-top: 2px;
        }

        .header .doctor-info {
            margin-bottom: 4px;
        }

        .header .small {
            font-size: 12px;
            color: #C26C85;
            margin-top: 4px;
        }


        .header h1 {
            font-size: 22px;
            margin: 0;
            color: #C26C85;
            font-weight: bold;
        }

        .header .subtitle {
            margin-top: 5px;
            font-size: 13px;
            color: #C26C85;
        }

        .doctor-info,
        .patient-info {
            margin-bottom: 10px;
        }

        .section-title {
            margin-top: 15px;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: bold;
            color: #C26C85;
            border-bottom: 1px solid #EFAFC1;
            padding-bottom: 4px;
        }

        .box {
            border: 1px solid #F0C9D6;
            background: #FFF8FA;
            padding: 10px;
            border-radius: 6px;
            margin-top: 4px;
            margin-bottom: 12px;
        }

        .rp-content {
            min-height: 80px;
            line-height: 1.4;
        }

        .footer {
            margin-top: 25px;
            padding-top: 10px;
            font-size: 10px;
            border-top: 1px solid #EFAFC1;
            color: #777;
        }

        .sign-row {
            margin-top: 40px;
            padding: 0 30px;
            display: table;
            width: 100%;
        }

        .sign-col {
            display: table-cell;
            vertical-align: bottom;
            text-align: center;
            padding: 0 10px;
            width: 50%;
        }

        .sign-label {
            border-top: 1px solid #C26C85;
            margin-top: 5px;
            font-size: 10px;
            text-align: center;
        }

        .signature-name {
            text-align: left;
        }

        .qr-col {
            text-align: center;
        }

        .qr-col img {
            margin-top: 4px;
        }

        .small {
            font-size: 10px;
        }
    </style>
</head>

<body>

    @php
        // Generamos el QR UNA sola vez como SVG y lo reutilizamos en ambas páginas
        $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(120)
            ->errorCorrection('H')
            ->generate($verificationUrl);

        $qrImage = base64_encode($qrSvg);
    @endphp

    @php
        $titulo = 'Dr.';
        if ($doctor && $doctor->gender === 'female') {
            $titulo = 'Dra.';
        }
    @endphp


    {{-- ----------------------------------------------------------
     PÁGINA 1: RECETA CON RP (medicación)
   ---------------------------------------------------------- --}}
    <div class="page">
        <div class="header">
            <div class="header-logo">
                <img src="{{ public_path('images/logorecetas.webp') }}" alt="Logo médico">
            </div>
            <div class="doctor-info">
                <h1>{{ $titulo }} {{ $doctor->name ?? ' ' }}</h1><br>
                @if (!empty($doctor->specialty))
                    <div class="header-line">
                        Especialidad: {{ $doctor->specialty }}
                    </div>
                @endif

                @if (!empty($doctor->license_number))
                    <div class="header-line">
                        M.N. {{ $doctor->license_number }}
                    </div>
                @endif

                @if (!empty($doctor->provincial_license_number))
                    <div class="header-line">
                        M.P. {{ $doctor->provincial_license_number }}
                    </div>
                @endif
            </div>

            <div class="header-line">
                Receta médica
            </div>
            <div class="header-line">
                Copia 1 – Medicación (RP)
            </div>

            <div class="small">
                Fecha de emisión:
                {{ optional($prescription->issued_at)->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}
            </div>
        </div>


        <div class="patient-info">
            <div class="section-title">Datos del paciente</div>
            <div class="box">
                Nombre y apellido:
                <strong>
                    {{ $prescription->patient_first_name }}
                    {{ $prescription->patient_last_name }}
                </strong><br>
                DNI: {{ $prescription->patient_dni ?? '—' }}<br>
                Fecha de nacimiento:
                @if ($prescription->patient_birth_date)
                    {{ $prescription->patient_birth_date->format('d/m/Y') }}
                @else
                    —
                @endif
                <br>
                Edad: {{ $prescription->patient_age ?? '—' }} años<br>
                Obra social: {{ $prescription->patient_health_insurance ?? 'Sin obra social' }}
            </div>
        </div>

        {{-- SOLO RP en esta copia --}}
        <div class="section-title">RP / Recipere</div>
        <div class="box rp-content">
            {!! nl2br(e($prescription->rp)) !!}
        </div>

        <div class="sign-row">
            <div class="sign-col">
                {{-- Firma en texto por ahora (sin imágenes) --}}
                <div style="margin-bottom: 8px; text-align: center;">
                    <strong>{{ $titulo }} {{ $doctor->name ?? ' ' }}</strong><br>
                    @if (!empty($doctor->specialty))
                        Especialidad: {{ $doctor->specialty }}<br>
                    @endif
                    @if (!empty($doctor->license_number))
                        M.N. {{ $doctor->license_number }}<br>
                    @endif
                    @if (!empty($doctor->provincial_license_number))
                        M.P. {{ $doctor->provincial_license_number }}<br>
                    @endif
                </div>
                <div class="sign-label">
                    Firma y sello del profesional
                </div>
            </div>

            <div class="sign-col qr-col">
                <div class="small">
                    Verificación de receta
                </div>

                <img src="data:image/svg+xml;base64,{{ $qrImage }}" alt="QR de verificación"
                    style="height: 90px; width: 90px; margin-bottom: 4px;">

                <div class="small">
                    Escanee para verificar autenticidad<br>
                </div>
            </div>
        </div>

        <div class="footer">
            Esta receta fue generada electrónicamente por el sistema de RxDigital. 
            {{-- $doctor->name ?? ' ' --}}
            La verificación en línea no permite descargar el documento, solo consultar su contenido.
        </div>
    </div>

    {{-- ----------------------------------------------------------
     PÁGINA 2: RECETA CON NOTAS / INDICACIONES
   ---------------------------------------------------------- --}}
    <div class="page">
        <div class="header">
            <div class="header-logo">
                <img src="{{ public_path('images/logorecetas.webp') }}" alt="Logo médico">
            </div>
            <div class="doctor-info">
                <h1>{{ $titulo }} {{ $doctor->name ?? ' ' }}</h1><br>
                @if (!empty($doctor->specialty))
                    <div class="header-line">
                        Especialidad: {{ $doctor->specialty }}
                    </div>
                @endif

                @if (!empty($doctor->license_number))
                    <div class="header-line">
                        M.N. {{ $doctor->license_number }}
                    </div>
                @endif

                @if (!empty($doctor->provincial_license_number))
                    <div class="header-line">
                        M.P. {{ $doctor->provincial_license_number }}
                    </div>
                @endif
            </div>

            <div class="header-line">
                Receta médica
            </div>
            <div class="header-line">
                Copia 2 – Indicaciones / Notas
            </div>

            <div class="small">
                Fecha de emisión:
                {{ optional($prescription->issued_at)->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}
            </div>
        </div>
        <div class="patient-info">
            <div class="section-title">Datos del paciente</div>
            <div class="box">
                Nombre y apellido:
                <strong>
                    {{ $prescription->patient_first_name }}
                    {{ $prescription->patient_last_name }}
                </strong><br>
                DNI: {{ $prescription->patient_dni ?? '—' }}<br>
                Fecha de nacimiento:
                @if ($prescription->patient_birth_date)
                    {{ $prescription->patient_birth_date->format('d/m/Y') }}
                @else
                    —
                @endif
                <br>
                Edad: {{ $prescription->patient_age ?? '—' }} años<br>
                Obra social: {{ $prescription->patient_health_insurance ?? 'Sin obra social' }}
            </div>
        </div>

        {{-- SOLO NOTAS / INDICACIONES en esta copia --}}
        <div class="section-title">Indicaciones / Notas para el paciente</div>
        <div class="box rp-content">
            @if ($prescription->notes)
                {!! nl2br(e($prescription->notes)) !!}
            @else
                —
            @endif
        </div>

        <div class="sign-row">
            <div class="sign-col">
                {{-- Firma en texto por ahora --}}
                <div style="margin-bottom: 8px; text-align: center;">
                    <strong>{{ $titulo }} {{ $doctor->name ?? ' ' }}</strong><br>
                    @if (!empty($doctor->specialty))
                        Especialidad: {{ $doctor->specialty }}<br>
                    @endif
                    @if (!empty($doctor->license_number))
                        M.N. {{ $doctor->license_number }}<br>
                    @endif
                    @if (!empty($doctor->provincial_license_number))
                        M.P. {{ $doctor->provincial_license_number }}<br>
                    @endif
                </div>
                <div class="sign-label">
                    Firma y sello del profesional
                </div>
            </div>

            <div class="sign-col qr-col">
                <div class="small">
                    Verificación de receta
                </div>

                <img src="data:image/svg+xml;base64,{{ $qrImage }}" alt="QR de verificación"
                    style="height: 90px; width: 90px; margin-bottom: 4px;">

                <div class="small">
                    Escanee para verificar autenticidad<br>
                </div>
            </div>
        </div>

        <div class="footer">
            Esta receta fue generada electrónicamente por el sistema de RxDigital.
            {{-- $doctor->name ?? ' ' --}}
            La verificación en línea no permite descargar el documento, solo consultar su contenido.
        </div>
    </div>

</body>

</html>
