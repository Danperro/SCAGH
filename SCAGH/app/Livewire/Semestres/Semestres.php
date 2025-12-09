<?php

namespace App\Livewire\Semestres;

use App\Models\Semestre;
use Livewire\Component;

class Semestres extends Component
{
    public function render()
    {
        $semestres= Semestre::get();
        return view('livewire.semestres.semestres',[
            'semestres'=>$semestres
        ]);
    }
}
