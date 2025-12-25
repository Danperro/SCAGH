<section class="container-fluid py-4">

    <style>
        /* Botón base */
        .estado-btn {
            font-size: 0.85rem;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            border: none;
        }

        /* Estado seleccionado */
        .estado-btn.asistio {
            background-color: #28a745;
            /* verde */
            color: white;
        }

        .estado-btn.noasistio {
            background-color: #ffc107;
            /* amarillo */
            color: black;
        }

        .estado-btn.justificado {
            background-color: #0d6efd;
            /* azul */
            color: white;
        }

        /* Estado no seleccionado */
        .estado-btn.neutro {
            background-color: #e9ecef;
            color: #6c757d;
        }
    </style>
    <!-- Título -->
    <h2 class="fw-bold mb-1">Asistencia</h2>
    <p class="text-muted mb-4">Realizar Asistencia de los estudiantes</p>


    <div class="card mb-4 shadow-sm p-4">
        <div class="row g-2 align-items-end">

            <!-- Horarios -->
            <div class="col-12 col-md-4">
                <h5 class="fw-bold text-success mb-3">Seleccionar Horario</h5>
                <select class="form-select" wire:model.live="horario_id" wire:change="actualizarHorario">
                    <option value="" hidden>Seleccione</option>
                    @foreach ($horarios as $horario)
                        <option value="{{ $horario->id }}">{{ $horario->nombre }}</option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>

    <!-- Información de la Clase -->
    @if ($curso)
        <div class="card shadow-sm p-4 mb-4">

            <h5 class="fw-bold text-success mb-3">Información de la Clase</h5>

            <div class="row text-center">

                <!-- CURSO -->
                <div class="col-md-2">
                    <div class="d-flex flex-column align-items-center">
                        <div class="p-2 rounded-circle mb-1" style="background: #e6fff3;">
                            <i class="bi bi-book-fill fs-4 text-success"></i>
                        </div>
                        <p class="text-muted mb-0">Curso</p>
                        <h6 class="fw-semibold">{{ $curso }}</h6>
                    </div>
                </div>

                <!-- CARRERA -->
                <div class="col-md-2">
                    <div class="d-flex flex-column align-items-center">
                        <div class="p-2 rounded-circle mb-1" style="background: #e6fff3;">
                            <i class="bi bi-diagram-3-fill fs-4 text-success"></i>
                        </div>
                        <p class="text-muted mb-0">Carrera</p>
                        <h6 class="fw-semibold">{{ $carrera }}</h6>
                    </div>
                </div>

                <!-- FACULTAD -->
                <div class="col-md-2">
                    <div class="d-flex flex-column align-items-center">
                        <div class="p-2 rounded-circle mb-1" style="background: #e6fff3;">
                            <i class="bi bi-building fs-4 text-success"></i>
                        </div>
                        <p class="text-muted mb-0">Facultad</p>
                        <h6 class="fw-semibold">{{ $facultad }}</h6>
                    </div>
                </div>

                <!-- DOCENTE -->
                <div class="col-md-2">
                    <div class="d-flex flex-column align-items-center">
                        <div class="p-2 rounded-circle mb-1" style="background: #e6fff3;">
                            <i class="bi bi-mortarboard-fill fs-4 text-success"></i>
                        </div>
                        <p class="text-muted mb-0">Docente</p>
                        <h6 class="fw-semibold">{{ $docente }}</h6>
                    </div>
                </div>

                <!-- FECHA -->
                <div class="col-md-2">
                    <div class="d-flex flex-column align-items-center">
                        <div class="p-2 rounded-circle mb-1" style="background: #e6fff3;">
                            <i class="bi bi-calendar-event-fill fs-4 text-success"></i>
                        </div>
                        <p class="text-muted mb-0">Fecha</p>
                        <h6 class="fw-semibold">{{ $fechaActual }}</h6>
                    </div>
                </div>

                <!-- HORARIO -->
                <div class="col-md-2">
                    <div class="d-flex flex-column align-items-center">
                        <div class="p-2 rounded-circle mb-1" style="background: #e6fff3;">
                            <i class="bi bi-clock-fill fs-4 text-success"></i>
                        </div>
                        <p class="text-muted mb-0">Horario</p>
                        <h6 class="fw-semibold">{{ $horas }}</h6>
                    </div>
                </div>

            </div>

        </div>
    @endif



    <!-- Tabla de Estudiantes registrados en ese curso -->
    <form wire:submit.prevent="guardarAsistencia">

        <div class="card mb-4">
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>Codigo</th>
                            <th>Estudiante</th>
                            <th>Correo</th>
                            <th>Estado de asistencia</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($EstudianteCursoDocentes as $item)
                            <tr class="text-center">
                                <td>{{ $item->estudiante->codigo }}</td>

                                <td>
                                    {{ $item->estudiante->persona->nombre }}
                                    {{ $item->estudiante->persona->apellido_paterno }}
                                    {{ $item->estudiante->persona->apellido_materno }}
                                </td>

                                <td>{{ $item->estudiante->persona->correo }}</td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- PRESENTE --}}
                                        <button type="button"
                                            class="btn estado-btn
    @if (($asistencia[$item->id] ?? null) == 40) asistio @else neutro @endif"
                                            wire:click="asistenciaSet({{ $item->id }}, 40)">
                                            <i class="bi bi-check-circle-fill me-1"></i> Presente
                                        </button>

                                        {{-- AUSENTE --}}
                                        <button type="button"
                                            class="btn estado-btn
    @if (($asistencia[$item->id] ?? null) == 41) noasistio @else neutro @endif"
                                            wire:click="asistenciaSet({{ $item->id }}, 41)">
                                            <i class="bi bi-x-circle-fill me-1"></i> Ausente
                                        </button>

                                        {{-- JUSTIFICADO --}}
                                        <button type="button"
                                            class="btn estado-btn
    @if (($asistencia[$item->id] ?? null) == 42) justificado @else neutro @endif"
                                            wire:click="asistenciaSet({{ $item->id }}, 42)">
                                            <i class="bi bi-file-earmark-text-fill me-1"></i> Justificado
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <!-- BOTONES DEL FORMULARIO -->
        <div class="d-flex justify-content-end gap-2 mt-3">

            <!-- Botón cancelar -->
            <button type="button" class="btn btn-outline-secondary px-4" wire:click="limpiar">
                Cancelar
            </button>

            <!-- Botón guardar -->
            <button type="submit" class="btn btn-success px-4 fw-semibold">
                Guardar Asistencias
            </button>

        </div>

    </form>


    <!-- TOAST DE GENERAL -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
        <div id="toastGeneral" class="toast align-items-center text-white fw-bold border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastGeneralTexto">

                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- MODAL: NO HAY CURSO ABIERTO -->
    <div wire:ignore.self class="modal fade" id="modalSinCurso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Curso no disponible
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center py-4">
                    <p class="mb-2 fw-semibold">
                        No hay ningún curso abierto en este horario.
                    </p>
                    <p class="text-muted mb-0">
                        Verifique el horario seleccionado o espere el inicio de la clase.
                    </p>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        Entendido
                    </button>
                </div>

            </div>
        </div>
    </div>

</section>
<script>
    document.addEventListener('livewire:initialized', () => {

        // ✅ Listener ÚNICO y reutilizable
        window.addEventListener('abrir-modal', (event) => {

            const modalId = event.detail.id;
            if (!modalId) return;

            const modalEl = document.getElementById(modalId);
            if (!modalEl) {
                console.warn(`Modal no encontrado: ${modalId}`);
                return;
            }

            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        });

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
