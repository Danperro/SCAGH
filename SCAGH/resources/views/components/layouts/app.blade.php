<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Control de Incidencias</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <style>
        :root {
            --primary-green: #1e6b3a;
            --primary-green-dark: #155529;
            --primary-green-light: #2d8f57;
            --sidebar-bg: #1a1a1a;
            --sidebar-hover: rgba(30, 107, 58, 0.2);
        }

        html,
        body {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            display: flex;
            height: 100vh;
            position: relative;
        }

        .sidebar {
            width: 280px;
            background-color: var(--sidebar-bg);
            transition: transform 0.3s ease-in-out;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1050;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar .nav-link {
            color: #f8f9fa;
            padding: 0.875rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
            border: none;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: var(--sidebar-hover);
            transform: translateX(2px);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background-color: var(--primary-green);
            box-shadow: 0 2px 4px rgba(30, 107, 58, 0.3);
        }

        .sidebar .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* Estilo específico para botones de colapso */
        .collapse-btn {
            color: #f8f9fa;
            padding: 0.875rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
            border: none;
            background: none;
            display: flex;
            align-items: center;
            width: 100%;
            text-align: left;
        }

        .collapse-btn:hover {
            color: #fff;
            background-color: var(--sidebar-hover);
            transform: translateX(2px);
        }

        .collapse-btn i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .collapse-btn .chevron {
            transition: transform 0.3s ease;
        }

        .collapse-btn[aria-expanded="true"] .chevron {
            transform: rotate(180deg);
        }

        .sidebar-heading {
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 1rem 1rem 0.5rem;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 1.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .brand-link {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
        }

        .brand-link i {
            color: var(--primary-green);
        }

        .content-area {
            flex: 1;
            margin-left: 280px;
            padding: 1.5rem;
            overflow-y: auto;
            transition: margin-left 0.3s ease-in-out;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .content-area.expanded {
            margin-left: 0;
        }

        .navbar-toggler {
            display: none;
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1060;
            background-color: var(--primary-green);
            border: none;
            color: white;
            padding: 0.75rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .navbar-toggler:hover,
        .navbar-toggler:focus {
            background-color: var(--primary-green-dark);
            transform: scale(1.05);
            outline: none;
        }

        .navbar-toggler i {
            font-size: 1.25rem;
        }

        .dropdown-menu-dark {
            background-color: #2a2a2a;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-dropdown {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
        }

        .user-dropdown .dropdown-toggle {
            padding: 0.75rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s ease;
        }

        .user-dropdown .dropdown-toggle:hover {
            background-color: var(--sidebar-hover);
        }

        .overlay {
            display: none;
            pointer-events: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }

        .overlay.show {
            display: block;
            pointer-events: auto;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content-area {
                margin-left: 0;
                padding: 4rem 1rem 1rem;
            }

            .navbar-toggler {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
            }

            .content-area {
                padding: 4rem 0.75rem 1rem;
            }
        }

        @media (max-width: 576px) {
            .brand-link .fs-4 {
                font-size: 1.1rem !important;
            }

            .sidebar .nav-link {
                padding: 0.75rem;
                font-size: 0.9rem;
            }

            .content-area {
                padding: 4rem 0.5rem 1rem;
            }
        }

        .mobile-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 90px;
            background-color: var(--sidebar-bg);
            z-index: 1100;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .3);
        }

        .mobile-header-content {
            height: 100%;
            padding: 0 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mobile-header .brand {
            display: flex;
            align-items: center;
            gap: .5rem;
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .mobile-header .brand i {
            color: #1e6b3a;
            font-size: 1.3rem;
        }

        .mobile-header .menu-btn {
            background: #1e6b3a;
            border: none;
            color: #fff;
            padding: .45rem .65rem;
            border-radius: .5rem;
            font-size: 1.4rem;
        }

        /* espacio para que no tape el contenido */
        @media (max-width: 992px) {
            .content-area {
                padding-top: 80px !important;
            }
        }
    </style>
</head>

<body>
    <!-- HEADER FIJO SOLO MÓVIL -->
    <header class="mobile-header d-lg-none">
        <div class="mobile-header-content">
            <div class="brand">
                <i class="bi bi-clock-history"></i>
                <span>SCAGH</span>
            </div>

            <!-- USAMOS EL MISMO BOTÓN -->
            <button id="sidebarToggle" class="menu-btn">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </header>

    <div id="overlay" class="overlay"></div>

    <div class="main-container">

        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="d-flex flex-column" style="height: 100%; overflow-y: auto;">

                <!-- Brand -->
                <div class="brand-link text-center">
                    <a href="/Horarios" class="d-flex flex-column align-items-center text-white text-decoration-none">
                        <span class="fs-3 fw-bold" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Sistema de control de asistencia y gestion de horarios de los laboratorios">
                            <i class="bi bi-clock-history fs-1 me-2"></i>
                            SCAGH
                        </span>
                    </a>
                </div>

                <!-- Navigation -->
                <div class="flex-grow-1">
                    <ul class="nav nav-pills flex-column px-3" id="sidebarAccordion">

                        <!-- SOLO ACTIVAMOS ESTE MÓDULO -->
                        <li class="nav-item">
                            <a href="/Carreras" class="nav-link text-white d-flex align-items-center gap-2">
                                <i class="bi bi-mortarboard"></i> Gestión de Carreras
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/Cursos" class="nav-link text-white d-flex align-items-center gap-2">
                                <i class="bi bi-journal-bookmark"></i> Gestión de Cursos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/Semestres" class="nav-link text-white d-flex align-items-center gap-2">
                                <i class="bi bi-journal-bookmark"></i> Gestión de Semestres
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/Horarios" class="nav-link text-white d-flex align-items-center gap-2">
                                <i class="bi bi-calendar-week"></i> Gestión de Horarios
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/Docentes" class="nav-link text-white d-flex align-items-center gap-2">
                                <i class="bi bi-person-badge"></i> Gestión de Docentes
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/Estudiantes" class="nav-link text-white d-flex align-items-center gap-2">
                                <i class="bi bi-people"></i> Gestión de Estudiantes
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/Asistencias" class="nav-link text-white d-flex align-items-center gap-2">
                                <i class="bi bi-clipboard-check"></i> Realizar Asistencia
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/Reportes" class="nav-link text-white d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-bar-graph"></i> Gestión de Reportes
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/Usuarios" class="nav-link text-white d-flex align-items-center gap-2">
                                <i class="bi bi-people-fill"></i> Gestión de Usuarios
                            </a>
                        </li>



                    </ul>
                </div>

                <!-- User -->
                <div class="user-dropdown">
                    <div class="dropdown">
                        <a href="#"
                            class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5 me-2"></i>
                            <div class="d-flex flex-column">
                                <strong class="small">Usuario</strong>
                                <small class="text-white-50">Invitado</small>
                            </div>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-dark shadow">
                            <li><a class="dropdown-item" href="/Perfil"><i class="bi bi-person me-2"></i>Mi perfil</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </nav>

        <!-- Content area -->
        <div class="content-area">
            {{ $slot }}
        </div>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const contentArea = document.querySelector('.content-area');

            function openMobile() {
                sidebar.classList.add('show');
                overlay.classList.add('show');
            }

            function closeMobile() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }

            function toggleMobile() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            }

            function openDesktop() {
                sidebar.classList.remove('collapsed');
                contentArea?.classList.remove('expanded');
            }

            function closeDesktop() {
                sidebar.classList.add('collapsed');
                contentArea?.classList.add('expanded');
            }

            function toggleDesktop() {
                sidebar.classList.toggle('collapsed');
                contentArea?.classList.toggle('expanded');
            }

            // Click del botón de toggle del sidebar
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (window.innerWidth >= 992) {
                        toggleDesktop();
                    } else {
                        toggleMobile();
                    }
                });
            }

            // Cerrar al tocar overlay en móvil
            overlay?.addEventListener('click', closeMobile);

            // Al redimensionar, limpia estados móviles
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    closeMobile();
                    openDesktop();
                } else {
                    sidebar.classList.remove('collapsed');
                    contentArea?.classList.remove('expanded');
                    closeMobile();
                }
            });

            // Estado inicial
            if (window.innerWidth >= 992) {
                openDesktop();
            } else {
                closeMobile();
            }

            // === Activar link actual (match más específico) ===
            const current = location.pathname.replace(/\/+$/, '') || '/';
            const links = Array.from(document.querySelectorAll('#sidebar a.nav-link[href]'));

            // Limpia estados previos
            links.forEach(a => a.classList.remove('active'));

            function normalize(p) {
                if (!p) return '/';
                p = p.replace(/\/+$/, '');
                return p === '' ? '/' : p;
            }

            function pathMatches(currentPath, href) {
                currentPath = normalize(currentPath);
                href = normalize(href);
                if (currentPath === href) return true;
                // Debe coincidir por segmento completo: /Reportes vs /ReportesIncidencias NO
                // pero /Reportes/Algo SÍ coincide con /Reportes
                return currentPath.startsWith(href + '/');
            }

            // Elige el mejor match por:
            // 1) exacto; si hay varios, el más largo
            // 2) si no hay exacto, el match por segmento con href más largo
            let best = null;
            for (const a of links) {
                const href = a.getAttribute('href');
                if (!href || href === '#') continue;

                if (pathMatches(current, href)) {
                    const exact = normalize(current) === normalize(href);
                    const score = (exact ? 1 : 0) * 1000 + normalize(href).length; // exacto gana
                    if (!best || score > best.score) {
                        best = {
                            el: a,
                            score,
                            href: normalize(href)
                        };
                    }
                }
            }

            // Si no hay match, usa /Control como fallback
            if (!best) {
                best = {
                    el: document.querySelector('#sidebar a.nav-link[href="/Control"]') || null,
                    score: 0
                };
            }

            if (best && best.el) {
                best.el.classList.add('active');

                // Abre su grupo colapsable padre sin crear instancias duplicadas
                const grp = best.el.closest('.collapse');
                if (grp && !grp.classList.contains('show')) {
                    grp.classList.add('show');
                    const triggerBtn = document.querySelector(
                        `[data-bs-toggle="collapse"][data-bs-target="#${grp.id}"]`);
                    if (triggerBtn) triggerBtn.setAttribute('aria-expanded', 'true');
                }
            }


        });
    </script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>


</body>

</html>
