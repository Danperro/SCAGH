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
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
            /* evita que se arrastre el fondo */
            overflow-y: auto;
        }

        body.sidebar-open {
            overflow: hidden !important;
            height: 100vh !important;
            position: fixed;
            width: 100%;
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

        /*aaaa
        /* Sidebar móvil oculto */
    </style>
</head>

<body>
    <div id="overlay" class="overlay"></div>

    <div class="main-container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="d-flex flex-column" style="height: 100%; overflow-y: auto;">

                <!-- Brand -->
                <div class="brand-link text-center">
                    <a href="/Horarios" class="d-flex flex-column align-items-center text-white text-decoration-none">
                        <span class="fs-3 fw-bold">
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
                            <a href="/Horarios" class="nav-link text-white d-flex align-items-center gap-2">
                                <i class="bi bi-clock"></i> Gestión de Horarios
                            </a>
                        </li>

                        <!-- ⛔ Los demás módulos todavía no existen — evitamos errores -->
                        {{-- 
                        <li class="nav-item"><a href="/Control" class="nav-link">Control</a></li>
                        <li class="nav-item"><a href="/Laboratorios" class="nav-link">Laboratorios</a></li>
                        <li class="nav-item"><a href="/Usuarios" class="nav-link">Usuarios</a></li>
                        --}}
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
                            <li>{{-- <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="dropdown-item">
        <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
    </button>
</form> --}}
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
        document.addEventListener("DOMContentLoaded", function() {

            /* -------------------------------------------------------
               VARIABLES GLOBALES
            ------------------------------------------------------- */
            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("overlay");
            const contentArea = document.querySelector(".content-area");
            const sidebarToggle = document.getElementById("sidebarToggle"); // Botón desktop
            const mobileMenuBtn = document.getElementById("mobileMenuBtn"); // Botón móvil (navbar)

            /* -------------------------------------------------------
               FUNCIONES PARA DESKTOP
               (Sidebar fijo a la izquierda)
            ------------------------------------------------------- */

            // Mostrar sidebar grande
            function openDesktop() {
                sidebar.classList.remove("collapsed");
                contentArea?.classList.remove("expanded");
            }

            // Minimizar sidebar en escritorio (modo colapsado)
            function closeDesktop() {
                sidebar.classList.add("collapsed");
                contentArea?.classList.add("expanded");
            }

            // Alternar entre abierto/cerrado
            function toggleDesktop() {
                sidebar.classList.toggle("collapsed");
                contentArea?.classList.toggle("expanded");
            }

            /* -------------------------------------------------------
               FUNCIONES PARA MÓVIL
               (Sidebar deslizable sobre el contenido)
            ------------------------------------------------------- */

            // Mostrar sidebar móvil
            function openMobile() {
                sidebar.classList.add("show");
                overlay.classList.add("show");
                overlay.style.opacity = "1"; // Aseguramos que la opacidad sea 1 al abrir
                overlay.style.display = "block"; // Mostramos el overlay
                document.body.classList.add("sidebar-open");
            }

            // Ocultar sidebar móvil
            function closeMobile() {
                sidebar.classList.remove("show");
                overlay.classList.remove("show");
                overlay.style.opacity = "0"; // Aseguramos que la opacidad sea 0 al cerrarlo
                overlay.style.display = "none"; // También ocultamos el overlay completamente
                document.body.classList.remove("sidebar-open");
            }

            // Alternar sidebar móvil
            function toggleMobile() {
                sidebar.classList.toggle("show");
                overlay.classList.toggle("show");
            }

            /* -------------------------------------------------------
               CONFIGURACIÓN DE BOTONES
            ------------------------------------------------------- */

            // --- Botón hamburguesa (MÓVIL) ---
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener("click", function() {
                    openMobile();
                });
            }

            // --- Botón colapsar/expandir (DESKTOP) ---
            if (sidebarToggle) {
                sidebarToggle.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (window.innerWidth >= 992) {
                        toggleDesktop();
                    } else {
                        toggleMobile();
                    }
                });
            }

            /* -------------------------------------------------------
               CERRAR SIDEBAR AL TOCAR OVERLAY (solo móvil)
            ------------------------------------------------------- */
            overlay?.addEventListener("click", closeMobile);

            /* -------------------------------------------------------
               MANEJO DEL RESIZE DE LA VENTANA
               Evita errores al rotar celular o cambiar tamaño de ventana
            ------------------------------------------------------- */
            function handleResize() {
                if (window.innerWidth >= 992) {
                    closeMobile(); // Por seguridad
                    openDesktop(); // Sidebar fijo
                } else {
                    sidebar.classList.remove("collapsed"); // Evita colapso en móvil
                    contentArea?.classList.remove("expanded");
                    closeMobile();
                }
            }
            window.addEventListener("resize", handleResize);

            // Inicial
            handleResize();

            /* -------------------------------------------------------
               ACTIVAR LINK ACTUAL DEL MENÚ (AUTO-DETECCIÓN DE RUTA)
            ------------------------------------------------------- */
            const current = location.pathname.replace(/\/+$/, '') || '/';
            const links = Array.from(document.querySelectorAll('#sidebar a.nav-link[href]'));

            // Quitar estados previos
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
                return currentPath.startsWith(href + '/');
            }

            let best = null;

            // Buscar coincidencia más precisa
            for (const a of links) {
                const href = a.getAttribute('href');
                if (!href || href === '#') continue;

                if (pathMatches(current, href)) {
                    const exact = normalize(current) === normalize(href);
                    const score = (exact ? 1000 : 0) + normalize(href).length;
                    if (!best || score > best.score) {
                        best = {
                            el: a,
                            score
                        };
                    }
                }
            }

            // Fallback a /Control si no encontró nada
            if (!best) best = {
                el: document.querySelector('#sidebar a.nav-link[href="/Control"]'),
                score: 0
            };

            // Activar enlace
            if (best && best.el) {
                best.el.classList.add("active");

                // Abrir grupo padre (colapsable)
                const grp = best.el.closest(".collapse");
                if (grp && !grp.classList.contains("show")) {
                    grp.classList.add("show");
                    const triggerBtn = document.querySelector(
                        `[data-bs-toggle="collapse"][data-bs-target="#${grp.id}"]`);
                    if (triggerBtn) triggerBtn.setAttribute("aria-expanded", "true");
                }
            }

            /* ============================================================
               GESTO GLOBAL (desde cualquier parte de la pantalla)
               ============================================================ */
            let startX = 0;
            let startY = 0;
            let dragging = false;
            let isHorizontal = false;

            const THRESHOLD = 20; // Qué tan horizontal debe ser el gesto
            const SWIPE_MIN = 60; // Distancia mínima para abrir/cerrar

            document.addEventListener("touchstart", (e) => {
                if (window.innerWidth >= 992) return;

                const t = e.touches[0];
                startX = t.clientX;
                startY = t.clientY;
                dragging = true;
                isHorizontal = false;

                sidebar.style.transition = "none";
                overlay.style.transition = "none";
            });

            function handleTouchMove(e) {
                if (!dragging) return;
                if (window.innerWidth >= 992) return;

                const t = e.touches[0];
                const deltaX = t.clientX - startX;
                const deltaY = Math.abs(t.clientY - startY);

                // Detectar si el gesto es horizontal
                if (!isHorizontal) {
                    if (deltaY > THRESHOLD) {
                        dragging = false;
                        return;
                    }
                    if (Math.abs(deltaX) > THRESHOLD) {
                        isHorizontal = true;
                    }
                }

                if (!isHorizontal) return;

                const sidebarOpen = sidebar.classList.contains("show");

                // Abrir (swipe derecha)
                if (!sidebarOpen) {
                    let pos = Math.min(0, -260 + deltaX);
                    sidebar.style.transform = `translateX(${pos}px)`;
                    overlay.style.opacity = Math.min(1, deltaX / 260);
                    overlay.style.display = "block";
                }
                // Cerrar (swipe izquierda)
                else {
                    let pos = Math.max(-260, deltaX);
                    sidebar.style.transform = `translateX(${pos}px)`;
                    overlay.style.opacity = Math.max(0, 1 - Math.abs(deltaX) / 260);
                }

                e.preventDefault(); // Evita gesto del navegador
            }

            document.addEventListener("touchmove", handleTouchMove, {
                passive: false
            });

            document.addEventListener("touchend", (e) => {
                if (!dragging || !isHorizontal) return;
                dragging = false;

                const endX = e.changedTouches[0].clientX;
                const deltaX = endX - startX;

                sidebar.style.transition = "";
                overlay.style.transition = "";

                const sidebarOpen = sidebar.classList.contains("show");

                if (!sidebarOpen) {
                    if (deltaX > SWIPE_MIN) openMobile();
                    else closeMobile();
                } else {
                    if (deltaX < -SWIPE_MIN) closeMobile();
                    else openMobile();
                }

                sidebar.style.transform = "";
            });


        });
    </script>


</body>

</html>
