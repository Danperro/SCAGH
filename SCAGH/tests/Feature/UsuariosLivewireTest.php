<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Livewire\Usuarios\Usuarios;
use App\Models\Usuario;
use App\Models\UsuarioRol;
use App\Models\Rol;

class UsuariosLivewireTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 🧱 El componente Usuarios renderiza correctamente
     */
    public function test_componente_usuarios_renderiza()
    {
        Livewire::test(Usuarios::class)
            ->assertStatus(200);
    }

    /**
     * ✍️ Un ADMIN puede crear un usuario correctamente
     */
    public function test_admin_puede_crear_usuario()
    {
        // 🔐 Crear ADMIN
        Rol::factory()->create([
            'id' => 1,
            'nombre' => 'ADMIN'
        ]);

        $admin = Usuario::factory()->create();

        UsuarioRol::factory()->create([
            'usuario_id' => $admin->id,
            'rol_id' => 1,
            'estado' => 1,
        ]);

        // ▶️ Ejecutar Livewire
        Livewire::actingAs($admin)
            ->test(Usuarios::class)
            ->set('nombre', 'Juan')
            ->set('apellido_paterno', 'Perez')
            ->set('apellido_materno', 'Lopez')
            ->set('dni', '12345678')
            ->set('telefono', '912345678')
            ->set('correo', 'juan@test.com')
            ->set('fecha_nacimiento', now()->subYears(20)->format('Y-m-d'))
            ->set('username', 'juanp')
            ->set('password', '123456')
            ->set('password_confirmation', '123456')
            ->set('email', 'juanuser@test.com')
            ->set('rolesSeleccionados', [1])
            ->call('CrearUsuario')
            ->assertHasNoErrors();

        // ✅ Verificar base de datos
        $this->assertDatabaseHas('usuario', [
            'username' => 'juanp',
            'email' => 'juanuser@test.com',
        ]);

        $this->assertDatabaseHas('persona', [
            'dni' => '12345678',
            'nombre' => 'Juan',
        ]);
    }

    /**
     * 🚫 Validaciones: no debe crear usuario con datos vacíos
     */
    public function test_no_crea_usuario_con_datos_invalidos()
    {
        Livewire::test(Usuarios::class)
            ->set('nombre', '')
            ->call('CrearUsuario')
            ->assertHasErrors([
                'nombre',
                'apellido_paterno',
                'apellido_materno',
                'dni',
                'telefono',
                'correo',
                'username',
                'password',
                'rolesSeleccionados',
            ]);
    }
}
