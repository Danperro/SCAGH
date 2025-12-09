<section class="container-fluid py-4">
    <!-- Título -->
    <h2 class="fw-bold mb-1">Cursos</h2>
    <p class="text-muted mb-4">Gestión de cursos</p>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm p-4">

        <!-- 🔹 Fila 1: Buscar, Ciclo, Botones -->
        <div class="row g-3 align-items-end">

            <!-- Buscar -->
            <div class="col-12 col-md-5">
                <label class="form-label fw-semibold">Buscar</label>
                <input class="form-control" type="text" wire:model.live.debounce.500ms="query"
                    placeholder="Buscar curso...">
            </div>

            <!-- Ciclo -->
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Ciclo</label>
                <select class="form-select" wire:model.live="filtrociclo_id">
                    <option value="" hidden>Todos los ciclos</option>
                    @foreach ($ciclos as $ciclo)
                        <option value="{{ $ciclo->id }}">{{ $ciclo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Botones -->
            <div class="col-12 col-md-4 d-flex flex-column flex-md-row gap-2">
                <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#modalCrearCurso"
                    wire:click="limpiar">
                    + Crear Curso
                </button>

                <button class="btn btn-outline-success w-100" wire:click="limpiar">
                    Limpiar filtros
                </button>
            </div>

        </div>

        <hr class="my-4">

        <!-- 🔹 Fila 2: Facultad y Carrera -->
        <div class="row g-3">

            <!-- Facultad -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Facultad</label>
                <select class="form-select" wire:model.live="filtrofacultad_id">
                    <option value="" hidden>Todas las facultades</option>
                    @foreach ($facultades as $facultad)
                        <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Carrera -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Carrera</label>
                <select class="form-select" wire:model.live="filtrocarrera_id" @disabled(!$filtrofacultad_id)>
                    <option value="" hidden>Todas las carreras</option>

                    @foreach ($carrerasFiltro as $carrera)
                        <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                    @endforeach

                </select>
            </div>

        </div>

    </div>

    
    <!-- Tabla -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-center">
                        <th scope="col">Nombre</th>
                        <th scope="col">Carrera</th>
                        <th scope="col">Ciclo</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cursos as $curso)
                        <tr>
                            <td>{{ $curso->nombre }}</td>
                            <td>{{ $curso->carrera->nombre }}</td>
                            <td class="text-center">{{ $curso->ciclo->nombre }}</td>
                            <td class="text-center">
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"
                        arial-label="close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- SELECT FACULTADES -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Facultades</label>
                            <select class="form-select @error('facultad_id') is-invalid @enderror"
                                style="width:100%; white-space:normal;" wire:model.live="facultad_id">
                                <option value="" hidden>Seleccionar</option>
                                @foreach ($facultades as $facultad)
                                    <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                                @endforeach
                            </select>
                            @error('facultad_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- SELECT CARRERAS -->
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <!-- SELECT FACULTADES -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Facultades</label>
                            <select class="form-select @error('facultad_id') is-invalid @enderror"
                                style="width:100%; white-space:normal;" wire:model.live="facultad_id">
                                <option value="" hidden>Seleccionar</option>
                                @foreach ($facultades as $facultad)
                                    <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                                @endforeach
                            </select>
                            @error('facultad_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- SELECT CARRERAS -->
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
            // Obtener todos los modales visibles en la página
            const modales = document.querySelectorAll('.modal.show');

            modales.forEach(modal => {
                const instancia = bootstrap.Modal.getInstance(modal);

                // Si no existe instancia (ej. primer uso), la creamos
                const modalBootstrap = instancia ?? new bootstrap.Modal(modal);

                modalBootstrap.hide();
            });
        });

        // Toast éxito
        Livewire.on('toast-exito', (mensaje) => {
            document.getElementById('toastExitoTexto').innerText = mensaje;
            const toast = new bootstrap.Toast(document.getElementById('toastExito'));
            toast.show();
        });

    });
</script>
