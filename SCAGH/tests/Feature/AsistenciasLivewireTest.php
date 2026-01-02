<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

use App\Livewire\Asistencias\Asistencias;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\Usuario;
use App\Models\UsuarioRol;
use App\Models\Catalogo;
use App\Models\Horario;
use App\Models\Laboratorio;

class AsistenciasLivewireTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 🔐 Login ADMIN
     */
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

        $usuario = Usuario::create([
            'persona_id' => $persona->id,
            'username' => 'admin',
            'password' => bcrypt('123456'),
            'email' => 'admin@test.com',
            'estado' => 1,
            'must_change_password' => false,
        ]);

        UsuarioRol::create([
            'usuario_id' => $usuario->id,
            'rol_id' => $rol->id,
            'estado' => 1,
        ]);

        return $usuario;
    }

    /**
     * 📅 Datos mínimos requeridos
     */
    private function crearDatosBase()
    {
        // ====== DIAS ======
        $padreDias = Catalogo::create([
            'padre_id' => null,
            'nombre' => 'DIAS',
            'estado' => 1,
        ]);

        foreach (['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES'] as $dia) {
            Catalogo::create([
                'padre_id' => $padreDias->id,
                'nombre' => $dia,
                'estado' => 1,
            ]);
        }

        // ====== TIPO ASISTENCIA ======
        $padreAsistencia = Catalogo::create([
            'padre_id' => null,
            'nombre' => 'TIPOASISTENCIA',
            'estado' => 1,
        ]);

        Catalogo::create([
            'padre_id' => $padreAsistencia->id,
            'nombre' => 'ASISTIO',
            'estado' => 1,
        ]);

        // ====== AREA ======
        $padreAreas = Catalogo::create([
            'padre_id' => null,
            'nombre' => 'AREAS',
            'estado' => 1,
        ]);

        $area = Catalogo::create([
            'padre_id' => $padreAreas->id,
            'nombre' => 'LABORATORIOS',
            'estado' => 1,
        ]);

        // ====== SEMESTRE (TABLA REAL) ======
        $semestre = \App\Models\Semestre::create([
            'nombre' => '2025-II',
            'fecha_inicio' => now()->startOfMonth(),
            'fecha_fin' => now()->endOfMonth(),
            'estado' => 1,
        ]);

        // ====== LABORATORIO ======
        $laboratorio = Laboratorio::create([
            'area_id' => $area->id,
            'nombre' => 'LAB A',
            'estado' => 1,
        ]);

        // ====== HORARIO ======
        Horario::create([
            'laboratorio_id' => $laboratorio->id,
            'semestre_id' => $semestre->id,
            'nombre' => 'HORARIO MAÑANA',
            'estado' => 1,
        ]);
    }



    // ===========================
    // TESTS
    // ===========================

    #[Test]
    public function componente_asistencias_renderiza()
    {
        $admin = $this->loginComoAdmin();
        $this->crearDatosBase();

        Livewire::actingAs($admin)
            ->test(Asistencias::class)
            ->assertStatus(200);
    }

    #[Test]
    public function guarda_asistencia_crea_cabecera_y_detalle()
    {
        $admin = $this->loginComoAdmin();
        $this->crearDatosBase();

        // ===========================
        // CATÁLOGOS BASE
        // ===========================

        $dia = Catalogo::where('nombre', 'LUNES')->first();
        $tipoAsistencia = Catalogo::where('nombre', 'ASISTIO')->first();

        // ====== ESPECIALIDAD (OBLIGATORIA PARA DOCENTE) ======
        $padreEspecialidades = Catalogo::create([
            'padre_id' => null,
            'nombre' => 'ESPECIALIDADES',
            'estado' => 1,
        ]);

        $especialidad = Catalogo::create([
            'padre_id' => $padreEspecialidades->id,
            'nombre' => 'INGENIERIA DE SISTEMAS',
            'estado' => 1,
        ]);

        // ===========================
        // DOCENTE
        // ===========================

        $personaDocente = Persona::create([
            'nombre' => 'DOCENTE',
            'apellido_paterno' => 'TEST',
            'apellido_materno' => 'UNO',
            'dni' => '11111111',
            'telefono' => '999999999',
            'correo' => 'docente@test.com',
            'fecha_nacimiento' => '1980-01-01',
        ]);

        $docente = \App\Models\Docente::create([
            'persona_id' => $personaDocente->id,
            'especialidad_id' => $especialidad->id, // 👈 CLAVE
            'estado' => 1,
        ]);

        // ===========================
        // FACULTAD / CARRERA / CICLO
        // ===========================

        $facultad = Catalogo::create([
            'padre_id' => null,
            'nombre' => 'FACULTAD TEST',
            'estado' => 1,
        ]);

        $carrera = \App\Models\Carrera::create([
            'facultad_id' => $facultad->id,
            'nombre' => 'INGENIERIA',
            'ciclos_total' => 10,
            'estado' => 1,
        ]);

        $padreCiclos = Catalogo::create([
            'padre_id' => null,
            'nombre' => 'CICLOS',
            'estado' => 1,
        ]);

        $ciclo = Catalogo::create([
            'padre_id' => $padreCiclos->id,
            'nombre' => 'CICLO 1',
            'estado' => 1,
        ]);

        $curso = \App\Models\Curso::create([
            'carrera_id' => $carrera->id,
            'ciclo_id' => $ciclo->id,
            'nombre' => 'ALGEBRA',
            'codigo' => 'MAT01',
            'estado' => 1,
        ]);

        $semestre = \App\Models\Semestre::first(); // 👈 ya existe
        $grupo = Catalogo::create([
            'padre_id' => null, // o el padre real si usas GRUPOS como categoría
            'nombre' => 'GRUPO A',
            'estado' => 1,
        ]);

        $docenteCurso = \App\Models\DocenteCurso::create([
            'docente_id' => $docente->id,
            'curso_id' => $curso->id,
            'semestre_id' => $semestre->id,
            'grupo_id' => $grupo->id, // 👈 CLAVE
            'estado' => 1,
        ]);


        // ===========================
        // HORARIO
        // ===========================

        $horario = Horario::first();

        $horarioCursoDocente = \App\Models\HorarioCursoDocente::create([
            'horario_id' => $horario->id,
            'docente_curso_id' => $docenteCurso->id,
            'semana_id' => $dia->id,
            'hora_inicio' => '07:00:00',
            'hora_fin' => '09:00:00',
            'estado' => 1,
        ]);

        // ===========================
        // ESTUDIANTE
        // ===========================

        $personaEstudiante = Persona::create([
            'nombre' => 'ESTUDIANTE',
            'apellido_paterno' => 'TEST',
            'apellido_materno' => 'UNO',
            'dni' => '22222222',
            'telefono' => '999999999',
            'correo' => 'estudiante@test.com',
            'fecha_nacimiento' => '2003-01-01',
        ]);

        $estudiante = \App\Models\Estudiante::create([
            'persona_id' => $personaEstudiante->id,
            'carrera_id' => $carrera->id, // 👈 OBLIGATORIO
            'codigo' => 'EST001',
            'estado' => 1,
        ]);


        $ecd = \App\Models\EstudianteCursoDocente::create([
            'estudiante_id' => $estudiante->id,
            'docente_curso_id' => $docenteCurso->id,
            'estado' => 1,
        ]);

        // ===========================
        // EJECUCIÓN
        // ===========================

        Livewire::actingAs($admin)
            ->test(Asistencias::class)
            ->set('horario_curso_docente_id', $horarioCursoDocente->id)
            ->set('docente_curso_id', $docenteCurso->id)
            ->set('asistencia', [
                $ecd->id => $tipoAsistencia->id,
            ])
            ->call('guardarAsistencia')
            ->assertHasNoErrors();

        // ===========================
        // ASSERTS
        // ===========================

        $this->assertDatabaseHas('asistencia', [
            'horario_curso_docente_id' => $horarioCursoDocente->id,
            'fecha_registro' => now()->toDateString(),
        ]);

        $this->assertDatabaseHas('asistencia_estudiante', [
            'estudiante_id' => $estudiante->id,
            'tipo_asistencia_id' => $tipoAsistencia->id,
        ]);
    }
}
