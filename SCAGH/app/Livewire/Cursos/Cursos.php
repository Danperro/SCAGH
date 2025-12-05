<?php

namespace App\Livewire\Cursos;

use App\Models\Carrera;
use App\Models\Catalogo;
use App\Models\Curso;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Cursos extends Component
{
    use WithPagination;
    public $filtrocurso_id, $filtrociclo_id, $filtrocarrera_id, $filtrofacultad_id, $query;
    public $curso_id, $nombre, $codigo, $ciclo_id, $carrera_id, $facultad_id;
    public $carreras = [];
    public $editando = false;
    public $carrerasFiltro = []; // Para los filtros de la tabla

    public function mount()
    {
        $this->carreras = Carrera::all(); // para evitar select vacío al inicio
        $this->carrerasFiltro = Carrera::all();
    }
    public function updatedFacultadId($value)
    {
        if (!empty($value)) {
            $this->carreras = Carrera::where('facultad_id', $value)->get();
        } else {
            $this->carreras = Carrera::all();
        }

        // Solo borrar carrera_id cuando NO estamos editando
        if (!$this->editando) {
            $this->carrera_id = '';
        }
    }

    public function selectInfo($id)
    {
        $this->editando = true;
        $curso = Curso::findOrFail($id);

        $this->curso_id = $curso->id;
        // 1) Buscar la carrera del curso
        $carrera = Carrera::find($curso->carrera_id);

        // 2) Primero: facultad + lista de carreras correctas

        $this->facultad_id = $carrera->facultad_id;
        $this->carreras = Carrera::where('facultad_id', $carrera->facultad_id)->get();


        // 3) Recién al final: carrera seleccionada
        $this->carrera_id = $curso->carrera_id;
        $this->ciclo_id   = $curso->ciclo_id;
        $this->nombre     = $curso->nombre;
        $this->codigo     = $curso->codigo;
    }


    public function limpiar()
    {
        $this->reset(['facultad_id', 'carrera_id', 'ciclo_id', 'nombre', 'codigo', 'query']);
        $this->reset(['filtrofacultad_id', 'filtrocarrera_id', 'filtrociclo_id']);
        $this->carreras = Carrera::all();
        $this->editando = false;
        $this->resetValidation();
    }


    public function updatedFiltrofacultadId($value)
    {
        if (!empty($value)) {
            $this->carrerasFiltro  = carrera::where('facultad_id', $value)->get();
        } else {
            $this->carrerasFiltro  = carrera::all();
        }
        $this->filtrocarrera_id = ''; // <-- AGREGAR ESTO
        $this->resetPage();
    }



    protected $rules = [
        'carrera_id' => 'required',
        'ciclo_id' => 'required',
        'nombre' => 'required|min:3',
        'codigo' => 'required|min:3',

    ];

    public function updated($campo)
    {
        $this->validateOnly($campo);
    }

    public function CrearCurso()
    {
        try {
            $this->validate();
            curso::create([
                'carrera_id' => $this->carrera_id,
                'ciclo_id' => $this->ciclo_id,
                'nombre' => strtoupper($this->nombre),
                'codigo' => strtoupper($this->codigo),
                'estado' => 1
            ]);

            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-exito', 'Curso registrado correctamente');
        } catch (\Throwable $e) {
            Log::error("Error al crear el curso " . $e->getMessage());
        }
    }
    public function EditarCurso()
    {
        try {
            $this->validate();
            curso::find($this->curso_id)->update([
                'carrera_id' => $this->carrera_id,
                'ciclo_id' => $this->ciclo_id,
                'nombre' => strtoupper($this->nombre),
                'codigo' => strtoupper($this->codigo),
                'estado' => 1
            ]);
            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-exito', 'Curso Editado correctamente');
        } catch (\Throwable $e) {
            Log::error("Error al editar el curso " . $e->getMessage());
        }
    }
    public function EliminarCurso()
    {
        try {
            curso::find($this->curso_id)->delete();
            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-exito', 'Curso Eliminado correctamente');
        } catch (\Throwable $e) {
            Log::error("Error al eliminar el curso " . $e->getMessage());
        }
    }
    public function render()
    {
        $facultades = catalogo::where('padre_id', 4)->get();

        $cursos = Curso::with(['carrera', 'ciclo'])
            ->search($this->query, $this->filtrocarrera_id, $this->filtrofacultad_id, $this->filtrociclo_id)
            ->get();
        $ciclos = catalogo::where('padre_id', 13)->get();
        return view('livewire.cursos.cursos', [
            'facultades' => $facultades,
            'carreras' => $this->carreras,
            'carrerasFiltro' => $this->carrerasFiltro,
            'cursos' => $cursos,
            'ciclos' => $ciclos
        ]);
    }
}
