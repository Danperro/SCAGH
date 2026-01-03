<?php

namespace App\Livewire\Cursos;

use App\Models\Carrera;
use App\Models\Catalogo;
use App\Models\Curso;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithPagination;

class Cursos extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $filtrociclo_id, $filtrocarrera_id, $filtrofacultad_id, $query;

    public $curso_id, $nombre, $codigo, $ciclo_id, $carrera_id, $facultad_id;

    public $carrerasFiltro = [];
    public $carreras = [];

    // =========================
    // Helpers
    // =========================
    private function ciclosPermitidos(?int $carreraId)
    {
        $padre = Catalogo::where('nombre', 'CICLO')->first();

        if (!$padre) {
            return collect();
        }

        $base = Catalogo::where('padre_id', $padre->id)->orderBy('id');

        if (empty($carreraId)) {
            return $base->get();
        }

        if (!Schema::hasColumn('carrera', 'ciclos_total')) {
            return $base->get();
        }

        $max = Carrera::whereKey($carreraId)->value('ciclos_total') ?? 12;

        return $base->take($max)->get();
    }

    private function cicloEsValidoParaCarrera(?int $carreraId, $cicloId): bool
    {
        if (empty($carreraId) || empty($cicloId)) return false;

        // si aún no migras, no bloquees
        if (!Schema::hasColumn('carrera', 'ciclos_total')) return true;

        $permitidos = $this->ciclosPermitidos((int)$carreraId)
            ->pluck('id')
            ->map(fn($v) => (int)$v)
            ->all();

        return in_array((int)$cicloId, $permitidos, true);
    }

    // =========================
    // Events / Updates
    // =========================
    public function updatedFacultadId($value)
    {
        $this->carreras = !empty($value)
            ? Carrera::where('facultad_id', $value)->get()
            : Carrera::all();

        $this->carrera_id = '';
        $this->ciclo_id = '';
    }

    public function updatedCarreraId($value)
    {
        // al cambiar carrera, resetea ciclo siempre
        $this->ciclo_id = '';
    }

    public function updatedFiltrofacultadId($value)
    {
        $this->carrerasFiltro = !empty($value)
            ? Carrera::where('facultad_id', $value)->get()
            : Carrera::all();

        $this->filtrocarrera_id = '';
        $this->filtrociclo_id = '';
    }

    public function updatedFiltrocarreraId($value)
    {
        // al cambiar carrera en filtros, resetea ciclo filtro
        $this->filtrociclo_id = '';
    }

    // =========================
    // CRUD helpers
    // =========================
    public function selectInfo($id)
    {
        $curso = Curso::findOrFail($id);

        $this->curso_id = $curso->id;

        $carrera = Carrera::find($curso->carrera_id);
        $this->facultad_id = $carrera?->facultad_id;

        $this->carreras = $this->facultad_id
            ? Carrera::where('facultad_id', $this->facultad_id)->get()
            : Carrera::all();

        $this->carrera_id = $curso->carrera_id;
        $this->ciclo_id   = $curso->ciclo_id;
        $this->nombre     = $curso->nombre;
        $this->codigo     = $curso->codigo;

        // si el ciclo guardado ya no aplica (porque cambiaste ciclos_total), lo limpias
        if (!$this->cicloEsValidoParaCarrera($this->carrera_id, $this->ciclo_id)) {
            $this->ciclo_id = '';
        }
    }

    public function limpiar()
    {
        $this->reset(['facultad_id', 'carrera_id', 'ciclo_id', 'nombre', 'codigo', 'query']);
        $this->reset(['filtrofacultad_id', 'filtrocarrera_id', 'filtrociclo_id']);
        $this->resetValidation();
    }

    // =========================
    // Validation
    // =========================
    protected function rules()
    {
        return [
            'facultad_id' => ['required', 'integer'],
            'carrera_id'  => ['required', 'integer'],
            'ciclo_id'    => [
                'required',
                'integer',
                function ($attr, $value, $fail) {
                    if (!$this->cicloEsValidoParaCarrera($this->carrera_id, $value)) {
                        $fail('El ciclo seleccionado no corresponde a la carrera.');
                    }
                }
            ],
            'nombre' => ['required', 'min:3'],
            'codigo' => ['required', 'min:3'],
        ];
    }
    protected function messages()
    {
        return [
            'facultad_id.required' => 'Selecciona una facultad.',
            'facultad_id.integer'  => 'La facultad seleccionada no es válida.',

            'carrera_id.required'  => 'Selecciona una carrera.',
            'carrera_id.integer'   => 'La carrera seleccionada no es válida.',

            'ciclo_id.required'    => 'Selecciona un ciclo.',
            'ciclo_id.integer'     => 'El ciclo seleccionado no es válido.',

            'nombre.required'      => 'El nombre es obligatorio.',
            'nombre.min'           => 'El nombre debe tener al menos :min caracteres.',

            'codigo.required'      => 'El código es obligatorio.',
            'codigo.min'           => 'El código debe tener al menos :min caracteres.',
        ];
    }

    public function updated($campo)
    {
        $this->validateOnly($campo);
    }

    // =========================
    // Actions
    // =========================
    public function CrearCurso()
    {
        $this->validate();
        try {

            Curso::create([
                'carrera_id' => $this->carrera_id,
                'ciclo_id'   => $this->ciclo_id,
                'nombre'     => strtoupper($this->nombre),
                'codigo'     => strtoupper($this->codigo),
                'estado'     => 1
            ]);

            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Curso creado correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error("Error al crear el curso: " . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Error al registrar carrera.', tipo: 'danger');
        }
    }

    public function EditarCurso()
    {
        $this->validate();
        try {

            Curso::findOrFail($this->curso_id)->update([
                'carrera_id' => $this->carrera_id,
                'ciclo_id'   => $this->ciclo_id,
                'nombre'     => strtoupper($this->nombre),
                'codigo'     => strtoupper($this->codigo),
                'estado'     => 1
            ]);

            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Curso editado correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error("Error al editar el curso: " . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Error al registrar carrera.', tipo: 'danger');
        }
    }

    public function EliminarCurso()
    {
        try {
            $curso = Curso::findOrFail($this->curso_id);
            $curso->update([
                'estado' => 0
            ]);

            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'curso al Eliminado correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error("Error al eliminar el curso: " . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Ocurrió un error al eliminar el Curso.', tipo: 'danger');
        }
    }

    // =========================
    // Render
    // =========================
    public function render()
    {
        $facultades = Catalogo::where('padre_id', 4)->get();

        $cursos = Curso::with(['carrera', 'ciclo'])
            ->search($this->query, $this->filtrocarrera_id, $this->filtrofacultad_id, $this->filtrociclo_id)
            ->orderBy('id', 'desc')->paginate(10);

        // ✅ separados
        $ciclosForm   = $this->ciclosPermitidos($this->carrera_id ? (int)$this->carrera_id : null);
        $ciclosFiltro = $this->ciclosPermitidos($this->filtrocarrera_id ? (int)$this->filtrocarrera_id : null);

        return view('livewire.cursos.cursos', [
            'facultades'     => $facultades,
            'carreras'       => $this->carreras,
            'carrerasFiltro' => $this->carrerasFiltro,
            'cursos'         => $cursos,
            'ciclosForm'     => $ciclosForm,
            'ciclosFiltro'   => $ciclosFiltro,
        ]);
    }
}
