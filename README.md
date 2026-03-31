# Pomodoro Back (PHP + MySQL)

Backend simple para autenticación de usuarios del proyecto Pomodoro.

## Tecnologías

- PHP (PDO)
- MySQL / MariaDB
- XAMPP (recomendado en entorno local)

## Estructura

```text
pomodoro-back/
  api/v1/
    login.php
    register.php
    seed_user.php
  config/
    database.php
  src/utils/
    EnvLoader.php
  .env
```

## Configuración rápida

1. Crear base de datos:

```sql
CREATE DATABASE pomodoro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Crear tabla `users`:

```sql
USE pomodoro;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

3. Revisar `.env`:

```env
DB_HOST=localhost
DB_NAME=pomodoro
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4

JWT_SECRET=tu_clave_secreta_super_larga_123
```

4. Levantar Apache y MySQL en XAMPP.

## Endpoints

Base URL local con XAMPP:

`http://localhost/pomodoro-back/api/v1`

### `POST /register.php`

Registra usuario nuevo.

Body JSON:

```json
{
  "username": "marvin",
  "email": "marvin@mail.com",
  "password": "123456"
}
```

### `POST /login.php`

Autentica usuario.

Body JSON:

```json
{
  "email": "marvin@mail.com",
  "password": "123456"
}
```

### `GET /seed_user.php`

Crea usuario de prueba:

- Usuario: `marvin_admin`
- Password: `123456`

## Pruebas rápidas con curl

Registro:

```bash
curl -X POST http://localhost/pomodoro-back/api/v1/register.php \
  -H "Content-Type: application/json" \
  -d "{\"username\":\"test\",\"email\":\"test@mail.com\",\"password\":\"123456\"}"
```

Login:

```bash
curl -X POST http://localhost/pomodoro-back/api/v1/login.php \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"test@mail.com\",\"password\":\"123456\"}"
```

## Notas

- Las contraseñas se guardan con `password_hash` (`PASSWORD_BCRYPT`).
- El login actual no genera JWT todavía; devuelve datos básicos del usuario para el frontend.
