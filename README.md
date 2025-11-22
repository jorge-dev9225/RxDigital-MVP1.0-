# RxDigital – Recetas médicas digitales seguras

RxDigital es una aplicación web para médicos que permite **generar recetas médicas digitales en PDF**, firmadas, con QR de verificación y flujo sencillo para que el paciente complete sus datos de forma segura.

Este repositorio contiene la **versión MVP (v1.0)** del sistema, centrada en:

- Gestión básica de usuarios (médicos) con registro, login y verificación por email.
- Generación de recetas (RP + Notas/Indicaciones).
- Envío de link seguro al paciente para completar sus datos.
- Generación de PDF con estilos personalizados y código QR de verificación.
- Panel de control para que el médico gestione recetas.

> ⚠️ **Importante:** Este proyecto está pensado como MVP. No debe usarse en producción sin una revisión legal/compliance respecto a la normativa sanitaria y de protección de datos vigente en tu país.

---

## ✨ Funcionalidades principales

### 👨‍⚕️ Para el médico

- Registro de cuenta y login seguro.
- Verificación de email (enlace enviado al correo del médico).
- Recuperación de contraseña vía email (flujo estándar de Laravel, estilizado).
- Panel de control (dashboard) con:
  - Listado de recetas.
  - Estados: pendiente, enviada al paciente, completada por paciente, finalizada.
  - Botón para **generar nueva receta**.
  - Botones para:
    - Enviar enlace al paciente.
    - Generar/descargar PDF.
    - Cancelar o eliminar recetas.

- Datos de perfil de médico:
  - Nombre completo.
  - Género (para mostrar Dr./Dra.).
  - Especialidad.
  - Matrícula Nacional.
  - Matrícula Provincial.
  - Domicilio de consultorio.

### 🧑‍🦰 Para el paciente

- Recibe un enlace único y seguro (con token público) para completar sus datos:
  - Nombre y apellido.
  - DNI.
  - Fecha de nacimiento (con validaciones de rango).
  - Obra social (opcional).

- Validaciones de formulario en servidor:
  - Nombres/apellidos con mínimo de caracteres.
  - DNI con rango de dígitos.
  - Fecha de nacimiento coherente (no futura, no absurda).

### 📄 Recetas en PDF

- Generación de PDF con:
  - Encabezado con datos del médico:
    - Dr./Dra. dinámico según género.
    - Nombre del profesional.
    - Especialidad.
    - Matrículas (M.N., M.P.).
  - Cuerpo:
    - Copia 1 – Medicación (RP).
    - Copia 2 – Indicaciones / Notas.
  - Datos del paciente.
  - Firma y sello del profesional alineados con el QR.
  - QR de verificación que apunta a una vista pública de verificación.
  - Estilos personalizados (colores, tipografía, layout).

### 🔐 Seguridad y verificaciones

- Autenticación y verificación de email para médicos.
- Rutas de médico protegidas con `auth` + `verified`.
- Control de acceso a recetas:
  - Solo el médico propietario puede ver/generar/descargar PDFs.
- Tokens públicos para pacientes y verificación:
  - `public_token` único por receta.
- Configurado para uso con **Gmail SMTP + App Password** (en `.env`).

### 📩 Notificaciones por email

- **Verificación de email** al registrarse.
- **Recuperación de contraseña** con vistas personalizadas.
- **Notificación al médico** cuando un paciente completa el formulario:
  - Asunto: “Nuevo formulario completado – RxDigital”.
  - Email enviado a la dirección de correo con la que se registró el médico.

---

## 🧱 Tecnologías utilizadas

- **Backend**: Laravel 12 (PHP 8.x)
- **Frontend**: Blade + Tailwind CSS
- **Autenticación**: Laravel Breeze (login, registro, email verification, reset password)
- **Base de datos**: MySQL
- **PDF**: `barryvdh/laravel-dompdf`
- **QR Codes**: `simplesoftwareio/simple-qrcode`
- **Mailing**: SMTP (Gmail con App Password)

---

## 🚀 Requisitos

- PHP >= 8.2
- Composer
- Node.js + npm
- MySQL / MariaDB (u otra BD compatible configurada en `.env`)
- Cuenta de Gmail con **App Password** (no la contraseña normal).

---
