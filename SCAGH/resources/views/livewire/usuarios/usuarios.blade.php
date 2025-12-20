<section class="container-fluid py-4">
    <!-- Título -->
    <h2 class="fw-bold mb-1">Usuarios</h2>
    <p class="text-muted mb-4">Gestión de Usuarios</p>


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

            <!-- Botón Crear Usuarios -->
            <div class="col-12 col-md-3 d-flex flex-column gap-2">
                <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario"
                    wire:click="limpiar">+ Crear Administrador</button>

                <button class="btn btn-outline-success w-100" wire:click="limpiar">
                    Limpiar filtros</button>
            </div>

        </div>
    </div>

    <!-- Tabla de Estudiantes -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-center">
                        <th scope="col">Usuario</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td class="text-center">{{ $usuario->username }}</td>
                            <td class="text-center">{{ $usuario->rol->nombre }}</td>
                            <td class="text-center">
                                <span class="badge {{ $usuario->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $usuario->estado_texto }}
                                </span>
                            </td>
                            <td><button class="btn btn-warning btn-sm" wire:click="selectInfo({{ $usuario->id }})"
                                    data-bs-toggle="modal" data-bs-target="#modalEditarUsuario">
                                    <i class="bi bi-pencil-square"></i></button>

                                <button class="btn btn-danger btn-sm" wire:click="selectInfo({{ $usuario->id }})"
                                    data-bs-toggle="modal" data-bs-target="#modalEliminarUsuario">
                                    <i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Crear Administrador -->
    <div wire:ignore.self class="modal fade" id="modalCrearUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="CrearUsuario" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Crear Administrador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <!-- DNI -->
                        <div class="col-md-4">
                            <label class="form-label">DNI</label>
                            <input type="text" class="form-control @error('dni') is-invalid @enderror"
                                wire:model.live="dni">
                            @error('dni')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NOMBRE -->
                        <div class="col-md-4">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                wire:model.live="nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO PATERNO -->
                        <div class="col-md-4">
                            <label class="form-label">Apellido Paterno</label>
                            <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror"
                                wire:model.live="apellido_paterno">
                            @error('apellido_paterno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- APELLIDO MATERNO -->
                        <div class="col-md-4">
                            <label class="form-label">Apellido Materno</label>
                            <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror"
                                wire:model.live="apellido_materno">
                            @error('apellido_materno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- TELEFONO -->
                        <div class="col-md-4">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                wire:model.live="telefono">
                            @error('telefono')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CORREO -->
                        <div class="col-md-4">
                            <label class="form-label">Correo</label>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror"
                                wire:model.live="correo">
                            @error('correo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FECHA NACIMIENTO -->
                        <div class="col-md-4">
                            <label class="form-label">Fecha de nacimiento</label>
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
                                <label class="form-label">Usuario</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror"
                                    wire:model.live="username">
                                @error('username')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- PASSWORD -->
                            <div class="col-md-4">
                                <label class="form-label">Contraseña</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    wire:model.live="password">
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- CONFIRMAR PASSWORD -->
                            <div class="col-md-4">
                                <label class="form-label">Confirmar Contraseña</label>
                                <input type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    wire:model.live="password_confirmation">
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

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

    <!-- Modal Editar Usuario -->
    <div wire:ignore.self class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <form wire:submit.prevent="EditarUsuario" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="limpiar"></button>
                </div>

                <div class="modal-body">

                    {{-- =====================  CAMPOS PERSONA  ===================== --}}
                    <div class="row g-3">

                        <!-- DNI -->
                        <div class="col-md-4">
                            <label class="form-label">DNI</label>
                            <input type="text" class="form-control @error('dni') is-invalid @enderror"
                                wire:model.live="dni">
                            @error('dni')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nombre -->
                        <div class="col-md-4">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                wire:model.live="nombre">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Apellido Paterno -->
                        <div class="col-md-4">
                            <label class="form-label">Apellido Paterno</label>
                            <input type="text"
                                class="form-control @error('apellido_paterno') is-invalid @enderror"
                                wire:model.live="apellido_paterno">
                            @error('apellido_paterno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Apellido Materno -->
                        <div class="col-md-4">
                            <label class="form-label">Apellido Materno</label>
                            <input type="text"
                                class="form-control @error('apellido_materno') is-invalid @enderror"
                                wire:model.live="apellido_materno">
                            @error('apellido_materno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div class="col-md-4">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                wire:model.live="telefono">
                            @error('telefono')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Correo -->
                        <div class="col-md-4">
                            <label class="form-label">Correo</label>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror"
                                wire:model.live="correo">
                            @error('correo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fecha de nacimiento -->
                        <div class="col-md-4">
                            <label class="form-label">Fecha de nacimiento</label>
                            <input type="date"
                                class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                wire:model.live="fecha_nacimiento">
                            @error('fecha_nacimiento')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>


                    {{-- =====================  CAMPOS USUARIO  ===================== --}}
                    <div class="row g-3 mt-3">

                        <!-- Username -->
                        <div class="col-md-4">
                            <label class="form-label">Usuario</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                wire:model.live="username">
                            @error('username')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Rol -->
                        <div class="col-md-4">
                            <label class="form-label">Rol</label>
                            <select class="form-select @error('rol_id') is-invalid @enderror"
                                wire:model.live="rol_id" wire:change="actualizarCamposPorRol">
                                <option value="" hidden>Seleccione</option>
                                <option value="1">Administrador</option>
                                <option value="2">Docente</option>
                                <option value="3">Estudiante</option>
                            </select>
                            @error('rol_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                Nueva contraseña
                                <small class="text-muted">(opcional)</small>
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                wire:model.live="password">
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Confirmar contraseña
                                <small class="text-muted">(opcional)</small>
                            </label>
                            <input type="password"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                wire:model.live="password_confirmation">
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    {{-- =====================  CAMPOS DINÁMICOS SEGÚN ROL  ===================== --}}

                    {{-- DOCENTE --}}
                    @if ($rol_id == 2)
                        <div class="row g-3 mt-3">

                            <div class="col-md-4">
                                <label class="form-label">Especialidad</label>
                                <select class="form-select @error('especialidad_id') is-invalid @enderror"
                                    wire:model.live="especialidad_id">
                                    <option value="">Seleccione</option>
                                    @foreach ($especialidades as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('especialidad_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    @endif


                    {{-- ESTUDIANTE --}}
                    @if ($rol_id == 3)
                        <div class="row g-3 mt-3">

                            <div class="col-md-4">
                                <label class="form-label">Código</label>
                                <input type="text" class="form-control @error('codigo') is-invalid @enderror"
                                    wire:model.live="codigo">
                                @error('codigo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Carrera</label>
                                <select class="form-select @error('carrera_id') is-invalid @enderror"
                                    wire:model.live="carrera_id">
                                    <option value="">Seleccione</option>
                                    @foreach ($carreras as $car)
                                        <option value="{{ $car->id }}">{{ $car->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('carrera_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    @endif

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
