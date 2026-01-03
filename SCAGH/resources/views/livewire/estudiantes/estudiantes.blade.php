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
                <i class="bi bi-people fs-3 text-success"></i>
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
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <!-- Header de Filtros -->
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-funnel fs-4 text-primary me-2"></i>
                <h6 class="fw-bold mb-0">Filtros de búsqueda</h6>
            </div>

            <div class="row g-3">

                <!-- BUSCAR -->
                <div class="col-12 col-lg-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-search me-1"></i>Buscar por
                    </label>
                    <input class="form-control" type="text" wire:model.live.debounce.500ms="query"
                        placeholder="Nombre, apellido, DNI o código...">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>La búsqueda se actualiza automáticamente
                    </small>
                </div>

                <!-- FILTRAR POR ESTADO -->
                <div class="col-12 col-lg-2">
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

                    <!-- Indicador de filtros activos -->
                    @if ($filtroestado !== '' || $filtrofacultad_id || $filtrocarrera_id || $query)
                        <small class="text-success d-block mt-2">
                            <i class="bi bi-check-circle-fill me-1"></i>Filtros activos
                        </small>
                    @endif
                </div>

                <!-- FILTRAR POR FACULTAD Y CARRERA -->
                <div class="col-12 col-lg-6">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-building me-1"></i>Facultad y Carrera
                    </label>

                    <!-- Facultad -->
                    <select class="form-select mb-2" wire:model.live="filtrofacultad_id">
                        <option value="" hidden>Todas las facultades</option>
                        @foreach ($facultades as $facultad)
                            <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                        @endforeach
                    </select>

                    <!-- Carrera -->
                    <select class="form-select" wire:model.live="filtrocarrera_id"
                        wire:key="carrera-{{ $filtrofacultad_id }}" @disabled(!$filtrofacultad_id)>
                        <option value="" hidden>Todas las carreras</option>
                        @foreach ($carreras as $carrera)
                            <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                        @endforeach
                    </select>
                </div>



            </div>

            <!-- Resumen de filtros activos (opcional) -->
            @if ($query || $filtrofacultad_id || $filtrocarrera_id || $filtroestado !== '')
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex flex-wrap gap-2">
                            @if ($query)
                                <span class="badge bg-primary">
                                    <i class="bi bi-search me-1"></i>Búsqueda: "{{ $query }}"
                                </span>
                            @endif
                            @if ($filtrofacultad_id)
                                <span class="badge bg-info">
                                    <i class="bi bi-building me-1"></i>Facultad filtrada
                                </span>
                            @endif
                            @if ($filtrocarrera_id)
                                <span class="badge bg-info">
                                    <i class="bi bi-book me-1"></i>Carrera filtrada
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

    <!-- Tabla de Estudiantes -->
    <div class="card shadow-sm">

        <!-- Header de la tabla -->
        <div class="card-header bg-white border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-table fs-4 text-success me-2"></i>
                    <h6 class="fw-bold mb-0">Lista de Estudiantes</h6>
                </div>
                <span class="badge bg-primary">
                    Total: {{ $estudiantes->total() }} estudiante(s)
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-3">
                                <i class="bi bi-person me-1"></i>Estudiante
                            </th>
                            <th scope="col" class="text-center">
                                <i class="bi bi-hash me-1"></i>Código
                            </th>
                            <th scope="col" class="text-center">
                                <i class="bi bi-person-vcard me-1"></i>DNI
                            </th>
                            <th scope="col">
                                <i class="bi bi-book me-1"></i>Carrera
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
                        @forelse ($estudiantes as $estudiante)
                            <tr>
                                <!-- ESTUDIANTE -->
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-success bg-opacity-10 text-success me-2">
                                            {{ substr($estudiante->persona->nombre, 0, 1) }}{{ substr($estudiante->persona->apellido_paterno, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $estudiante->persona->nombre . ' ' . $estudiante->persona->apellido_paterno }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $estudiante->persona->apellido_materno }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <!-- CÓDIGO -->
                                <td class="text-center">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        {{ $estudiante->codigo }}
                                    </span>
                                </td>

                                <!-- DNI -->
                                <td class="text-center">
                                    <span class="font-monospace">{{ $estudiante->persona->dni }}</span>
                                </td>

                                <!-- CARRERA -->
                                <td>
                                    <small class="text-muted d-block"
                                        style="max-width: 500px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $estudiante->carrera->nombre }}
                                    </small>
                                </td>

                                <!-- ESTADO -->
                                <td class="text-center">
                                    <span class="badge {{ $estudiante->estado ? 'bg-success' : 'bg-danger' }}">
                                        <i
                                            class="bi {{ $estudiante->estado ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                        {{ $estudiante->estado_texto }}
                                    </span>
                                </td>

                                <!-- ACCIONES -->
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-info"
                                            wire:click="selectInfo({{ $estudiante->id }})" data-bs-toggle="modal"
                                            data-bs-target="#modalAsignarCurso" title="Asignar cursos">
                                            <i class="bi bi-journal-bookmark"></i>
                                        </button>
                                        <button class="btn btn-outline-warning"
                                            wire:click="selectInfo({{ $estudiante->id }})" data-bs-toggle="modal"
                                            data-bs-target="#modalEditarEstudiante" title="Editar estudiante">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-outline-danger"
                                            wire:click="selectInfo({{ $estudiante->id }})" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminarEstudiante" title="Eliminar estudiante">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                        <h6 class="fw-semibold">No hay estudiantes registrados</h6>
                                        <p class="mb-0 small">Comienza agregando un nuevo estudiante</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if ($estudiantes->hasPages())
                <div class="card-footer bg-white border-top">
                    {{ $estudiantes->links() }}
                </div>
            @endif
        </div>
    </div>



    <!-- Modal Crear Estudiante -->
    <div wire:ignore.self class="modal fade" id="modalCrearEstudiante" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="CrearEstudiante" class="modal-content">

                <!-- HEADER -->
                <div class="modal-header bg-success bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>Registrar Estudiante
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <!-- Bloque informativo -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-mortarboard fs-3 text-success me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Datos del estudiante</h6>
                                <small class="text-muted">Completa la información académica y personal</small>
                            </div>
                        </div>
                    </div>

                    <!-- DATOS ACADÉMICOS -->
                    <div class="row g-3">

                        <!-- FACULTAD -->
                        <div class="col-12">
                            <label for="facultadSelect" class="form-label fw-semibold">
                                <i class="bi bi-building me-1"></i>Facultad <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('facultad_id') is-invalid @enderror" id="facultadSelect"
                                wire:model.live="facultad_id">
                                <option value="" hidden>Seleccione una facultad</option>
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
                            <label for="carreraSelect" class="form-label fw-semibold">
                                <i class="bi bi-book me-1"></i>Carrera <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('carrera_id') is-invalid @enderror" id="carreraSelect"
                                wire:model.live="carrera_id" @disabled(!$facultad_id)>
                                <option value="" hidden>Seleccione una carrera</option>
                                @foreach ($carreras as $carrera)
                                    <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                                @endforeach
                            </select>
                            @error('carrera_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CÓDIGO -->
                        <div class="col-md-6">
                            <label for="codigoEstudiante" class="form-label fw-semibold">
                                <i class="bi bi-hash me-1"></i>Código de Estudiante <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('codigo') is-invalid @enderror"
                                wire:model.live="codigo" placeholder="Ingrese el código">
                            @error('codigo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DNI -->
                        <div class="col-md-6">
                            <label for="dniEstudiante" class="form-label fw-semibold">
                                <i class="bi bi-person-vcard me-1"></i>DNI <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('dni') is-invalid @enderror"
                                wire:model.live.debounce.500ms="dni" maxlength="8" inputmode="numeric"
                                placeholder="8 dígitos">
                            @error('dni')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <hr class="my-4">

                    <!-- DATOS PERSONALES -->
                    <div class="row g-3">

                        <!-- NOMBRE -->
                        <div class="col-md-4">
                            <label for="nombreEstudiante" class="form-label fw-semibold">
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
                            <label for="apellidoPaternoEstudiante" class="form-label fw-semibold">
                                <i class="bi bi-person-badge me-1"></i>Apellido Paterno <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror"
                                wire:model.live="apellido_paterno" placeholder="Ingrese el apellido paterno">
                            @error('apellido_paterno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO MATERNO -->
                        <div class="col-md-4">
                            <label for="apellidoMaternoEstudiante" class="form-label fw-semibold">
                                <i class="bi bi-person-badge-fill me-1"></i>Apellido Materno <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror"
                                wire:model.live="apellido_materno" placeholder="Ingrese el apellido materno">
                            @error('apellido_materno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- TELEFONO -->
                        <div class="col-md-4">
                            <label for="telefonoEstudiante" class="form-label fw-semibold">
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
                            <label for="correoEstudiante" class="form-label fw-semibold">
                                <i class="bi bi-envelope me-1"></i>Correo <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror"
                                wire:model.live="correo" placeholder="correo@ejemplo.com">
                            @error('correo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FECHA NACIMIENTO -->
                        <div class="col-md-4">
                            <label for="fechaNacimientoEstudiante" class="form-label fw-semibold">
                                <i class="bi bi-calendar-event me-1"></i>Fecha de nacimiento <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                wire:model.live="fecha_nacimiento">
                            @error('fecha_nacimiento')
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

    <!-- Modal Editar Estudiante -->
    <div wire:ignore.self class="modal fade" id="modalEditarEstudiante" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="EditarEstudiante" class="modal-content">

                <!-- HEADER -->
                <div class="modal-header bg-warning bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Editar Estudiante
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <!-- Bloque informativo -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-pencil-square fs-3 text-warning me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Modificar información del estudiante</h6>
                                <small class="text-muted">Actualiza los datos académicos y personales</small>
                            </div>
                        </div>
                    </div>

                    <!-- DATOS ACADÉMICOS -->
                    <div class="row g-3">

                        <!-- FACULTAD -->
                        <div class="col-12">
                            <label for="facultadSelectEdit" class="form-label fw-semibold">
                                <i class="bi bi-building me-1"></i>Facultad <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('facultad_id') is-invalid @enderror"
                                id="facultadSelectEdit" wire:model.live="facultad_id">
                                <option value="" hidden>Seleccione una facultad</option>
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
                            <label for="carreraSelectEdit" class="form-label fw-semibold">
                                <i class="bi bi-book me-1"></i>Carrera <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('carrera_id') is-invalid @enderror"
                                id="carreraSelectEdit" wire:model.live="carrera_id"
                                wire:key="carrera-{{ $facultad_id }}" @disabled(!$facultad_id)>
                                <option value="" hidden>Seleccione una carrera</option>
                                @foreach ($carreras as $carrera)
                                    <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                                @endforeach
                            </select>
                            @error('carrera_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CÓDIGO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-hash me-1"></i>Código de Estudiante <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('codigo') is-invalid @enderror"
                                wire:model.live="codigo" placeholder="Ingrese el código">
                            @error('codigo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DNI -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-vcard me-1"></i>DNI <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('dni') is-invalid @enderror"
                                wire:model.live="dni" maxlength="8" placeholder="8 dígitos">
                            @error('dni')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <hr class="my-4">

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
                            <input type="text"
                                class="form-control @error('apellido_paterno') is-invalid @enderror"
                                wire:model.live="apellido_paterno" placeholder="Ingrese el apellido paterno">
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
                                wire:model.live="apellido_materno" placeholder="Ingrese el apellido materno">
                            @error('apellido_materno')
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
                        <div class="col-md-4">
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

                <!-- HEADER -->
                <div class="modal-header bg-info bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-journal-bookmark me-2 text-info"></i>Asignar Cursos
                        <span class="text-primary">
                            – {{ $nombre . ' ' . $apellido_paterno . ' ' . $apellido_materno }}
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
                                <h6 class="mb-0 fw-bold">Asignar curso al estudiante</h6>
                                <small class="text-muted">Los campos de Facultad y Carrera están bloqueados según los
                                    datos del estudiante</small>
                            </div>
                        </div>
                    </div>

                    <form wire:submit.prevent="GuardarAsignacionCurso">

                        <!-- DATOS ACADÉMICOS BLOQUEADOS -->
                        <div class="row g-3">

                            <!-- FACULTAD -->
                            <div class="col-12">
                                <label for="facultadSelectAsignar" class="form-label fw-semibold">
                                    <i class="bi bi-building me-1"></i>Facultad
                                </label>
                                <select class="form-select" id="facultadSelectAsignar" wire:model.live="facultad_id"
                                    @if ($facultad_id) disabled @endif>
                                    <option value="" hidden>Seleccione una facultad</option>
                                    @foreach ($facultades as $facultad)
                                        <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- CARRERA -->
                            <div class="col-12">
                                <label for="carreraSelectAsignar" class="form-label fw-semibold">
                                    <i class="bi bi-book me-1"></i>Carrera
                                </label>
                                <select class="form-select" id="carreraSelectAsignar" wire:model.live="carrera_id"
                                    wire:key="carrera-{{ $facultad_id }}"
                                    @if ($facultad_id) disabled @endif>
                                    <option value="" hidden>Seleccione una carrera</option>
                                    @foreach ($carreras as $carrera)
                                        <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <hr class="my-4">

                        <!-- ASIGNACIÓN DE CURSO -->
                        <div class="row g-3">

                            <!-- CURSO -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-journal-text me-1"></i>Curso <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('curso_id') is-invalid @enderror"
                                    wire:model.live="curso_id" wire:key="curso-{{ $carrera_id }}"
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

                            <!-- GRUPO -->
                            <div class="col-md-4">
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

                            <!-- SEMESTRE -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar3 me-1"></i>Semestre <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('semestre_id') is-invalid @enderror"
                                    wire:model="semestre_id">
                                    <option value="" hidden>Seleccione un semestre</option>
                                    @foreach ($semestres as $sem)
                                        <option value="{{ $sem->id }}">{{ $sem->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('semestre_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- BOTONES DEL FORMULARIO -->
                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                wire:click="limpiar">
                                <i class="bi bi-x-circle me-1"></i>Cancelar
                            </button>
                            <button type="submit" class="btn btn-info text-white" wire:loading.attr="disabled">
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

                    <!-- TABLA DE CURSOS ASIGNADOS -->
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-list-check fs-4 text-success me-2"></i>
                        <h6 class="fw-bold mb-0">Cursos asignados</h6>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-journal-text me-1"></i>Curso</th>
                                    <th><i class="bi bi-calendar3 me-1"></i>Semestre</th>
                                    <th><i class="bi bi-people me-1"></i>Grupo</th>
                                    <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($estudiantesCursosDocentes as $estudiantesCursosDocentes)
                                    <tr>
                                        <td>{{ $estudiantesCursosDocentes->docenteCurso->curso->nombre }}</td>
                                        <td>{{ $estudiantesCursosDocentes->semestre->nombre }}</td>
                                        <td>{{ $estudiantesCursosDocentes->docenteCurso->grupo->nombre }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm"
                                                wire:click="selectAsignacionCurso({{ $estudiantesCursosDocentes->id }})"
                                                data-bs-toggle="modal" data-bs-target="#modalEliminarAsignacionCurso"
                                                title="Eliminar asignación">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                            No hay cursos asignados aún.
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
