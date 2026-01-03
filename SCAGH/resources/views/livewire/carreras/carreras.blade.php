<section class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="row g-3 align-items-center mb-4">

        <div class="col-12 col-md-8">
            <div
                class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start text-center text-md-start">
                <i class="bi bi-book fs-3 text-success"></i>
                <div>
                    <h3 class="fw-bold mb-0">LISTADO DE CARRERAS</h3>
                    <small class="text-muted">Gestión de carreras por facultad y ciclos máximos</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-end gap-2">
                <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#modalCrearCarrera"
                    wire:click="limpiar">
                    <i class="bi bi-plus-circle me-1"></i> Nueva Carrera
                </button>

                <button class="btn btn-outline-success" type="button" wire:click="limpiar">
                    <i class="bi bi-eraser me-1"></i> Limpiar Filtros
                </button>
            </div>
        </div>

    </div>

    {{-- FILTROS --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <!-- Header de Filtros -->
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-funnel fs-4 text-primary me-2"></i>
                <h6 class="fw-bold mb-0">Filtros de búsqueda</h6>
            </div>

            <div class="row g-3">

                {{-- Buscar --}}
                <div class="col-12 col-lg-5">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-search me-1"></i>Buscar por
                    </label>
                    <input type="text" class="form-control" placeholder="Nombre de la carrera..."
                        wire:model.live.debounce.400ms="query">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>La búsqueda se actualiza automáticamente
                    </small>
                </div>

                {{-- Facultad --}}
                <div class="col-12 col-lg-5">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-building me-1"></i>Facultad
                    </label>
                    <select class="form-select" wire:model.live="filtrofacultadId">
                        <option value="" hidden>Todas las facultades</option>
                        @foreach ($facultades as $facultad)
                            <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Estado --}}
                <div class="col-12 col-lg-2">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-toggle-on me-1"></i>Estado
                    </label>
                    <select class="form-select" wire:model.live="filtroestado">
                        <option value="" hidden>Todos</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                    </select>

                    @if ($query || $filtrofacultadId || $filtroestado !== '')
                        <small class="text-success d-block mt-2">
                            <i class="bi bi-check-circle-fill me-1"></i>Filtros activos
                        </small>
                    @endif
                </div>

            </div>

            <!-- Resumen de filtros activos -->
            @if ($query || $filtrofacultadId || $filtroestado !== '')
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex flex-wrap gap-2">
                            @if ($query)
                                <span class="badge bg-primary">
                                    <i class="bi bi-search me-1"></i>Búsqueda: "{{ $query }}"
                                </span>
                            @endif
                            @if ($filtrofacultadId)
                                <span class="badge bg-info">
                                    <i class="bi bi-building me-1"></i>Facultad filtrada
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

    {{-- TABLA --}}
    <div class="card shadow-sm">

        <!-- Header de la tabla -->
        <div class="card-header bg-white border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-table fs-4 text-success me-2"></i>
                    <h6 class="fw-bold mb-0">Lista de Carreras</h6>
                </div>
                <span class="badge bg-primary">
                    Total: {{ $carreras->total() }} carrera(s)
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-3">
                                <i class="bi bi-book me-1"></i>Carrera
                            </th>
                            <th scope="col">
                                <i class="bi bi-building me-1"></i>Facultad
                            </th>
                            <th scope="col" class="text-center">
                                <i class="bi bi-layers me-1"></i>Ciclos
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
                        @forelse ($carreras as $c)
                            <tr>
                                <!-- CARRERA -->
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-success bg-opacity-10 text-success me-2"
                                            style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.75rem;">
                                            {{ substr($c->nombre, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $c->nombre }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- FACULTAD -->
                                <td>
                                    <small class="text-muted d-block"
                                        style="max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $c->facultad?->nombre ?? '-' }}
                                    </small>
                                </td>

                                <!-- CICLOS -->
                                <td class="text-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        <i class="bi bi-layers me-1"></i>{{ $c->ciclos_total ?? 12 }} ciclos
                                    </span>
                                </td>

                                <!-- ESTADO -->
                                <td class="text-center">
                                    @if ((int) $c->estado === 1)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Activo
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>Inactivo
                                        </span>
                                    @endif
                                </td>

                                <!-- ACCIONES -->
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-warning" type="button"
                                            wire:click="selectInfo({{ $c->id }})" data-bs-toggle="modal"
                                            data-bs-target="#modalEditarCarrera" title="Editar carrera">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" type="button"
                                            wire:click="selectInfo({{ $c->id }})" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminarCarrera" title="Eliminar carrera">
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
                                        <h6 class="fw-semibold">No hay carreras registradas</h6>
                                        <p class="mb-0 small">Comienza agregando una nueva carrera</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if ($carreras->hasPages())
                <div class="card-footer bg-white border-top">
                    {{ $carreras->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL CREAR --}}
    <div wire:ignore.self class="modal fade" id="modalCrearCarrera" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form class="modal-content" wire:submit.prevent="CrearCarrera">

                <!-- HEADER -->
                <div class="modal-header bg-success bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>Registrar Carrera
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <!-- Bloque informativo -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-book fs-3 text-success me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Datos de la carrera</h6>
                                <small class="text-muted">Completa la información de la carrera profesional</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">

                        {{-- Facultad --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-building me-1"></i>Facultad <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('facultadId') is-invalid @enderror"
                                wire:model.live="facultadId">
                                <option value="" hidden>Seleccione una facultad</option>
                                @foreach ($facultades as $facultad)
                                    <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                                @endforeach
                            </select>
                            @error('facultadId')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nombre --}}
                        <div class="col-12 col-md-8">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-journal-text me-1"></i>Nombre <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                placeholder="Ej. Ingeniería de Sistemas" wire:model.live="nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ciclos --}}
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-layers me-1"></i>Ciclos máximos
                            </label>
                            <select class="form-select @error('ciclosTotal') is-invalid @enderror"
                                wire:model.live="ciclosTotal">
                                <option value="10">10 ciclos</option>
                                <option value="12">12 ciclos</option>
                                <option value="14">14 ciclos</option>
                            </select>
                            @error('ciclosTotal')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Estado hidden --}}
                        <input type="hidden" wire:model.live="estado" value="1">

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

    {{-- MODAL EDITAR --}}
    <div wire:ignore.self class="modal fade" id="modalEditarCarrera" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form class="modal-content" wire:submit.prevent="EditarCarrera">

                <!-- HEADER -->
                <div class="modal-header bg-warning bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Editar Carrera
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <!-- Bloque informativo -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-pencil-square fs-3 text-warning me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Modificar información de la carrera</h6>
                                <small class="text-muted">Actualiza los datos de la carrera profesional</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">

                        {{-- Facultad --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-building me-1"></i>Facultad <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('facultadId') is-invalid @enderror"
                                wire:model.live="facultadId">
                                <option value="" hidden>Seleccione una facultad</option>
                                @foreach ($facultades as $facultad)
                                    <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                                @endforeach
                            </select>
                            @error('facultadId')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nombre --}}
                        <div class="col-12 col-md-8">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-journal-text me-1"></i>Nombre <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                placeholder="Ej. Ingeniería de Sistemas" wire:model.live="nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ciclos --}}
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-layers me-1"></i>Ciclos máximos
                            </label>
                            <select class="form-select @error('ciclosTotal') is-invalid @enderror"
                                wire:model.live="ciclosTotal">
                                <option value="10">10 ciclos</option>
                                <option value="12">12 ciclos</option>
                                <option value="14">14 ciclos</option>
                            </select>
                            @error('ciclosTotal')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Estado --}}
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-toggle-on me-1"></i>Estado
                            </label>
                            <select class="form-select @error('estado') is-invalid @enderror"
                                wire:model.live="estado">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="hidden" wire:model.live="carreraId">

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

    {{-- MODAL ELIMINAR --}}
    <div wire:ignore.self class="modal fade" id="modalEliminarCarrera" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-body text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-trash-fill text-danger fs-1"></i>
                    </div>

                    <h4 class="fw-bold">¿Estas seguro de eliminar esta carrera?</h4>

                    <p class="text-muted">
                        Esta acción eliminará la carrera de forma permanente.
                    </p>

                    <div class="d-flex gap-3 mt-4">
                        <button class="btn btn-light flex-fill" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-danger flex-fill" wire:click="EliminarCarrera">Eliminar</button>
                    </div>

                </div>

            </div>
        </div>
    </div>



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

            toast.classList.remove('bg-success', 'bg-danger', 'bg-warning');
            toast.classList.add(`bg-${tipo}`);

            document.getElementById('toastGeneralTexto').innerText = mensaje;

            const toastShow = new bootstrap.Toast(toast);
            toastShow.show();
        });

    });
</script>
