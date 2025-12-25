<?php

namespace App\Livewire\Estudiantes;

use App\Models\Carrera;
use App\Models\Catalogo;
use App\Models\Curso;
use App\Models\DocenteCurso;
use App\Models\Estudiante;
use App\Models\EstudianteCursoDocente;
use App\Models\Persona;
use App\Models\Semestre;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Estudiantes extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $estudiante_id, $persona_id, $nombre, $apellido_paterno, $apellido_materno, $dni, $telefono, $correo, $fecha_nacimiento, $codigo;

    public $facultad_id, $semestre_id, $carreras = [], $carrera_id, $cursos = [], $curso_id, $grupo_id, $grupos = [], $estudiante_curso_docente_id;
    public $query = '', $filtroestado, $filtrocarrera_id, $filtrofacultad_id;


    #[Url('Busqueda')]


    public function selectInfo($id)
    {
        $estudiante = Estudiante::with(['persona', 'carrera'])->find($id);
        if (!$estudiante || !$estudiante->persona || !$estudiante->carrera) {
            return;
        }

        $this->estudiante_id     = $estudiante->id;
        $this->persona_id        = $estudiante->persona_id;
        $this->nombre            = $estudiante->persona->nombre;
        $this->apellido_paterno  = $estudiante->persona->apellido_paterno;
        $this->apellido_materno  = $estudiante->persona->apellido_materno;
        $this->dni               = $estudiante->persona->dni;
        $this->telefono          = $estudiante->persona->telefono;
        $this->correo            = $estudiante->persona->correo;
        $this->fecha_nacimiento  = $estudiante->persona->fecha_nacimiento;
        $this->codigo            = $estudiante->codigo;

        $this->facultad_id = $estudiante->carrera->facultad_id;

        $this->carreras = Carrera::where('facultad_id', $this->facultad_id)->get();
        $this->carrera_id = $estudiante->carrera_id;

        $this->curso_id = null;
        $this->grupo_id = null;
        $this->grupos   = [];
        $this->cursos   = [];

        $this->updatedCarreraId($this->carrera_id);

        $semestreVigente = $this->obtenerSemestreVigente();
        $this->semestre_id = $semestreVigente ? $semestreVigente->id : null;
    }


    public function selectAsignacionCurso($id)
    {
        $this->estudiante_curso_docente_id = $id;
    }


    public function limpiar()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset('estudiante_id', 'persona_id', 'nombre', 'apellido_paterno', 'apellido_materno', 'dni', 'telefono', 'correo', 'fecha_nacimiento', 'codigo', 'carrera_id', 'facultad_id');
        $this->reset('query', 'filtroestado', 'filtrocarrera_id', 'filtrofacultad_id');
        $this->resetPage();
    }


    public function rulesEstudiante()
    {
        return [
            'nombre' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'apellido_paterno' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'apellido_materno' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',

            'dni' => 'required|digits:8|regex:/^[0-9]+$/|unique:persona,dni,' . $this->persona_id,

            'telefono' => [
                'required',
                'regex:/^9\d{8}$/',
            ],
            'correo' => [
                'required',
                'email',
                'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.(com|pe|edu|es)$/',
                'unique:persona,correo,' . $this->persona_id,
            ],
            'fecha_nacimiento' => [
                'required',
                'date',
                'before:' . now()->subYears(18)->format('Y-m-d'),
            ],
            'codigo' => 'required|digits:10|regex:/^[0-9]+$/|unique:estudiante,codigo,' . $this->estudiante_id,

            'carrera_id' => 'required|exists:carrera,id',
        ];
    }

    public function rulesAsignarCurso()
    {
        return [
            'curso_id' => 'required|exists:curso,id',
            'grupo_id' => [
                'required',
                'integer',
                Rule::unique('estudiante_curso_docente', 'docente_curso_id')
                    ->where(fn($q) => $q->where('estudiante_id', $this->estudiante_id)
                        ->where('semestre_id', $this->semestre_id)
                        ->where('estado', 1)),
            ],

            'semestre_id' => 'required|exists:semestre,id',
        ];
    }


    protected $messages = [
        // Nombres y apellidos
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.regex' => 'El nombre solo debe contener letras.',
        'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
        'nombre.max' => 'El nombre no debe superar los 50 caracteres.',

        'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
        'apellido_paterno.regex' => 'El apellido paterno solo debe contener letras.',
        'apellido_paterno.min' => 'El apellido paterno debe tener al menos 2 caracteres.',
        'apellido_paterno.max' => 'El apellido paterno no debe superar los 50 caracteres.',

        'apellido_materno.required' => 'El apellido materno es obligatorio.',
        'apellido_materno.regex' => 'El apellido materno solo debe contener letras.',
        'apellido_materno.min' => 'El apellido materno debe tener al menos 2 caracteres.',
        'apellido_materno.max' => 'El apellido materno no debe superar los 50 caracteres.',

        // DNI
        'dni.required' => 'El DNI es obligatorio.',
        'dni.digits' => 'El DNI debe tener exactamente 8 dígitos.',
        'dni.regex' => 'El DNI solo debe contener números.',
        'dni.unique' => 'Ya existe una persona registrada con este DNI.',


        // Teléfono
        'telefono.required' => 'El teléfono es obligatorio.',
        'telefono.regex' => 'El teléfono debe iniciar con 9 y tener 9 dígitos.',

        // Correo
        'correo.required' => 'El correo es obligatorio.',
        'correo.email' => 'Debe ingresar un correo electrónico válido.',
        'correo.regex' => 'El correo debe terminar en .com, .pe, .edu o .es.',
        'correo.unique' => 'Este correo ya está registrado.',

        // Fecha de nacimiento
        'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
        'fecha_nacimiento.date' => 'Debe ingresar una fecha válida.',
        'fecha_nacimiento.before' => 'El docente debe ser mayor de 18 años.',

        // Código
        'codigo.required' => 'El código es obligatorio.',
        'codigo.digits' => 'El código debe tener exactamente 10 dígitos.',
        'codigo.regex' => 'El código solo debe contener números.',
        'codigo.unique' => 'Ya existe un estudiante registrado con este código.',

        // Carrera
        'carrera_id.required' => 'La carrera es obligatoria.',
        'carrera_id.exists' => 'La carrera seleccionada no es válida.',

        // Curso
        'curso_id.required' => 'Debe seleccionar un curso.',
        'curso_id.exists' => 'El curso seleccionado no es válido.',

        // Grupo
        'grupo_id.required' => 'Debe seleccionar un grupo.',
        'grupo_id.integer' => 'El grupo seleccionado no es válido.',
        'grupo_id.unique' => 'El estudiante ya tiene este curso y grupo asignado en el semestre seleccionado.',

        // Semestre
        'semestre_id.required' => 'Debe seleccionar un semestre.',
        'semestre_id.exists' => 'El semestre seleccionado no es válido.',



    ];


    public function updated($campo)
    {
        $camposEstudiante = [
            'nombre',
            'apellido_paterno',
            'apellido_materno',
            'dni',
            'telefono',
            'correo',
            'fecha_nacimiento',
            'codigo',
            'carrera_id',
        ];
        if (in_array($campo, $camposEstudiante)) {
            $this->validateOnly($campo, $this->rulesEstudiante());
        }
    }


    public function updatedQuery()
    {
        $this->resetPage();
    }


    private function obtenerSemestreVigente()
    {
        $fechaActual = now();

        return Semestre::where('fecha_inicio', '<=', $fechaActual)
            ->where('fecha_fin', '>=', $fechaActual)
            ->first();
    }


    public function updatedFiltroFacultadId($value)
    {
        if (!$value) {
            $this->carreras = Carrera::all();
            return;
        }

        $this->carreras = Carrera::where('facultad_id', $value)->get();
    }


    public function updatedFacultadId($value)
    {
        if (!$value) {
            $this->carreras = Carrera::all();
            return;
        }

        $this->carreras = Carrera::where('facultad_id', $value)->get();
    }


    public function updatedCarreraId($value)
    {
        $this->curso_id = '';

        if (empty($value)) {
            $this->cursos = collect();
            return;
        }

        $semestreVigente = Semestre::where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->first();

        if (!$semestreVigente) {
            $this->cursos = collect();
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
        if (!$semestreVigente) {
            $this->grupos = [];
            return;
        }

        $this->grupos = DocenteCurso::with('grupo')
            ->where('curso_id', $cursoSeleccionado)
            ->where('semestre_id', $semestreVigente->id)
            ->get()
            ->map(function ($dc) {
                return [
                    'docente_curso_id' => $dc->id,
                    'grupo_id'         => $dc->grupo->id,
                    'grupo_nombre'     => $dc->grupo->nombre,
                ];
            })
            ->toArray();
    }


    public function CrearEstudiante()
    {
        $this->validate($this->rulesEstudiante());
        try {
            $persona = Persona::create([
                'nombre' => strtoupper($this->nombre),
                'apellido_paterno' => strtoupper($this->apellido_paterno),
                'apellido_materno' => strtoupper($this->apellido_materno),
                'dni' => $this->dni,
                'telefono' => $this->telefono,
                'correo' => strtolower($this->correo),
                'fecha_nacimiento' => $this->fecha_nacimiento,
            ]);

            Estudiante::create([
                'persona_id' => $persona->id,
                'codigo' => $this->codigo,
                'carrera_id' => $this->carrera_id,
            ]);
            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Estudiante registrado correctamente.', tipo: 'success');
        } catch (\Exception $e) {
            Log::error("Error al crear el estudiante " . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Ocurrio un error al registrar un estudiante.', tipo: 'danger');
        }
    }


    public function EditarEstudiante()
    {
        $this->validate($this->rulesEstudiante());
        try {

            $estudiante = Estudiante::findOrFail($this->estudiante_id);

            $estudiante->update([
                'codigo' => $this->codigo,
                'carrera_id' => $this->carrera_id,
            ]);

            $persona = persona::findOrFail($estudiante->persona_id);

            $persona->update([
                'nombre' => strtoupper($this->nombre),
                'apellido_paterno' => strtoupper($this->apellido_paterno),
                'apellido_materno' => strtoupper($this->apellido_materno),
                'dni' => $this->dni,
                'telefono' => $this->telefono,
                'correo' => strtolower($this->correo),
                'fecha_nacimiento' => $this->fecha_nacimiento,
            ]);


            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Estudiante editado correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error("Error al editar el estudiante " . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Ocurrió un error al editar el estudiante.', tipo: 'danger');
        }
    }


    public function EliminarEstudiante()
    {
        try {
            $estudiante = Estudiante::findOrFail($this->estudiante_id);
            $persona = $estudiante->persona;

            // Eliminar usuario
            //if ($persona->usuario) {
            //  $persona->usuario->delete();
            //}

            $estudiante->delete();

            $persona->delete();

            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Estudiante eliminado correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error("Error al eliminar el estudiante " . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Ocurrió un error al eliminar el estudiante.', tipo: 'danger');
        }
    }


    public function GuardarAsignacionCurso()
    {
        $this->validate($this->rulesAsignarCurso());
        try {
            $existe = EstudianteCursoDocente::where('estudiante_id', $this->estudiante_id)
                ->where('docente_curso_id', $this->grupo_id)
                ->where('semestre_id', $this->semestre_id)
                ->first();

            if ($existe) {
                $existe->update([
                    'estado' => 1,
                ]);
            } else {
                EstudianteCursoDocente::create([
                    'estudiante_id'     => $this->estudiante_id,
                    'docente_curso_id'  => $this->grupo_id,
                    'semestre_id'       => $this->semestre_id,
                    'estado'            => 1,
                ]);
            }
            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Asignacion del Curso al Estudiante creado correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error("Error al Guardar la Asignacion del Curso al estudiante " . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Ocurrió un error al Guardar el Asignacion del Curso al estudiante.', tipo: 'danger');
        }
    }

    public function EliminarAsignacionCurso()
    {
        try {
            $asignacion = EstudianteCursoDocente::find($this->estudiante_curso_docente_id);

            if (!$asignacion) {
                $this->dispatch('toast-general', mensaje: 'No se encontró la asignación.', tipo: 'danger');
                return;
            }

            $asignacion->update([
                'estado' => 0
            ]);

            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Asignación eliminada correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error("Error al eliminar asignación: " . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Error al eliminar la asignación.', tipo: 'danger');
        }
    }


    public function render()
    {
        $estudiantes = Estudiante::with('persona', 'carrera')
            ->search($this->query, $this->filtrocarrera_id, $this->filtroestado)
            ->orderBy('id', 'desc')
            ->paginate(10);
        $facultades = Catalogo::where('padre_id', 4)->get();

        $fechaActual = now();
        $semestres = Semestre::where('fecha_inicio', '<=', $fechaActual)
            ->where('fecha_fin', '>=', $fechaActual)->paginate(10);

        $estudiantesCursosDocentes = EstudianteCursoDocente::where('estudiante_id', $this->estudiante_id)->where('estado', 1)->get();
        return view('livewire.estudiantes.estudiantes', [
            'estudiantes' => $estudiantes,
            'facultades' => $facultades,
            'carreras' => $this->carreras,
            'cursos' => $this->cursos,
            'grupos' => $this->grupos,
            'semestres' => $semestres,
            'estudiantesCursosDocentes' => $estudiantesCursosDocentes,
        ]);
    }
}
