<section class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="row g-3 align-items-center mb-4">

        <div class="col-12 col-md-8">
            <div
                class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start text-center text-md-start">
                <i class="bi bi-mortarboard fs-3 text-success"></i>
                <div>
                    <h3 class="fw-bold mb-0">LISTADO DE DOCENTES</h3>
                    <small class="text-muted">Gestión de Docentres y asignacion de cursos</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-end gap-2">
                <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#modalCrearDocente"
                    wire:click="limpiar">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Docente
                </button>

                <button class="btn btn-outline-success" type="button" wire:click="limpiar">
                    <i class="bi bi-eraser me-1"></i> Limpiar Filtros
                </button>
            </div>
        </div>

    </div>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm p-4">

        <div class="row g-3 align-items-end">

            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Buscar</label>
                <input class="form-control" type="text" wire:model.live.debounce.500ms="query"
                    placeholder="Bucar docente...">
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Especialidad</label>
                <select class="form-select" wire:model.live="filtroespecialidad_id">
                    <option value="" hidden>Todos las especialidades</option>
                    @foreach ($especialidades as $especialidad)
                        <option value="{{ $especialidad->id }}">{{ $especialidad->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Estado</label>
                <select class="form-select" wire:model.live="filtroestado">
                    <option value="" hidden>Todos los estados</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

        </div>
    </div>

    <!-- Tabla -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th class="text-start ps-4">Docente</th>
                            <th scope="col">Especialidad</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($docentes as $docente)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark">
                                        {{ $docente->persona->nombre . ' ' . $docente->persona->apellido_paterno . ' ' . $docente->persona->apellido_materno }}
                                    </div>
                                </td>
                                <td class="text-center">{{ $docente->especialidad->nombre }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $docente->estado ? 'bg-success' : 'bg-danger' }}">
                                        {{ $docente->estado_texto }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-info btn-sm" wire:click="selectInfo({{ $docente->id }})"
                                        data-bs-toggle="modal" data-bs-target="#modalAsignarCurso">
                                        <i class="bi bi-journal-bookmark"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm" wire:click="selectInfo({{ $docente->id }})"
                                        data-bs-toggle="modal" data-bs-target="#modalEditarDocente">
                                        <i class="bi bi-pencil-square"></i></button>

                                    <button class="btn btn-danger btn-sm" wire:click="selectInfo({{ $docente->id }})"
                                        data-bs-toggle="modal" data-bs-target="#modalEliminarDocente">
                                        <i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No hay Docentes registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $docentes->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Crear Docente -->
    <div wire:ignore.self class="modal fade" id="modalCrearDocente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="CrearDocente" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>Registrar Docente
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <!-- NOMBRE -->
                        <div class="col-md-4">
                            <label class="form-label">Nombre<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                wire:model.live="nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO PATERNO -->
                        <div class="col-md-4">
                            <label class="form-label">Apellido Paterno<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror"
                                wire:model.live="apellido_paterno">
                            @error('apellido_paterno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO MATERNO -->
                        <div class="col-md-4">
                            <label class="form-label">Apellido Materno<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror"
                                wire:model.live="apellido_materno">
                            @error('apellido_materno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DNI -->
                        <div class="col-md-4">
                            <label class="form-label">DNI<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('dni') is-invalid @enderror"
                                wire:model.live="dni">
                            @error('dni')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- TELEFONO -->
                        <div class="col-md-4">
                            <label class="form-label">Teléfono<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                wire:model.live="telefono">
                            @error('telefono')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CORREO -->
                        <div class="col-md-4">
                            <label class="form-label">Correo<span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror"
                                wire:model.live="correo">
                            @error('correo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FECHA NACIMIENTO -->
                        <div class="col-md-6">
                            <label class="form-label">Fecha de nacimiento<span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                wire:model.live="fecha_nacimiento">
                            @error('fecha_nacimiento')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ESPECIALIDAD -->
                        <div class="col-md-6 ">
                            <label class="form-label">Especialidad<span class="text-danger">*</span></label>
                            <select class="form-select  @error('especialidad_id') is-invalid @enderror"
                                wire:model.live="especialidad_id">
                                <option hidden value="">Seleccione</option>
                                @foreach ($especialidades as $e)
                                    <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                                @endforeach
                            </select>
                            @error('especialidad_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- USERNAME -->
                        <div class="col-md-4">
                            <label class="form-label">Usuario<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                wire:model.live="username">
                            @error('username')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- PASSWORD -->
                        <div class="col-md-4">
                            <label class="form-label">Contraseña<span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                wire:model.live="password">
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CONFIRMAR PASSWORD -->
                        <div class="col-md-4">
                            <label class="form-label">Confirmar Contraseña<span class="text-danger">*</span></label>
                            <input type="password"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                wire:model.live="password_confirmation">
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- CORREO DE RECUPERACION -->
                        <div class="col-md-4">
                            <label id="emailLabel" class="form-label">Correo de Recuperacion<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('email') is-invalid @enderror"
                                wire:model.live="email">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" wire:click="limpiar">Cerrar</button>

                    <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading.remove><i class="bi bi-check2-circle me-1"></i> Guardar</span>
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- Modal Editar Docente -->
    <div wire:ignore.self class="modal fade" id="modalEditarDocente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="EditarDocente" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Editar Docente
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <!-- NOMBRE -->
                        <div class="col-md-4">
                            <label class="form-label">Nombre<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                wire:model.live="nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO PATERNO -->
                        <div class="col-md-4">
                            <label class="form-label">Apellido Paterno<span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control @error('apellido_paterno') is-invalid @enderror"
                                wire:model.live="apellido_paterno">
                            @error('apellido_paterno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO MATERNO -->
                        <div class="col-md-4">
                            <label class="form-label">Apellido Materno<span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control @error('apellido_materno') is-invalid @enderror"
                                wire:model.live="apellido_materno">
                            @error('apellido_materno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DNI -->
                        <div class="col-md-4">
                            <label class="form-label">DNI<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('dni') is-invalid @enderror"
                                wire:model.live="dni">
                            @error('dni')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- TELEFONO -->
                        <div class="col-md-4">
                            <label class="form-label">Teléfono<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                wire:model.live="telefono">
                            @error('telefono')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CORREO -->
                        <div class="col-md-4">
                            <label class="form-label">Correo<span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror"
                                wire:model.live="correo">
                            @error('correo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FECHA NACIMIENTO -->
                        <div class="col-md-6">
                            <label class="form-label">Fecha de nacimiento<span class="text-danger">*</span></label>
                            <input type="date"
                                class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                wire:model.live="fecha_nacimiento">
                            @error('fecha_nacimiento')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ESPECIALIDAD -->
                        <div class="col-md-6">
                            <label class="form-label">Especialidad<span class="text-danger">*</span></label>
                            <select class="form-select @error('especialidad_id') is-invalid @enderror"
                                wire:model.live="especialidad_id">
                                <option hidden value="">Seleccione</option>
                                @foreach ($especialidades as $e)
                                    <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                                @endforeach
                            </select>
                            @error('especialidad_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" wire:click="limpiar">Cerrar</button>

                    <button type="submit" class="btn btn-warning" wire:loading.attr="disabled">
                        <span wire:loading.remove> <i class="bi bi-save2 me-1"></i> Guardar cambios</span>
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- MODAL DE ELIMINAR DOCENTE -->
    <div wire:ignore.self class="modal fade" id="modalEliminarDocente" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-body text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-trash-fill text-danger fs-1"></i>
                    </div>

                    <h4 class="fw-bold">¿Estas seguro de eliminar al Docente?</h4>

                    <p class="text-muted">
                        Esta acción es permanente y no podrás recuperarlo.
                    </p>

                    <div class="d-flex gap-3 mt-4">
                        <button class="btn btn-light flex-fill" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-danger flex-fill" wire:click="EliminarDocente">Eliminar</button>
                    </div>

                </div>

            </div>
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


    <!-- MODAL ASIGNNAR CURSO -->
    <div wire:ignore.self class="modal fade" id="modalAsignarCurso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        Asignar cursos –
                        <span class="text-primary">
                            {{ $nombre . ' ' . $apellido_paterno . ' ' . $apellido_materno }}
                        </span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="GuardarAsignacionCurso">
                        <!-- =============== FACULTAD =============== -->
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="facultadSelect" class="form-label">Facultad</label>
                                <select class="form-select" id="facultadSelect" wire:model.live="facultad_id">
                                    <option value="" hidden>Seleccione</option>
                                    @foreach ($facultades as $facultad)
                                        <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- =============== CARRERA =============== -->
                        <div class="row g-3 mt-1">
                            <div class="col-md-12">
                                <label for="carreraSelect" class="form-label">Carrera<span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="carreraSelect" wire:model.live="carrera_id"
                                    @disabled(!$facultad_id)>
                                    <option value="" hidden>Seleccione</option>
                                    @foreach ($carreras as $carrera)
                                        <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-3">
                            <!-- =============== CURSOS =============== -->
                            <div class="col-md-6">
                                <label class="form-label">Curso<span class="text-danger">*</span></label>
                                <select class="form-select @error('curso_id') is-invalid @enderror"
                                    wire:model.live="curso_id" @disabled(!$carrera_id)>
                                    <option value="" hidden>Seleccione</option>
                                    @foreach ($cursos as $curso)
                                        <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('curso_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- =============== SEMESTRES =============== -->
                            <div class="col-md-6">
                                <label class="form-label">Semestre<span class="text-danger">*</span></label>
                                <select class="form-select @error('semestre_id') is-invalid @enderror"
                                    wire:model="semestre_id">
                                    <option value="" hidden>Seleccione</option>
                                    @foreach ($semestres as $sem)
                                        <option value="{{ $sem->id }}">{{ $sem->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('semestre_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- =============== GRUPOS =============== -->
                            <div class="col-12">
                                <label class="form-label">Grupos<span class="text-danger">*</span></label>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach ($grupos as $grupo)
                                        @php
                                            $yaAsignado = in_array($grupo->id, $gruposAsignadosCursoActual ?? []);
                                        @endphp
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                id="grupo{{ $grupo->id }}" value="{{ $grupo->id }}"
                                                wire:model.live="gruposSeleccionados"
                                                @if ($yaAsignado) disabled checked @endif>
                                            <label class="form-check-label" for="grupo{{ $grupo->id }}">
                                                {{ $grupo->nombre }}
                                                @if ($yaAsignado)
                                                    <span class="badge bg-success ms-1">Asignado</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('gruposSeleccionados')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                wire:click="limpiar">Cancelar</button>
                            <button type="submit" class="btn btn-success">Guardar asignación</button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-2">Cursos asignados</h6>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Curso</th>
                                    <th>Semestre</th>
                                    <th>Grupo</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($asignacionesDocente as $asig)
                                    <tr>
                                        <td>{{ $asig->curso->nombre }}</td>
                                        <td>{{ $asig->semestre->nombre }}</td>
                                        <td>{{ $asig->grupo->nombre }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-outline-danger btn-sm"
                                                wire:click="eliminarAsignacion({{ $asig->id }})">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No hay cursos asignados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

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
