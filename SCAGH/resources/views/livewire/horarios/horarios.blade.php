<section class="container-fluid py-4">

    <!-- Título -->
    <h2 class="fw-bold mb-1">Horarios</h2>
    <p class="text-muted mb-4">Gestión de horarios por laboratorio</p>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm p-4">
        <div class="row g-2 align-items-end">
            <!-- Semestre -->
            <div class="col-12 col-md-5">
                <label class="form-label fw-semibold">Horarios</label>
                <select class="form-select" wire:model.live="horario_id" wire:change="actualizarHorario">
                    <option value="" hidden>Seleccione</option>
                    @foreach ($horarios as $horario)
                        <option value="{{ $horario->id }}">{{ $horario->nombre }}</option>
                    @endforeach
                </select>
            </div>


            <!-- Botón Crear Horario -->
            <div class="col-12 col-md-3">
                <button class="btn btn-success w-100 px-4" data-bs-target="#modalCrearHorario" data-bs-toggle="modal"
                    wire:click="limpiar">
                    + Crear un Nuevo Horario
                </button>
            </div>

            <!-- Botón Añadir Curso -->
            <div class="col-12 col-md-3">
                <button class="btn btn-success w-100 px-4" data-bs-target="#modalAñadirCurso" data-bs-toggle="modal"
                    wire:click="limpiar">
                    + Añadir Curso
                </button>
            </div>
        </div>
    </div>


    <!-- Días de la semana -->
    <div class="row g-4">

        {{-- ========================= LUNES ========================= --}}
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Lunes
            </div>

            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                @if (count($cursosPorDia[5]) == 0)
                    <p class="text-muted text-center">Sin cursos asignados</p>
                @else
                    @foreach ($cursosPorDia[5] as $curso)
                        <div class="mb-2 p-2 border rounded bg-light">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $curso->curso->nombre . ' GRUPO:' . $curso->docenteCurso->grupo->nombre }}</strong><br>
                                    <strong>
                                        Docente: {{ $curso->docenteCurso->docente->persona->nombre }}
                                        {{ $curso->docenteCurso->docente->persona->apellido_paterno }}
                                        {{ $curso->docenteCurso->docente->persona->apellido_materno }}
                                    </strong><br>

                                    {{ \Carbon\Carbon::parse($curso->hora_inicio)->format('h:i A') }}
                                    -
                                    {{ \Carbon\Carbon::parse($curso->hora_fin)->format('h:i A') }}
                                </div>

                                <div class="d-flex flex-column gap-1">
                                    <button class="btn btn-warning btn-sm" wire:click="selectInfo({{ $curso->id }})"
                                        data-bs-toggle="modal" data-bs-target="#modalEditarCursoHorario">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button class="btn btn-danger btn-sm" wire:click="selectInfo({{ $curso->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>


        {{-- ========================= MARTES ========================= --}}
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Martes
            </div>

            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                @if (count($cursosPorDia[6]) == 0)
                    <p class="text-muted text-center">Sin cursos asignados</p>
                @else
                    @foreach ($cursosPorDia[6] as $curso)
                        <div class="mb-2 p-2 border rounded bg-light">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $curso->curso->nombre . ' GRUPO:' . $curso->docenteCurso->grupo->nombre }}</strong><br>
                                    <strong>
                                        Docente: {{ $curso->docenteCurso->docente->persona->nombre }}
                                        {{ $curso->docenteCurso->docente->persona->apellido_paterno }}
                                        {{ $curso->docenteCurso->docente->persona->apellido_materno }}
                                    </strong><br>

                                    {{ \Carbon\Carbon::parse($curso->hora_inicio)->format('h:i A') }}
                                    -
                                    {{ \Carbon\Carbon::parse($curso->hora_fin)->format('h:i A') }}
                                </div>

                                <div class="d-flex flex-column gap-1">
                                    <button class="btn btn-warning btn-sm" wire:click="selectInfo({{ $curso->id }})"
                                        data-bs-toggle="modal" data-bs-target="#modalEditarCursoHorario">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button class="btn btn-danger btn-sm" wire:click="selectInfo({{ $curso->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>


        {{-- ========================= MIÉRCOLES ========================= --}}
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Miércoles
            </div>

            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                @if (count($cursosPorDia[7]) == 0)
                    <p class="text-muted text-center">Sin cursos asignados</p>
                @else
                    @foreach ($cursosPorDia[7] as $curso)
                        <div class="mb-2 p-2 border rounded bg-light">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $curso->curso->nombre . ' GRUPO:' . $curso->docenteCurso->grupo->nombre }}</strong><br>
                                    <strong>
                                        Docente: {{ $curso->docenteCurso->docente->persona->nombre }}
                                        {{ $curso->docenteCurso->docente->persona->apellido_paterno }}
                                        {{ $curso->docenteCurso->docente->persona->apellido_materno }}
                                    </strong><br>

                                    {{ \Carbon\Carbon::parse($curso->hora_inicio)->format('h:i A') }}
                                    -
                                    {{ \Carbon\Carbon::parse($curso->hora_fin)->format('h:i A') }}
                                </div>

                                <div class="d-flex flex-column gap-1">
                                    <button class="btn btn-warning btn-sm" wire:click="selectInfo({{ $curso->id }})"
                                        data-bs-toggle="modal" data-bs-target="#modalEditarCursoHorario">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button class="btn btn-danger btn-sm" wire:click="selectInfo({{ $curso->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>


        {{-- ========================= JUEVES ========================= --}}
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Jueves
            </div>

            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                @if (count($cursosPorDia[8]) == 0)
                    <p class="text-muted text-center">Sin cursos asignados</p>
                @else
                    @foreach ($cursosPorDia[8] as $curso)
                        <div class="mb-2 p-2 border rounded bg-light">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $curso->curso->nombre . ' GRUPO:' . $curso->docenteCurso->grupo->nombre }}</strong><br>
                                    <strong>
                                        Docente: {{ $curso->docenteCurso->docente->persona->nombre }}
                                        {{ $curso->docenteCurso->docente->persona->apellido_paterno }}
                                        {{ $curso->docenteCurso->docente->persona->apellido_materno }}
                                    </strong><br>

                                    {{ \Carbon\Carbon::parse($curso->hora_inicio)->format('h:i A') }}
                                    -
                                    {{ \Carbon\Carbon::parse($curso->hora_fin)->format('h:i A') }}
                                </div>

                                <div class="d-flex flex-column gap-1">
                                    <button class="btn btn-warning btn-sm" wire:click="selectInfo({{ $curso->id }})"
                                        data-bs-toggle="modal" data-bs-target="#modalEditarCursoHorario">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button class="btn btn-danger btn-sm" wire:click="selectInfo({{ $curso->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>


        {{-- ========================= VIERNES ========================= --}}
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Viernes
            </div>

            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                @if (count($cursosPorDia[9]) == 0)
                    <p class="text-muted text-center">Sin cursos asignados</p>
                @else
                    @foreach ($cursosPorDia[9] as $curso)
                        <div class="mb-2 p-2 border rounded bg-light">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $curso->curso->nombre . ' GRUPO:' . $curso->docenteCurso->grupo->nombre }}</strong><br>
                                    <strong>
                                        Docente: {{ $curso->docenteCurso->docente->persona->nombre }}
                                        {{ $curso->docenteCurso->docente->persona->apellido_paterno }}
                                        {{ $curso->docenteCurso->docente->persona->apellido_materno }}
                                    </strong><br>

                                    {{ \Carbon\Carbon::parse($curso->hora_inicio)->format('h:i A') }}
                                    -
                                    {{ \Carbon\Carbon::parse($curso->hora_fin)->format('h:i A') }}
                                </div>

                                <div class="d-flex flex-column gap-1">
                                    <button class="btn btn-warning btn-sm" wire:click="selectInfo({{ $curso->id }})"
                                        data-bs-toggle="modal" data-bs-target="#modalEditarCursoHorario">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button class="btn btn-danger btn-sm"
                                        wire:click="selectInfo({{ $curso->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>


        {{-- ========================= SÁBADO ========================= --}}
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Sábado
            </div>

            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                @if (count($cursosPorDia[10]) == 0)
                    <p class="text-muted text-center">Sin cursos asignados</p>
                @else
                    @foreach ($cursosPorDia[10] as $curso)
                        <div class="mb-2 p-2 border rounded bg-light">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $curso->curso->nombre . ' GRUPO:' . $curso->docenteCurso->grupo->nombre }}</strong><br>
                                    <strong>
                                        Docente: {{ $curso->docenteCurso->docente->persona->nombre }}
                                        {{ $curso->docenteCurso->docente->persona->apellido_paterno }}
                                        {{ $curso->docenteCurso->docente->persona->apellido_materno }}
                                    </strong><br>

                                    {{ \Carbon\Carbon::parse($curso->hora_inicio)->format('h:i A') }}
                                    -
                                    {{ \Carbon\Carbon::parse($curso->hora_fin)->format('h:i A') }}
                                </div>

                                <div class="d-flex flex-column gap-1">
                                    <button class="btn btn-warning btn-sm"
                                        wire:click="selectInfo({{ $curso->id }})" data-bs-toggle="modal"
                                        data-bs-target="#modalEditarCursoHorario">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button class="btn btn-danger btn-sm"
                                        wire:click="selectInfo({{ $curso->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>


    <!-- Modal crear Horario -->
    <div wire:ignore.self class="modal fade" id="modalCrearHorario" tabindex="-1"
        aria-labelledby="modalCrearHorario" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form wire:submit.prevent="CrearHorario" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Crear Horario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"
                        arial-label="close"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <!-- Laboratorio -->
                        <div class="col-md-6">
                            <label class="form-label">Seleccionar Laboratorio</label>
                            <select class="form-select @error('laboratorio_id') is-invalid @enderror"
                                wire:model.live="laboratorio_id">
                                <option hidden value="">Seleccione</option>
                                @foreach ($laboratorios as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->nombre }}</option>
                                @endforeach
                            </select>
                            @error('laboratorio_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Semestre -->
                        <div class="col-md-6">
                            <label class="form-label">Seleccionar Semestre</label>
                            <select class="form-select @error('semestre_id') is-invalid @enderror"
                                wire:model.live="semestre_id">
                                <option hidden value="">Seleccione</option>
                                @foreach ($semestres as $semestre)
                                    <option value="{{ $semestre->id }}">{{ $semestre->nombre }}</option>
                                @endforeach
                            </select>
                            @error('semestre_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <!-- Nombre_horario -->
                    <div class="mt-3">
                        <label class="form-label">Nombre del Horario</label>
                        <input type="text" class="form-control @error('nombreHorario') is-invalid @enderror"
                            wire:model.live="nombreHorario" @disabled(!$editableNombre)>
                        @error('nombreHorario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Check editar -->
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" wire:model.live="editableNombre">
                        <label class="form-check-label">Editar Nombre</label>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" wire:click="limpiar">Cerrar</button>
                    <button type="submit"class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading.remove>Guardar</span>
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                    </button>
                </div>

            </form>
        </div>
    </div>


    <!-- Modal Añadir Curso -->
    <div wire:ignore.self class="modal fade" id="modalAñadirCurso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="AsignarCurso" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Añadir Curso al Horario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <!-- FACULTAD -->
                        <div class="col-md-12">
                            <label class="form-label">Seleccionar Facultad</label>
                            <select class="form-select @error('facultad_id') is-invalid @enderror"
                                wire:model.live="facultad_id">
                                <option hidden value="">Seleccione</option>
                                @foreach ($facultades as $facultad)
                                    <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                                @endforeach
                            </select>
                            @error('facultad_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CARRERA -->
                        <div class="col-md-12">
                            <label class="form-label">Seleccionar Carrera</label>
                            <select class="form-select @error('carrera_id') is-invalid @enderror"
                                wire:model.live="carrera_id" @disabled(!$facultad_id)>
                                <option hidden value="">Seleccione</option>
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
                            <label class="form-label">Seleccionar Curso</label>
                            <select class="form-select @error('curso_id') is-invalid @enderror"
                                wire:model.live="curso_id" @disabled(!$carrera_id)>
                                <option hidden value="">Seleccione</option>
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                @endforeach
                            </select>

                            @error('curso_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Seleccionar Grupo</label>
                            <select class="form-select @error('grupo_id') is-invalid @enderror"
                                wire:model.live="grupo_id" @disabled(!$curso_id)>
                                <option hidden value="">Seleccione</option>

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
                            <label class="form-label">Seleccionar Horario</label>
                            <select class="form-select @error('horario_id') is-invalid @enderror"
                                wire:model.live="horario_id">
                                <option hidden value="">Seleccione</option>
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
                            <label class="form-label">Seleccionar Día</label>
                            <select class="form-select @error('semana_id') is-invalid @enderror"
                                wire:model.live="semana_id">
                                <option hidden value="">Seleccione</option>
                                @foreach ($dias as $dia)
                                    <option value="{{ $dia->id }}">{{ $dia->nombre }}</option>
                                @endforeach
                            </select>
                            @error('semana_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- HORA INICIO -->
                        <div class="col-md-3">
                            <label class="form-label">Hora de Inicio</label>
                            <input type="time" class="form-control @error('hora_inicio') is-invalid @enderror"
                                wire:model.live="hora_inicio">
                            @error('hora_inicio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- HORA FIN -->
                        <div class="col-md-3">
                            <label class="form-label">Hora de Fin</label>
                            <input type="time" class="form-control @error('hora_fin') is-invalid @enderror"
                                wire:model.live="hora_fin">
                            @error('hora_fin')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" wire:click="limpiar">Cerrar</button>

                    <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading.remove>Guardar</span>
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- Modal Editar Curso del Horario-->
    <div wire:ignore.self class="modal fade" id="modalEditarCursoHorario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="EditarCursoHorario" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Editar el Curso al Horario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <!-- FACULTAD -->
                        <div class="col-md-12">
                            <label class="form-label">Seleccionar Facultad</label>
                            <select class="form-select @error('facultad_id') is-invalid @enderror"
                                wire:model.live="facultad_id">
                                <option hidden value="">Seleccione</option>
                                @foreach ($facultades as $facultad)
                                    <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                                @endforeach
                            </select>
                            @error('facultad_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CARRERA -->
                        <div class="col-md-12">
                            <label class="form-label">Seleccionar Carrera</label>
                            <select class="form-select @error('carrera_id') is-invalid @enderror"
                                wire:model.live="carrera_id" wire:key="carrera-{{ $facultad_id }}"
                                @disabled(!$facultad_id)>
                                <option hidden value="">Seleccione</option>
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
                            <label class="form-label">Seleccionar Curso</label>
                            <select class="form-select @error('curso_id') is-invalid @enderror"
                                wire:model.live="curso_id" wire:key="curso-{{ $carrera_id }}"
                                @disabled(!$carrera_id)>
                                <option hidden value="">Seleccione</option>
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
                            <label class="form-label">Seleccionar Grupo</label>
                            <select class="form-select @error('grupo_id') is-invalid @enderror"
                                wire:model.live="grupo_id" wire:key="grupo-{{ $curso_id }}"
                                @disabled(!$curso_id)>
                                <option hidden value="">Seleccione</option>

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
                            <label class="form-label">Seleccionar Horario</label>
                            <select class="form-select @error('horario_id') is-invalid @enderror"
                                wire:model.live="horario_id">
                                <option hidden value="">Seleccione</option>
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
                            <label class="form-label">Seleccionar Día</label>
                            <select class="form-select @error('semana_id') is-invalid @enderror"
                                wire:model.live="semana_id">
                                <option hidden value="">Seleccione</option>
                                @foreach ($dias as $dia)
                                    <option value="{{ $dia->id }}">{{ $dia->nombre }}</option>
                                @endforeach
                            </select>
                            @error('semana_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- HORA INICIO -->
                        <div class="col-md-3">
                            <label class="form-label">Hora de Inicio</label>
                            <input type="time" class="form-control @error('hora_inicio') is-invalid @enderror"
                                wire:model.live="hora_inicio">
                            @error('hora_inicio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- HORA FIN -->
                        <div class="col-md-3">
                            <label class="form-label">Hora de Fin</label>
                            <input type="time" class="form-control @error('hora_fin') is-invalid @enderror"
                                wire:model.live="hora_fin">
                            @error('hora_fin')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" wire:click="limpiar">Cerrar</button>

                    <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading.remove>Guardar</span>
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                    </button>
                </div>

            </form>

        </div>
    </div>





    <!-- TOAST DE GENERAL -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
        <div id="toastGeneral" class="toast align-items-center text-white fw-bold border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastGeneralTexto">

                </div>
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
            // Obtener todos los modales visibles en la página
            const modales = document.querySelectorAll('.modal.show');

            modales.forEach(modal => {
                const instancia = bootstrap.Modal.getInstance(modal);

                // Si no existe instancia (ej. primer uso), la creamos
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

            // Limpia clases previas
            toast.classList.remove('bg-success', 'bg-danger', 'bg-warning');

            // Agrega la clase según el tipo
            toast.classList.add(`bg-${tipo}`);

            // Cambia el texto
            document.getElementById('toastGeneralTexto').innerText = mensaje;

            // Muestra el toast
            const toastShow = new bootstrap.Toast(toast);
            toastShow.show();
        });

    });
</script>
