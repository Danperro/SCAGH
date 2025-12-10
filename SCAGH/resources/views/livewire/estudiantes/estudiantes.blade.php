<section class="container-fluid py-4">
    <!-- Título -->
    <h2 class="fw-bold mb-1">Estudiantes</h2>
    <p class="text-muted mb-4">Gestión de estudiantes</p>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm p-4">
        <div class="row g-3 align-items-end">
            <!-- Buscar -->
            <div class="col-12 col-md-7">
                <label class="form-label fw-semibold">Buscar por nombre, apellido, dni o codigo</label>
                <input class="form-control" type="text" wire:model.live.debounce.500ms="query"
                    placeholder="Bucar estudiante...">
            </div>
            <!-- Filtrar por estado -->
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Estado</label>
                <select class="form-select" wire:model.live="filtroestado">
                    <option value="" hidden>Todos los estados</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
            <!-- Botón Crear Estudiante -->
            <div class="col-12 col-md-2 d-flex flex-column gap-2">
                <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#modalCrearEstudiante"
                    wire:click="limpiar">+ Crear Estudiante</button>

                <button class="btn btn-outline-success w-100" wire:click="limpiar">
                    Limpiar filtros</button>
            </div>
        </div>
    </div>

    <!-- Tabla de Estudiantes -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-center">
                        <th scope="col">Dni</th>
                        <th scope="col">Estudiante</th>
                        <th scope="col">codigo</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estudiantes as $estudiante)
                        <tr>
                            <td>{{ $estudiante->persona->dni }}</td>
                            <td>{{ $estudiante->persona->nombre . ' ' . $estudiante->persona->apellido_paterno . ' ' . $estudiante->persona->apellido_materno }}
                            </td>
                            <td>{{ $estudiante->codigo }}</td>
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
                                    data-bs-toggle="modal" data-bs-target="#modalEditarEstudiante">
                                    <i class="bi bi-pencil-square"></i></button>

                                <button class="btn btn-danger btn-sm" wire:click="selectInfo({{ $docente->id }})"
                                    data-bs-toggle="modal" data-bs-target="#modalEliminarEstudiante">
                                    <i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Crear Estudiante -->
    <div wire:ignore.self class="modal fade" id="modalCrearEstudiante" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="CrearEstudiante" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Crear Estudiante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <!-- =============== FACULTAD =============== -->
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="facultadSelect" class="form-label">Seleccionar Facultad</label>
                                <select class="form-select" id="facultadSelect" wire:model.live="facultad_id">
                                    <option value="" hidden>Seleccione</option>
                                   
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
                                   
                                </select>
                            </div>
                        </div>

                        <!-- CÓDIGO -->
                        <div class="col-md-4">
                            <label class="form-label">Codigo de Estudiante</label>
                            <input type="text" class="form-control @error('codigo') is-invalid @enderror"
                                wire:model.live="codigo">
                            @error('codigo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DNI -->
                        <div class="col-md-4">
                            <label class="form-label">DNI</label>
                            <input type="text" class="form-control @error('dni') is-invalid @enderror"
                                wire:model.live="dni">
                            @error('dni')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NOMBRE -->
                        <div class="col-md-4">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                wire:model.live="nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO PATERNO -->
                        <div class="col-md-4">
                            <label class="form-label">Apellido Paterno</label>
                            <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror"
                                wire:model.live="apellido_paterno">
                            @error('apellido_paterno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO MATERNO -->
                        <div class="col-md-4">
                            <label class="form-label">Apellido Materno</label>
                            <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror"
                                wire:model.live="apellido_materno">
                            @error('apellido_materno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- TELEFONO -->
                        <div class="col-md-4">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                wire:model.live="telefono">
                            @error('telefono')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CORREO -->
                        <div class="col-md-4">
                            <label class="form-label">Correo</label>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror"
                                wire:model.live="correo">
                            @error('correo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FECHA NACIMIENTO -->
                        <div class="col-md-4">
                            <label class="form-label">Fecha de nacimiento</label>
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
                        <span wire:loading.remove>Guardar</span>
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                    </button>
                </div>

            </form>

        </div>
    </div>

</section>
