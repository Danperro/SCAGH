<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Livewire\Carreras\Carreras;
use App\Models\Carrera;
use App\Models\Catalogo;
use App\Models\Usuario;
use App\Models\UsuarioRol;
use App\Models\Rol;
use App\Models\Persona;

class CarrerasLivewireTest extends TestCase
{
    use RefreshDatabase;

    
    private function loginComoAdmin(): Usuario
    {
        // Rol catálogo
        $rol = Rol::create([
            'nombre' => 'ADMIN',
            'estado' => 1,
        ]);



        // Persona requerida por FK (persona_id NOT NULL)
        $persona = Persona::create([
            'nombre' => 'ADMIN',
            'apellido_paterno' => 'TEST',
            'apellido_materno' => 'SYSTEM',
            'dni' => '00000001',
            'telefono' => '999999999',
            'correo' => 'admin.persona@test.com',
            'fecha_nacimiento' => '2000-01-01',
        ]);

        // Usuario admin apuntando a persona_id
        $admin = Usuario::create([
            'persona_id' => $persona->id,
            'username' => 'admin',
            'password' => bcrypt('123456'),
            'email' => 'admin@test.com',
            'estado' => 1,
            'must_change_password' => false,
        ]);

        // Relación usuario-rol
        UsuarioRol::create([
            'usuario_id' => $admin->id,
            'rol_id' => $rol->id, // 👈 ID REAL
            'estado' => 1,
        ]);

        return $admin;
    }

    public function test_componente_carreras_renderiza()
    {
        $admin = $this->loginComoAdmin();

        Livewire::actingAs($admin)
            ->test(Carreras::class)
            ->assertStatus(200);
    }

    public function test_admin_puede_crear_carrera()
    {
        $admin = $this->loginComoAdmin();

        $padreFacultad = Catalogo::create([
            'padre_id' => null,
            'nombre' => 'FACULTADES',
            'estado' => 1,
        ]);

        $facultad = Catalogo::create([
            'padre_id' => $padreFacultad->id,
            'nombre' => 'FACULTAD DE INGENIERIA',
            'estado' => 1,
        ]);


        Livewire::actingAs($admin)
            ->test(Carreras::class)
            ->set('facultadId', $facultad->id)
            ->set('nombre', 'Ingenieria de Sistemas')
            ->set('ciclosTotal', 12)
            ->set('estado', 1)
            ->call('CrearCarrera')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('carrera', [
            'facultad_id' => $facultad->id,
            'nombre' => 'INGENIERIA DE SISTEMAS',
            'ciclos_total' => 12,
            'estado' => 1,
        ]);
    }

    public function test_admin_puede_editar_carrera()
    {
        $admin = $this->loginComoAdmin();

        $padreFacultad = Catalogo::create([
            'padre_id' => null,
            'nombre' => 'FACULTADES',
            'estado' => 1,
        ]);

        $facultad = Catalogo::create([
            'padre_id' => $padreFacultad->id,
            'nombre' => 'FACULTAD DE INGENIERIA',
            'estado' => 1,
        ]);


        $carrera = Carrera::create([
            'facultad_id' => $facultad->id,
            'nombre' => 'BIOLOGIA',
            'ciclos_total' => 10,
            'estado' => 1,
        ]);

        Livewire::actingAs($admin)
            ->test(Carreras::class)
            ->set('carreraId', $carrera->id)
            ->set('facultadId', $facultad->id)
            ->set('nombre', 'Biologia Marina')
            ->set('ciclosTotal', 12)
            ->set('estado', 1)
            ->call('EditarCarrera')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('carrera', [
            'id' => $carrera->id,
            'nombre' => 'BIOLOGIA MARINA',
            'ciclos_total' => 12,
        ]);
    }

    public function test_admin_puede_eliminar_carrera()
    {
        $admin = $this->loginComoAdmin();

        $padreFacultad = Catalogo::create([
            'padre_id' => null,
            'nombre' => 'FACULTADES',
            'estado' => 1,
        ]);

        $facultad = Catalogo::create([
            'padre_id' => $padreFacultad->id,
            'nombre' => 'FACULTAD DE INGENIERIA',
            'estado' => 1,
        ]);

        $carrera = Carrera::create([
            'facultad_id' => $facultad->id,
            'nombre' => 'HISTORIA',
            'ciclos_total' => 10,
            'estado' => 1,
        ]);

        Livewire::actingAs($admin)
            ->test(Carreras::class)
            ->set('carreraId', $carrera->id)
            ->call('EliminarCarrera')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('carrera', [
            'id' => $carrera->id,
            'estado' => 0,
        ]);
    }

    public function test_no_crea_carrera_con_datos_invalidos()
    {
        $admin = $this->loginComoAdmin();

        Livewire::actingAs($admin)
            ->test(Carreras::class)
            ->set('facultadId', null)
            ->set('nombre', '')
            ->set('ciclosTotal', 8)
            ->call('CrearCarrera')
            ->assertHasErrors([
                'facultadId',
                'nombre',
                'ciclosTotal',
            ]);
    }
}
