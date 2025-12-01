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
    public $curso_id, $nombre, $codigo, $ciclo_id, $carrera_id, $facultad_id;

    public function limpiar()
    {
        $this->reset(['carrera_id', 'ciclo_id', 'nombre', 'codigo']);
        $this->resetValidation();
    }

    public function selectInfo($id)
    {
        $curso = Curso::find($id);

        $this->curso_id   = $curso->id;
        $this->carrera_id = $curso->carrera_id;
        $this->ciclo_id   = $curso->ciclo_id;
        $this->nombre     = $curso->nombre;
        $this->codigo     = $curso->codigo;
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
        $carreras = Carrera::get();
        $cursos = Curso::get();
        $ciclos = catalogo::where('padre_id', 13)->get();
        return view('livewire.cursos.cursos', [
            'facultades' => $facultades,
            'carreras' => $carreras,
            'cursos' => $cursos,
            'ciclos' => $ciclos
        ]);
    }
}
