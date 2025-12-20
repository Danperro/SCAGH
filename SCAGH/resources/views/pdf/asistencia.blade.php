<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Reporte de Asistencia</title>

    <style>
        @font-face {
            font-family: "DejaVu Sans";
            font-style: normal;
            font-weight: normal;
            src: url("fonts/DejaVuSans.ttf") format("truetype");
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10px;
        }

        /* ===== CABECERA (igual estilo que tu ejemplo) ===== */
        .encabezado {
            width: 100%;
            display: table;
            /* DOMPDF-friendly */
            background-color: #cce4cc;
            border: 1px solid #000;
            padding: 10px 15px;
        }

        .encabezado .titulo {
            display: table-cell;
            width: 80%;
            vertical-align: middle;
            text-align: left;
        }

        .encabezado .titulo h1 {
            font-size: 16px;
            margin: 0;
            font-weight: bold;
        }

        .encabezado .titulo h3 {
            font-size: 11px;
            margin: 3px 0 0 0;
            font-weight: normal;
        }

        .encabezado .logo {
            display: table-cell;
            width: 20%;
            text-align: right;
            vertical-align: middle;
        }

        .encabezado .logo img {
            max-width: 95px;
            height: auto;
        }

        /* ===== BLOQUE DATOS (en “cuadro”, no en el aire) ===== */
        .fila-datos {
            width: 100%;
            display: table;
            /* DOMPDF-friendly */
            border: 1px solid #000;
            padding: 7px 10px;
            margin-top: 8px;
            font-size: 11px;
        }

        .fila-datos .col {
            display: table-cell;
            width: 50%;
            vertical-align: middle;
        }

        .fila-datos .col p {
            margin: 0;
        }

        /* ===== SECCIONES ===== */
        .titulo-seccion {
            background-color: #a8d5a8;
            font-weight: bold;
            text-align: left;
            padding: 6px 8px;
            font-size: 11px;
            margin-top: 12px;
            border: 1px solid #000;
        }

        /* ===== TABLA ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #e0f2e0;
            font-size: 10px;
        }

        td.txt-left {
            text-align: left;
        }

        /* ===== FIRMAS ===== */
        .firmas {
            margin-top: 18px;
        }

        .firmas th {
            background-color: #e0f2e0;
        }

        .firmas td {
            height: 45px;
            vertical-align: top;
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- CABECERA --}}
    <div class="encabezado">
        <div class="titulo">
            <h1>REPORTE DE ASISTENCIA</h1>
            <h3>Registro de asistencia de estudiantes</h3>
        </div>

        {{-- Si no tienes logo, borra este bloque --}}
        <div class="logo">
            {{-- Cambia la ruta si tu logo está en otra carpeta --}}
            <img src="{{ public_path('images/cuc.png') }}" alt="logo">
        </div>
    </div>

    {{-- DATOS GENERALES (en cuadro) --}}
    <div class="fila-datos">
        <div class="col">
            <p><strong>Asignatura:</strong>
                {{ $asistencia->horarioCursoDocente->docenteCurso->curso->nombre }}
                <span style="margin-left: 10px;">
                    <strong>Grupo:</strong>
                    {{ $asistencia->horarioCursoDocente->docenteCurso->grupo->nombre }}
                </span>
            </p>
            <p><strong>Docente:</strong>
                {{ $asistencia->horarioCursoDocente->docenteCurso->docente->persona->nombre }}
                {{ $asistencia->horarioCursoDocente->docenteCurso->docente->persona->apellido_paterno }}
                {{ $asistencia->horarioCursoDocente->docenteCurso->docente->persona->apellido_materno }}
            </p>
            <p><strong>Laboratorio:</strong>
                {{ $asistencia->horarioCursoDocente->horario->laboratorio->nombre }}
                <span style="margin-left: 10px;">
                    <strong>Semestre:</strong>
                    {{ $asistencia->horarioCursoDocente->docenteCurso->semestre->nombre ?? '---' }}</span>
            </p>
        </div>

        <div class="col" style="text-align:right;">

            <p>
                <strong>Horario:</strong>
                {{ \Carbon\Carbon::parse($asistencia->horarioCursoDocente->hora_inicio)->format('h:i A') }}
                -
                {{ \Carbon\Carbon::parse($asistencia->horarioCursoDocente->hora_fin)->format('h:i A') }}
            </p>


            <p>
                <strong>Hora de registro:</strong>
                {{ \Carbon\Carbon::parse($asistencia->hora_registro)->format('h:i A') }}
            </p>

            <p><strong>Fecha de registro:</strong>
                {{ \Carbon\Carbon::parse($asistencia->fecha_registro)->format('d/m/Y') }}
            </p>

        </div>
    </div>


    {{-- LISTA --}}
    <div class="titulo-seccion">LISTA DE ESTUDIANTES</div>

    <table>
        <thead>
            <tr>
                <th style="width:5%;">#</th>
                <th style="width:18%;">Código</th>
                <th>Estudiante</th>
                <th style="width:18%;">Asistencia</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($asistencia->asistenciaEstudiantes as $i => $detalle)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detalle->estudiante->codigo }}</td>
                    <td class="txt-left">
                        {{ $detalle->estudiante->persona->apellido_paterno }}
                        {{ $detalle->estudiante->persona->apellido_materno }},
                        {{ $detalle->estudiante->persona->nombre }}
                    </td>
                    <td>
                        {{ strtoupper(optional($detalle->tipoAsistencia)->nombre ?? '---') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay estudiantes registrados en esta asistencia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FIRMAS --}}
    <table class="firmas">
        <thead>
            <tr>
                <th>Docente Responsable:</th>
                <th>Delegado del aula:</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

</body>

</html>
