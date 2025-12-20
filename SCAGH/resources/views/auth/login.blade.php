<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }

        .login-box {
            background: rgba(20, 25, 23, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.10);
            padding: 2.5rem 2rem;
            border: 1px solid rgba(255, 255, 255, 0.10);
            transition: transform .3s ease, box-shadow .3s ease;
            animation: fadeIn .6s ease-out;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-icon {
            width: 200px;
            height: 200px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.20);
            overflow: hidden;
        }

        .login-icon img {
            width: 200px;
            height: 200px;
            object-fit: contain;
            display: block;
        }

        .login-title {
            color: #fff;
            font-weight: 800;
            font-size: 1.8rem;
            margin: 0;
        }

        .login-subtitle {
            color: rgba(255, 255, 255, 0.75);
            font-size: .95rem;
            margin-top: .5rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.2rem;
        }

        .form-label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.90);
            font-size: .9rem;
            margin-bottom: .5rem;
        }

        .form-control {
            border: 2px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: .75rem 1rem .75rem 3rem;
            font-size: 1rem;
            transition: all .3s ease;
            background-color: rgba(255, 255, 255, 0.06);
            color: #fff;
            padding-left: 3.6rem !important;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.45);
        }

        .form-control:focus {
            border-color: #20c997;
            box-shadow: 0 0 0 .2rem rgba(32, 201, 151, 0.18);
            background-color: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: calc(50% + 12px);
            transform: translateY(-50%);
            font-size: 1.05rem;
            z-index: 5;
            color: #20c997 !important;
            background: rgba(32, 201, 151, 0.15);
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-login {
            background: linear-gradient(135deg, #198754, #20c997);
            border: none;
            border-radius: 12px;
            padding: .75rem 2rem;
            font-weight: 800;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            transition: all .3s ease;
            width: 100%;
            color: #fff;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(25, 135, 84, 0.40);
            background: linear-gradient(135deg, #157347, #1aa179);
        }

        .links-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: .25rem;
            margin-bottom: 1.25rem;
        }

        .form-check-label {
            color: #fff !important;
            font-weight: 600;
            font-size: .92rem;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: .18rem;
            border: 2px solid rgba(255, 255, 255, 0.45);
            background-color: transparent;
        }

        .form-check-input:checked {
            background-color: #20c997;
            border-color: #20c997;
        }

        .links-row a {
            color: #74c0fc;
        }

        .links-row a:hover {
            color: #a5d8ff;
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem;
            margin-bottom: 1.25rem;
            font-size: .9rem;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.12);
            color: #ffb3b8;
            border: 1px solid rgba(220, 53, 69, 0.25);
            border-left: 4px solid #dc3545;
        }

        /* ✅ NUEVO: alerta verde para status */
        .alert-success {
            background: rgba(32, 201, 151, 0.12);
            color: #bff5e6;
            border: 1px solid rgba(32, 201, 151, 0.25);
            border-left: 4px solid #20c997;
        }

        .invalid-feedback {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-box">

            <div class="login-header">
                <div class="login-icon">
                    <img src="{{ asset('images/cuc.png') }}" alt="CUC Logo">
                </div>

                <h4 class="login-title">Iniciar sesión</h4>
                <p class="login-subtitle">Accede con tu usuario y contraseña</p>
            </div>

            {{-- ✅ Mensaje verde (por ejemplo: "Tu contraseña fue restablecida...") --}}
            @if (session('status'))
                <div class="alert alert-success">
                    <i class="fas fa-circle-check me-2"></i>
                    <strong>Listo:</strong> {{ session('status') }}
                </div>
            @endif

            {{-- Error de login --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Error:</strong> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" novalidate>
                @csrf

                <div class="form-group">
                    <label for="username" class="form-label">Usuario</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}"
                        class="form-control @error('username') is-invalid @enderror" placeholder="Ingresa tu usuario"
                        autocomplete="username" autofocus required>
                    <i class="fas fa-user input-icon"></i>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <input id="password" type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Ingresa tu contraseña"
                        autocomplete="current-password" required>
                    <i class="fas fa-lock input-icon"></i>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="links-row">
                    <div class="form-check m-0 d-flex align-items-center gap-2">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Recordarme</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a class="small text-decoration-none" href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Ingresar
                </button>
            </form>

        </div>
    </div>
</body>

</html>
