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
                <i class="bi bi-mortarboard fs-3 text-success"></i>
                <div>
                    <h3 class="fw-bold mb-0">LISTADO DE CURSOS</h3>
                    <small class="text-muted">Gestión de cursos por carreras y facultades</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-end gap-2">
                <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#modalCrearCurso"
                    wire:click="limpiar">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Curso
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
                        placeholder="Nombre del curso...">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>La búsqueda se actualiza automáticamente
                    </small>
                </div>

                <!-- FILTRAR POR CICLO -->
                <div class="col-12 col-lg-2">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-layers me-1"></i>Ciclo
                    </label>
                    <select class="form-select" wire:model.live="filtrociclo_id"
                        wire:key="filtro-ciclo-{{ $filtrocarrera_id ?? 'all' }}">
                        <option value="" hidden>Todos los ciclos</option>
                        @foreach ($ciclosFiltro as $ciclo)
                            <option value="{{ $ciclo->id }}">{{ $ciclo->nombre }}</option>
                        @endforeach
                    </select>

                    <!-- Indicador de filtros activos -->
                    @if ($query || $filtrofacultad_id || $filtrocarrera_id || $filtrociclo_id)
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
                    <select class="form-select" wire:model.live="filtrocarrera_id" @disabled(!$filtrofacultad_id)>
                        <option value="" hidden>Todas las carreras</option>
                        @foreach ($carrerasFiltro as $carrera)
                            <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- Resumen de filtros activos -->
            @if ($query || $filtrofacultad_id || $filtrocarrera_id || $filtrociclo_id)
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
                            @if ($filtrociclo_id)
                                <span class="badge bg-success">
                                    <i class="bi bi-layers me-1"></i>Ciclo filtrado
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Tabla de Cursos -->
    <div class="card shadow-sm">

        <!-- Header de la tabla -->
        <div class="card-header bg-white border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-table fs-4 text-success me-2"></i>
                    <h6 class="fw-bold mb-0">Lista de Cursos</h6>
                </div>
                <span class="badge bg-primary">
                    Total: {{ $cursos->total() }} curso(s)
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-3">
                                <i class="bi bi-mortarboard me-1"></i>Curso
                            </th>
                            <th scope="col">
                                <i class="bi bi-book me-1"></i>Carrera
                            </th>
                            <th scope="col" class="text-center">
                                <i class="bi bi-layers me-1"></i>Ciclo
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
                        @forelse ($cursos as $curso)
                            <tr>
                                <!-- CURSO -->
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-success bg-opacity-10 text-success me-2">
                                            {{ substr($curso->nombre, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $curso->nombre }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $curso->carrera->facultad->nombre ?? 'Sin facultad' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <!-- CARRERA -->
                                <td>
                                    <small class="text-muted d-block"
                                        style="max-width: 450px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $curso->carrera->nombre }}
                                    </small>
                                </td>

                                <!-- CICLO -->
                                <td class="text-center">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        {{ $curso->ciclo->nombre }}
                                    </span>
                                </td>

                                <!-- ESTADO -->
                                <td class="text-center">
                                    <span class="badge {{ $curso->estado ? 'bg-success' : 'bg-danger' }}">
                                        <i
                                            class="bi {{ $curso->estado ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                        {{ $curso->estado_texto }}
                                    </span>
                                </td>

                                <!-- ACCIONES -->
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-warning"
                                            wire:click="selectInfo({{ $curso->id }})" data-bs-toggle="modal"
                                            data-bs-target="#modalEditarCurso" title="Editar curso">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-outline-danger"
                                            wire:click="selectInfo({{ $curso->id }})" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminarCurso" title="Eliminar curso">
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
                                        <h6 class="fw-semibold">No hay cursos registrados</h6>
                                        <p class="mb-0 small">Comienza agregando un nuevo curso</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if ($cursos->hasPages())
                <div class="card-footer bg-white border-top">
                    {{ $cursos->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- MODAL DE CREAR CURSO -->
    <div wire:ignore.self class="modal fade" id="modalCrearCurso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="CrearCurso" class="modal-content">

                <!-- HEADER -->
                <div class="modal-header bg-success bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>Registrar Curso
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <!-- Bloque informativo -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-mortarboard fs-3 text-success me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Datos del curso</h6>
                                <small class="text-muted">Completa la información académica del curso</small>
                            </div>
                        </div>
                    </div>

                    <!-- DATOS ACADÉMICOS -->
                    <div class="row g-3">

                        <!-- FACULTAD -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-building me-1"></i>Facultad <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('facultad_id') is-invalid @enderror"
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
                            <label class="form-label fw-semibold">
                                <i class="bi bi-book me-1"></i>Carrera <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('carrera_id') is-invalid @enderror"
                                wire:model.live="carrera_id" wire:key="carrera-{{ $facultad_id }}"
                                @disabled(!$facultad_id)>
                                <option value="" hidden>Seleccione una carrera</option>
                                @foreach ($carreras as $carrera)
                                    <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                                @endforeach
                            </select>
                            @error('carrera_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <hr class="my-4">

                    <!-- INFORMACIÓN DEL CURSO -->
                    <div class="row g-3">

                        <!-- NOMBRE DEL CURSO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-journal-text me-1"></i>Nombre del Curso <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                wire:model.live="nombre" placeholder="Ej. Introducción a la Programación">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CÓDIGO -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-hash me-1"></i>Código <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('codigo') is-invalid @enderror"
                                wire:model.live="codigo" placeholder="Ej. EDU101">
                            @error('codigo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CICLO -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-layers me-1"></i>Ciclo <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('ciclo_id') is-invalid @enderror"
                                wire:model.live="ciclo_id">
                                <option value="" hidden>Seleccionar</option>
                                @foreach ($ciclosForm as $ciclo)
                                    <option value="{{ $ciclo->id }}">{{ $ciclo->nombre }}</option>
                                @endforeach
                            </select>
                            @error('ciclo_id')
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

    <!-- MODAL DE EDITAR CURSO -->
    <div wire:ignore.self class="modal fade" id="modalEditarCurso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="EditarCurso" class="modal-content">

                <!-- HEADER -->
                <div class="modal-header bg-warning bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Editar Curso
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <!-- Bloque informativo -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-pencil-square fs-3 text-warning me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Modificar información del curso</h6>
                                <small class="text-muted">Actualiza los datos académicos del curso</small>
                            </div>
                        </div>
                    </div>

                    <!-- DATOS ACADÉMICOS -->
                    <div class="row g-3">

                        <!-- FACULTAD -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-building me-1"></i>Facultad <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('facultad_id') is-invalid @enderror"
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
                            <label class="form-label fw-semibold">
                                <i class="bi bi-book me-1"></i>Carrera <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('carrera_id') is-invalid @enderror"
                                wire:model.live="carrera_id" wire:key="carrera-{{ $facultad_id }}"
                                @disabled(!$facultad_id)>
                                <option value="" hidden>Seleccione una carrera</option>
                                @foreach ($carreras as $carrera)
                                    <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                                @endforeach
                            </select>
                            @error('carrera_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <hr class="my-4">

                    <!-- INFORMACIÓN DEL CURSO -->
                    <div class="row g-3">

                        <!-- NOMBRE DEL CURSO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-journal-text me-1"></i>Nombre del Curso <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                wire:model.live="nombre" placeholder="Ej. Introducción a la Programación">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CÓDIGO -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-hash me-1"></i>Código <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('codigo') is-invalid @enderror"
                                wire:model.live="codigo" placeholder="Ej. EDU101">
                            @error('codigo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CICLO -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-layers me-1"></i>Ciclo <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('ciclo_id') is-invalid @enderror"
                                wire:model.live="ciclo_id">
                                <option value="" hidden>Seleccionar</option>
                                @foreach ($ciclosForm as $ciclo)
                                    <option value="{{ $ciclo->id }}">{{ $ciclo->nombre }}</option>
                                @endforeach
                            </select>
                            @error('ciclo_id')
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

    <!-- MODAL DE ELIMINAR CURSO -->
    <div wire:ignore.self class="modal fade" id="modalEliminarCurso" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-body text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-trash-fill text-danger fs-1"></i>
                    </div>

                    <h4 class="fw-bold">¿Estas seguro de eliminar este curso?</h4>

                    <p class="text-muted">
                        Esta acción es permanente y no podrás recuperarlo.
                    </p>

                    <div class="d-flex gap-3 mt-4">
                        <button class="btn btn-light flex-fill" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-danger flex-fill" wire:click="EliminarCurso">Eliminar</button>
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
