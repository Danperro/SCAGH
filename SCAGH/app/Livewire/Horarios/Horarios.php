<?php

namespace App\Livewire\Horarios;

use App\Models\Laboratorio;
use App\Models\Semestre;
use Livewire\Component;
use Livewire\WithPagination;

class Horarios extends Component
{
    use WithPagination;
    public $laboratorio_id, $semestre_id, $nombre, $editableNombre = 0;
    public function render()
    {
        $laboratorios = Laboratorio::get();
        $semestres = Semestre::get();
        return view('livewire.horarios.horarios', [
            'laboratorios' => $laboratorios,
            'semestres' => $semestres,
        ]);
    }
}
