<?php

namespace App\Livewire\Docentes;

use App\Models\Carrera;
use App\Models\Catalogo;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Persona;
use App\Models\Semestre;
use App\Models\DocenteCurso;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Docentes extends Component
{
    use WithPagination;
    public $docente_id, $persona_id, $nombre, $apellido_paterno, $apellido_materno, $dni, $telefono, $correo, $fecha_nacimiento, $especialidad_id;
    public $query = '', $filtroespecialidad_id, $filtroestado;
    public $asignacionesDocente = [], $gruposSeleccionados = [];
    public $carrera_id, $facultad_id, $curso_id, $carreras = [], $cursos = [], $semestre_id, $gruposAsignadosCursoActual = [];

    #[Url('Busqueda')]
    public function selectInfo($id)
    {
        $docente = Docente::find($id);

        if (!$docente || !$docente->persona) {
            return;
        }
        $this->docente_id = $docente->id;
        $this->persona_id = $docente->persona_id;
        $this->nombre = $docente->persona->nombre;
        $this->apellido_paterno = $docente->persona->apellido_paterno;
        $this->apellido_materno = $docente->persona->apellido_materno;
        $this->dni = $docente->persona->dni;
        $this->telefono = $docente->persona->telefono;
        $this->correo = $docente->persona->correo;
        $this->fecha_nacimiento = $docente->persona->fecha_nacimiento;

        $this->especialidad_id = $docente->especialidad_id;
    }


    public function limpiar()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset(['docente_id', 'nombre', 'apellido_paterno', 'apellido_materno', 'dni', 'telefono', 'correo', 'fecha_nacimiento', 'especialidad_id']);
        $this->reset(['query', 'filtroespecialidad_id', 'filtroestado']);
        $this->reset(['carrera_id', 'facultad_id', 'curso_id', 'carreras', 'cursos', 'semestre_id', 'gruposSeleccionados']);
        $this->resetPage();
    }


    protected function rulesDocente()
    {
        return [
            'nombre' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'apellido_paterno' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'apellido_materno' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',

            'dni' => 'required|digits:8|numeric|unique:persona,dni,' . $this->persona_id,

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

            'especialidad_id' => 'required|exists:catalogo,id',

        ];
    }


    protected function rulesAsignacionCurso()
    {
        return [
            'docente_id'          => 'required|exists:docente,id',
            'facultad_id'         => 'required|exists:catalogo,id',
            'carrera_id'          => 'required|exists:carrera,id',
            'curso_id'            => 'required|exists:curso,id',
            'semestre_id'         => 'required|exists:semestre,id',
            'gruposSeleccionados'   => 'required|array|min:1',
            'gruposSeleccionados.*' => 'exists:catalogo,id',
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
        'dni.numeric' => 'El DNI solo debe contener números.',
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

        // Especialidad
        'especialidad_id.required' => 'Debe seleccionar una especialidad.',
        'especialidad_id.exists' => 'La especialidad seleccionada no es válida.',

        // Asigna Curso
        'facultad_id.required'         => 'Debe seleccionar una facultad.',
        'carrera_id.required'          => 'Debe seleccionar una carrera.',
        'curso_id.required'            => 'Debe seleccionar un curso.',
        'semestre_id.required'         => 'Debe seleccionar un semestre.',
        'gruposSeleccionados.required' => 'Debe seleccionar al menos un grupo.',
    ];


    public function updated($campo)
    {
        $camposDocente = [
            'nombre',
            'apellido_paterno',
            'apellido_materno',
            'dni',
            'telefono',
            'correo',
            'fecha_nacimiento',
            'especialidad_id',
        ];

        $camposAsignacion = [
            'docente_id',
            'facultad_id',
            'carrera_id',
            'curso_id',
            'semestre_id',
            'gruposSeleccionados',
        ];

        if (in_array($campo, $camposDocente)) {
            $this->validateOnly($campo, $this->rulesDocente());
        } elseif (in_array($campo, $camposAsignacion)) {
            $this->validateOnly($campo, $this->rulesAsignacionCurso());
        }
    }


    public function updatedQuery()
    {
        $this->resetPage();
    }


    public function CrearDocente()
    {
        $this->validate($this->rulesDocente());
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

            docente::create([
                'persona_id' => $persona->id,
                'especialidad_id' => $this->especialidad_id,
            ]);
            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', 'Docente registrado correctamente.', 'success');
        } catch (\Throwable $e) {
            Log::error("Error al crear el docente " . $e->getMessage());
            $this->dispatch('toast-general', 'Ocurrio un error al registrar un docente.', 'danger');
        }
    }


    public function EditarDocente()
    {
        $this->validate($this->rulesDocente());
        try {

            $docente = Docente::findOrFail($this->docente_id);

            $docente->update([
                'especialidad_id' => $this->especialidad_id,
            ]);

            $persona = persona::findOrFail($docente->persona_id);

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
            $this->dispatch('toast-general', mensaje: 'Docente editado correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error("Error al editar el docente " . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Ocurrió un error al editar el docente.', tipo: 'danger');
        }
    }

    public function EliminarDocente()
    {
        try {
            $docente = docente::findOrFail($this->docente_id);
            $persona = $docente->persona;

            // Eliminar usuario
            //if ($persona->usuario) {
            //  $persona->usuario->delete();
            //}

            $docente->delete();

            $persona->delete();

            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Docente eliminado correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error("Error al eliminar el docente " . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Ocurrió un error al eliminar el docente.', tipo: 'danger');
        }
    }


    public function updatedFacultadId($value)
    {
        if (!empty($value)) {
            $this->carreras = Carrera::where('facultad_id', $value)->get();
        } else {
            $this->carreras = carrera::all();
        }
        $this->carrera_id = '';
        $this->cursos = [];
        $this->curso_id = '';
    }

    public function updatedCarreraId($value)
    {
        $this->curso_id = '';
        if (empty($value)) {
            $this->cursos = [];
            return;
        }

        $fechaActual = now();
        $semestreVigente = Semestre::where('fecha_inicio', '<=', $fechaActual)
            ->where('fecha_fin', '>=', $fechaActual)
            ->first();

        if (!$semestreVigente) {
            $this->cursos = Curso::where('carrera_id', $value)
                ->orderBy('nombre')
                ->get();
            return;
        }

        $cursosOcupados = DocenteCurso::where('semestre_id', $semestreVigente->id)
            ->when($this->docente_id, function ($q) {
                $q->where('docente_id', '!=', $this->docente_id);
            })
            ->pluck('curso_id');

        $this->cursos = Curso::where('carrera_id', $value)
            ->when($cursosOcupados->isNotEmpty(), function ($q) use ($cursosOcupados) {
                $q->whereNotIn('id', $cursosOcupados);
            })
            ->orderBy('nombre')
            ->get();
    }


    public function updatedCursoId($value)
    {
        $this->gruposSeleccionados = [];

        if ($this->docente_id && $this->semestre_id && $value) {
            $this->gruposAsignadosCursoActual = DocenteCurso::where('docente_id', $this->docente_id)
                ->where('curso_id', $value)
                ->where('semestre_id', $this->semestre_id)
                ->pluck('grupo_id')
                ->toArray();
        } else {
            $this->gruposAsignadosCursoActual = [];
        }
    }


    public function GuardarAsignacionCurso()
    {
        $this->validate($this->rulesAsignacionCurso());
        try {
            foreach ($this->gruposSeleccionados as $grupoId) {
                DocenteCurso::firstOrCreate(
                    [
                        'docente_id'  => $this->docente_id,
                        'curso_id'    => $this->curso_id,
                        'semestre_id' => $this->semestre_id,
                        'grupo_id'    => $grupoId,
                    ]
                );
            }
            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Docente asignado al curso correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error("Error al asignar el curso al docente " . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Ocurrió un error al asignar el curso al docente.', tipo: 'danger');
        }
    }


    public function render()
    {
        $fechaActual = now();

        $docentes = Docente::with('persona')
            ->search($this->query, $this->filtroespecialidad_id, $this->filtroestado)
            ->orderBy('id', 'desc')
            ->paginate(20);

        $especialidades = Catalogo::where('padre_id', 26)->get();


        $semestreVigente = Semestre::where('fecha_inicio', '<=', $fechaActual)
            ->where('fecha_fin', '>=', $fechaActual)
            ->first();


        $semestres = $semestreVigente ? collect([$semestreVigente]) : collect();


        $grupos = Catalogo::where('padre_id', 36)->get();
        $facultades = catalogo::where('padre_id', 4)->get();

        if (empty($this->carreras)) {
            $this->carreras = Carrera::all();
        }


        if ($this->docente_id && $semestreVigente) {
            $this->asignacionesDocente = DocenteCurso::with(['curso', 'semestre', 'grupo'])
                ->where('docente_id', $this->docente_id)
                ->where('semestre_id', $semestreVigente->id)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $this->asignacionesDocente = collect();
        }

        return view('livewire.docentes.docentes', [
            'docentes' => $docentes,
            'especialidades' => $especialidades,
            'cursos'        =>  $this->cursos,
            'semestres'     => $semestres,
            'grupos'        => $grupos,
            'facultades' => $facultades,
            'carreras' => $this->carreras,
            'asignacionesDocente' => $this->asignacionesDocente,
            'gruposAsignadosCursoActual' => $this->gruposAsignadosCursoActual,
        ]);
    }
}
