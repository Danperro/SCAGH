<section class="container-fluid py-4">
    <!-- Título -->
    <h2 class="fw-bold mb-1">Semestres</h2>
    <p class="text-muted mb-4">Gestión de semestres</p>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm p-4">

        <!-- 🔹 Fila 1: Buscar, Ciclo, Botones -->
        <div class="row g-3 align-items-end">

            <!-- BUSCAR -->
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Buscar</label>
                <input type="text" class="form-control" wire:model.live.debounce.500ms="query"
                    placeholder="Buscar semestre...">
            </div>

            <!-- FECHA INICIO -->
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Desde</label>
                <input type="date" class="form-control" wire:model.live="filtroFechaInicio">
            </div>

            <!-- FECHA FIN -->
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Hasta</label>
                <input type="date" class="form-control" wire:model.live="filtroFechaFin">
            </div>


            <!-- Botones -->
            <div class="col-12 col-md-2 d-flex flex-column gap-2">
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
                    <tr class="text-center">
                        <th scope="col">Nombre</th>
                        <th scope="col">Fecha Inicio</th>
                        <th scope="col">Fecha Fin</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($semestres as $semestre)
                        <tr class="text-center">
                            <td>{{ $semestre->nombre }}</td>
                            <td>{{ optional(\Carbon\Carbon::parse($semestre->fecha_inicio))->format('d/m/Y') }}</td>
                            <td>{{ optional(\Carbon\Carbon::parse($semestre->fecha_fin))->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <span class="badge {{ $semestre->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $semestre->estado_texto }}
                                </span>
                            </td>
                            <td class="text-center">
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
        <div class="modal-dialog modal-dialog-centered ">
            <form wire:submit.prevent="CrearSemestre" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalCrearSemestreLabel">Crear Semestre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"
                        arial-label="close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- INPUT NOMBRE -->
                        <div class="col-12 col-md-12">
                            <label class="form-label fw-semibold @error('nombre') is-invalid @enderror">Nombre del
                                Semestre</label>
                            <input type="text" class="form-control" wire:model.live="nombre"
                                placeholder="Ej. Semestre I 2025">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT FECHA INICIO -->
                        <div class="col-12 col-md-12">
                            <label class="form-label fw-semibold @error('fecha_inicio') is-invalid @enderror">Fecha de
                                Inicio</label>
                            <input type="date" class="form-control" wire:model.live="fecha_inicio">
                            @error('fecha_inicio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT FECHA FIN -->
                        <div class="col-12 col-md-12">
                            <label class="form-label fw-semibold @error('fecha_fin') is-invalid @enderror">Fecha de
                                Fin</label>
                            <input type="date" class="form-control" wire:model.live="fecha_fin">
                            @error('fecha_fin')
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
        <div class="modal-dialog modal-dialog-centered">
            <form wire:submit.prevent="EditarSemestre" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Editar Semestre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <!-- INPUT NOMBRE -->
                        <div class="col-12 col-md-12">
                            <label class="form-label fw-semibold @error('nombre') is-invalid @enderror">Nombre del
                                Semestre</label>
                            <input type="text" class="form-control" wire:model.live="nombre"
                                placeholder="Ej. Semestre I 2025">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT FECHA INICIO -->
                        <div class="col-12 col-md-12">
                            <label class="form-label fw-semibold @error('fecha_inicio') is-invalid @enderror">Fecha de
                                Inicio</label>
                            <input type="date" class="form-control" wire:model.live="fecha_inicio">
                            @error('fecha_inicio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT FECHA FIN -->
                        <div class="col-12 col-md-12">
                            <label class="form-label fw-semibold @error('fecha_fin') is-invalid @enderror">Fecha de
                                Fin</label>
                            <input type="date" class="form-control" wire:model.live="fecha_fin">
                            @error('fecha_fin')
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
