<x-layouts.app>
    <div class="container py-5" style="max-width: 520px;">
        <div class="card shadow-sm">
            <div class="card-header fw-bold">
                Cambiar contraseña
            </div>

            <div class="card-body">
                <div class="alert alert-warning">
                    Por seguridad, debes cambiar tu contraseña antes de continuar.
                </div>

                <form method="POST" action="{{ route('password.change.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Contraseña actual</label>
                        <input type="password" name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nueva contraseña</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirmar nueva contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <button class="btn btn-success w-100">
                        Guardar nueva contraseña
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
