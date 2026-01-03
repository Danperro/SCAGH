<section class="container-fluid py-4">

    <!-- Estilos adicionales -->
    <style>
        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.75rem;
            flex-shrink: 0;
        }
    </style>

    {{-- HEADER --}}
    <div class="row g-3 align-items-center mb-4">

        <div class="col-12 col-md-8">
            <div
                class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start text-center text-md-start">
                <i class="bi bi-person-workspace fs-3 text-success"></i>
                <div>
                    <h3 class="fw-bold mb-0">LISTADO DE DOCENTES</h3>
                    <small class="text-muted">Gestión de docentes y asignación de cursos</small>
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
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <!-- Header de Filtros -->
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-funnel fs-4 text-primary me-2"></i>
                <h6 class="fw-bold mb-0">Filtros de búsqueda</h6>
            </div>

            <div class="row g-3">

                <!-- BUSCAR -->
                <div class="col-12 col-lg-6">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-search me-1"></i>Buscar por
                    </label>
                    <input class="form-control" type="text" wire:model.live.debounce.500ms="query"
                        placeholder="Nombre, apellido o DNI...">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>La búsqueda se actualiza automáticamente
                    </small>
                </div>

                <!-- FILTRAR POR ESPECIALIDAD -->
                <div class="col-12 col-lg-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-award me-1"></i>Especialidad
                    </label>
                    <select class="form-select" wire:model.live="filtroespecialidad_id">
                        <option value="" hidden>Todas las especialidades</option>
                        @foreach ($especialidades as $especialidad)
                            <option value="{{ $especialidad->id }}">{{ $especialidad->nombre }}</option>
                        @endforeach
                    </select>

                    <!-- Indicador de filtros activos -->
                    @if ($query || $filtroespecialidad_id || $filtroestado !== '')
                        <small class="text-success d-block mt-2">
                            <i class="bi bi-check-circle-fill me-1"></i>Filtros activos
                        </small>
                    @endif
                </div>

                <!-- FILTRAR POR ESTADO -->
                <div class="col-12 col-lg-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-toggle-on me-1"></i>Estado
                    </label>
                    <select class="form-select" wire:model.live="filtroestado">
                        <option value="" hidden>Todos los estados</option>
                        <option value="1">
                            <i class="bi bi-check-circle"></i> Activo
                        </option>
                        <option value="0">
                            <i class="bi bi-x-circle"></i> Inactivo
                        </option>
                    </select>
                </div>

            </div>

            <!-- Resumen de filtros activos -->
            @if ($query || $filtroespecialidad_id || $filtroestado !== '')
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex flex-wrap gap-2">
                            @if ($query)
                                <span class="badge bg-primary">
                                    <i class="bi bi-search me-1"></i>Búsqueda: "{{ $query }}"
                                </span>
                            @endif
                            @if ($filtroespecialidad_id)
                                <span class="badge bg-info">
                                    <i class="bi bi-award me-1"></i>Especialidad filtrada
                                </span>
                            @endif
                            @if ($filtroestado !== '')
                                <span class="badge bg-success">
                                    <i class="bi bi-toggle-on me-1"></i>Estado:
                                    {{ $filtroestado == '1' ? 'Activo' : 'Inactivo' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Tabla de Docentes -->
    <div class="card shadow-sm">

        <!-- Header de la tabla -->
        <div class="card-header bg-white border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-table fs-4 text-success me-2"></i>
                    <h6 class="fw-bold mb-0">Lista de Docentes</h6>
                </div>
                <span class="badge bg-primary">
                    Total: {{ $docentes->total() }} docente(s)
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-3">
                                <i class="bi bi-person-workspace me-1"></i>Docente
                            </th>
                            <th scope="col" class="text-center">
                                <i class="bi bi-person-vcard me-1"></i>DNI
                            </th>
                            <th scope="col">
                                <i class="bi bi-award me-1"></i>Especialidad
                            </th>
                            <th scope="col" class="text-center">
                                <i class="bi bi-toggle-on me-1"></i>Estado
                            </th>
                            <th scope="col" class="text-center">
                                <i class="bi bi-gear me-1"></i>Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($docentes as $docente)
                            <tr>
                                <!-- DOCENTE -->
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-success bg-opacity-10 text-success me-2">
                                            {{ substr($docente->persona->nombre, 0, 1) }}{{ substr($docente->persona->apellido_paterno, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $docente->persona->nombre . ' ' . $docente->persona->apellido_paterno }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $docente->persona->apellido_materno }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <!-- DNI -->
                                <td class="text-center">
                                    <span class="font-monospace">{{ $docente->persona->dni }}</span>
                                </td>

                                <!-- ESPECIALIDAD -->
                                <td>
                                    <small class="text-muted d-block"
                                        style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $docente->especialidad->nombre }}
                                    </small>
                                </td>

                                <!-- ESTADO -->
                                <td class="text-center">
                                    <span class="badge {{ $docente->estado ? 'bg-success' : 'bg-danger' }}"
                                        style="cursor: pointer;" wire:click="CambiarEstadoDocente({{ $docente->id }})"
                                        title="Click para cambiar estado">
                                        <i
                                            class="bi {{ $docente->estado ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                        {{ $docente->estado_texto }}
                                    </span>
                                </td>

                                <!-- ACCIONES -->
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-info"
                                            wire:click="selectInfo({{ $docente->id }})" data-bs-toggle="modal"
                                            data-bs-target="#modalAsignarCurso" title="Asignar cursos">
                                            <i class="bi bi-journal-bookmark"></i>
                                        </button>
                                        <button class="btn btn-outline-warning"
                                            wire:click="selectInfo({{ $docente->id }})" data-bs-toggle="modal"
                                            data-bs-target="#modalEditarDocente" title="Editar docente">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-outline-danger"
                                            wire:click="selectInfo({{ $docente->id }})" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminarDocente" title="Eliminar docente">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                        <h6 class="fw-semibold">No hay docentes registrados</h6>
                                        <p class="mb-0 small">Comienza agregando un nuevo docente</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if ($docentes->hasPages())
                <div class="card-footer bg-white border-top">
                    {{ $docentes->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Crear Docente -->
    <div wire:ignore.self class="modal fade" id="modalCrearDocente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="CrearDocente" class="modal-content">

                <!-- HEADER -->
                <div class="modal-header bg-success bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>Registrar Docente
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <!-- Bloque informativo -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-workspace fs-3 text-success me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Datos del docente</h6>
                                <small class="text-muted">Completa la información personal y profesional</small>
                            </div>
                        </div>
                    </div>

                    <!-- DATOS PERSONALES -->
                    <div class="row g-3">

                        <!-- NOMBRE -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person me-1"></i>Nombre <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                wire:model.live="nombre" placeholder="Ingrese el nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO PATERNO -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-badge me-1"></i>Apellido Paterno <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror"
                                wire:model.live="apellido_paterno" placeholder="Ingrese apellido paterno">
                            @error('apellido_paterno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO MATERNO -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-badge-fill me-1"></i>Apellido Materno <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror"
                                wire:model.live="apellido_materno" placeholder="Ingrese apellido materno">
                            @error('apellido_materno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DNI -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-vcard me-1"></i>DNI <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('dni') is-invalid @enderror"
                                wire:model.live="dni" maxlength="8" inputmode="numeric" placeholder="8 dígitos">
                            @error('dni')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- TELEFONO -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-telephone me-1"></i>Teléfono <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                wire:model.live="telefono" placeholder="9XXXXXXXX">
                            @error('telefono')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CORREO -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-envelope me-1"></i>Correo <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror"
                                wire:model.live="correo" placeholder="correo@ejemplo.com">
                            @error('correo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FECHA NACIMIENTO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-event me-1"></i>Fecha de nacimiento <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                wire:model.live="fecha_nacimiento">
                            @error('fecha_nacimiento')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ESPECIALIDAD -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-award me-1"></i>Especialidad <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('especialidad_id') is-invalid @enderror"
                                wire:model.live="especialidad_id">
                                <option value="" hidden>Seleccione una especialidad</option>
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

    <!-- Modal Editar Docente -->
    <div wire:ignore.self class="modal fade" id="modalEditarDocente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="EditarDocente" class="modal-content">

                <!-- HEADER -->
                <div class="modal-header bg-warning bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Editar Docente
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <!-- Bloque informativo -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-pencil-square fs-3 text-warning me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Modificar información del docente</h6>
                                <small class="text-muted">Actualiza los datos personales y profesionales</small>
                            </div>
                        </div>
                    </div>

                    <!-- DATOS PERSONALES -->
                    <div class="row g-3">

                        <!-- NOMBRE -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person me-1"></i>Nombre <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                wire:model.live="nombre" placeholder="Ingrese el nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO PATERNO -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-badge me-1"></i>Apellido Paterno <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror"
                                wire:model.live="apellido_paterno" placeholder="Ingrese apellido paterno">
                            @error('apellido_paterno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO MATERNO -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-badge-fill me-1"></i>Apellido Materno <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control @error('apellido_materno') is-invalid @enderror"
                                wire:model.live="apellido_materno" placeholder="Ingrese apellido materno">
                            @error('apellido_materno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DNI -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-vcard me-1"></i>DNI <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('dni') is-invalid @enderror"
                                wire:model.live="dni" maxlength="8" placeholder="8 dígitos">
                            @error('dni')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- TELEFONO -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-telephone me-1"></i>Teléfono <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                wire:model.live="telefono" placeholder="9XXXXXXXX">
                            @error('telefono')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CORREO -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-envelope me-1"></i>Correo <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror"
                                wire:model.live="correo" placeholder="correo@ejemplo.com">
                            @error('correo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FECHA NACIMIENTO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-event me-1"></i>Fecha de nacimiento <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="date"
                                class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                wire:model.live="fecha_nacimiento">
                            @error('fecha_nacimiento')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ESPECIALIDAD -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-award me-1"></i>Especialidad <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('especialidad_id') is-invalid @enderror"
                                wire:model.live="especialidad_id">
                                <option value="" hidden>Seleccione una especialidad</option>
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

    <!-- MODAL ASIGNAR CURSO -->
    <div wire:ignore.self class="modal fade" id="modalAsignarCurso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header bg-info bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-journal-bookmark me-2 text-info"></i>Asignar cursos
                        <span class="text-primary d-block mt-1 fs-6 fw-normal">
                            {{ $nombre . ' ' . $apellido_paterno . ' ' . $apellido_materno }}
                        </span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <!-- Bloque informativo -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle fs-3 text-info me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Nueva asignación</h6>
                                <small class="text-muted">Selecciona el curso y los grupos que el docente
                                    impartirá</small>
                            </div>
                        </div>
                    </div>

                    <form wire:submit.prevent="GuardarAsignacionCurso">
                        <div class="row g-3">

                            <!-- FACULTAD -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-building me-1"></i>Facultad
                                </label>
                                <select class="form-select" wire:model.live="facultad_id">
                                    <option value="" hidden>Seleccione una facultad</option>
                                    @foreach ($facultades as $facultad)
                                        <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- CARRERA -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-book me-1"></i>Carrera <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" wire:model.live="carrera_id"
                                    wire:key="carrera-select-{{ $facultad_id ?? 'x' }}" @disabled(!$facultad_id)>
                                    <option value="" hidden>Seleccione una carrera</option>
                                    @foreach ($carreras as $carrera)
                                        <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- CURSO -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-mortarboard me-1"></i>Curso <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('curso_id') is-invalid @enderror"
                                    wire:model.live="curso_id" wire:key="curso-select-{{ $carrera_id ?? 'x' }}"
                                    @disabled(!$carrera_id)>
                                    <option value="" hidden>Seleccione un curso</option>
                                    @foreach ($cursos as $curso)
                                        <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('curso_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- SEMESTRE -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar3 me-1"></i>Semestre <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('semestre_id') is-invalid @enderror"
                                    wire:model="semestre_id" wire:key="semestre-select-{{ $curso_id ?? 'x' }}"
                                    disabled>
                                    <option value="" hidden>Seleccione un semestre</option>
                                    @foreach ($semestres as $sem)
                                        <option value="{{ $sem->id }}">{{ $sem->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('semestre_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- GRUPOS -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-people me-1"></i>Grupos <span class="text-danger">*</span>
                                </label>
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            @forelse($grupos as $grupo)
                                                @php
                                                    $yaAsignado = in_array(
                                                        $grupo->id,
                                                        $gruposAsignadosCursoActual ?? [],
                                                    );
                                                @endphp
                                                <div class="col-md-4 col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="grupo{{ $grupo->id }}"
                                                            value="{{ $grupo->id }}"
                                                            wire:model.live="gruposSeleccionados"
                                                            @if ($yaAsignado) disabled checked @endif>
                                                        <label class="form-check-label"
                                                            for="grupo{{ $grupo->id }}">
                                                            {{ $grupo->nombre }}
                                                            @if ($yaAsignado)
                                                                <span class="badge bg-success ms-1">
                                                                    <i class="bi bi-check-circle me-1"></i>Asignado
                                                                </span>
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12 text-center text-muted py-3">
                                                    <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                                    No hay grupos disponibles
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                @error('gruposSeleccionados')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                wire:click="limpiar">
                                <i class="bi bi-x-circle me-1"></i>Cancelar
                            </button>
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="bi bi-check2-circle me-1"></i>Guardar asignación
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Guardando...
                                </span>
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <!-- CURSOS ASIGNADOS -->
                    <div class="card border-0 bg-light">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-list-check fs-4 text-success me-2"></i>
                                <h6 class="fw-bold mb-0">Cursos asignados actualmente</h6>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3">
                                                <i class="bi bi-mortarboard me-1"></i>Curso
                                            </th>
                                            <th class="text-center">
                                                <i class="bi bi-calendar3 me-1"></i>Semestre
                                            </th>
                                            <th class="text-center">
                                                <i class="bi bi-people me-1"></i>Grupo
                                            </th>
                                            <th class="text-center">
                                                <i class="bi bi-gear me-1"></i>Acciones
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($asignacionesDocente as $asig)
                                            <tr>
                                                <td class="ps-3">
                                                    <div class="fw-semibold">{{ $asig->curso->nombre }}</div>
                                                    <small
                                                        class="text-muted">{{ $asig->curso->carrera->nombre ?? 'N/A' }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                        {{ $asig->semestre->nombre }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info bg-opacity-10 text-info">
                                                        {{ $asig->grupo->nombre }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-outline-danger btn-sm"
                                                        wire:click="eliminarAsignacion({{ $asig->id }})"
                                                        title="Eliminar asignación">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
                                                        <h6 class="fw-semibold">No hay cursos asignados</h6>
                                                        <p class="mb-0 small">Asigna el primer curso al docente</p>
                                                    </div>
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
