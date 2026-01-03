<section class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="row g-3 align-items-center mb-4">

        <div class="col-12 col-md-8">
            <div
                class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start text-center text-md-start">
                <i class="bi bi-calendar-week fs-3 text-success"></i>
                <div>
                    <h3 class="fw-bold mb-0">HORARIOS</h3>
                    <small class="text-muted">Gestión de horarios por laboratorio</small>
                </div>
            </div>
        </div>


    </div>

    {{-- FILTROS --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <!-- Header de Filtros -->
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-funnel fs-4 text-primary me-2"></i>
                <h6 class="fw-bold mb-0">Seleccionar Horario</h6>
            </div>

            <div class="row g-3">

                {{-- Horario --}}
                <div class="col-12 col-lg-6">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar-week me-1"></i>Horarios
                    </label>
                    <select class="form-select" wire:model.live="horario_id" wire:change="actualizarHorario">
                        <option value="" hidden>Seleccione un horario</option>
                        @foreach ($horarios as $horario)
                            <option value="{{ $horario->id }}">{{ $horario->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Botones de acción --}}
                <div class="col-12 col-lg-6">
                    <label class="form-label fw-semibold d-block">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success flex-fill" data-bs-target="#modalCrearHorario"
                            data-bs-toggle="modal" wire:click="limpiar">
                            <i class="bi bi-plus-circle me-1"></i> Crear Horario
                        </button>
                        <button class="btn btn-success flex-fill" data-bs-target="#modalAñadirCurso"
                            data-bs-toggle="modal" wire:click="limpiar">
                            <i class="bi bi-plus-circle me-1"></i> Añadir Curso
                        </button>
                    </div>
                </div>

            </div>

            <!-- Resumen de horario seleccionado -->
            @if ($horario_id)
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>Horario seleccionado
                        </span>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- DÍAS DE LA SEMANA --}}
    <div class="card shadow-sm">

        <!-- Header de la sección -->
        <div class="card-header bg-white border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-calendar-week fs-4 text-success me-2"></i>
                    <h6 class="fw-bold mb-0">Cursos por Día</h6>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3">

                {{-- ========================= LUNES ========================= --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border">
                        <div class="card-header bg-success text-white">
                            <i class="bi bi-calendar-day me-2"></i>
                            <strong>Lunes</strong>
                        </div>
                        <div class="card-body">
                            @if (count($cursosPorDia[5]) == 0)
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    <small>Sin cursos asignados</small>
                                </div>
                            @else
                                @foreach ($cursosPorDia[5] as $curso)
                                    <div class="card mb-2 bg-light">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold small">
                                                        {{ $curso->curso->nombre . ' GRUPO:' . $curso->docenteCurso->grupo->nombre }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        Docente: {{ $curso->docenteCurso->docente->persona->nombre }}
                                                        {{ $curso->docenteCurso->docente->persona->apellido_paterno }}
                                                        {{ $curso->docenteCurso->docente->persona->apellido_materno }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ \Carbon\Carbon::parse($curso->hora_inicio)->format('h:i A') }}
                                                        - {{ \Carbon\Carbon::parse($curso->hora_fin)->format('h:i A') }}
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column gap-1 ms-2">
                                                    <button class="btn btn-warning btn-sm"
                                                        wire:click="selectInfo({{ $curso->id }})"
                                                        data-bs-toggle="modal" data-bs-target="#modalEditarCursoHorario"
                                                        title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm"
                                                        wire:click="selectInfo({{ $curso->id }})" title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ========================= MARTES ========================= --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border">
                        <div class="card-header bg-success text-white">
                            <i class="bi bi-calendar-day me-2"></i>
                            <strong>Martes</strong>
                        </div>
                        <div class="card-body">
                            @if (count($cursosPorDia[6]) == 0)
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    <small>Sin cursos asignados</small>
                                </div>
                            @else
                                @foreach ($cursosPorDia[6] as $curso)
                                    <div class="card mb-2 bg-light">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold small">
                                                        {{ $curso->curso->nombre . ' GRUPO:' . $curso->docenteCurso->grupo->nombre }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        Docente: {{ $curso->docenteCurso->docente->persona->nombre }}
                                                        {{ $curso->docenteCurso->docente->persona->apellido_paterno }}
                                                        {{ $curso->docenteCurso->docente->persona->apellido_materno }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ \Carbon\Carbon::parse($curso->hora_inicio)->format('h:i A') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($curso->hora_fin)->format('h:i A') }}
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column gap-1 ms-2">
                                                    <button class="btn btn-warning btn-sm"
                                                        wire:click="selectInfo({{ $curso->id }})"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditarCursoHorario" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm"
                                                        wire:click="selectInfo({{ $curso->id }})"
                                                        title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ========================= MIÉRCOLES ========================= --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border">
                        <div class="card-header bg-success text-white">
                            <i class="bi bi-calendar-day me-2"></i>
                            <strong>Miércoles</strong>
                        </div>
                        <div class="card-body">
                            @if (count($cursosPorDia[7]) == 0)
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    <small>Sin cursos asignados</small>
                                </div>
                            @else
                                @foreach ($cursosPorDia[7] as $curso)
                                    <div class="card mb-2 bg-light">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold small">
                                                        {{ $curso->curso->nombre . ' GRUPO:' . $curso->docenteCurso->grupo->nombre }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        Docente: {{ $curso->docenteCurso->docente->persona->nombre }}
                                                        {{ $curso->docenteCurso->docente->persona->apellido_paterno }}
                                                        {{ $curso->docenteCurso->docente->persona->apellido_materno }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ \Carbon\Carbon::parse($curso->hora_inicio)->format('h:i A') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($curso->hora_fin)->format('h:i A') }}
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column gap-1 ms-2">
                                                    <button class="btn btn-warning btn-sm"
                                                        wire:click="selectInfo({{ $curso->id }})"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditarCursoHorario" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm"
                                                        wire:click="selectInfo({{ $curso->id }})"
                                                        title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ========================= JUEVES ========================= --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border">
                        <div class="card-header bg-success text-white">
                            <i class="bi bi-calendar-day me-2"></i>
                            <strong>Jueves</strong>
                        </div>
                        <div class="card-body">
                            @if (count($cursosPorDia[8]) == 0)
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    <small>Sin cursos asignados</small>
                                </div>
                            @else
                                @foreach ($cursosPorDia[8] as $curso)
                                    <div class="card mb-2 bg-light">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold small">
                                                        {{ $curso->curso->nombre . ' GRUPO:' . $curso->docenteCurso->grupo->nombre }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        Docente: {{ $curso->docenteCurso->docente->persona->nombre }}
                                                        {{ $curso->docenteCurso->docente->persona->apellido_paterno }}
                                                        {{ $curso->docenteCurso->docente->persona->apellido_materno }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ \Carbon\Carbon::parse($curso->hora_inicio)->format('h:i A') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($curso->hora_fin)->format('h:i A') }}
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column gap-1 ms-2">
                                                    <button class="btn btn-warning btn-sm"
                                                        wire:click="selectInfo({{ $curso->id }})"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditarCursoHorario" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm"
                                                        wire:click="selectInfo({{ $curso->id }})"
                                                        title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ========================= VIERNES ========================= --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border">
                        <div class="card-header bg-success text-white">
                            <i class="bi bi-calendar-day me-2"></i>
                            <strong>Viernes</strong>
                        </div>
                        <div class="card-body">
                            @if (count($cursosPorDia[9]) == 0)
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    <small>Sin cursos asignados</small>
                                </div>
                            @else
                                @foreach ($cursosPorDia[9] as $curso)
                                    <div class="card mb-2 bg-light">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold small">
                                                        {{ $curso->curso->nombre . ' GRUPO:' . $curso->docenteCurso->grupo->nombre }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        Docente: {{ $curso->docenteCurso->docente->persona->nombre }}
                                                        {{ $curso->docenteCurso->docente->persona->apellido_paterno }}
                                                        {{ $curso->docenteCurso->docente->persona->apellido_materno }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ \Carbon\Carbon::parse($curso->hora_inicio)->format('h:i A') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($curso->hora_fin)->format('h:i A') }}
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column gap-1 ms-2">
                                                    <button class="btn btn-warning btn-sm"
                                                        wire:click="selectInfo({{ $curso->id }})"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditarCursoHorario" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm"
                                                        wire:click="selectInfo({{ $curso->id }})"
                                                        title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ========================= SÁBADO ========================= --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border">
                        <div class="card-header bg-success text-white">
                            <i class="bi bi-calendar-day me-2"></i>
                            <strong>Sábado</strong>
                        </div>
                        <div class="card-body">
                            @if (count($cursosPorDia[10]) == 0)
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    <small>Sin cursos asignados</small>
                                </div>
                            @else
                                @foreach ($cursosPorDia[10] as $curso)
                                    <div class="card mb-2 bg-light">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold small">
                                                        {{ $curso->curso->nombre . ' GRUPO:' . $curso->docenteCurso->grupo->nombre }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        Docente: {{ $curso->docenteCurso->docente->persona->nombre }}
                                                        {{ $curso->docenteCurso->docente->persona->apellido_paterno }}
                                                        {{ $curso->docenteCurso->docente->persona->apellido_materno }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ \Carbon\Carbon::parse($curso->hora_inicio)->format('h:i A') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($curso->hora_fin)->format('h:i A') }}
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column gap-1 ms-2">
                                                    <button class="btn btn-warning btn-sm"
                                                        wire:click="selectInfo({{ $curso->id }})"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditarCursoHorario" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm"
                                                        wire:click="selectInfo({{ $curso->id }})"
                                                        title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- MODAL CREAR HORARIO --}}
    <div wire:ignore.self class="modal fade" id="modalCrearHorario" tabindex="-1"
        aria-labelledby="modalCrearHorario" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form wire:submit.prevent="CrearHorario" class="modal-content">

                <!-- HEADER -->
                <div class="modal-header bg-success bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>Crear Horario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <!-- Bloque informativo -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-week fs-3 text-success me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Datos del horario</h6>
                                <small class="text-muted">Completa la información del horario</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">

                        <!-- Laboratorio -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-laptop me-1"></i>Laboratorio <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('laboratorio_id') is-invalid @enderror"
                                wire:model.live="laboratorio_id">
                                <option hidden value="">Seleccione</option>
                                @foreach ($laboratorios as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->nombre }}</option>
                                @endforeach
                            </select>
                            @error('laboratorio_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Semestre -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-range me-1"></i>Semestre <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('semestre_id') is-invalid @enderror"
                                wire:model.live="semestre_id">
                                <option hidden value="">Seleccione</option>
                                @foreach ($semestres as $semestre)
                                    <option value="{{ $semestre->id }}">{{ $semestre->nombre }}</option>
                                @endforeach
                            </select>
                            @error('semestre_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nombre del Horario -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-journal-text me-1"></i>Nombre del Horario <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nombreHorario') is-invalid @enderror"
                                wire:model.live="nombreHorario" placeholder="Ej. Horario Lab A - 2024-I"
                                @disabled(!$editableNombre)>
                            @error('nombreHorario')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Check editar -->
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model.live="editableNombre"
                                    id="checkEditarNombre">
                                <label class="form-check-label" for="checkEditarNombre">
                                    Editar nombre manualmente
                                </label>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="limpiar">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>

                    <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="bi bi-check2-circle me-1"></i>Guardar
                        </span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Guardando...
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>


    {{-- MODAL AÑADIR CURSO --}}
    <div wire:ignore.self class="modal fade" id="modalAñadirCurso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="AsignarCurso" class="modal-content">

                <!-- HEADER -->
                <div class="modal-header bg-success bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>Añadir Curso al Horario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <!-- Bloque informativo -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-book fs-3 text-success me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Asignación de curso</h6>
                                <small class="text-muted">Selecciona el curso y configura el horario</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">

                        <!-- FACULTAD -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-building me-1"></i>Facultad <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('facultad_id') is-invalid @enderror"
                                wire:model.live="facultad_id">
                                <option hidden value="">Seleccione una facultad</option>
                                @foreach ($facultades as $facultad)
                                    <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                                @endforeach
                            </select>
                            @error('facultad_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CARRERA -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-mortarboard me-1"></i>Carrera <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('carrera_id') is-invalid @enderror"
                                wire:model.live="carrera_id" @disabled(!$facultad_id)>
                                <option hidden value="">Seleccione una carrera</option>
                                @foreach ($carreras as $carrera)
                                    <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                                @endforeach
                            </select>
                            @error('carrera_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CURSO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-journal-text me-1"></i>Curso <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('curso_id') is-invalid @enderror"
                                wire:model.live="curso_id" @disabled(!$carrera_id)>
                                <option hidden value="">Seleccione un curso</option>
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                @endforeach
                            </select>
                            @error('curso_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- GRUPO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-people me-1"></i>Grupo <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('grupo_id') is-invalid @enderror"
                                wire:model.live="grupo_id" @disabled(!$curso_id)>
                                <option hidden value="">Seleccione un grupo</option>
                                @foreach ($grupos as $grupo)
                                    <option value="{{ $grupo['docente_curso_id'] }}">
                                        {{ $grupo['grupo_nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('grupo_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- HORARIO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-week me-1"></i>Horario <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('horario_id') is-invalid @enderror"
                                wire:model.live="horario_id">
                                <option hidden value="">Seleccione un horario</option>
                                @foreach ($horarios as $horario)
                                    <option value="{{ $horario->id }}">{{ $horario->nombre }}</option>
                                @endforeach
                            </select>
                            @error('horario_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DÍA -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-day me-1"></i>Día <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('semana_id') is-invalid @enderror"
                                wire:model.live="semana_id">
                                <option hidden value="">Seleccione un día</option>
                                @foreach ($dias as $dia)
                                    <option value="{{ $dia->id }}">{{ $dia->nombre }}</option>
                                @endforeach
                            </select>
                            @error('semana_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- HORA INICIO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-clock me-1"></i>Hora de Inicio <span class="text-danger">*</span>
                            </label>
                            <input type="time" class="form-control @error('hora_inicio') is-invalid @enderror"
                                wire:model.live="hora_inicio">
                            @error('hora_inicio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- HORA FIN -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-clock-fill me-1"></i>Hora de Fin <span class="text-danger">*</span>
                            </label>
                            <input type="time" class="form-control @error('hora_fin') is-invalid @enderror"
                                wire:model.live="hora_fin">
                            @error('hora_fin')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="limpiar">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>

                    <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="bi bi-check2-circle me-1"></i>Guardar
                        </span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Guardando...
                        </span>
                    </button>
                </div>

            </form>

        </div>
    </div>

    {{-- MODAL EDITAR CURSO DEL HORARIO --}}
    <div wire:ignore.self class="modal fade" id="modalEditarCursoHorario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="EditarCursoHorario" class="modal-content">

                <!-- HEADER -->
                <div class="modal-header bg-warning bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Editar Curso del Horario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <!-- Bloque informativo -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-pencil-square fs-3 text-warning me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Modificar asignación de curso</h6>
                                <small class="text-muted">Actualiza la información del curso en el horario</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">

                        <!-- FACULTAD -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-building me-1"></i>Facultad <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('facultad_id') is-invalid @enderror"
                                wire:model.live="facultad_id">
                                <option hidden value="">Seleccione una facultad</option>
                                @foreach ($facultades as $facultad)
                                    <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                                @endforeach
                            </select>
                            @error('facultad_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CARRERA -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-mortarboard me-1"></i>Carrera <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('carrera_id') is-invalid @enderror"
                                wire:model.live="carrera_id" wire:key="carrera-{{ $facultad_id }}"
                                @disabled(!$facultad_id)>
                                <option hidden value="">Seleccione una carrera</option>
                                @foreach ($carreras as $carrera)
                                    <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                                @endforeach
                            </select>
                            @error('carrera_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CURSO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-journal-text me-1"></i>Curso <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('curso_id') is-invalid @enderror"
                                wire:model.live="curso_id" wire:key="curso-{{ $carrera_id }}"
                                @disabled(!$carrera_id)>
                                <option hidden value="">Seleccione un curso</option>
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                @endforeach
                            </select>
                            @error('curso_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- GRUPO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-people me-1"></i>Grupo <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('grupo_id') is-invalid @enderror"
                                wire:model.live="grupo_id" wire:key="grupo-{{ $curso_id }}"
                                @disabled(!$curso_id)>
                                <option hidden value="">Seleccione un grupo</option>
                                @foreach ($grupos as $grupo)
                                    <option value="{{ $grupo['docente_curso_id'] }}">
                                        {{ $grupo['grupo_nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('grupo_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- HORARIO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-week me-1"></i>Horario <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('horario_id') is-invalid @enderror"
                                wire:model.live="horario_id">
                                <option hidden value="">Seleccione un horario</option>
                                @foreach ($horarios as $horario)
                                    <option value="{{ $horario->id }}">{{ $horario->nombre }}</option>
                                @endforeach
                            </select>
                            @error('horario_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DÍA -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-day me-1"></i>Día <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('semana_id') is-invalid @enderror"
                                wire:model.live="semana_id">
                                <option hidden value="">Seleccione un día</option>
                                @foreach ($dias as $dia)
                                    <option value="{{ $dia->id }}">{{ $dia->nombre }}</option>
                                @endforeach
                            </select>
                            @error('semana_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- HORA INICIO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-clock me-1"></i>Hora de Inicio <span class="text-danger">*</span>
                            </label>
                            <input type="time" class="form-control @error('hora_inicio') is-invalid @enderror"
                                wire:model.live="hora_inicio">
                            @error('hora_inicio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- HORA FIN -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-clock-fill me-1"></i>Hora de Fin <span class="text-danger">*</span>
                            </label>
                            <input type="time" class="form-control @error('hora_fin') is-invalid @enderror"
                                wire:model.live="hora_fin">
                            @error('hora_fin')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="limpiar">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>

                    <button type="submit" class="btn btn-warning" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="bi bi-save2 me-1"></i>Guardar cambios
                        </span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Guardando...
                        </span>
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- TOAST DE GENERAL -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
        <div id="toastGeneral" class="toast align-items-center text-white fw-bold border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastGeneralTexto"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

</section>

<script>
    document.addEventListener('livewire:initialized', () => {

        // Cerrar los modales al recibir el evento
        Livewire.on('cerrarModal', () => {
            const modales = document.querySelectorAll('.modal.show');
            modales.forEach(modal => {
                const instancia = bootstrap.Modal.getInstance(modal);
                const modalBootstrap = instancia ?? new bootstrap.Modal(modal);
                modalBootstrap.hide();
            });
        });

        // Toast general
        Livewire.on('toast-general', ({
            mensaje,
            tipo
        }) => {
            const toast = document.getElementById('toastGeneral');
            toast.classList.remove('bg-success', 'bg-danger', 'bg-warning');
            toast.classList.add(`bg-${tipo}`);
            document.getElementById('toastGeneralTexto').innerText = mensaje;
            const toastShow = new bootstrap.Toast(toast);
            toastShow.show();
        });

    });
</script>
