<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer contraseña</title>

    <!-- Bootstrap 5 (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="min-vh-100 d-flex align-items-center justify-content-center p-3">
        <div class="card shadow-sm border-0" style="max-width: 520px; width: 100%;">
            <div class="card-body p-4 p-md-5">

                <div class="mb-4">
                    <h1 class="h3 fw-bold mb-2">Restablecer contraseña</h1>
                    <p class="text-muted mb-0">
                        Ingresa tu nueva contraseña para finalizar el proceso.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.update') }}" novalidate>
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Correo <span class="text-danger">*</span>
                        </label>

                        <input type="email" name="email" value="{{ old('email', $email ?? '') }}"
                            class="form-control @error('email') is-invalid @enderror" autocomplete="email" required>

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nueva contraseña <span class="text-danger">*</span>
                        </label>

                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" autocomplete="new-password"
                            required>

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Confirmar contraseña <span class="text-danger">*</span>
                        </label>

                        <input type="password" name="password_confirmation" class="form-control"
                            autocomplete="new-password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">
                        Cambiar contraseña
                    </button>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            ← Volver al login
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
