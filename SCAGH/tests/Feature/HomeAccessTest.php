<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Usuario;
use App\Models\UsuarioRol;

class HomeAccessTest extends TestCase
{
    use RefreshDatabase;

   
    public function test_usuario_no_autenticado_es_redirigido_a_login()
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function usuario_con_password_pendiente_es_redirigido()
    {
        $user = Usuario::factory()->create([
            'must_change_password' => true,
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirect('/cambiar-password');
    }

    public function usuario_valido_puede_ver_home()
    {
        $user = Usuario::factory()->create([
            'must_change_password' => false,
        ]);

        // Rol permitido (1,2,3)
        UsuarioRol::create([
            'usuario_id' => $user->id,
            'rol_id' => 1,
            'estado' => 1,
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }
}
