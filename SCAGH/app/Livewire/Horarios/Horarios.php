<?php

namespace App\Livewire\Horarios;

use App\Models\Carrera;
use App\Models\Catalogo;
use App\Models\Curso;
use App\Models\Horario;
use App\Models\Laboratorio;
use App\Models\Semestre;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Horarios extends Component
{
    use WithPagination;
    public $laboratorio_id, $semestre_id, $nombre, $editableNombre = false,
        $carrera_id, $facultad_id, $curso_id, $carreras = [], $cursos = [];

    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset(['laboratorio_id', 'semestre_id', 'nombre', 'editableNombre']);
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

    public function updatedCarreraId($value)
    {
        if (!empty($value)) {
            $this->cursos = Curso::where('carrera_id', $value)->get();
        } else {
            $this->cursos = Curso::all();
        }
        $this->curso_id = '';
    }
    protected $rules = [
        'laboratorio_id' => 'required',
        'semestre_id' => 'required',
        'nombre' => 'required|min:5|unique:horario,nombre'
    ];

    protected $messages = [
        'laboratorio_id.required' => 'Debe seleccionar un laboratorio.',
        'semestre_id.required' => 'Debe seleccionar un semestre.',
        'nombre.required' => 'El nombre del horario es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 5 caracteres.',
        'nombre.unique' => 'Ya existe un horario registrado con este nombre'
    ];

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
            $this->nombre = 'HORARIO DEL ' . $laboratorio->nombre . ' DEL SEMESTRE ' . $semestre->nombre;
        } else {
            $this->nombre = '';
        }
    }
    public function toggleEditableNombre()
    {
        $this->editableNombre = !$this->editableNombre;
    }

    public function CrearHorario()
    {
        try {

            $this->validate();

            Horario::create([
                'semestre_id' => $this->semestre_id,
                'laboratorio_id' => $this->laboratorio_id,
                'nombre' => $this->nombre
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
        try {
            $this->validate();

            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-exito','Se asigno el Curso correctamente');
        } catch (\Throwable $e) {
            Log::error("Error al Asignar Curso " . $e->getMessage());
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
        $cursos = Curso::get();
        return view('livewire.horarios.horarios', [
            'laboratorios' => $laboratorios,
            'semestres' => $semestres,
            'horarios' => $horarios,
            'facultades' => $facultades,
            'dias' => $dias,
            'carreras' => $carreras,
            'cursos' => $cursos
        ]);
    }
}
