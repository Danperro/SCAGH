<section class="container-fluid py-4">
    <!-- Título -->
    <h2 class="fw-bold mb-1">Reportes</h2>
    <p class="text-muted mb-4">Reporetes de los docentes del uso de los laboratorios</p>

    <!-- ================== FILTROS ================== -->
    <div class="card mb-4 shadow-sm p-4">

        {{-- FILA 1 : BÚSQUEDA Y FILTROS GENERALES --}}
        <div class="row g-3 align-items-end">

            <!-- Buscar docente -->
            <div class="col-12 col-md-5">
                <label class="form-label fw-semibold">Buscar</label>
                <input type="text" class="form-control" wire:model.live.debounce.500ms="query"
                    placeholder="Buscar docente...">
            </div>

            <!-- Laboratorio -->
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Laboratorio</label>
                <select class="form-select" wire:model.live="filtrolaboratorio_id">
                    <option value="" hidden>Todos los laboratorios</option>
                    @foreach ($laboratorios as $lab)
                        <option value="{{ $lab->id }}">{{ $lab->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Semestre -->
            <div class="col-12 col-md-2">
                <label class="form-label fw-semibold">Semestre</label>
                <select class="form-select" wire:model.live="filtrosemetre_id">
                    <option value="" hidden>Todos</option>
                    @foreach ($semestres as $sem)
                        <option value="{{ $sem->id }}">{{ $sem->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Limpiar filtros -->
            <div class="col-12 col-md-2 d-grid">
                <button type="button" class="btn btn-outline-success" wire:click="limpiar">
                    Limpiar filtros
                </button>
            </div>

        </div>

        <hr class="my-4">

        {{-- FILA 2 : JERARQUÍA ACADÉMICA --}}
        <div class="row g-3 justify-content-center">

            <!-- Facultad -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Facultad</label>
                <select class="form-select" wire:model.live="filtrofacultad_id">
                    <option value="" hidden>Todas las facultades</option>
                    @foreach ($facultades as $fac)
                        <option value="{{ $fac->id }}">{{ $fac->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Carrera (depende de Facultad) -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Carrera</label>
                <select class="form-select" wire:model.live="filtrocarrera_id" wire:key="filtrofacultad_id"
                    @disabled(!$filtrofacultad_id)>
                    <option value="" hidden>Todas las carreras</option>
                    @foreach ($carreras as $car)
                        <option value="{{ $car->id }}">{{ $car->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Curso (depende de Carrera) -->
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Curso</label>
                <select class="form-select" wire:model.live="filtrocurso_id" wire:key="filtrocarrera_id"
                    @disabled(!$filtrocarrera_id)>
                    <option value="" hidden>Todos los cursos</option>
                    @foreach ($cursos as $cur)
                        <option value="{{ $cur->id }}">{{ $cur->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Fecha inicio -->
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Fecha inicio</label>
                <input type="date" class="form-control" wire:model.live="fecha_inicio">
            </div>

            <!-- Fecha fin -->
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Fecha fin</label>
                <input type="date" class="form-control" wire:model.live="fecha_fin">
            </div>
        </div>

        <hr class="my-4">
    </div>
    <!-- ================ FIN FILTROS ================= -->


    <!-- Tabla de reportes-->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-center">
                        <th scope="col">Docente</th>
                        <th scope="col">Curso</th>
                        <th scope="col">Laboratorio</th>
                        <th scope="col">fecha</th>
                        <th scope="col">Descargar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($asistencias as $asistencia)
                        <tr>
                            <td class="text-center">
                                {{ $asistencia->horariocursodocente->docenteCurso->docente->persona->nombre .
                                    ' ' .
                                    $asistencia->horariocursodocente->docenteCurso->docente->persona->apellido_paterno .
                                    ' ' .
                                    $asistencia->horariocursodocente->docenteCurso->docente->persona->apellido_materno }}
                            </td>
                            <td class="text-center">{{ $asistencia->horariocursodocente->docenteCurso->curso->nombre }}
                            </td>
                            <td class="text-center">
                                {{ $asistencia->horariocursodocente->horario->laboratorio->nombre }}
                            </td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($asistencia->fecha_registro)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('ReporteAsistencia.pdf', $asistencia->id) }}"
                                    class="btn btn-success btn-sm" title="Descargar reporte de asistencia"
                                    target="_blank">
                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
