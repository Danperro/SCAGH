<?php

namespace App\Livewire\Usuarios;

use App\Models\Catalogo;
use App\Models\Docente;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\Usuario;
use App\Models\UsuarioRol;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Usuarios extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $query, $filtroestado, $filtrorol;
    public $usuario_id, $username, $password, $password_confirmation, $rolesSeleccionados = [], $estado, $email;
    public $persona_id, $nombre, $apellido_paterno, $apellido_materno, $dni, $telefono, $correo, $fecha_nacimiento;
    public $especialidad_id;
    public $rolesUsuario = [];
    #[Url('Busqueda')]


    public function selectInfo($id)
    {
        $this->limpiar();
        $usuario = Usuario::with('persona')->find($id);

        if (!$usuario) {
            return;
        }

        $this->usuario_id = $usuario->id;

        $this->persona_id       = $usuario->persona->id;
        $this->nombre            = $usuario->persona->nombre;
        $this->apellido_paterno  = $usuario->persona->apellido_paterno;
        $this->apellido_materno  = $usuario->persona->apellido_materno;
        $this->dni               = $usuario->persona->dni;
        $this->telefono          = $usuario->persona->telefono;
        $this->correo            = $usuario->persona->correo;
        $this->fecha_nacimiento  = $usuario->persona->fecha_nacimiento;

        $this->username          = $usuario->username;
        $this->estado            = $usuario->estado;
        $this->email = $usuario->email;
        $this->password = null;
        $this->password_confirmation = null;

        // Cargar solo los IDs de los roles ACTIVOS (estado = 1)
        $this->rolesSeleccionados = $usuario->roles()
            ->wherePivot('estado', 1)
            ->pluck('rol.id')
            ->toArray();

        $docente = Docente::where('persona_id', $this->persona_id)->first();
        $this->especialidad_id = $docente ? $docente->especialidad_id : null;
    }


    public function limpiar()
    {
        $this->reset([
            'usuario_id',
            'username',
            'password',
            'password_confirmation',
            'email',
            'rolesSeleccionados',
            'estado',

            'nombre',
            'apellido_paterno',
            'apellido_materno',
            'dni',
            'telefono',
            'correo',
            'fecha_nacimiento',
            'query',
            'filtroestado',
            'filtrorol',
        ]);

        $this->resetValidation();
    }

    public function rulesAdministrador()
    {
        return [
            // Datos personales
            'nombre' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'apellido_paterno' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'apellido_materno' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',

            'dni' => 'required|digits:8|regex:/^[0-9]+$/|unique:persona,dni,' . $this->persona_id,

            'telefono' => [
                'required',
                'regex:/^9\d{8}$/',
            ],

            'correo' => [
                'required',
                'email',
                'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.(com|pe|edu|es)$/',
                'unique:persona,correo,' . $this->persona_id,
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('usuario', 'email')->ignore($this->usuario_id),
                'different:correo',
            ],
            'fecha_nacimiento' => [
                'required',
                'date',
                'before:' . now()->subYears(18)->format('Y-m-d'),
            ],

            // Datos de usuario
            'username' => 'required|min:4|max:30|unique:usuario,username,' . $this->usuario_id,
            'password' => 'required|min:6|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
        ];
    }

    public function rulesEditarAdministrador()
    {
        $rules = [
            'nombre' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'apellido_paterno' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'apellido_materno' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'dni' => 'required|digits:8|unique:persona,dni,' . $this->persona_id,
            'telefono' => ['required', 'regex:/^9\d{8}$/'],
            'correo' => 'required|email|unique:persona,correo,' . $this->persona_id,
            'fecha_nacimiento' => 'required|date|before:' . now()->subYears(18)->format('Y-m-d'),
            'username' => 'required|min:4|max:30|unique:usuario,username,' . $this->usuario_id,
            'rolesSeleccionados' => 'required|array|min:1',
            'rolesSeleccionados.*' => 'exists:rol,id',

        ];

        if (!empty($this->password)) {
            $rules['password'] = 'min:6|same:password_confirmation';
            $rules['password_confirmation'] = 'min:6';
        }

        return $rules;
    }




    protected $messages = [

        // Nombres y apellidos
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.regex' => 'El nombre solo debe contener letras.',
        'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
        'nombre.max' => 'El nombre no debe superar los 50 caracteres.',

        'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
        'apellido_paterno.regex' => 'El apellido paterno solo debe contener letras.',
        'apellido_paterno.min' => 'El apellido paterno debe tener al menos 2 caracteres.',
        'apellido_paterno.max' => 'El apellido paterno no debe superar los 50 caracteres.',

        'apellido_materno.required' => 'El apellido materno es obligatorio.',
        'apellido_materno.regex' => 'El apellido materno solo debe contener letras.',
        'apellido_materno.min' => 'El apellido materno debe tener al menos 2 caracteres.',
        'apellido_materno.max' => 'El apellido materno no debe superar los 50 caracteres.',

        // DNI
        'dni.required' => 'El DNI es obligatorio.',
        'dni.digits' => 'El DNI debe tener exactamente 8 dígitos.',
        'dni.regex' => 'El DNI solo debe contener números.',
        'dni.unique' => 'Ya existe una persona registrada con este DNI.',

        // Teléfono
        'telefono.required' => 'El teléfono es obligatorio.',
        'telefono.regex' => 'El teléfono debe iniciar con 9 y tener 9 dígitos.',

        // Correo
        'correo.required' => 'El correo es obligatorio.',
        'correo.email' => 'Debe ingresar un correo electrónico válido.',
        'correo.regex' => 'El correo debe terminar en .com, .pe, .edu o .es.',
        'correo.unique' => 'Este correo ya está registrado.',

        // Correo de confirmacion
        'email.required' => 'El correo es obligatorio.',
        'email.email' => 'Debe ingresar un correo electrónico válido.',
        'email.regex' => 'El correo debe terminar en .com, .pe, .edu o .es.',
        'email.unique' => 'Este correo ya está registrado.',

        // Fecha nacimiento
        'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
        'fecha_nacimiento.before' => 'Debe ser mayor de 18 años.',

        // Username
        'username.required' => 'El nombre de usuario es obligatorio.',
        'username.min' => 'El usuario debe tener al menos 4 caracteres.',
        'username.max' => 'El usuario no debe superar los 30 caracteres.',
        'username.unique' => 'Este usuario ya está registrado.',

        // Password
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        'password.same' => 'La confirmación no coincide.',

        'password_confirmation.required' => 'Debe confirmar la contraseña.',
        'password_confirmation.min' => 'La confirmación debe tener al menos 6 caracteres.',

        // Rol
        'rolesSeleccionados.required' => 'Debe seleccionar al menos un rol.',

    ];

    public function updated($propertyName)
    {
        if ($this->usuario_id) {
            $this->validateOnly($propertyName, $this->rulesEditarAdministrador());
        } else {
            $this->validateOnly($propertyName, $this->rulesAdministrador());
        }
    }



    public function CrearUsuario()
    {
        $this->validate($this->rulesAdministrador());

        try {

            $persona = Persona::create([
                'nombre'            => $this->nombre,
                'apellido_paterno'  => $this->apellido_paterno,
                'apellido_materno'  => $this->apellido_materno,
                'dni'               => $this->dni,
                'telefono'          => $this->telefono,
                'correo'            => $this->correo,
                'fecha_nacimiento'  => $this->fecha_nacimiento,
            ]);

            $usuario = Usuario::create([
                'persona_id' => $persona->id,
                'username'   => $this->username,
                'password'   => Hash::make($this->password),
                'email'      => $this->email,
                'estado'     => 1,
            ]);

            $usuario->roles()->sync($this->rolesSeleccionados);


            $this->limpiar();

            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Administrador registrado correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Ocurrio un error al registrar un Administrador.', tipo: 'danger');
        }
    }


    public function EditarUsuario()
    {
        $this->validate($this->rulesEditarAdministrador());

        try {
            $usuario = Usuario::findOrFail($this->usuario_id);
            $persona = Persona::findOrFail($usuario->persona_id);

            $persona->update([
                'nombre' => $this->nombre,
                'apellido_paterno' => $this->apellido_paterno,
                'apellido_materno' => $this->apellido_materno,
                'dni' => $this->dni,
                'telefono' => $this->telefono,
                'correo' => $this->correo,
                'fecha_nacimiento' => $this->fecha_nacimiento,
            ]);

            $usuario->update([
                'username' => $this->username,
                'email'    => $this->email,
                'estado'   => $this->estado,

            ]);

            $rolesActuales = UsuarioRol::where('usuario_id', $this->usuario_id)
                ->pluck('rol_id')
                ->toArray();

            $rolesADesactivar = array_diff($rolesActuales, $this->rolesSeleccionados);

            // 🔴 Desactivar solo los que se apagaron
            if (!empty($rolesADesactivar)) {
                UsuarioRol::where('usuario_id', $this->usuario_id)
                    ->whereIn('rol_id', $rolesADesactivar)
                    ->update(['estado' => 0]);
            }

            // 🟢 Activar / crear los seleccionados
            foreach ($this->rolesSeleccionados as $rolId) {
                UsuarioRol::updateOrCreate(
                    [
                        'usuario_id' => $this->usuario_id,
                        'rol_id'     => $rolId,
                    ],
                    [
                        'estado' => 1,
                    ]
                );
            }



            // Actualizar contraseña solo si se ingresó una nueva
            if (!empty($this->password)) {
                $usuario->update([
                    'password' => Hash::make($this->password),
                ]);
            }

            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Usuario actualizado correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error('Error al actualizar usuario: ' . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Ocurrio un error al actualizar el usuario.', tipo: 'danger');
        }
    }


    public function EliminarUsuario()
    {
        try {
            $usuario = Usuario::findOrFail($this->usuario_id);

            $usuario->update([
                'estado' => $usuario->estado == 1 ? 0 : 1
            ]);

            $this->limpiar();
            $this->dispatch('cerrarModal');
            $this->dispatch('toast-general', mensaje: 'Usuario se elimino correctamente.', tipo: 'success');
        } catch (\Throwable $e) {
            Log::error('Error al actualizar usuario: ' . $e->getMessage());
            $this->dispatch('toast-general', mensaje: 'Ocurrio un error al eliminar el usuario.', tipo: 'danger');
        }
    }

    public function render()
    {
        $usuarios = Usuario::with('persona', 'roles')
            ->search($this->query, $this->filtrorol, $this->filtroestado)
            ->orderBy('id', 'desc')
            ->paginate(10);
        $especialidades = Catalogo::where('padre_id', 26)->get();
        $usuarioId = $this->usuario_id;

        // 1️⃣ IDs de roles que existen para el usuario
        $rolesIds = UsuarioRol::query()
            ->where('usuario_id', $usuarioId)
            ->pluck('rol_id')
            ->toArray();

        // 2️⃣ Forzar ADMINISTRADOR (id = 1)
        $rolesIds = array_unique(array_merge([1], $rolesIds));

        // 3️⃣ Traer los ROLES reales
        $roles = Rol::query()
            ->whereIn('id', $rolesIds)
            ->orderByRaw("CASE WHEN id = 1 THEN 0 ELSE 1 END")
            ->orderBy('nombre')
            ->get();


        return view('livewire.usuarios.usuarios', [
            'usuarios' => $usuarios,
            'roles' => $roles,
            'especialidades' => $especialidades,
        ]);
    }
}
