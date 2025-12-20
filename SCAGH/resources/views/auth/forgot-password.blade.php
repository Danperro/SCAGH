<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar contraseña</title>

    <!-- Bootstrap 5 (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="min-vh-100 d-flex align-items-center justify-content-center p-3">
        <div class="card shadow-sm border-0" style="max-width: 520px; width: 100%;">
            <div class="card-body p-4 p-md-5">

                <div class="mb-4">
                    <h1 class="h3 fw-bold mb-2">Recuperar contraseña</h1>
                    <p class="text-muted mb-0">
                        Escribe tu correo y te enviaremos un enlace para restablecer tu contraseña.
                    </p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success py-2">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">
                            Correo <span class="text-danger">*</span>
                        </label>

                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="tu-correo@dominio.com" required autocomplete="email">

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center justify-content-between gap-3 mt-4">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            ← Volver al login
                        </a>

                        <button type="submit" class="btn btn-primary px-4">
                            Enviar enlace
                        </button>
                    </div>
                </form>

                <hr class="my-4">

                <p class="text-muted small mb-0">
                    Si no encuentras el correo, revisa “Spam” o “Promociones”.
                </p>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS (opcional, por si usas componentes luego) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
