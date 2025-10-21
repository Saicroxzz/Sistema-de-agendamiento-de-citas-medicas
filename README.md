# 🏥 Sistema de Agendamiento de Citas Médicas

Un sistema web para la **gestión y programación de citas médicas**, desarrollado con **PHP, MySQL, HTML, JavaScript, AJAX y Bootstrap 4.1**.
El sistema implementa autenticación por roles (Administrador, Doctor, Usuario) y ofrece funcionalidades específicas según el tipo de usuario.

---

## 📋 Características principales

* **Autenticación y sesiones seguras** por roles (Administrador, Doctor, Usuario).
* **Gestión de usuarios** y control de acceso según privilegios.
* **Agenda de citas médicas** con control de disponibilidad y estados (pendiente, completada, cancelada).
* **Historial de citas** y registro de notas médicas.
* **Panel de administración** con control de usuarios, doctores y pacientes.
* **Diseño responsive** con Bootstrap 4.1.

---

## ⚙️ Tecnologías utilizadas

* **Backend:** PHP (procedural)
* **Frontend:** HTML5, CSS3, JavaScript, AJAX, JSON
* **Base de datos:** MySQL
* **Framework CSS:** Bootstrap 4.1

---

## 🔑 Credenciales de prueba

| Rol               | Correo electrónico                              | Contraseña |
| ----------------- | ----------------------------------------------- | ---------- |
| **Administrador** | [admin1@example.com](mailto:admin1@example.com) | brownandy  |
| **Doctor**        | [doc1@example.com](mailto:doc1@example.com)     | brownandy  |
| **Usuario 1**     | [user1@example.com](mailto:user1@example.com)   | brownandy  |
| **Usuario 2**     | [user2@example.com](mailto:user2@example.com)   | brownandy  |

---

## 🧩 Estructura y archivos funcionales

### 📁 Archivos de prueba

**`localhost/insert_test_user.php`**

* Creación de usuarios de prueba (modo *hard*).
* Permite modificar datos según el rol (administrador, doctor o usuario).
* En modo *hard*, el dominio del correo no afecta la creación del usuario.

**`localhost/password_test.php`**

* Verifica la conexión a la base de datos.
* Permite validar el *hash* de las contraseñas almacenadas.

---

## 🗄️ Configuración de la base de datos

1. Crear la base de datos:

   ```
   nombre: gestor_citas
   ```

2. Importar el archivo SQL incluido:

   ```
   gestor_citas.sql
   ```

3. La base de datos incluye:

   * 1 administrador
   * 1 doctor
   * 2 usuarios de ejemplo

4. Contiene tablas para:

   * Gestión de usuarios
   * Estados y registros de citas
   * Historial de consultas

---

## 🌐 Estructura de rutas web

| Ruta / Archivo           | Descripción                                               |
| ------------------------ | --------------------------------------------------------- |
| `index.php`              | Página principal sin sesión (botones de agendar y login). |
| `/public/index.php`      | Página principal del sistema.                             |
| `/includes/login.php`    | Formulario y validación de inicio de sesión.              |
| `/includes/register.php` | Lógica del registro de usuarios.                          |
| `/includes/session.php`  | Gestión de sesión de usuario.                             |
| `/includes/logout.php`   | Cierre y destrucción de sesión.                           |
| `/includes/config.php`   | Configuración de conexión a la base de datos.             |
| `/views/admin.php`       | Vista del **Administrador**.                              |
| `/views/consultorio.php` | Vista del **Doctor**.                                     |
| `/views/user.php`        | Vista del **Usuario**.                                    |

> ⚠️ Algunos archivos funcionales se encuentran fuera de la carpeta `/includes` y aún no están completamente clasificados.

---

## 👥 Descripción de roles

### 👨‍💼 Administrador

* Gestiona la disponibilidad de doctores.
* Visualiza y controla el historial de citas.
* Administra usuarios del sistema.

### 🩺 Doctor

* Gestiona su agenda de citas.
* Añade notas médicas.
* Puede aceptar, cancelar o completar citas.
* Visualiza su historial de atención.

### 👤 Usuario (Paciente)

* Agenda nuevas citas.
* Consulta su historial de citas y su estado actual.
* Visualiza horarios y doctores disponibles.

---

## 🚀 Instalación y despliegue

1. Clonar el repositorio:

   ```bash
   git clone https://github.com/Saicroxzz/Sistema-de-agendamiento-de-citas-medicas.git
   ```

2. Configurar el entorno local (XAMPP, Laragon, etc.).

3. Colocar el proyecto en la carpeta `htdocs`.

4. Crear la base de datos e importar el archivo `gestor_citas.sql`.

5. Ingresar desde el navegador a:

   ```
   http://localhost/Sistema-de-agendamiento-de-citas-medicas/
   ```

---

## 📸 Capturas de pantalla

<img width="1275" height="657" alt="image" src="https://github.com/user-attachments/assets/11a42ab7-b174-47b9-b16c-8628a9cfa740" />
<img width="1042" height="659" alt="image" src="https://github.com/user-attachments/assets/50878ac6-8287-4908-8682-2c00467a1ffb" />
<img width="1230" height="556" alt="image" src="https://github.com/user-attachments/assets/8d8c2a34-de21-4b00-b172-8090069dfe43" />


---

## 🧑‍💻 Autor

**Wilever Beltrán**
📎 [Repositorio en GitHub](https://github.com/Saicroxzz/Sistema-de-agendamiento-de-citas-medicas.git)

---

## 📄 Licencia

Este proyecto se distribuye bajo la licencia **MIT**. Puedes usarlo, modificarlo y distribuirlo libremente, siempre que se mantenga la atribución al autor original.
