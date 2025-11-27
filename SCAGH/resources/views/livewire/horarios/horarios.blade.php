<div class="container-fluid py-4">

    <!-- Título -->
    <h2 class="fw-bold mb-1">Horarios</h2>
    <p class="text-muted mb-4">Gestión de horarios por laboratorio</p>

    <!-- Filtros -->
    <div class="row g-3 mb-4">

        <!-- Semestre -->
        <div class="col-md-3">
            <label class="form-label fw-semibold">Semestre</label>
            <select class="form-select">
                <option value="">Seleccione</option>
                {{-- Se llenará con la BD --}}
            </select>
        </div>

        <!-- Laboratorio -->
        <div class="col-md-3">
            <label class="form-label fw-semibold">Laboratorio</label>
            <select class="form-select">
                <option value="">Todos los laboratorios</option>
                {{-- Se llenará con la BD --}}
            </select>
        </div>

        <!-- Botón añadir -->
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-success px-4">
                + Añadir Curso
            </button>
        </div>
    </div>

    <!-- Días de la semana -->
    <div class="row g-4">

        <!-- Lunes -->
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Lunes
            </div>
            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                <p class="text-muted text-center">Sin cursos asignados</p>
            </div>
        </div>

        <!-- Martes -->
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Martes
            </div>
            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                <p class="text-muted text-center">Sin cursos asignados</p>
            </div>
        </div>

        <!-- Miércoles -->
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Miércoles
            </div>
            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                <p class="text-muted text-center">Sin cursos asignados</p>
            </div>
        </div>

        <!-- Jueves -->
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Jueves
            </div>
            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                <p class="text-muted text-center">Sin cursos asignados</p>
            </div>
        </div>

        <!-- Viernes -->
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Viernes
            </div>
            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                <p class="text-muted text-center">Sin cursos asignados</p>
            </div>
        </div>

        <!-- Sábado -->
        <div class="col-md-4">
            <div class="bg-success text-white px-3 py-2 rounded-top fw-semibold">
                <i class="bi bi-calendar-event"></i> Sábado
            </div>
            <div class="border rounded-bottom p-3 bg-white shadow-sm">
                <p class="text-muted text-center">Sin cursos asignados</p>
            </div>
        </div>

    </div>

</div>
