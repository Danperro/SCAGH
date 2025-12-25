<section class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="row g-3 align-items-center mb-4">

        <div class="col-12 col-md-8">
            <div
                class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start text-center text-md-start">
                <i class="bi bi-mortarboard fs-3 text-success"></i>
                <div>
                    <h3 class="fw-bold mb-0">LISTADO DE ESTUDIANTES</h3>
                    <small class="text-muted">Gestión de Estudiantes y asignacion de cursos</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-end gap-2">
                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                    data-bs-target="#modalCrearEstudiante" wire:click="limpiar">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Estudiante
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
            <!-- Buscar -->
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Buscar por:</label>
                <input class="form-control" type="text" wire:model.live.debounce.500ms="query"
                    placeholder="nombre, apellido, dni o codigo...">
            </div>
            <!-- Filtrar por carrera -->
            <div class="col-12 col-md-5 d-flex flex-column gap-2">
                <label class="form-label fw-semibold">Facultar y Carreras</label>
                <select class="form-select form-select-sm" wire:model.live="filtrofacultad_id">
                    <option value="" hidden>Todos las facultades</option>
                    @foreach ($facultades as $facultad)
                        <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                    @endforeach
                </select>
                <select class="form-select form-select-sm " wire:model.live="filtrocarrera_id"
                    wire:key="carrera-{{ $filtrofacultad_id }}" @disabled(!$filtrofacultad_id)>
                    <option value="" hidden>Todos las carreras</option>
                    @foreach ($carreras as $carrera)
                        <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Filtrar por estado -->
            <div class="col-12 col-md-2">
                <label class="form-label fw-semibold">Estado</label>
                <select class="form-select" wire:model.live="filtroestado">
                    <option value="" hidden>Todos los estados</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

        </div>
    </div>


    <!-- Tabla de Estudiantes -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th scope="col">Estudiante</th>
                            <th scope="col">Codigo</th>
                            <th scope="col">Carrera</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($estudiantes as $estudiante)
                            <tr>
                                <td>{{ $estudiante->persona->nombre . ' ' . $estudiante->persona->apellido_paterno . ' ' . $estudiante->persona->apellido_materno }}
                                </td>
                                <td>{{ $estudiante->codigo }}</td>
                                <td>{{ $estudiante->carrera->nombre }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $estudiante->estado ? 'bg-success' : 'bg-danger' }}">
                                        {{ $estudiante->estado_texto }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-info btn-sm" wire:click="selectInfo({{ $estudiante->id }})"
                                        data-bs-toggle="modal" data-bs-target="#modalAsignarCurso">
                                        <i class="bi bi-journal-bookmark"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm"
                                        wire:click="selectInfo({{ $estudiante->id }})" data-bs-toggle="modal"
                                        data-bs-target="#modalEditarEstudiante">
                                        <i class="bi bi-pencil-square"></i></button>

                                    <button class="btn btn-danger btn-sm"
                                        wire:click="selectInfo({{ $estudiante->id }})" data-bs-toggle="modal"
                                        data-bs-target="#modalEliminarEstudiante">
                                        <i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No hay estudiantes registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $estudiantes->links() }}
            </div>
        </div>
    </div>



    <!-- Modal Crear Estudiante -->
    <div wire:ignore.self class="modal fade" id="modalCrearEstudiante" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="CrearEstudiante" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>Registrar Estudiante
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <!-- FACULTAD -->
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="facultadSelect" class="form-label">Seleccionar Facultad</label>
                                <select class="form-select" id="facultadSelect" wire:model.live="facultad_id"
                                    @error('facultad_id') is-invalid @enderror>
                                    <option value="" hidden>Seleccione</option>
                                    @foreach ($facultades as $facultad)
                                        <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('facultad_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CARRERA -->
                        <div class="row g-3 mt-1">
                            <div class="col-md-12">
                                <label for="carreraSelect" class="form-label">Carrera<span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="carreraSelect" wire:model.live="carrera_id"
                                    @disabled(!$facultad_id) @error('carrera_id') is-invalid @enderror>
                                    <option value="" hidden>Seleccione</option>
                                    @foreach ($carreras as $carrera)
                                        <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('carrera_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CÓDIGO -->
                        <div class="col-md-4">
                            <label for="codigoEstudiante" class="form-label">Codigo de Estudiante<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('codigo') is-invalid @enderror"
                                wire:model.live="codigo">
                            @error('codigo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DNI -->
                        <div class="col-md-4">
                            <label for="dniEstudiante"class="form-label">DNI<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('dni') is-invalid @enderror"
                                wire:model.live="dni">
                            @error('dni')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NOMBRE -->
                        <div class="col-md-4">
                            <label for="nombreEstudiante" class="form-label">Nombre<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                wire:model.live="nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO PATERNO -->
                        <div class="col-md-4">
                            <label for="apellidoPaternoEstudiante" class="form-label">Apellido Paterno<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror"
                                wire:model.live="apellido_paterno">
                            @error('apellido_paterno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO MATERNO -->
                        <div class="col-md-4">
                            <label for="apellidoMaternoEstudiante" class="form-label">Apellido Materno<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror"
                                wire:model.live="apellido_materno">
                            @error('apellido_materno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- TELEFONO -->
                        <div class="col-md-4">
                            <label for="telefonoEstudiante" class="form-label">Teléfono<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                wire:model.live="telefono">
                            @error('telefono')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CORREO -->
                        <div class="col-md-4">
                            <label for="correoEstudiante" class="form-label">Correo<span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror"
                                wire:model.live="correo">
                            @error('correo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FECHA NACIMIENTO -->
                        <div class="col-md-4">
                            <label for="fechaNacimientoEstudiante" class="form-label">Fecha de nacimiento<span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                wire:model.live="fecha_nacimiento">
                            @error('fecha_nacimiento')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" wire:click="limpiar">Cerrar</button>

                    <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading.remove><i class="bi bi-check2-circle me-1"></i>Guardar</span>
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                    </button>
                </div>

            </form>

        </div>
    </div>


    <!-- Modal Editar Estudiante -->
    <div wire:ignore.self class="modal fade" id="modalEditarEstudiante" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="EditarEstudiante" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Editar Estudiante
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <!-- FACULTAD -->
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="facultadSelect" class="form-label">Seleccionar Facultad</label>
                                <select class="form-select" id="facultadSelect" wire:model.live="facultad_id"
                                    @error('facultad_id') is-invalid @enderror>
                                    <option value="" hidden>Seleccione</option>
                                    @foreach ($facultades as $facultad)
                                        <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('facultad_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CARRERA -->
                        <div class="row g-3 mt-1">
                            <div class="col-md-12">
                                <label for="carreraSelect" class="form-label">Carrera<span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="carreraSelect" wire:model.live="carrera_id"
                                    wire:key="carrera-{{ $facultad_id }}" @disabled(!$facultad_id)
                                    @error('carrera_id') is-invalid @enderror>
                                    <option value="" hidden>Seleccione</option>
                                    @foreach ($carreras as $carrera)
                                        <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('carrera_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CÓDIGO -->
                        <div class="col-md-4">
                            <label class="form-label">Codigo de Estudiante<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('codigo') is-invalid @enderror"
                                wire:model.live="codigo">
                            @error('codigo')
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
                        <div class="col-md-4">
                            <label class="form-label">Fecha de nacimiento<span class="text-danger">*</span></label>
                            <input type="date"
                                class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                wire:model.live="fecha_nacimiento">
                            @error('fecha_nacimiento')
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


    <!-- Modal Eliminar Estudiante -->
    <div wire:ignore.self class="modal fade" id="modalEliminarEstudiante" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-body text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-trash-fill text-danger fs-1"></i>
                    </div>

                    <h4 class="fw-bold">¿Estas seguro de eliminar al Estudiante?</h4>

                    <p class="text-muted">
                        Esta acción es permanente y no podrás recuperarlo.
                    </p>

                    <div class="d-flex gap-3 mt-4">
                        <button class="btn btn-light flex-fill" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-danger flex-fill" wire:click="EliminarEstudiante">Eliminar</button>
                    </div>

                </div>

            </div>
        </div>
    </div>


    <!-- Modal Asignar Curso Estudiante -->
    <div wire:ignore.self class="modal fade" id="modalAsignarCurso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <!-- Header-->
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
                                <label for="facultadSelect" class="form-label">Seleccionar Facultad</label>
                                <select class="form-select" id="facultadSelect" wire:model.live="facultad_id"
                                    @if ($facultad_id) disabled @endif>
                                    <option value="" hidden>Seleccione</option>
                                    @foreach ($facultades as $facultad)
                                        <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- =============== CARRERA =============== -->
                        <div class="row g-3 ">
                            <div class="col-md-12">
                                <label for="carreraSelect" class="form-label">Seleccionar Carrera</label>
                                <select class="form-select" id="carreraSelect" wire:model.live="carrera_id"
                                    wire:key="carrera-{{ $facultad_id }}"
                                    @if ($facultad_id) disabled @endif>
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
                                    wire:model.live="curso_id" wire:key="curso-{{ $carrera_id }}"
                                    @disabled(!$carrera_id)>
                                    <option value="" hidden>Seleccione</option>
                                    @foreach ($cursos as $curso)
                                        <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('curso_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- GRUPO -->
                            <div class="col-md-6">
                                <label class="form-label">Grupo<span class="text-danger">*</span></label>
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
                                @foreach ($estudiantesCursosDocentes as $estudiantesCursosDocentes)
                                    <tr>
                                        <td>{{ $estudiantesCursosDocentes->docenteCurso->curso->nombre }}</td>
                                        <td>{{ $estudiantesCursosDocentes->semestre->nombre }}</td>
                                        <td>{{ $estudiantesCursosDocentes->docenteCurso->grupo->nombre }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm"
                                                wire:click="selectAsignacionCurso({{ $estudiantesCursosDocentes->id }})"
                                                data-bs-toggle="modal" data-bs-target="#modalEliminarAsignacionCurso">
                                                <i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        </div>
    </div>


    <!-- Modal Eliminar Asignacion Curso -->
    <div wire:ignore.self class="modal fade" id="modalEliminarAsignacionCurso" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-body text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-trash-fill text-danger fs-1"></i>
                    </div>

                    <h4 class="fw-bold">¿Estas seguro de eliminar el curso del estudiante?</h4>

                    <p class="text-muted">
                        Esta acción es permanente y no podrás recuperarlo.
                    </p>

                    <div class="d-flex gap-3 mt-4">
                        <button class="btn btn-light flex-fill" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-danger flex-fill"
                            wire:click="EliminarAsignacionCurso">Eliminar</button>
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
