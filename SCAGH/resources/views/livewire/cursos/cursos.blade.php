<section class="container-fluid py-4">
    <!-- Título -->
    <h2 class="fw-bold mb-1">Cursos</h2>
    <p class="text-muted mb-4">Gestión de cursos</p>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm p-4">
        <div class="row g-2 align-items-end">
            <!-- Buscar -->
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Buscar</label>
                <input class="form-control" type="text" id="query" wire:model.live="query">
            </div>

            <!-- Facultad -->
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Facultad</label>
                <select class="form-select" id="facultad_id" wire:model.live="facultad_id">
                    <option value="" hidden>Todos las facultades</option>
                    @foreach ($facultades as $facultad)
                        <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Carrera-->
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Carrera</label>
                <select class="form-select" id="carrera_id" wire:model.live="carrera_id">
                    <option value="" hidden>Todas las carreras</option>
                    @foreach ($carreras as $carrera)
                        <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Botón Crear nuevo Curso -->
            <div class="col-12 col-md-3">
                <button class="btn btn-success " data-bs-target="#modalCrearCurso" data-bs-toggle="modal"
                    wire:click="limpiar">
                    + Crear Curso
                </button>
            </div>
            <!-- Botón Limpiar filtro -->
            <div class="col-12 col-md-3">
                <button class="btn btn-success " wire:click="limpiar">
                    limpiar filtros
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-centered">
                        <th scope="col">nombre</th>
                        <th scope="col">carrera</th>
                        <th scope="col">ciclo</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cursos as $curso)
                        <tr>
                            <td>{{ $curso->nombre }}</td>
                            <td>{{ $curso->carrera->nombre }}</td>
                            <td>{{ $curso->ciclo->nombre }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" wire:click="selectInfo({{ $curso->id }})"
                                    data-bs-toggle="modal" data-bs-target="#modalEditarCurso">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" wire:click="selectInfo({{ $curso->id }})"
                                    data-bs-toggle="modal" data-bs-target="#modalEliminarCurso">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL DE CREAR CURSO -->
    <div wire:ignore.self class="modal fade" id="modalCrearCurso" tabindex="-1" aria-labelledby="modalCrearCurso"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form wire:submit.prevent="CrearCurso" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalCrearCursoLabel">Crear Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" arial-label="close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Carreras</label>
                            <select class="form-select @error('carrera_id') is-invalid @enderror"
                                style="width:100%; white-space:normal;" wire:model.live="carrera_id">
                                <option value="" hidden>Seleccionar</option>
                                @foreach ($carreras as $carrera)
                                    <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                                @endforeach
                            </select>
                            @error('carrera_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- INPUT NOMBRE -->
                        <div class="col-12 col-md-5">
                            <label class="form-label fw-semibold @error('nombre') is-invalid @enderror">Nombre del
                                Curso</label>
                            <input type="text" class="form-control" wire:model.live="nombre"
                                placeholder="Ingrese nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT CÓDIGO -->
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold @error('codigo') is-invalid @enderror">Código</label>
                            <input type="text" class="form-control" wire:model.live="codigo"
                                placeholder="Ej. EDU101">
                            @error('codigo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- SELECT CICLO -->
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Ciclo</label>
                            <select class="form-select @error('ciclo_id') is-invalid @enderror"
                                wire:model.live="ciclo_id">
                                <option value="" hidden>Selecionar</option>
                                @foreach ($ciclos as $ciclo)
                                    <option value="{{ $ciclo->id }}">
                                        {{ $ciclo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ciclo_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" wire:click="limpiar">Cerrar</button>
                    <button type="submit"class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading.remove>Guardar</span>
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DE EDITAR CURSO -->
    <div wire:ignore.self class="modal fade" id="modalEditarCurso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form wire:submit.prevent="EditarCurso" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Editar Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <!-- Select Carrera -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Carrera</label>
                            <select class="form-select" wire:model.live="carrera_id">
                                <option value="" hidden>Seleccionar</option>
                                @foreach ($carreras as $car)
                                    <option value="{{ $car->id }}">{{ $car->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Nombre</label>
                            <input class="form-control" wire:model.live="nombre">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Código</label>
                            <input class="form-control" wire:model.live="codigo">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Ciclo</label>
                            <select class="form-select" wire:model.live="ciclo_id">
                                <option hidden>Seleccionar</option>
                                @foreach ($ciclos as $cx)
                                    <option value="{{ $cx->id }}">{{ $cx->nombre }}</option>
                                @endforeach
                            </select>
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


    <!-- MODAL DE ELIMINAR CURSO -->
    <div wire:ignore.self class="modal fade" id="modalEliminarCurso" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Eliminar Curso</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="fs-5">¿Estás seguro de eliminar este curso?</p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-danger" wire:click="EliminarCurso">Eliminar</button>
                </div>

            </div>
        </div>
    </div>

    <!-- TOAST DE ÉXITO -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
        <div id="toastExito" class="toast align-items-center text-bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastExitoTexto">
                    <!-- Texto dinámico -->
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
            const modalCrear = bootstrap.Modal.getInstance(document.getElementById('modalCrearCurso'));
            if (modalCrear) modalCrear.hide();

            const modalEditar = bootstrap.Modal.getInstance(document.getElementById(
            'modalEditarCurso'));
            if (modalEditar) modalEditar.hide();

            const modalEliminar = bootstrap.Modal.getInstance(document.getElementById(
                'modalEliminarCurso'));
            if (modalEliminar) modalEliminar.hide();
        });

        // Toast éxito
        Livewire.on('toast-exito', (mensaje) => {
            document.getElementById('toastExitoTexto').innerText = mensaje;
            const toast = new bootstrap.Toast(document.getElementById('toastExito'));
            toast.show();
        });

    });
</script>
