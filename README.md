# 🎬 Cine-YAAM

# Evidencia Integradora de la materia de Tecnologias y Aplicaciones en Internet.

Aplicación web para buscar películas, ver detalles, guardar favoritos en listas, comentar y reaccionar a comentarios.

Está construida con Laravel y consume la API de TMDB para traer la información de películas.

---

## ¿Qué hace este proyecto?

En resumen, permite:

- registro e inicio de sesión;
- búsqueda y exploración de películas;
- detalle de película con comentarios;
- reacciones a comentarios (like/dislike);
- favoritos por listas personalizadas;
- panel de administración de usuarios;
- alertas visuales (éxito, error, advertencia) en todo el sistema;
- correo de alerta cuando un usuario inicia sesión.

---

## Stack usado

- **Backend:** PHP 8.2 + Laravel 12
- **Frontend:** Blade + Bootstrap + Vite
- **Base de datos:** MySQL (usando XAMPP en desarrollo)
- **API externa:** TMDB

---

## Módulos principales

- **Autenticación:** registro, login y logout.
- **Películas:** búsqueda, sugerencias y detalle.
- **Comentarios:** crear, editar, eliminar y reaccionar.
- **Favoritos:** guardar películas y organizarlas en listas.
- **Perfil:** ver comentarios, favoritos y notificaciones.
- **Admin:** CRUD de usuarios con middleware de administrador.

---

## Requisitos

- PHP 8.2+
- Composer
- Node.js + npm
- MySQL

---

## Instalación rápida

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configura en tu `.env`:

- conexión de base de datos (`DB_*`)
- token de TMDB (`TMDB_TOKEN`)
- correo (`MAIL_*`) si quieres probar alertas por email

Luego ejecuta:

```bash
php artisan migrate
npm install
npm run build
```

Para desarrollo:

```bash
composer run dev
```

---

## Variables importantes en `.env`

Este proyecto usa en especial:

- `TMDB_TOKEN`
- `TMDB_LANG` (opcional, por defecto `es-MX`)
- `TMDB_BASE_URL` (opcional)
- `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, etc.

---

## Rutas importantes

- `/search` → buscador
- `/search/suggest` → sugerencias (JSON)
- `/movies/{id}` → detalle de película
- `/favorites` → listas de favoritos
- `/user/comments` → comentarios del usuario
- `/admin/dashboard` → panel admin

---

## Alertas del sistema

Se manejan con un partial global en `resources/views/partials/flash-alerts.blade.php` y se muestran desde la navbar.

Tipos implementados:

- `success`
- `error`
- `warning`
- `info`

---

## API y correo

- Consumo de API TMDB desde `SearchController` y `CommentController` usando `Http::...`.
- Envío de correo al iniciar sesión mediante listener `EnviarCorreo` + mailable `AlertaLoginCorreo`.

### Uso mínimo de API implementado

Para cumplir con la implementación mínima de API en el proyecto se realizó:

- **Consulta externa de información**: llamada a TMDB para buscar películas y obtener detalle (`/search` y `/movies/{id}`).
- **Exposición de datos en formato JSON**: endpoint interno de sugerencias en `/search/suggest`.
- **Operación de almacenamiento vinculada al flujo API**: guardado de películas/comentarios en base local a partir de datos consultados en TMDB.

---

## Estructura (resumen)

```text
app/
	Http/Controllers/
	Http/Middleware/
	Listeners/
	Mail/
	Models/
resources/views/
	admin/
	favorites/
	movie/
	partials/
	user/
routes/
	web.php
```

---

## Estado actual

Proyecto funcional para flujo completo de usuario (buscar, guardar y comentar películas) + módulo admin para gestión de usuarios.

---

## Autores

Cordero Beltran Joel Yosset
Cruz Mendoza Aylin
Olvera Ayala Diego Alexis
Ochoa-Zavala Victor Manuel



**Victor Zavala**  
Repositorio: `VictorZavalaa/Cine-YAAM`
