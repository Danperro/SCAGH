<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteAsistenciaController extends Controller
{
    public function generarPDF($id)
    {
        $asistencia = Asistencia::with([
            'horarioCursoDocente.horario.laboratorio',
            'horarioCursoDocente.docenteCurso.curso',
            'horarioCursoDocente.docenteCurso.docente.persona',
            'asistenciaEstudiantes.estudiante.persona',
            'asistenciaEstudiantes.tipoAsistencia',
        ])->findOrFail($id);


        $pdf = Pdf::loadView('pdf.asistencia', compact('asistencia'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('reporte_asistencia_' . $id . '.pdf');
    }
}
