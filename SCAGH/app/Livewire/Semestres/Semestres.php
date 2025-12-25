<?php

namespace App\Livewire\Semestres;

use App\Models\Semestre;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Semestres extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $query;
    #[Url('Busqueda')]
    public $filtroEstado = null;
    public $filtroFechaInicio = null;
    public $filtroFechaFin = null;

    public $semestre_id;
    public $nombre;
    public $fecha_inicio;
    public $fecha_fin;
    public $estado = 1;



    public function selectInfo($id)
    {
        $semestre = Semestre::findOrFail($id);

        $this->semestre_id   = $semestre->id;
        $this->nombre        = $semestre->nombre;
        $this->fecha_inicio = $semestre->fecha_inicio
            ? Carbon::parse($semestre->fecha_inicio)->format('Y-m-d')
            : null;

        $this->fecha_fin = $semestre->fecha_fin
            ? Carbon::parse($semestre->fecha_fin)->format('Y-m-d')
            : null;
        $this->estado        = $semestre->estado;
    }

    protected $rules = [
        'nombre'       => 'required|string|min:3',
        'fecha_inicio' => 'required|date',
        'fecha_fin'    => 'required|date|after:fecha_inicio',
    ];

    protected $messages = [
        'nombre.required' => 'El nombre del semestre es obligatorio.',
        'nombre.string'   => 'El nombre del semestre debe ser texto.',
        'nombre.min'      => 'El nombre del semestre debe tener mínimo :min caracteres.',

        'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
        'fecha_inicio.date'     => 'La fecha de inicio no tiene un formato válido.',

        'fecha_fin.required' => 'La fecha de fin es obligatoria.',
        'fecha_fin.date'     => 'La fecha de fin no tiene un formato válido.',
        'fecha_fin.after'    => 'La fecha de fin debe ser posterior a la fecha de inicio.',
    ];

    protected $validationAttributes = [
        'nombre'       => 'nombre del semestre',
        'fecha_inicio' => 'fecha de inicio',
        'fecha_fin'    => 'fecha de fin',
    ];

    public function updated($campo)
    {
        $this->validateOnly($campo);
    }

    public function CrearSemestre()
    {
        try {
            $this->validate();

            Semestre::create([
                'nombre'       => strtoupper($this->nombre),
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin'    => $this->fecha_fin,
                'estado'       => 1,
            ]);

            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Semestre registrado correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error('Error al crear semestre: ' . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Semestre no se pudo registrar correctamente.', tipo: 'danger');
        }
    }

    public function EditarSemestre()
    {
        try {
            $this->validate();

            Semestre::findOrFail($this->semestre_id)->update([
                'nombre'       => strtoupper($this->nombre),
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin'    => $this->fecha_fin,
            ]);

            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Semestre registrado correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error('Error al editar semestre: ' . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Semestre no se pudo editar correctamente.', tipo: 'danger');
        }
    }


    public function EliminarSemestre()
    {
        try {
            $semestre = Semestre::findOrFail($this->semestre_id);

            $semestre->update([
                'estado' => $semestre->estado == 1 ? 0 : 1
            ]);

            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'El Semestre se elimino correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error('Error al eliminar semestre: ' . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'El Semestre no se pudo eliminar correctamente.', tipo: 'danger');
        }
    }


    public function limpiar()
    {
        $this->reset([
            'semestre_id',
            'nombre',
            'fecha_inicio',
            'fecha_fin',
            'estado',
            'query',
            'filtroEstado',
            'filtroFechaInicio',
            'filtroFechaFin',
        ]);

        $this->resetValidation();
    }


    public function render()
    {
        $semestres = Semestre::search(
            $this->query,
            $this->filtroEstado,
            $this->filtroFechaInicio,
            $this->filtroFechaFin
        )
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(10);

        return view('livewire.semestres.semestres', [
            'semestres' => $semestres
        ]);
    }
}
