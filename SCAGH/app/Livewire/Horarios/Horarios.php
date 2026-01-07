<?php

namespace App\Livewire\Horarios;

use App\Models\Carrera;
use App\Models\Catalogo;
use App\Models\Curso;
use App\Models\DocenteCurso;
use App\Models\Horario;
use App\Models\HorarioCursoDocente;
use App\Models\Laboratorio;
use App\Models\Semestre;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Horarios extends Component
{
    use WithPagination;
    public $laboratorio_id, $semestre_id, $nombre, $editableNombre = false, $nombreHorario,
        $carrera_id, $facultad_id, $curso_id, $carreras = [], $cursos = [], $docente_curso_id, $semana_id, $horario_id, $hora_inicio, $hora_fin;
    public $asignaciones = [];
    public $cursosPorDia = [
        5 => [],
        6 => [],
        7 => [],
        8 => [],
        9 => [],
        10 => []
    ];
    public $grupos = [];
    public $grupo_id;

    public $asignacion_id = null;

    public function selectInfo($id)
    {
        $asig = HorarioCursoDocente::with(['docenteCurso.curso.carrera'])
            ->findOrFail($id);


        $this->asignacion_id = $id;

        $curso = $asig->docenteCurso->curso;
        $carrera = $curso->carrera;

        $this->facultad_id = $carrera->facultad_id;


        $this->carreras = Carrera::where('facultad_id', $this->facultad_id)->get();
        $this->carrera_id = $carrera->id;

        $this->updatedCarreraId($this->carrera_id);
        $this->curso_id = $curso->id;

        $this->updatedCursoId($this->curso_id);

        $this->grupo_id = $asig->docente_curso_id;

        $this->horario_id = $asig->horario_id;
        $this->semana_id = $asig->semana_id;

        $this->hora_inicio = $asig->hora_inicio;
        $this->hora_fin = $asig->hora_fin;

        $this->dispatch('abrirModalEditarCurso');
    }


    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset([
            'laboratorio_id',
            'semestre_id',
            'nombreHorario',
            'editableNombre',
            'horario_id',
            'facultad_id',
            'carrera_id',
            'curso_id',
            'docente_curso_id',
            'semana_id',
            'hora_inicio',
            'hora_fin'
        ]);
        $this->resetValidation();
    }

    public function updatedFacultadId($value)
    {
        if (!empty($value)) {
            $this->carreras = carrera::where('facultad_id', $value)->get();
        } else {
            $this->carreras = carrera::all();
        }
        $this->carrera_id = '';
    }

    private function obtenerSemestreVigente()
    {
        $fechaActual = now();

        return Semestre::where('fecha_inicio', '<=', $fechaActual)
            ->where('fecha_fin', '>=', $fechaActual)
            ->first();
    }

    public function updatedCarreraId($value)
    {
        $this->curso_id = '';

        if (empty($value)) {
            $this->cursos = [];
            return;
        }

        $semestreVigente = $this->obtenerSemestreVigente();
        if (!$semestreVigente) {
            $this->cursos = [];
            return;
        }


        $this->cursos = DocenteCurso::with('curso')
            ->where('semestre_id', $semestreVigente->id)
            ->whereHas('curso', function ($q) use ($value) {
                $q->where('carrera_id', $value);
            })
            ->select('curso_id')
            ->distinct()
            ->get()
            ->map(function ($dc) {
                return $dc->curso;
            })
            ->sortBy('nombre')
            ->values();
    }


    public function updatedCursoId($cursoSeleccionado)
    {
        $this->grupo_id = null;

        if (!$cursoSeleccionado) {
            $this->grupos = [];
            return;
        }

        $semestreVigente = $this->obtenerSemestreVigente();


        $this->grupos = DocenteCurso::with('grupo')
            ->where('curso_id', $cursoSeleccionado)
            ->where('semestre_id', $semestreVigente->id)
            ->get()
            ->map(function ($dc) {
                return [
                    'docente_curso_id' => $dc->id,
                    'grupo_id' => $dc->grupo->id,
                    'grupo_nombre' => $dc->grupo->nombre
                ];
            });
    }


    protected $rules = [
        'laboratorio_id' => 'required',
        'semestre_id' => 'required',
        'nombreHorario' => 'required|unique:horario,nombre',
    ];
    protected function rulesAsignacionCurso()
    {
        return [
            'docente_curso_id' => 'required|exists:docente_curso,id',
            'horario_id'       => 'required|exists:horario,id',
            'semana_id'        => 'required|exists:catalogo,id',
            'hora_inicio'      => 'required|date_format:H:i',
            'hora_fin'         => 'required|date_format:H:i|after:hora_inicio',
            'grupo_id' => 'required',

        ];
    }
    protected $messages = [
        'laboratorio_id.required' => 'Debe seleccionar un laboratorio.',
        'semestre_id.required' => 'Debe seleccionar un semestre.',
        'nombreHorario.unique' => 'Ya existe un horario registrado con este nombre',
        'nombreHorario.required' => 'El nombre del horario es obligatorio.',
        'docente_curso_id.required' => 'Debe seleccionar un curso.',
        'docente_curso_id.exists'   => 'El curso seleccionado no es válido.',

        'horario_id.required' => 'Debe seleccionar un horario.',
        'horario_id.exists'   => 'El horario seleccionado no existe.',

        'semana_id.required' => 'Debe seleccionar un día.',
        'semana_id.exists'   => 'El día seleccionado no es válido.',

        'hora_inicio.required' => 'Debe ingresar la hora de inicio.',
        'hora_inicio.date_format' => 'El formato de la hora de inicio no es válido.',

        'hora_fin.required' => 'Debe ingresar la hora de fin.',
        'hora_fin.date_format' => 'El formato de la hora de fin no es válido.',
        'hora_fin.after' => 'La hora de fin debe ser mayor a la hora de inicio.',

        'grupo_id.required' => 'Debe seleccionar un grupo.',
        'grupo_id.exists'   => 'El grupo seleccionado no es válido.',
    ];


    private function validarCruceHorarios()
    {

        if (!$this->hora_inicio || !$this->hora_fin) {
            return;
        }
        $cruce = HorarioCursoDocente::where('horario_id', $this->horario_id)
            ->where('semana_id', $this->semana_id)
            ->when($this->asignacion_id, function ($q) {
                $q->where('id', '!=', $this->asignacion_id);
            })
            ->where(function ($q) {
                $q->whereBetween('hora_inicio', [$this->hora_inicio, $this->hora_fin])
                    ->orWhereBetween('hora_fin', [$this->hora_inicio, $this->hora_fin])
                    ->orWhere(function ($sub) {
                        $sub->where('hora_inicio', '<=', $this->hora_inicio)
                            ->where('hora_fin', '>=', $this->hora_fin);
                    });
            })
            ->exists();

        if ($cruce) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'hora_inicio' => 'Existe un curso asignado en este horario.',
                'hora_fin' => 'El horario seleccionado se cruza con otro curso.',
            ]);
        }
    }

    public function updated($campo)
    {

        if ($campo === 'laboratorio_id' || $campo === 'semestre_id') {
            $this->generarNombreHorario();
            $this->validateOnly($campo);
            return;
        }

        $this->validateOnly($campo);
    }
    public function generarNombreHorario()
    {
        $laboratorio = Laboratorio::find($this->laboratorio_id);
        $semestre = Semestre::find($this->semestre_id);

        if ($laboratorio && $semestre) {
            $this->nombreHorario = 'HORARIO DEL ' . $laboratorio->nombre . ' DEL SEMESTRE ' . $semestre->nombre;
        } else {
            $this->nombreHorario = '';
        }
    }
    public function toggleEditableNombre()
    {
        $this->editableNombre = !$this->editableNombre;
    }

    public function CrearHorario()
    {
        $this->validate();
        try {
            Horario::create([
                'semestre_id' => $this->semestre_id,
                'laboratorio_id' => $this->laboratorio_id,
                'nombre' => $this->nombreHorario,
            ]);

            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-exito', 'Horario registrado correctamente');
        } catch (\Throwable $e) {
            Log::error("Error al guardar Horario " . $e->getMessage());
        }
    }

    public function AsignarCurso()
    {
        $this->validate($this->rulesAsignacionCurso());
        $this->validarCruceHorarios();

        try {
            HorarioCursoDocente::create([
                'horario_id' => $this->horario_id,
                'docente_curso_id' => $this->grupo_id,
                'semana_id' => $this->semana_id,
                'hora_inicio' => $this->hora_inicio,
                'hora_fin' => $this->hora_fin
            ]);

            $this->actualizarHorario();

            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Curso asignado al horario correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error("Error al Asignar Curso " . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Ocurrió un error al asignar el curso al horario.', tipo: 'danger');
        }
    }


    public function EditarCursoHorario()
    {
        $this->validarCruceHorarios();

        try {
            $asignacion = HorarioCursoDocente::findOrFail($this->asignacion_id);

            $asignacion->update([
                'horario_id'        => $this->horario_id,
                'docente_curso_id'  => $this->grupo_id,
                'semana_id'         => $this->semana_id,
                'hora_inicio'       => $this->hora_inicio,
                'hora_fin'          => $this->hora_fin,
            ]);
            $this->actualizarHorario();

            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Curso actualizado correctamente.', tipo: 'success');
        } catch (\Throwable $e) {

            Log::error("Error al actualizar curso del horario: " . $e->getMessage());

            $this->dispatch(
                'toast-general',
                mensaje: 'Ocurrió un error al actualizar el curso del horario.',
                tipo: 'danger'
            );
        }
    }


    public function actualizarHorario()
    {
        $this->asignaciones = HorarioCursoDocente::with(['curso', 'semana'])
            ->where('horario_id', $this->horario_id)
            ->orderBy('hora_inicio', 'asc')
            ->get();

        $dias = [5, 6, 7, 8, 9, 10];
        $this->cursosPorDia = array_fill_keys($dias, []);

        foreach ($this->asignaciones as $asig) {
            $this->cursosPorDia[$asig->semana_id][] = $asig;
        }
    }



    public function render()
    {
        $fechaActual = now();

        $semestreVigente = Semestre::where('fecha_inicio', '<=', $fechaActual)
            ->where('fecha_fin', '>=', $fechaActual)
            ->first();

        $sinSemestreVigente = $semestreVigente === null;

        // ✅ Horarios solo si hay vigente
        $horarios = $sinSemestreVigente
            ? collect()
            : Horario::where('semestre_id', $semestreVigente->id)->get();

        $laboratorios = Laboratorio::get();

        // OJO: esto te devuelve SOLO vigentes, está bien si eso quieres
        $semestres = Semestre::where('fecha_inicio', '<=', $fechaActual)
            ->where('fecha_fin', '>=', $fechaActual)
            ->get();

        $facultades = catalogo::where('padre_id', 4)->get();
        $dias = catalogo::where('padre_id', 3)->get();
        $carreras = Carrera::get();

        // ✅ Cursos solo si hay vigente (aquí estaba tu error)
        $cursos = $sinSemestreVigente
            ? collect()
            : DocenteCurso::with('curso')
            ->where('semestre_id', $semestreVigente->id)
            ->get();

        return view('livewire.horarios.horarios', [
            'laboratorios' => $laboratorios,
            'semestres' => $semestres,
            'horarios' => $horarios,
            'facultades' => $facultades,
            'dias' => $dias,
            'carreras' => $carreras,
            'cursos' => $cursos,
            'cursosPorDia' => $this->cursosPorDia,

            // ✅ para mostrar aviso y/o deshabilitar botones en el blade
            'semestreVigente' => $semestreVigente,
            'sinSemestreVigente' => $sinSemestreVigente,
        ]);
    }
}
