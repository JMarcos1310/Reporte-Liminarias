# Sistema de Peticiones Ciudadanas

Este proyecto es un sistema web desarrollado con Laravel que permite a los ciudadanos enviar peticiones relacionadas con servicios públicos mediante un formulario en línea. Cuenta con validación, almacenamiento seguro de datos e imágenes, y notificaciones por correo electrónico.

---

## 🚀 Funcionalidades principales

- Formulario público para enviar peticiones sin necesidad de autenticación.
- Validación robusta de datos, incluyendo validación y almacenamiento de imágenes.
- Registro o actualización de ciudadanos automáticamente.
- Generación automática de número único para cada solicitud.
- Envío de correo electrónico de confirmación al ciudadano.
- Manejo de errores y transacciones para asegurar integridad de la información.

---

## 🗂 Rutas web disponibles

| Ruta                      | Método | Descripción                              | Vista / Acción                        | Nombre de ruta           |
|---------------------------|--------|----------------------------------------|-------------------------------------|-------------------------|
| `/`                       | GET    | Página principal - formulario de peticiones | `welcome` con comunidades y tipos de servicio | `home`                  |
| `/peticiones/create`       | GET    | Muestra el formulario con errores de validación | `welcome` con comunidades y tipos de servicio | `peticiones.create`      |
| `/peticiones/store`        | POST   | Procesa y almacena la petición enviada | Valida datos, guarda imagen, registra petición, envía correo | `peticiones.store`       |

---

## 📋 Validaciones importantes

- `nombre`: obligatorio, texto, máximo 255 caracteres.
- `tipo_servicio_id`: obligatorio, entero, debe existir en `tipos_servicio`.
- `comunidad_id`: obligatorio, entero, debe existir en `comunidades`.
- `observaciones`: obligatorio, texto, máximo 1000 caracteres.
- `email`: obligatorio, correo válido, máximo 255 caracteres.
- `telefono`: opcional, texto, máximo 20 caracteres.
- `direccion`: obligatorio, texto, máximo 255 caracteres.
- `colonia`: obligatorio, texto, máximo 255 caracteres.
- `latitud` y `longitud`: obligatorios, valores numéricos.
- `evidencia_foto`: obligatorio, imagen válida (`jpeg,png,jpg,gif,webp`), máximo 5 MB.

---

## 🛠 Requisitos técnicos

- PHP >= 8.1
- Laravel >= 10
- MySQL / MariaDB
- Composer
- Servidor con soporte para correo saliente (SMTP configurado)
- Storage configurado para guardar imágenes (`storage/app/public/evidencias`)

---

## 📦 Instalación y configuración

1. Clonar repositorio:
```bash
git clone <URL-del-repositorio>
cd <nombre-del-proyecto>
