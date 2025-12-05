<section class="container-fluid py-4">

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
    <div wire:ignore.self class="modal fade" id="modalCrearHorario" tabindex="-1" aria-labelledby="modalCrearHorario"
        aria-hidden="true">
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


                    <!-- Nombre -->
                    <div class="mt-3">
                        <label class="form-label">Nombre del Horario</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                            wire:model.live="nombre" @disabled(!$editableNombre)>
                        @error('nombre')
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
                            <select class="form-select" id="carreraSelect" wire:model.live="carrera_id"
                                @disabled(!$facultad_id)>
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
                            <select class="form-select" id="cursoSelect" wire:model.live="curso_id"
                                @disabled(!$carrera_id)>
                                <option value="" hidden>Seleccione</option>
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                @endforeach
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

    <!-- TOAST DE ÉXITO -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
        <div id="toastExito" class="toast align-items-center text-bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastExitoTexto">
                    <!-- Texto dinámico -->
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


        // Toast éxito
        Livewire.on('toast-exito', (mensaje) => {
            document.getElementById('toastExitoTexto').innerText = mensaje;
            const toast = new bootstrap.Toast(document.getElementById('toastExito'));
            toast.show();
        });

    });
</script>
