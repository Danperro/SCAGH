<?php

namespace App\Livewire\Reportes;

use App\Models\Asistencia;
use App\Models\Carrera;
use App\Models\Catalogo;
use App\Models\Curso;
use App\Models\Laboratorio;
use App\Models\Semestre;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Reportes extends Component
{
    use WithPagination;
    #[Url('Busqueda')]
    public $query = '';

    public $filtrofacultad_id = null;
    public $filtrocarrera_id = null;
    public $filtrocurso_id = null;

    public $filtrolaboratorio_id = null;
    public $filtrosemetre_id = null;

    public $fecha_inicio = null;
    public $fecha_fin = null;

    public function updatedFiltrofacultadId()
    {
        $this->filtrocarrera_id = null;
        $this->filtrocurso_id = null;
        $this->resetPage();
    }

    public function updatedFiltrocarreraId()
    {
        $this->filtrocurso_id = null;
        $this->resetPage();
    }

    public function updated($property)
    {
        $this->resetPage();
    }

    public function limpiar()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset([
            'query',
            'filtrofacultad_id',
            'filtrocarrera_id',
            'filtrocurso_id',
            'filtrolaboratorio_id',
            'filtrosemetre_id',
            'fecha_inicio',
            'fecha_fin',
        ]);
        $this->resetPage();
    }
    public function render()
    {
   
        $facultad_id = Catalogo::where('nombre', 'FACULTAD')->first()->id;

        $facultades = Catalogo::where('padre_id', $facultad_id)->get();

        $carreras = $this->filtrofacultad_id
            ? Carrera::where('facultad_id', $this->filtrofacultad_id)->orderBy('nombre')->get()
            : collect();

        $cursos = $this->filtrocarrera_id
            ? Curso::where('carrera_id', $this->filtrocarrera_id)->orderBy('nombre')->get()
            : collect();

        $laboratorios = Laboratorio::orderBy('nombre')->get();
        $semestres = Semestre::orderBy('nombre')->get();

      

        $asistencias = Asistencia::with([
            'horarioCursoDocente.horario.laboratorio',
            'horarioCursoDocente.docenteCurso.curso.carrera.facultad',
            'horarioCursoDocente.docenteCurso.docente.persona',
        ])
            ->search([
                'query'           => $this->query,
                'laboratorio_id'  => $this->filtrolaboratorio_id,
                'semestre_id'     => $this->filtrosemetre_id,
                'facultad_id'     => $this->filtrofacultad_id,
                'carrera_id'      => $this->filtrocarrera_id,
                'curso_id'        => $this->filtrocurso_id,
                'fecha_inicio'    => $this->fecha_inicio,
                'fecha_fin'       => $this->fecha_fin,
            ])
            ->orderBy('fecha_registro', 'desc')
            ->paginate(10);

        return view('livewire.reportes.reportes', compact(
            'asistencias',
            'facultades',
            'carreras',
            'cursos',
            'laboratorios',
            'semestres'
        ));
    }
}
