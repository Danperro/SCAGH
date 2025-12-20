<?php

namespace App\Livewire\Asistencias;

use App\Models\Asistencia;
use App\Models\AsistenciaEstudiante;
use App\Models\Catalogo;
use App\Models\EstudianteCursoDocente;
use App\Models\Horario;
use App\Models\HorarioCursoDocente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Asistencias extends Component
{

    public $diaHoy;
    public $horaHoy;
    public $horario_id;
    public $curso;
    public $laboratorioActual;
    public $fechaActual;
    public $horas, $docente;
    public $horario_curso_docente_id, $curso_id, $docente_curso_id, $tipo_asistencia_id;
    public $asistencia = [];

    public function limpiar()
    {

        $this->curso = null;
        $this->horas = null;
        $this->docente_curso_id = null;
        $this->horario_id = null;
        $this->asistencia = [];
    }
    public function actualizarHorario()
    {
        $now = Carbon::now('America/Lima');
        $horaActual = $now->format('H:i:s');
        $this->fechaActual = $now->translatedFormat('d \d\e F, Y');

        $diaNombre = strtoupper($now->locale('es')->dayName);

        $diaCatalogo = Catalogo::where('nombre', $diaNombre)
            ->where('padre_id', 3)
            ->first();

        if (!$diaCatalogo) {
            $this->curso = null;
            $this->horas = null;
            $this->docente_curso_id = null;  
            return;
        }

        $registro = HorarioCursoDocente::where('horario_id', $this->horario_id)
            ->where('semana_id', $diaCatalogo->id)
            ->where('hora_inicio', '<=', $horaActual)
            ->where('hora_fin', '>=', $horaActual)
            ->with(['docenteCurso.curso', 'horario.laboratorio'])
            ->first();

        if (!$registro) {
            $this->curso = null;
            $this->horas = null;
            $this->docente_curso_id = null;

            $this->dispatch('abrir-modal', id: 'modalSinCurso');
            return;
        }


        $this->horario_curso_docente_id = $registro->id;
        $this->docente_curso_id = $registro->docenteCurso->id; 

        $this->curso = $registro->docenteCurso->curso->nombre;
        $this->docente = $registro->docenteCurso->docente->persona->nombre . ' ' .
            $registro->docenteCurso->docente->persona->apellido_paterno . ' ' .
            $registro->docenteCurso->docente->persona->apellido_materno;

        $this->laboratorioActual = $registro->horario->laboratorio->nombre ?? '';
        $this->horas = $registro->semana->nombre . ': ' . substr($registro->hora_inicio, 0, 5) . " - " . substr($registro->hora_fin, 0, 5);

       
        $this->asistencia = [];

        $fechaHoy = Carbon::now('America/Lima')->toDateString();

        $cabecera = Asistencia::where('horario_curso_docente_id', $this->horario_curso_docente_id)
            ->where('fecha_registro', $fechaHoy)
            ->first();

        if ($cabecera) {
            $detalles = AsistenciaEstudiante::where('asistencia_id', $cabecera->id)->get();

            foreach ($detalles as $d) {
                $ecd = EstudianteCursoDocente::where('estudiante_id', $d->estudiante_id)
                    ->where('docente_curso_id', $this->docente_curso_id)
                    ->first();

                if ($ecd) {
                    $this->asistencia[$ecd->id] = $d->tipo_asistencia_id;
                }
            }
        }
    }


    public function asistenciaSet($idECD, $tipoId)
    {
        $this->asistencia[$idECD] = $tipoId;
    }


    public function guardarAsistencia()
    {
        try {

            $fechaHoy = Carbon::now('America/Lima')->toDateString();

            $cabecera = Asistencia::where('horario_curso_docente_id', $this->horario_curso_docente_id)
                ->where('fecha_registro', $fechaHoy)
                ->first();

            if (!$cabecera) {
                $cabecera = Asistencia::create([
                    'horario_curso_docente_id' => $this->horario_curso_docente_id,
                    'fecha_registro' => $fechaHoy,
                    'hora_registro' => Carbon::now('America/Lima')->format('H:i:s'),
                ]);
            }

            foreach ($this->asistencia as $idECD => $tipoId) {

                if (!$tipoId) continue;

                $est = EstudianteCursoDocente::find($idECD);

                $detalle = AsistenciaEstudiante::where('asistencia_id', $cabecera->id)
                    ->where('estudiante_id', $est->estudiante_id)
                    ->first();

                if ($detalle) {

                    $detalle->update([
                        'tipo_asistencia_id' => $tipoId,
                    ]);
                } else {
                    AsistenciaEstudiante::create([
                        'asistencia_id' => $cabecera->id,
                        'estudiante_id' => $est->estudiante_id,
                        'tipo_asistencia_id' => $tipoId,
                    ]);
                }
            }

            $this->limpiar();
            $this->dispatch('toast-general', mensaje: 'Asistencias guardadas correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error("Error al guardar asistencia: " . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Error al guardar asistencia.', tipo: 'danger');
        }
    }


    public function render()
    {
        $horarios = Horario::get();
        $lista = EstudianteCursoDocente::where('docente_curso_id', $this->docente_curso_id)->get();
        $tipoasistencia_id = Catalogo::where('nombre', 'TIPOASISTENCIA')->first();

        $tipoasistencia = Catalogo::where('padre_id', $tipoasistencia_id->id)
            ->orderBy('nombre')
            ->get();

        return view('livewire.asistencias.asistencias', [
            'horarios' => $horarios,
            'EstudianteCursoDocentes' => $lista,
            'tipoasistencias' => $tipoasistencia,
        ]);
    }
}
