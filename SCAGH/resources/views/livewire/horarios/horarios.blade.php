<div class="container-fluid py-4">

    <!-- Título -->
    <h2 class="fw-bold mb-1">Horarios</h2>
    <p class="text-muted mb-4">Gestión de horarios por laboratorio</p>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm p-4">
        <div class="row g-2 align-items-end">
            <!-- Semestre -->
            <div class="col-12 col-lg-3">
                <label class="form-label fw-semibold">Semestre</label>
                <select class="form-select">
                    <option value="">Seleccione</option>
                    {{-- Se llenará con la BD --}}
                </select>
            </div>

            <!-- Laboratorio -->
            <div class="col-12 col-lg-3">
                <label class="form-label fw-semibold">Laboratorio</label>
                <select class="form-select" id="laboratorio_id" wire:0model.live="laboratorio_id">
                    <option value="" hidden>Todos los laboratorios</option>
                    @foreach ($laboratorios as $lab)
                        <option value="{{ $lab->id }}">{{ $lab->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Botón crear horario -->
            <div class="col-12 col-lg-3">
                <button class="btn btn-success px-4" data-bs-target="#modalCrearHorario" data-bs-toggle="modal"
                    wire:click="">
                    + Crear un Nuevo Horario
                </button>
            </div>

            <!-- Botón añadir -->
            <div class="col-12 col-lg-3">
                <button class="btn btn-success px-4" data-bs-target="#modalAñadirCurso" data-bs-toggle="modal"
                    wire:click="">
                    + Añadir Curso
                </button>
            </div>
        </div>
    </div>

    <!-- Días de la semana -->
    <div class="row g-4">

        <!-- Lunes -->
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Lunes
            </div>
            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                <p class="text-muted text-center">Sin cursos asignados</p>
            </div>
        </div>

        <!-- Martes -->
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Martes
            </div>
            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                <p class="text-muted text-center">Sin cursos asignados</p>
            </div>
        </div>

        <!-- Miércoles -->
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Miércoles
            </div>
            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                <p class="text-muted text-center">Sin cursos asignados</p>
            </div>
        </div>

        <!-- Jueves -->
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Jueves
            </div>
            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                <p class="text-muted text-center">Sin cursos asignados</p>
            </div>
        </div>

        <!-- Viernes -->
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Viernes
            </div>
            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                <p class="text-muted text-center">Sin cursos asignados</p>
            </div>
        </div>

        <!-- Sábado -->
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Sábado
            </div>
            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                <p class="text-muted text-center">Sin cursos asignados</p>
            </div>
        </div>

    </div>
    <!-- Modal crear Horario -->
    <div wire:ignore.self class="modal fade" id="modalCrearHorario" tabindex="-1"
        aria-labelledby="modalCrearHorarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalCrearHorarioLabel">Añadir Horario al Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para agregar un curso al horario -->

                    <!-- Fila para Laboratorio y Semestre -->
                    <div class="row g-3">
                        <!-- Combo de Laboratorio -->
                        <div class="col-md-6">
                            <label for="laboratorioSelect" class="form-label">Seleccionar Laboratorio</label>
                            <select class="form-select" id="laboratorioSelect" wire:model="laboratorio_id">
                                <option value="">Seleccione</option>
                                <!-- Se llenará con la BD -->
                                @foreach ($laboratorios as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Combo de Semestre -->
                        <div class="col-md-6">
                            <label for="semestreSelect" class="form-label">Seleccionar Semestre</label>
                            <select class="form-select" id="semestreSelect" wire:model="semestre_id">
                                <option value="">Seleccione</option>
                                <!-- Se llenará con la BD -->
                                @foreach ($semestres as $semestre)
                                    <option value="{{ $semestre->id }}">{{ $semestre->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Campo de Nombre del Horario -->
                    <div class="mb-3">
                        <label for="nombreHorario" class="form-label">Nombre del Horario</label>
                        <input type="text" class="form-control" id="nombreHorario"
                            placeholder="Ingrese nombre del horario" wire:model="nombre">
                    </div>

                    <!-- Radio Button para Editar Nombre del Horario -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="editNombre" id="editarNombreRadio"
                                wire:model="editableNombre" value="1">
                            <label class="form-check-label" for="editarNombreRadio">
                                Editar Nombre
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="guardarHorario">Guardar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal añadircurso -->
    <div wire:ignore.self class="modal fade" id="modalAñadirCurso" tabindex="-1"
        aria-labelledby="modalAgregarHorario" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalAgregarHorario">Añadir Curso al Horario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
</div>
