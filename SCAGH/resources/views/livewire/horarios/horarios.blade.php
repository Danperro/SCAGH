<div class="container-fluid py-4">

    <!-- Título -->
    <h2 class="fw-bold mb-1">Horarios</h2>
    <p class="text-muted mb-4">Gestión de horarios por laboratorio</p>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm p-4">
        <div class="row g-2 align-items-end">
            <!-- Semestre -->
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Semestre</label>
                <select class="form-select">
                    <option value="" hidden>Seleccione</option>
                    @foreach ($semestres as $semestre)
                        <option value="{{ $semestre->id }}">{{ $semestre->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Laboratorio -->
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Laboratorio</label>
                <select class="form-select" id="laboratorio_id" wire:model="laboratorio_id">
                    <option value="" hidden>Todos los laboratorios</option>
                    @foreach ($laboratorios as $lab)
                        <option value="{{ $lab->id }}">{{ $lab->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Botón Crear Horario -->
            <div class="col-12 col-md-3">
                <button class="btn btn-success w-100 px-4" data-bs-target="#modalCrearHorario" data-bs-toggle="modal"
                    wire:click="">
                    + Crear un Nuevo Horario
                </button>
            </div>

            <!-- Botón Añadir Curso -->
            <div class="col-12 col-md-3">
                <button class="btn btn-success w-100 px-4" data-bs-target="#modalAñadirCurso" data-bs-toggle="modal"
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
                    <h5 class="modal-title fw-bold" id="modalCrearHorarioLabel">Crear Horario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para agregar un curso al horario -->

                    <!-- Fila para Laboratorio y Semestre -->
                    <div class="row g-3">
                        <!-- Combo de Laboratorio -->
                        <div class="col-md-6">
                            <label for="laboratorioSelect" class="form-label">Seleccionar Laboratorio</label>
                            <select class="form-select" id="laboratorioSelect" wire:model.live="laboratorio_id">
                                <option value="" hidden>Seleccione</option>
                                <!-- Se llenará con la BD -->
                                @foreach ($laboratorios as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Combo de Semestre -->
                        <div class="col-md-6">
                            <label for="semestreSelect" class="form-label">Seleccionar Semestre</label>
                            <select class="form-select" id="semestreSelect" wire:model.live="semestre_id">
                                <option value="" hidden>Seleccione</option>
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
                            placeholder="Ingrese nombre del horario" wire:model.live="nombre"
                            @disabled(!$editableNombre)>
                    </div>

                    <!-- Radio Button para Editar Nombre del Horario -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="editNombre" id="editarNombreRadio"
                                wire:model.live="editableNombre" value="1">
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


    <!-- Modal Añadir Curso -->
    <div wire:ignore.self class="modal fade" id="modalAñadirCurso" tabindex="-1"
        aria-labelledby="modalAñadirCursoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalAñadirCursoLabel">Añadir Curso al Horario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <!-- =============== FACULTAD =============== -->
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="facultadSelect" class="form-label">Seleccionar Facultad</label>
                            <select class="form-select" id="facultadSelect" wire:model.live="facultad_id">
                                <option value="" hidden>Seleccione</option>
                                @foreach ($facultades as $facultad)
                                    <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- =============== CARRERA (debajo) =============== -->
                    <div class="row g-3 mt-1">
                        <div class="col-md-12">
                            <label for="carreraSelect" class="form-label">Seleccionar Carrera</label>
                            <select class="form-select" id="carreraSelect" wire:model.live="carrera_id">
                                <option value="" hidden>Seleccione</option>
                                @foreach ($carreras as $carrera)
                                    <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- =============== CURSO + HORARIO =============== -->
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label for="cursoSelect" class="form-label">Seleccionar Curso</label>
                            <select class="form-select" id="cursoSelect" wire:model.live="curso_id">
                                <option value="" hidden>Seleccione</option>
                                
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="horarioSelect" class="form-label">Seleccionar Horario</label>
                            <select class="form-select" id="horarioSelect" wire:model.live="horario_id">
                                <option value="" hidden>Seleccione</option>
                                @foreach ($horarios as $horario)
                                    <option value="{{ $horario->id }}">{{ $horario->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- =============== DÍA + HORAS =============== -->
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label for="diaSelect" class="form-label">Seleccionar Día de la Semana</label>
                            <select class="form-select" id="diaSelect" wire:model.live="dia_id">
                                <option value="" hidden>Seleccione</option>
                                @foreach ($dias as $dia)
                                    <option value="{{ $dia->id }}">{{ $dia->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="inicioHorario" class="form-label">Hora de Inicio</label>
                            <input type="time" class="form-control" id="inicioHorario"
                                wire:model.live="inicio_horario">
                        </div>

                        <div class="col-md-3">
                            <label for="finHorario" class="form-label">Hora de Fin</label>
                            <input type="time" class="form-control" id="finHorario"
                                wire:model.live="fin_horario">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="guardarCurso">Guardar</button>
                </div>
            </div>
        </div>
    </div>


</div>
