<section class="container-fluid py-4">
    <!-- Título -->
    <h2 class="fw-bold mb-1">Semestres</h2>
    <p class="text-muted mb-4">Gestión de semestres</p>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm p-4">

        <!-- 🔹 Fila 1: Buscar, Ciclo, Botones -->
        <div class="row g-3 align-items-end">

            <!-- Buscar -->
            <div class="col-12 col-md-5">
                <label class="form-label fw-semibold">Buscar</label>
                <input class="form-control" type="text" wire:model.live.debounce.500ms="query"
                    placeholder="Buscar semestre...">
            </div>

            <!-- Botones -->
            <div class="col-12 col-md-4 d-flex flex-column flex-md-row gap-2">
                <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#modalCrearSemestre"
                    wire:click="limpiar">
                    + Crear Semestre
                </button>

                <button class="btn btn-outline-success w-100" wire:click="limpiar">
                    Limpiar filtros
                </button>
            </div>

        </div>

        <hr class="my-4">

    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-centered">
                        <th scope="col">Nombre</th>
                        <th scope="col">Fecha Inicio</th>
                        <th scope="col">Fecha Fin</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($semestres as $semestre)
                        <tr>
                            <td>{{ $semestre->nombre }}</td>
                            <td>{{ $semestre->fecha_inicio }}</td>
                            <td>{{ $semestre->fecha_fin }}</td>
                            <td>{{ $semestre->estado ? 'Activo' : 'Inactivo' }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" wire:click="selectInfo({{ $semestre->id }})"
                                    data-bs-toggle="modal" data-bs-target="#modalEditarSemestre">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" wire:click="selectInfo({{ $semestre->id }})"
                                    data-bs-toggle="modal" data-bs-target="#modalEliminarSemestre">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL DE CREAR SEMESTRE -->
    <div wire:ignore.self class="modal fade" id="modalCrearSemestre" tabindex="-1" aria-labelledby="modalCrearSemestre"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form wire:submit.prevent="CrearSemestre" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalCrearSemestreLabel">Crear Semestre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"
                        arial-label="close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- INPUT NOMBRE -->
                        <div class="col-12 col-md-5">
                            <label class="form-label fw-semibold @error('nombre') is-invalid @enderror">Nombre del
                                Semestre</label>
                            <input type="text" class="form-control" wire:model.live="nombre"
                                placeholder="Ej. Semestre I 2025">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT FECHA INICIO -->
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold @error('fecha_inicio') is-invalid @enderror">Fecha de
                                Inicio</label>
                            <input type="datetime-local" class="form-control" wire:model.live="fecha_inicio">
                            @error('fecha_inicio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT FECHA FIN -->
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold @error('fecha_fin') is-invalid @enderror">Fecha de
                                Fin</label>
                            <input type="datetime-local" class="form-control" wire:model.live="fecha_fin">
                            @error('fecha_fin')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SELECT ESTADO -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Estado</label>
                            <select class="form-select @error('estado') is-invalid @enderror" wire:model.live="estado">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            @error('estado')
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

    <!-- MODAL DE EDITAR SEMESTRE -->
    <div wire:ignore.self class="modal fade" id="modalEditarSemestre" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form wire:submit.prevent="EditarSemestre" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Editar Semestre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <!-- INPUT NOMBRE -->
                        <div class="col-12 col-md-5">
                            <label class="form-label fw-semibold @error('nombre') is-invalid @enderror">Nombre del
                                Semestre</label>
                            <input type="text" class="form-control" wire:model.live="nombre"
                                placeholder="Ej. Semestre I 2025">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT FECHA INICIO -->
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold @error('fecha_inicio') is-invalid @enderror">Fecha de
                                Inicio</label>
                            <input type="datetime-local" class="form-control" wire:model.live="fecha_inicio">
                            @error('fecha_inicio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT FECHA FIN -->
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold @error('fecha_fin') is-invalid @enderror">Fecha de
                                Fin</label>
                            <input type="datetime-local" class="form-control" wire:model.live="fecha_fin">
                            @error('fecha_fin')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SELECT ESTADO -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Estado</label>
                            <select class="form-select @error('estado') is-invalid @enderror"
                                wire:model.live="estado">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="limpiar">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>

            </form>
        </div>
    </div>

    <!-- MODAL DE ELIMINAR SEMESTRE -->
    <div wire:ignore.self class="modal fade" id="modalEliminarSemestre" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Eliminar Semestre</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="fs-5">¿Estás seguro de eliminar este semestre?</p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-danger" wire:click="EliminarSemestre">Eliminar</button>
                </div>

            </div>
        </div>
    </div>

</section>
