<?php

namespace App\Livewire\Horarios;

use App\Models\Carrera;
use App\Models\Catalogo;
use App\Models\Horario;
use App\Models\Laboratorio;
use App\Models\Semestre;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Horarios extends Component
{
    use WithPagination;
    public $laboratorio_id, $semestre_id, $nombre, $editableNombre = false;

    public function updated($propertyName)
    {
        if ($propertyName === 'laboratorio_id' || $propertyName === 'semestre_id') {
            $this->generarNombreHorario();
        }
    }
    public function generarNombreHorario()
    {
        $laboratorio = Laboratorio::find($this->laboratorio_id);
        $semestre = Semestre::find($this->semestre_id);

        if ($laboratorio && $semestre) {
            $this->nombre = 'HORARIO DEL ' . $laboratorio->nombre . ' DEL SEMESTRE ' . $semestre->nombre;
        } else {
            $this->nombre = '';
        }
    }
    public function toggleEditableNombre()
    {
        $this->editableNombre = !$this->editableNombre;
    }
    public function guardarHorario()
    {
        try {
            horario::create([
                'semestre_id' => $this->semestre_id,
                'laboratorio_id' => $this->laboratorio_id,
                'nombre' => $this->nombre
            ]);
        } catch (\Throwable $e) {
            Log::error('Error al crear el horario', ['mensaje' => $e->getMessage()]);
        }
    }

    public function render()
    {
        $laboratorios = Laboratorio::get();
        $semestres = Semestre::get();
        $horarios = horario::get();
        $facultades = catalogo::where('padre_id', 4)->get();
        $dias = catalogo::where('padre_id', 3)->get();
        $carreras = Carrera::get();
        return view('livewire.horarios.horarios', [
            'laboratorios' => $laboratorios,
            'semestres' => $semestres,
            'horarios' => $horarios,
            'facultades' => $facultades,
            'dias' => $dias,
            'carreras' => $carreras
        ]);
    }
}
