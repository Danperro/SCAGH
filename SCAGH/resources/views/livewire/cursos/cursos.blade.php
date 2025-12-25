<section class="container-fluid py-4">

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
                    <i class="bi bi-plus-circle me-1"></i> Nueva curso
                </button>

                <button class="btn btn-outline-success" type="button" wire:click="limpiar">
                    <i class="bi bi-eraser me-1"></i> Limpiar Filtros
                </button>
            </div>
        </div>

    </div>


    <!-- Filtros -->
    <div class="card mb-4 shadow-sm mb-4">
        <div class="card-body p-4">
            <!-- 🔹 Fila 1: Buscar, Ciclo, Botones -->
            <div class="row g-3 align-items-end">

                <!-- Buscar -->
                <div class="col-12 col-md-5">
                    <label class="form-label fw-semibold">Buscar</label>
                    <input class="form-control" type="text" wire:model.live.debounce.500ms="query"
                        placeholder="Buscar curso...">
                </div>

                <!-- Ciclo (FILTRO) -->
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold">Ciclo</label>

                    <select class="form-select" wire:model.live="filtrociclo_id"
                        wire:key="filtro-ciclo-{{ $filtrocarrera_id ?? 'all' }}">
                        <option value="" hidden>Todos los ciclos</option>

                        @foreach ($ciclosFiltro as $ciclo)
                            <option value="{{ $ciclo->id }}">{{ $ciclo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <hr class="my-4">

            <!-- 🔹 Fila 2: Facultad y Carrera -->
            <div class="row g-3 align-items-end">

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
    </div>


    <!-- Tabla -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th class="text-start ps-4">Nombre</th>
                            <th scope="col">Carrera</th>
                            <th scope="col">Ciclo</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cursos as $curso)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark">{{ $curso->nombre }}</div>
                                </td>
                                <td class="text-center">{{ $curso->carrera->nombre }}</td>
                                <td class="text-center">{{ $curso->ciclo->nombre }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $curso->estado ? 'bg-success' : 'bg-danger' }}">
                                        {{ $curso->estado_texto }}
                                    </span>
                                </td>
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
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No hay cursos registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $cursos->links() }}
            </div>
        </div>
    </div>

    <!-- MODAL DE CREAR CURSO -->
    <div wire:ignore.self class="modal fade" id="modalCrearCurso" tabindex="-1" aria-labelledby="modalCrearCurso"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form wire:submit.prevent="CrearCurso" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>Registrar Curso
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"
                        arial-label="close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- SELECT FACULTADES -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Facultades<span class="text-danger">*</span></label>
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
                            <label class="form-label fw-semibold">Carreras<span class="text-danger">*</span></label>
                            <select class="form-select @error('carrera_id') is-invalid @enderror"
                                style="width:100%; white-space:normal;" wire:model.live="carrera_id"
                                @disabled(!$facultad_id)>
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
                                Curso<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.live="nombre"
                                placeholder="Ingrese nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT CÓDIGO -->
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold @error('codigo') is-invalid @enderror">Código<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.live="codigo"
                                placeholder="Ej. EDU101">
                            @error('codigo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- SELECT CICLO -->
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Ciclo<span class="text-danger">*</span></label>
                            <select class="form-select @error('ciclo_id') is-invalid @enderror"
                                wire:model.live="ciclo_id">
                                <option value="" hidden>Selecionar</option>
                                @foreach ($ciclosForm as $ciclo)
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
                        <span wire:loading.remove> <i class="bi bi-check2-circle me-1"></i> Guardar</span>
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
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Editar Curso
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <!-- SELECT FACULTADES -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Facultades<span class="text-danger">*</span></label>
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
                            <label class="form-label fw-semibold">Carreras<span class="text-danger">*</span></label>
                            <select class="form-select @error('carrera_id') is-invalid @enderror"
                                style="width:100%; white-space:normal;" wire:model.live="carrera_id"
                                @disabled(!$facultad_id)>
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
                                Curso<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.live="nombre"
                                placeholder="Ingrese nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT CÓDIGO -->
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold @error('codigo') is-invalid @enderror">Código<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.live="codigo"
                                placeholder="Ej. EDU101">
                            @error('codigo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- SELECT CICLO -->
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Ciclo<span class="text-danger">*</span></label>
                            <select class="form-select @error('ciclo_id') is-invalid @enderror"
                                wire:model.live="ciclo_id">
                                <option value="" hidden>Selecionar</option>
                                @foreach ($ciclosForm as $ciclo)
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
                    <button type="submit"class="btn btn-warning" wire:loading.attr="disabled">
                        <span wire:loading.remove> <i class="bi bi-save2 me-1"></i> Guardar cambios</span>
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
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
