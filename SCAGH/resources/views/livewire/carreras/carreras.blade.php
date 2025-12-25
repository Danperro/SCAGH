<section class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="row g-3 align-items-center mb-4">

        <div class="col-12 col-md-8">
            <div
                class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start text-center text-md-start">
                <i class="bi bi-mortarboard fs-3 text-success"></i>
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
                    <i class="bi bi-plus-circle me-1"></i> Nueva carrera
                </button>

                <button class="btn btn-outline-success" type="button" wire:click="limpiar">
                    <i class="bi bi-eraser me-1"></i> Limpiar Filtros
                </button>
            </div>
        </div>

    </div>


    {{-- FILTROS --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">

                {{-- Buscar --}}
                <div class="col-12 col-md-5">
                    <label for="filtroQueryCarrera" class="form-label fw-semibold">Buscar</label>
                    <input id="filtroQueryCarrera" type="text" class="form-control"
                        placeholder="Buscar por nombre..." wire:model.live.debounce.400ms="query">
                </div>

                {{-- Facultad --}}
                <div class="col-12 col-md-5">
                    <label for="filtroFacultadCarrera" class="form-label fw-semibold">Facultad</label>
                    <select id="filtroFacultadCarrera" class="form-select" wire:model.live="filtrofacultadId">
                        <option value="" hidden>Todas las facultades</option>
                        @foreach ($facultades as $facultad)
                            <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Estado --}}
                <div class="col-12 col-md-2">
                    <label for="filtroEstadoCarrera" class="form-label fw-semibold">Estado</label>
                    <select id="filtroEstadoCarrera" class="form-select" wire:model.live="filtroestado">
                        <option value="" hidden>Todos</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                    </select>
                </div>

            </div>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th class="text-start ps-4">Carrera</th>
                            <th>Facultad</th>
                            <th>Ciclos</th>
                            <th>Estado</th>
                            <th style="width: 140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($carreras as $c)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark">{{ $c->nombre }}</div>
                                </td>

                                <td class="text-center">
                                    {{ $c->facultad?->nombre ?? '-' }}
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $c->ciclos_total ?? 12 }}</span>
                                </td>

                                <td class="text-center">
                                    @if ((int) $c->estado === 1)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <button class="btn btn-warning btn-sm" type="button"
                                        wire:click="selectInfo({{ $c->id }})" data-bs-toggle="modal"
                                        data-bs-target="#modalEditarCarrera">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <button class="btn btn-danger btn-sm" type="button"
                                        wire:click="selectInfo({{ $c->id }})" data-bs-toggle="modal"
                                        data-bs-target="#modalEliminarCarrera">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No hay carreras registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $carreras->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL CREAR --}}
    <div wire:ignore.self class="modal fade" id="modalCrearCarrera" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content" wire:submit.prevent="CrearCarrera">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>Registrar Carrera
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="limpiar"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Facultad --}}
                        <div class="col-12">
                            <label for="create_facultadId" class="form-label fw-semibold">Facultad<span
                                    class="text-danger">*</span></label>
                            <select id="create_facultadId"
                                class="form-select @error('facultadId') is-invalid @enderror"
                                wire:model.live="facultadId">
                                <option value="" hidden>Seleccionar</option>
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
                            <label for="create_nombre" class="form-label fw-semibold">Nombre<span class="text-danger">*</span></label>
                            <input id="create_nombre" type="text"
                                class="form-control @error('nombre') is-invalid @enderror"
                                placeholder="Ej: Ingeniería de Sistemas" wire:model.live="nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ciclos --}}
                        <div class="col-12 col-md-4">
                            <label for="create_ciclosTotal" class="form-label fw-semibold">Ciclos máximos</label>
                            <select id="create_ciclosTotal"
                                class="form-select @error('ciclosTotal') is-invalid @enderror"
                                wire:model.live="ciclosTotal">
                                <option value="10">10</option>
                                <option value="12">12</option>
                                <option value="14">14</option>
                            </select>
                            @error('ciclosTotal')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Estado hidden (para que pase tu regla "estado required boolean") --}}
                        <input type="hidden" wire:model.live="estado" value="1">
                        @error('estado')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" wire:click="limpiar">
                        Cerrar
                    </button>
                    <button class="btn btn-success" type="submit">
                        <i class="bi bi-check2-circle me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{--  MODAL EDITAR --}}
    <div wire:ignore.self class="modal fade" id="modalEditarCarrera" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content" wire:submit.prevent="EditarCarrera">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Editar Carrera
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="limpiar"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Facultad --}}
                        <div class="col-12">
                            <label for="edit_facultadId" class="form-label fw-semibold">Facultad<span class="text-danger">*</span></label>
                            <select id="edit_facultadId" class="form-select @error('facultadId') is-invalid @enderror"
                                wire:model.live="facultadId">
                                <option value="" hidden>Seleccionar</option>
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
                            <label for="edit_nombre" class="form-label fw-semibold">Nombre<span class="text-danger">*</span></label>
                            <input id="edit_nombre" type="text"
                                class="form-control @error('nombre') is-invalid @enderror"
                                placeholder="Ej: Ingeniería de Sistemas" wire:model.live="nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ciclos --}}
                        <div class="col-12 col-md-4">
                            <label for="edit_ciclosTotal" class="form-label fw-semibold">Ciclos máximos</label>
                            <select id="edit_ciclosTotal"
                                class="form-select @error('ciclosTotal') is-invalid @enderror"
                                wire:model.live="ciclosTotal">
                                <option value="10">10</option>
                                <option value="12">12</option>
                                <option value="14">14</option>
                            </select>
                            @error('ciclosTotal')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Estado --}}
                        <div class="col-12 col-md-4">
                            <label for="edit_estado" class="form-label fw-semibold">Estado</label>
                            <select id="edit_estado" class="form-select @error('estado') is-invalid @enderror"
                                wire:model.live="estado">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- id oculto (opcional) --}}
                        <input type="hidden" wire:model.live="carreraId">

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" wire:click="limpiar">
                        Cerrar
                    </button>
                    <button class="btn btn-warning" type="submit">
                        <i class="bi bi-save2 me-1"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL ELIMINAR --}}
    <div wire:ignore.self class="modal fade" id="modalEliminarCarrera" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-exclamation-triangle me-2 text-danger"></i>Confirmar eliminación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="limpiar"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-2">¿Seguro que deseas desactivar esta carrera?</p>
                    <div class="alert alert-warning mb-0">
                        Esto solo cambia el <strong>estado a 0</strong>, no borra registros.
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" wire:click="limpiar">
                        Cancelar
                    </button>
                    <button class="btn btn-danger" type="button" wire:click="EliminarCarrera">
                        <i class="bi bi-trash me-1"></i> Eliminar
                    </button>
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
