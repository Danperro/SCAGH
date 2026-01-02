<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

use App\Livewire\Cursos\Cursos;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\Usuario;
use App\Models\UsuarioRol;
use App\Models\Catalogo;
use App\Models\Carrera;
use App\Models\Curso;

class CursosLivewireTest extends TestCase
{
    use RefreshDatabase;

    // Helper login ADMIN
    // =========================
    private function loginComoAdmin(): Usuario
    {
        $rol = Rol::create([
            'nombre' => 'ADMIN',
            'estado' => 1,
        ]);

        $persona = Persona::create([
            'nombre' => 'ADMIN',
            'apellido_paterno' => 'TEST',
            'apellido_materno' => 'SYSTEM',
            'dni' => '00000001',
            'telefono' => '999999999',
            'correo' => 'admin@test.com',
            'fecha_nacimiento' => '2000-01-01',
        ]);

        $admin = Usuario::create([
            'persona_id' => $persona->id,
            'username' => 'admin',
            'password' => bcrypt('123456'),
            'email' => 'admin@test.com',
            'estado' => 1,
            'must_change_password' => false,
        ]);

        UsuarioRol::create([
            'usuario_id' => $admin->id,
            'rol_id' => $rol->id,
            'estado' => 1,
        ]);

        return $admin;
    }

    private function crearFacultad(): Catalogo
    {
        $padre = Catalogo::create([
            'padre_id' => null,
            'nombre' => 'FACULTADES',
            'estado' => 1,
        ]);

        return Catalogo::create([
            'padre_id' => $padre->id,
            'nombre' => 'FACULTAD DE INGENIERIA',
            'estado' => 1,
        ]);
    }

    private function crearCiclos()
    {
        $padre = Catalogo::create([
            'padre_id' => null,
            'nombre' => 'CICLOS',
            'estado' => 1,
        ]);

        for ($i = 1; $i <= 12; $i++) {
            Catalogo::create([
                'padre_id' => $padre->id,
                'nombre' => 'CICLO ' . $i,
                'estado' => 1,
            ]);
        }
    }

    #[Test]
    public function componente_cursos_renderiza()
    {
        $admin = $this->loginComoAdmin();

        Livewire::actingAs($admin)
            ->test(Cursos::class)
            ->assertStatus(200);
    }

    #[Test]
    public function admin_puede_crear_curso()
    {
        $admin = $this->loginComoAdmin();
        $facultad = $this->crearFacultad();
        $this->crearCiclos();

        $carrera = Carrera::create([
            'facultad_id' => $facultad->id,
            'nombre' => 'INGENIERIA DE SISTEMAS',
            'ciclos_total' => 10,
            'estado' => 1,
        ]);

        $ciclo = Catalogo::where('nombre', 'CICLO 5')->first();

        Livewire::actingAs($admin)
            ->test(Cursos::class)
            ->set('facultad_id', $facultad->id)
            ->set('carrera_id', $carrera->id)
            ->set('ciclo_id', $ciclo->id)
            ->set('nombre', 'Algoritmos')
            ->set('codigo', 'CS101')
            ->call('CrearCurso')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('curso', [
            'carrera_id' => $carrera->id,
            'ciclo_id' => $ciclo->id,
            'nombre' => 'ALGORITMOS',
            'codigo' => 'CS101',
            'estado' => 1,
        ]);
    }

    #[Test]
    public function admin_puede_editar_curso()
    {
        $admin = $this->loginComoAdmin();
        $facultad = $this->crearFacultad();
        $this->crearCiclos();

        $carrera = Carrera::create([
            'facultad_id' => $facultad->id,
            'nombre' => 'INGENIERIA',
            'ciclos_total' => 12,
            'estado' => 1,
        ]);

        $ciclo = Catalogo::where('nombre', 'CICLO 6')->first();

        $curso = Curso::create([
            'carrera_id' => $carrera->id,
            'ciclo_id' => $ciclo->id,
            'nombre' => 'MATEMATICA',
            'codigo' => 'MAT01',
            'estado' => 1,
        ]);

        Livewire::actingAs($admin)
            ->test(Cursos::class)
            ->set('curso_id', $curso->id)
            ->set('facultad_id', $facultad->id)
            ->set('carrera_id', $carrera->id)
            ->set('ciclo_id', $ciclo->id)
            ->set('nombre', 'Matematica Discreta')
            ->set('codigo', 'MAT02')
            ->call('EditarCurso')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('curso', [
            'id' => $curso->id,
            'nombre' => 'MATEMATICA DISCRETA',
            'codigo' => 'MAT02',
        ]);
    }

    #[Test]
    public function admin_puede_eliminar_curso()
    {
        $admin = $this->loginComoAdmin();
        $facultad = $this->crearFacultad();
        $this->crearCiclos();

        $carrera = Carrera::create([
            'facultad_id' => $facultad->id,
            'nombre' => 'INGENIERIA',
            'ciclos_total' => 12,
            'estado' => 1,
        ]);

        $ciclo = Catalogo::where('nombre', 'CICLO 3')->first();

        $curso = Curso::create([
            'carrera_id' => $carrera->id,
            'ciclo_id' => $ciclo->id,
            'nombre' => 'FISICA',
            'codigo' => 'FIS01',
            'estado' => 1,
        ]);

        Livewire::actingAs($admin)
            ->test(Cursos::class)
            ->set('curso_id', $curso->id)
            ->call('EliminarCurso')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('curso', [
            'id' => $curso->id,
            'estado' => 0,
        ]);
    }

    #[Test]
    public function no_crea_curso_con_datos_invalidos()
    {
        $admin = $this->loginComoAdmin();

        Livewire::actingAs($admin)
            ->test(Cursos::class)
            ->set('facultad_id', null)
            ->set('carrera_id', null)
            ->set('ciclo_id', null)
            ->set('nombre', '')
            ->set('codigo', '')
            ->call('CrearCurso')
            ->assertHasErrors([
                'facultad_id',
                'carrera_id',
                'ciclo_id',
                'nombre',
                'codigo',
            ]);
    }

    #[Test]
    public function no_permite_ciclo_fuera_del_rango_de_la_carrera()
    {
        $admin = $this->loginComoAdmin();
        $facultad = $this->crearFacultad();
        $this->crearCiclos();

        $carrera = Carrera::create([
            'facultad_id' => $facultad->id,
            'nombre' => 'INGENIERIA',
            'ciclos_total' => 5,
            'estado' => 1,
        ]);

        $cicloInvalido = Catalogo::where('nombre', 'CICLO 10')->first();

        Livewire::actingAs($admin)
            ->test(Cursos::class)
            ->set('facultad_id', $facultad->id)
            ->set('carrera_id', $carrera->id)
            ->set('ciclo_id', $cicloInvalido->id)
            ->set('nombre', 'Electronica')
            ->set('codigo', 'EL01')
            ->call('CrearCurso')
            ->assertHasErrors(['ciclo_id']);
    }
}
