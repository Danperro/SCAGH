<?php

namespace App\Livewire\Asistencia;


use App\Models\AsistenciaEstudiante;
use App\Models\Catalogo;
use App\Models\EstudianteCursoDocente;
use App\Models\Horario;
use App\Models\HorarioCursoDocente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

use function Livewire\str;

class Asistencia extends Component
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
            return;
        }

        $registro = HorarioCursoDocente::where('horario_id', $this->horario_id)
            ->where('semana_id', $diaCatalogo->id)
            ->where('hora_inicio', '<=', $horaActual)
            ->where('hora_fin', '>=', $horaActual)
            ->with(['docenteCurso.curso', 'horario.laboratorio'])
            ->first();
        $this->horario_curso_docente_id = $registro ? $registro->id : null;
        $this->docente_curso_id = $registro->docenteCurso->id;

        if (!$registro) {
            $this->curso = null;
            $this->horas = null;
            return;
        }

        $this->curso = $registro->docenteCurso->curso->nombre;
        $this->docente = $registro->docenteCurso->docente->persona->nombre . ' ' . $registro->docenteCurso->docente->persona->apellido_paterno . ' ' . $registro->docenteCurso->docente->persona->apellido_materno;
        $this->laboratorioActual = $registro->horario->laboratorio->nombre ?? '';
        $this->horas = $registro->semana->nombre . ': ' . substr($registro->hora_inicio, 0, 5) . " - " . substr($registro->hora_fin, 0, 5);
    }


    public function guardarAsistencia()
    {
        try {

            // 1. Crear la cabecera de asistencia
            $cabecera = Asistencia::create([
                'horario_curso_docente_id' => $this->horario_curso_docente_id,
                'fecha_registro' => Carbon::now('America/Lima')->toDateString(),
                'hora_registro' => Carbon::now('America/Lima')->format('H:i:s'),
            ]);

            // 2. Guardar asistencia por cada estudiante
            foreach ($this->asistencia as $idECD => $tipoId) {

                // Si no seleccionaron nada, continúa
                if (!$tipoId) continue;

                // Obtener el estudiante desde estudiante_curso_docente
                $est = EstudianteCursoDocente::find($idECD);

                AsistenciaEstudiante::create([
                    'asistencia_id' => $cabecera->id,
                    'estudiante_id' => $est->estudiante_id,
                    'tipo_asistencia_id' => $tipoId,
                ]);
            }

            // Toast bonito
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
        $tipoasistencia_id = Catalogo::where('nombre', str('TIPOASISTENCIA'))->first();
        $tipoasistencia = catalogo::where('padre_id', $tipoasistencia_id->id)->get();
        return view('livewire.asistencia.asistencia', [
            'horarios' => $horarios,
            'EstudianteCursoDocentes' => $lista,
            'tipoasistencias' => $tipoasistencia,
        ]);
    }
}
