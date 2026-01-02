<section class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="row g-3 align-items-center mb-4">

        <div class="col-12 col-md-8">
            <div
                class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start text-center text-md-start">
                <i class="bi bi-mortarboard fs-3 text-success"></i>
                <div>
                    <h3 class="fw-bold mb-0">LISTADO DE USUSARIOS</h3>
                    <small class="text-muted">Gestión de Usuarios</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-end gap-2">
                <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario"
                    wire:click="limpiar">
                    <i class="bi bi-plus-circle me-1"></i> Nueva carrera
                </button>

                <button class="btn btn-outline-success" type="button" wire:click="limpiar">
                    <i class="bi bi-eraser me-1"></i> Limpiar Filtros
                </button>
            </div>
        </div>

    </div>


    <!-- Filtros -->
    <div class="card mb-4 shadow-sm p-4">
        <div class="row g-3 align-items-end">

            <!-- Buscar -->
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Buscar por:</label>
                <input class="form-control" type="text" wire:model.live.debounce.500ms="query"
                    placeholder="nombre, apellido, dni o codigo...">
            </div>

            <!-- Filtrar por estado -->
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Rol</label>
                <select class="form-select" wire:model.live="filtrorol">
                    <option value="" hidden>Todos los rol</option>
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtrar por estado -->
            <div class="col-12 col-md-2">
                <label class="form-label fw-semibold">Estado</label>
                <select class="form-select" wire:model.live="filtroestado">
                    <option value="" hidden>Todos los estados</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

        </div>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th class="text-start ps-4">Usuario</th>
                            <th scope="col">Nombre y Apellido</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark">{{ $usuario->username }}</div>
                                </td>
                                <td class="text-center">
                                    {{ $usuario->persona->nombre . ' ' . $usuario->persona->apellido_paterno . ' ' . $usuario->persona->apellido_materno }}
                                </td>
                                <td class="text-center">
                                    @foreach ($usuario->roles as $rol)
                                        <span class="badge bg-primary">{{ $rol->nombre }}</span>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $usuario->estado ? 'bg-success' : 'bg-danger' }}">
                                        {{ $usuario->estado_texto }}
                                    </span>
                                </td>
                                <td class="text-center"><button class="btn btn-warning btn-sm"
                                        wire:click="selectInfo({{ $usuario->id }})" data-bs-toggle="modal"
                                        data-bs-target="#modalEditarUsuario">
                                        <i class="bi bi-pencil-square"></i></button>

                                    <button class="btn btn-danger btn-sm" wire:click="selectInfo({{ $usuario->id }})"
                                        data-bs-toggle="modal" data-bs-target="#modalEliminarUsuario">
                                        <i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No hay Usuarios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $usuarios->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Crear Administrador -->
    <div wire:ignore.self class="modal fade" id="modalCrearUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="CrearUsuario" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>Registrar Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <!-- DNI -->
                        <div class="col-md-4">
                            <label class="form-label">DNI<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('dni') is-invalid @enderror"
                                wire:model.live="dni">
                            @error('dni')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NOMBRE -->
                        <div class="col-md-4">
                            <label class="form-label">Nombre<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                wire:model.live="nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO PATERNO -->
                        <div class="col-md-4">
                            <label class="form-label">Apellido Paterno<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror"
                                wire:model.live="apellido_paterno">
                            @error('apellido_paterno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO MATERNO -->
                        <div class="col-md-4">
                            <label class="form-label">Apellido Materno<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror"
                                wire:model.live="apellido_materno">
                            @error('apellido_materno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- TELEFONO -->
                        <div class="col-md-4">
                            <label class="form-label">Teléfono<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                wire:model.live="telefono">
                            @error('telefono')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CORREO -->
                        <div class="col-md-4">
                            <label class="form-label">Correo<span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror"
                                wire:model.live="correo">
                            @error('correo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FECHA NACIMIENTO -->
                        <div class="col-md-4">
                            <label class="form-label">Fecha de nacimiento<span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                wire:model.live="fecha_nacimiento">
                            @error('fecha_nacimiento')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FILA NUEVA SOLO PARA USERNAME / PASSWORD / CONFIRMAR -->
                        <div class="row g-3 mt-2">

                            <!-- USERNAME -->
                            <div class="col-md-4">
                                <label class="form-label">Usuario<span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror"
                                    wire:model.live="username">
                                @error('username')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- PASSWORD -->
                            <div class="col-md-4">
                                <label class="form-label">Contraseña<span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    wire:model.live="password">
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- CONFIRMAR PASSWORD -->
                            <div class="col-md-4">
                                <label class="form-label">Confirmar Contraseña<span
                                        class="text-danger">*</span></label>
                                <input type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    wire:model.live="password_confirmation">
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- CORREO DE CONFIRMACION -->
                            <div class="col-md-4">
                                <label id="emailLabel" class="form-label">Correo de Recuperacion<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror"
                                    wire:model.live="email">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>


                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" wire:click="limpiar">Cerrar</button>

                    <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading.remove> <i class="bi bi-check2-circle me-1"></i> Guardar</span>
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- Modal Editar Usuario -->
    <div wire:ignore.self class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="EditarUsuario" class="modal-content">

                <div class="modal-header bg-warning bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Editar Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <!-- Información del Usuario -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle fs-3 text-warning me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">
                                    {{ $nombre . ' ' . $apellido_paterno . ' ' . $apellido_materno }}</h6>
                                <small class="text-muted">Información del usuario</small>
                            </div>
                        </div>
                    </div>

                    <!-- CAMPOS USUARIO -->
                    <div class="row g-3">

                        <!-- Username -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-badge me-1"></i>Usuario
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                wire:model.live="username" placeholder="Ingrese el usuario">
                            @error('username')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CORREO DE RECUPERACION -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-envelope me-1"></i>Correo de Recuperación
                                <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                wire:model.live="email" placeholder="correo@ejemplo.com">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <!-- ROLES -->
                    <div class="mt-4">
                        <label class="form-label fw-semibold mb-3">
                            <i class="bi bi-shield-check me-1"></i>Roles del Usuario
                            <span class="text-danger">*</span>
                        </label>

                        <div class="card border">
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach ($roles as $rol)
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="rol_{{ $rol->id }}" value="{{ $rol->id }}"
                                                    wire:model.live="rolesSeleccionados" style="cursor: pointer;">
                                                <label class="form-check-label" for="rol_{{ $rol->id }}"
                                                    style="cursor: pointer;">
                                                    {{ $rol->nombre }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('rolesSeleccionados')
                                    <div class="text-danger small mt-2">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Marca los roles que deseas asignar al usuario. Los roles desmarcados se desactivarán.
                        </small>
                    </div>

                    <!-- CONTRASEÑAS -->
                    <div class="row g-3 mt-3">
                        <div class="col-12">
                            <div class="alert alert-info border-info d-flex align-items-center py-2">
                                <i class="bi bi-info-circle me-2"></i>
                                <small>Solo completa estos campos si deseas cambiar la contraseña del usuario</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-key me-1"></i>Nueva contraseña
                                <small class="text-muted fw-normal">(opcional)</small>
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                wire:model.live="password" placeholder="••••••••">
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-key-fill me-1"></i>Confirmar contraseña
                                <small class="text-muted fw-normal">(opcional)</small>
                            </label>
                            <input type="password"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                wire:model.live="password_confirmation" placeholder="••••••••">
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>

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

    <!-- Modal Eliminar Usuario -->
    <div wire:ignore.self class="modal fade" id="modalEliminarUsuario" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-body text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-trash-fill text-danger fs-1"></i>
                    </div>

                    <h4 class="fw-bold">¿Estas seguro de eliminar este Usuario?</h4>

                    <p class="text-muted">
                        Esta acción es permanente y no podrás recuperarlo.
                    </p>

                    <div class="d-flex gap-3 mt-4">
                        <button class="btn btn-light flex-fill" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-danger flex-fill" wire:click="EliminarUsuario">Eliminar</button>
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
