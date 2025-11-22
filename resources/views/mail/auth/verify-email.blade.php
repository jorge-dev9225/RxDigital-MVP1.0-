@component('mail::message')
# Bienvenido/a, {{ $doctorName }} 👋

Para comenzar a utilizar **RxDigital**, necesitamos confirmar tu dirección de correo electrónico.

Por favor, hacé clic en el siguiente botón para verificar tu cuenta:

@component('mail::button', ['url' => $url])
Verificar mi correo
@endcomponent

Si vos no creaste esta cuenta, simplemente ignorá este mensaje.

Gracias por confiar en **RxDigital – Recetas médicas digitales seguras**.

Saludos,  
**Equipo de RxDigital**
@endcomponent
