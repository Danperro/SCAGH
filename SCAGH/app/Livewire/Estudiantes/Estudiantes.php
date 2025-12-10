<?php

namespace App\Livewire\Estudiantes;

use App\Models\Estudiante;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Estudiantes extends Component
{
    use WithPagination;

    public $facultad_id;
    public $query = '', $filtroestado;
    #[Url('Busqueda')]


    public function limpiar()
    {
        $this->reset('query');
        $this->resetPage();
    }


    public function CrearEstudiante() {}
    public function render()
    {
        $estudiantes = Estudiante::get();
        return view('livewire.estudiantes.estudiantes', [
            'estudiantes' => $estudiantes,
        ]);
    }
}
