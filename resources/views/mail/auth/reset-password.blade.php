@component('mail::message')
# Hola, {{ $doctorName }}

Recibimos una solicitud para **restablecer tu contraseña** en **RxDigital**.

Si fuiste vos, podés crear una contraseña nueva haciendo clic en el siguiente botón:

@component('mail::button', ['url' => $url])
Restablecer contraseña
@endcomponent

Si no solicitaste este cambio, podés ignorar este correo.  
Tu contraseña actual seguirá funcionando con normalidad.

Gracias por utilizar **RxDigital – Recetas médicas digitales seguras**.

Saludos cordiales,  
**Equipo de RxDigital**
@endcomponent
