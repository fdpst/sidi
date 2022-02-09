@component('mail::message')

Se ha registrado un nuevo usuario, asociada a
su correo electrónico {{ $user->email }}

Credenciales:

Usuario: <strong>{{ $user->email }}</strong><br>
Contraseña: <strong>{{ $password }}</strong>


@component('mail::button', ['url' => Request::root()])
    Ingresar
@endcomponent

¡¡Gracias!!!<br>
 
@endcomponent
