<section class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="row g-3 align-items-center mb-4">

        <div class="col-12 col-md-8">
            <div
                class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start text-center text-md-start">
                <i class="bi bi-calendar3 fs-3 text-success"></i>
                <div>
                    <h3 class="fw-bold mb-0">LISTADO DE SEMESTRES</h3>
                    <small class="text-muted">Gestión de semestres académicos de la Universidad Intercultural de la
                        Amazonia</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-end gap-2">
                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                    data-bs-target="#modalCrearSemestre" wire:click="limpiar">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Semestre
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
                    <input type="text" class="form-control" wire:model.live.debounce.500ms="query"
                        placeholder="Nombre del semestre...">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>La búsqueda se actualiza automáticamente
                    </small>
                </div>

                <!-- FECHA INICIO -->
                <div class="col-12 col-lg-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar-check me-1"></i>Desde
                    </label>
                    <input type="date" class="form-control" wire:model.live="filtroFechaInicio">
                </div>

                <!-- FECHA FIN -->
                <div class="col-12 col-lg-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar-x me-1"></i>Hasta
                    </label>
                    <input type="date" class="form-control" wire:model.live="filtroFechaFin">
                </div>

            </div>

            <!-- Resumen de filtros activos -->
            @if ($query || $filtroFechaInicio || $filtroFechaFin)
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex flex-wrap gap-2">
                            @if ($query)
                                <span class="badge bg-primary">
                                    <i class="bi bi-search me-1"></i>Búsqueda: "{{ $query }}"
                                </span>
                            @endif
                            @if ($filtroFechaInicio)
                                <span class="badge bg-info">
                                    <i class="bi bi-calendar-check me-1"></i>Desde:
                                    {{ \Carbon\Carbon::parse($filtroFechaInicio)->format('d/m/Y') }}
                                </span>
                            @endif
                            @if ($filtroFechaFin)
                                <span class="badge bg-info">
                                    <i class="bi bi-calendar-x me-1"></i>Hasta:
                                    {{ \Carbon\Carbon::parse($filtroFechaFin)->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Tabla de Semestres -->
    <div class="card shadow-sm">

        <!-- Header de la tabla -->
        <div class="card-header bg-white border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-table fs-4 text-success me-2"></i>
                    <h6 class="fw-bold mb-0">Lista de Semestres</h6>
                </div>
                <span class="badge bg-primary">
                    Total: {{ $semestres->total() }} semestre(s)
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-3">
                                <i class="bi bi-calendar3 me-1"></i>Nombre
                            </th>
                            <th scope="col" class="text-center">
                                <i class="bi bi-calendar-check me-1"></i>Fecha Inicio
                            </th>
                            <th scope="col" class="text-center">
                                <i class="bi bi-calendar-x me-1"></i>Fecha Fin
                            </th>
                            <th scope="col" class="text-center">
                                <i class="bi bi-clock-history me-1"></i>Duración
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
                        @forelse ($semestres as $semestre)
                            <tr>
                                <!-- NOMBRE -->
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-success bg-opacity-10 text-success me-2"
                                            style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.75rem;">
                                            <i class="bi bi-calendar3"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $semestre->nombre }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- FECHA INICIO -->
                                <td class="text-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        <i class="bi bi-calendar-check me-1"></i>
                                        {{ optional(\Carbon\Carbon::parse($semestre->fecha_inicio))->format('d/m/Y') }}
                                    </span>
                                </td>

                                <!-- FECHA FIN -->
                                <td class="text-center">
                                    <span class="badge bg-danger bg-opacity-10 text-danger">
                                        <i class="bi bi-calendar-x me-1"></i>
                                        {{ optional(\Carbon\Carbon::parse($semestre->fecha_fin))->format('d/m/Y') }}
                                    </span>
                                </td>

                                <!-- DURACIÓN -->
                                <td class="text-center">
                                    @php
                                        $inicio = \Carbon\Carbon::parse($semestre->fecha_inicio);
                                        $fin = \Carbon\Carbon::parse($semestre->fecha_fin);
                                        $meses = $inicio->diffInMonths($fin);
                                        $meses = (int) round($meses);
                                    @endphp
                                    <small class="text-muted">
                                        <i class="bi bi-clock-history me-1"></i>{{ $meses }} meses
                                    </small>
                                </td>

                                <!-- ESTADO -->
                                <td class="text-center">
                                    <span class="badge {{ $semestre->estado ? 'bg-success' : 'bg-danger' }}">
                                        <i
                                            class="bi {{ $semestre->estado ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                        {{ $semestre->estado_texto }}
                                    </span>
                                </td>

                                <!-- ACCIONES -->
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-warning"
                                            wire:click="selectInfo({{ $semestre->id }})" data-bs-toggle="modal"
                                            data-bs-target="#modalEditarSemestre" title="Editar semestre">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-outline-danger"
                                            wire:click="selectInfo({{ $semestre->id }})" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminarSemestre" title="Eliminar semestre">
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
                                        <h6 class="fw-semibold">No hay semestres registrados</h6>
                                        <p class="mb-0 small">Comienza agregando un nuevo semestre</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if ($semestres->hasPages())
                <div class="card-footer bg-white border-top">
                    {{ $semestres->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- MODAL DE CREAR SEMESTRE -->
    <div wire:ignore.self class="modal fade" id="modalCrearSemestre" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">

            <form wire:submit.prevent="CrearSemestre" class="modal-content">

                <!-- HEADER -->
                <div class="modal-header bg-success bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>Registrar Semestre
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <!-- Bloque informativo -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar3 fs-3 text-success me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Datos del semestre</h6>
                                <small class="text-muted">Completa la información del período académico</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">

                        <!-- NOMBRE -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar3 me-1"></i>Nombre del Semestre <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                wire:model.live="nombre" placeholder="Ej. Semestre I 2025">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FECHA INICIO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-check me-1"></i>Fecha de Inicio <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror"
                                wire:model.live="fecha_inicio">
                            @error('fecha_inicio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FECHA FIN -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-x me-1"></i>Fecha de Fin <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror"
                                wire:model.live="fecha_fin">
                            @error('fecha_fin')
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

    <!-- MODAL DE EDITAR SEMESTRE -->
    <div wire:ignore.self class="modal fade" id="modalEditarSemestre" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">

            <form wire:submit.prevent="EditarSemestre" class="modal-content">

                <!-- HEADER -->
                <div class="modal-header bg-warning bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Editar Semestre
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <!-- Bloque informativo -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-pencil-square fs-3 text-warning me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Modificar información del semestre</h6>
                                <small class="text-muted">Actualiza los datos del período académico</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">

                        <!-- NOMBRE -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar3 me-1"></i>Nombre del Semestre <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                wire:model.live="nombre" placeholder="Ej. Semestre I 2025">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FECHA INICIO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-check me-1"></i>Fecha de Inicio <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror"
                                wire:model.live="fecha_inicio">
                            @error('fecha_inicio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FECHA FIN -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-x me-1"></i>Fecha de Fin <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror"
                                wire:model.live="fecha_fin">
                            @error('fecha_fin')
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

    <!-- MODAL DE ELIMINAR SEMESTRE -->
    <div wire:ignore.self class="modal fade" id="modalEliminarSemestre" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-body text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-trash-fill text-danger fs-1"></i>
                    </div>

                    <h4 class="fw-bold">¿Estas seguro de eliminar el semestre?</h4>

                    <p class="text-muted">
                        Esta acción eliminará el semestre de forma permanente.
                    </p>

                    <div class="d-flex gap-3 mt-4">
                        <button class="btn btn-light flex-fill" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-danger flex-fill" wire:click="EliminarSemestre">Eliminar</button>
                    </div>

                </div>

            </div>
        </div>
    </div>



    <!-- TOAST DE GENERAL -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
        <div id="toastGeneral" class="toast align-items-center text-white fw-bold border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastGeneralTexto"></div>
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
            const modales = document.querySelectorAll('.modal.show');

            modales.forEach(modal => {
                const instancia = bootstrap.Modal.getInstance(modal);
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
