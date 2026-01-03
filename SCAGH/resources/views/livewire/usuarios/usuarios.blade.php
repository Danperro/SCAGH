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
            font-size: 1rem;
            flex-shrink: 0;
        }
    </style>
    {{-- HEADER --}}
    <div class="row g-3 align-items-center mb-4">

        <div class="col-12 col-md-8">
            <div
                class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start text-center text-md-start">
                <i class="bi bi-people fs-3 text-success"></i>
                <div>
                    <h3 class="fw-bold mb-0">LISTADO DE USUARIOS</h3>
                    <small class="text-muted">Gestión de Usuarios del Sistema</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-end gap-2">
                <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario"
                    wire:click="limpiar">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Usuario
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
                <div class="col-12 col-lg-5">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-search me-1"></i>Buscar
                    </label>
                    <input class="form-control" type="text" wire:model.live.debounce.500ms="query"
                        placeholder="Nombre, apellido, DNI o usuario...">
                </div>

                <!-- FILTRAR POR ROL -->
                <div class="col-12 col-lg-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-person-badge me-1"></i>Rol
                    </label>
                    <select class="form-select" wire:model.live="filtrorol">
                        <option value="" hidden>Todos los roles</option>
                        @foreach ($rolesfiltro as $rol)
                            <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- FILTRAR POR ESTADO -->
                <div class="col-12 col-lg-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-toggle-on me-1"></i>Estado
                    </label>
                    <select class="form-select" wire:model.live="filtroestado">
                        <option value="">Todos</option>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

            </div>

        </div>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="card shadow-sm">

        <!-- Header de la tabla -->
        <div class="card-header bg-white border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-table fs-4 text-success me-2"></i>
                    <h6 class="fw-bold mb-0">Lista de Usuarios</h6>
                </div>
                <span class="badge bg-primary">
                    Total: {{ $usuarios->total() }} usuario(s)
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-3">
                                <i class="bi bi-person-circle me-1"></i>Usuario
                            </th>
                            <th scope="col" class="text-center">
                                <i class="bi bi-person-vcard me-1"></i>DNI
                            </th>
                            <th scope="col">
                                <i class="bi bi-person me-1"></i>Nombre y Apellido
                            </th>
                            <th scope="col" class="text-center">
                                <i class="bi bi-person-badge me-1"></i>Rol
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
                        @forelse ($usuarios as $usuario)
                            <tr>
                                <!-- USUARIO -->
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-success bg-opacity-10 text-success me-2">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">
                                                {{ $usuario->username }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $usuario->email ?? 'Sin email' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <!-- DNI -->
                                <td class="text-center">
                                    <span class="font-monospace">{{ $usuario->persona->dni }}</span>
                                </td>

                                <!-- NOMBRE Y APELLIDO -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $usuario->persona->nombre . ' ' . $usuario->persona->apellido_paterno }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $usuario->persona->apellido_materno }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <!-- ROL -->
                                <td class="text-center">
                                    @foreach ($usuario->roles as $rol)
                                        <span class="badge bg-primary">
                                            <i class="bi bi-shield-check me-1"></i>{{ $rol->nombre }}
                                        </span>
                                    @endforeach
                                </td>

                                <!-- ESTADO -->
                                <td class="text-center">
                                    <span class="badge {{ $usuario->estado ? 'bg-success' : 'bg-danger' }}">
                                        <i
                                            class="bi {{ $usuario->estado ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                        {{ $usuario->estado_texto }}
                                    </span>
                                </td>

                                <!-- ACCIONES -->
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-warning"
                                            wire:click="selectInfo({{ $usuario->id }})" data-bs-toggle="modal"
                                            data-bs-target="#modalEditarUsuario" title="Editar usuario">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-outline-danger"
                                            wire:click="selectInfo({{ $usuario->id }})" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminarUsuario" title="Eliminar usuario">
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
                                        <h6 class="fw-semibold">No hay usuarios registrados</h6>
                                        <p class="mb-0 small">Comienza agregando un nuevo usuario</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if ($usuarios->hasPages())
                <div class="card-footer bg-white border-top">
                    {{ $usuarios->links() }}
                </div>
            @endif
        </div>
    </div>



    <!-- Modal Crear Usuario -->
    <div wire:ignore.self class="modal fade" id="modalCrearUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="CrearUsuario" class="modal-content">

                <!-- HEADER estilo como Editar -->
                <div class="modal-header bg-success bg-opacity-10">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>Registrar Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <!-- Bloque informativo -->
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-plus-fill fs-3 text-success me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Datos del usuario</h6>
                                <small class="text-muted">Completa la información personal y la cuenta de
                                    acceso</small>
                            </div>
                        </div>
                    </div>

                    <!-- DATOS PERSONALES -->
                    <div class="row g-3">

                        <!-- DNI -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-vcard me-1"></i>DNI <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('dni') is-invalid @enderror"
                                wire:model.live="dni" placeholder="8 dígitos">
                            @error('dni')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NOMBRE -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person me-1"></i>Nombre <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                wire:model.live="nombre" placeholder="Ingrese el nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO PATERNO -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-badge me-1"></i>Apellido Paterno <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror"
                                wire:model.live="apellido_paterno" placeholder="Ingrese el apellido paterno">
                            @error('apellido_paterno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO MATERNO -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-badge-fill me-1"></i>Apellido Materno <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror"
                                wire:model.live="apellido_materno" placeholder="Ingrese el apellido materno">
                            @error('apellido_materno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- TELEFONO -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-telephone me-1"></i>Teléfono <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                wire:model.live="telefono" placeholder="9XXXXXXXX">
                            @error('telefono')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CORREO -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-envelope me-1"></i>Correo <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror"
                                wire:model.live="correo" placeholder="correo@ejemplo.com">
                            @error('correo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FECHA NACIMIENTO -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-event me-1"></i>Fecha de nacimiento <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                wire:model.live="fecha_nacimiento">
                            @error('fecha_nacimiento')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- CREDENCIALES -->
                    <div class="row g-3">

                        <!-- USERNAME -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-badge me-1"></i>Usuario <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                wire:model.live="username" placeholder="Ingrese el usuario">
                            @error('username')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- PASSWORD -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-key me-1"></i>Contraseña <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                wire:model.live="password" placeholder="••••••••">
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CONFIRMAR PASSWORD -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-key-fill me-1"></i>Confirmar contraseña <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="password"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                wire:model.live="password_confirmation" placeholder="••••••••">
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CORREO DE RECUPERACION -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-envelope-check me-1"></i>Correo de Recuperación <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                wire:model.live="email" placeholder="correo@ejemplo.com">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-info-circle me-1"></i>Se usará para recuperar la contraseña.
                            </small>
                        </div>

                    </div>

                </div>

                <!-- FOOTER estilo como Editar -->
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
