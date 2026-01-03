<?php

namespace App\Livewire\Carreras;

use App\Models\Carrera;
use App\Models\Catalogo;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;

class Carreras extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $query, $filtrofacultadId, $filtroestado;
    #[Url('Busqueda')]
    public $carreraId, $facultadId, $nombre, $ciclosTotal, $estado;

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function updatedFiltrofacultadId()
    {
        $this->resetPage();
    }

    public function updatedFiltroestado()
    {
        $this->resetPage();
    }

    public function limpiar()
    {
        $this->reset([
            'query',
            'filtrofacultadId',
            'filtroestado',
            'carreraId',
            'facultadId',
            'nombre',
            'ciclosTotal',
            'estado',
        ]);

        $this->ciclosTotal = 12;
        $this->estado = 1;

        $this->resetValidation();
        $this->resetPage();
    }

    public function selectInfo($id)
    {
        $c = Carrera::findOrFail($id);
        $this->carreraId = $c->id;
        $this->facultadId = $c->facultad_id;
        $this->nombre = $c->nombre;
        $this->ciclosTotal = $c->ciclos_total;
        $this->estado = $c->estado;

        $this->resetValidation();
    }

    protected function rules()
    {
        return [
            'facultadId' => ['required', 'integer', 'exists:catalogo,id'],
            'nombre' => [
                'required',
                'min:3',
                'max:150',
                Rule::unique('carrera', 'nombre')->ignore($this->carreraId),
            ],
            'ciclosTotal' => ['required', 'integer', 'in:10,12,14'],
            'estado' => ['required', 'boolean'],
        ];
    }
    protected function messages()
    {
        return [
            'facultadId.required' => 'Selecciona una facultad.',
            'facultadId.integer'  => 'La facultad seleccionada no es válida.',
            'facultadId.exists'   => 'La facultad seleccionada no existe.',

            'nombre.required' => 'El nombre de la carrera es obligatorio.',
            'nombre.min'      => 'El nombre debe tener al menos :min caracteres.',
            'nombre.max'      => 'El nombre no puede exceder :max caracteres.',
            'nombre.unique'   => 'Ya existe una carrera con ese nombre.',

            'ciclosTotal.required' => 'Selecciona el total de ciclos.',
            'ciclosTotal.integer'  => 'El total de ciclos no es válido.',
            'ciclosTotal.in'       => 'El total de ciclos permitido es 10, 12 o 14.',

            'estado.required' => 'El estado es obligatorio.',
            'estado.boolean'  => 'El estado no es válido.',
        ];
    }

    public function updated($campo)
    {
        $this->validateOnly($campo);
    }

    public function CrearCarrera()
    {
        $this->validate();
        try {

            Carrera::create([
                'facultad_id'  => $this->facultadId,
                'nombre'       => strtoupper(trim($this->nombre)),
                'ciclos_total' => $this->ciclosTotal,
            ]);
            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Carrera registrada correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error("Error al crear carrera: " . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Error al registrar carrera.', tipo: 'danger');
        }
    }

    public function EditarCarrera()
    {
        $this->validate();
        try {

            Carrera::FindOrFail($this->carreraId)->update([
                'facultad_id'  => $this->facultadId,
                'nombre'       => strtoupper(trim($this->nombre)),
                'ciclos_total' => $this->ciclosTotal,
            ]);
            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Carrera editada correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error("Error al Editar carrera: " . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Error al editar carrera.', tipo: 'danger');
        }
    }

    public function EliminarCarrera()
    {
        try {
            Carrera::FindOrFail($this->carreraId)->update([
                'estado'  => 0,
            ]);
            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Carrera eliminada correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error("Error al eliminar carrera: " . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Error al eliminar carrera.', tipo: 'danger');
        }
    }
    public function render()
    {
        $facultades = Catalogo::where('padre_id', 4)->get();
        $carreras = Carrera::search($this->query, $this->filtrofacultadId, $this->filtroestado)
            ->orderBy('id', 'desc')->paginate(10);
        return view('livewire.carreras.carreras', [
            'facultades' => $facultades,
            'carreras' => $carreras,
        ]);
    }
}
