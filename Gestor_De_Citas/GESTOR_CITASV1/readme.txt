|====CREDENCIALES==================|
DOCTOR>
>doc1@example.com:brownandy
ADMNIN>
>admin1@example.com:brownandy
USUARIO 1>
>user1@example.com:brownandy
USUARIO 2>
>user2@example.com:brownandy
|==================================|


|==================================|
archivos funct:

localhost/insert_test_user.php

=> Creacion de usuario en hard.
=> Modificar datos dependiendo el rol 
=> administrador,doctor,usuario.
=> Cuando es Hard no importa la terminacion del correo.

localhost/password_test.php 

=> Verificacion del estado de conexion a base de datos,
y reverse del Hash asignado a la base de datos en la contraseña.

|==================================|

	BASE DE DATOS

|==================================|


Crear base de datos.
nombre: gestor_citas
=>importar el .sql ("gestor_citas.sql")

=> Incluye [1] ADMINISTRADOR, [1] DOCTOR, [2] USUARIOS.

=> Muestra de estados de citas, Historial de citas.

|==================================|



<WEB RUTAS>
?index.php => Main web sin session [Boton agendar y Login]
?/public/index.php => Main web [Boton agendar y Login]
?/includes/login.php => Login Form
?/includes/register.php => Backend Register.
?/includes/session.php => Session
?/includes/logout.php => Destroy session.
?/includes/config.php => Informacion DB
?/views/admin.php => Vista Administrador [Session rol"administrador"]
?/views/consultorio.php => Vista Doctor [Session rol"doctor"]
?/views/user.php => Vista usuario [Session rol"usuario"]

Demas archivos son funciones, algunas con rutas distribuidas fuera de la carpeta include, sin clasificar.


|==========================================|

<INFO WEB>

Website gestion de cita.
Cuenta con Login con validacion de privilegios mediante sesiones de rol.
Cada rol especifico [Administrador,Doctor,Usuario] muestra una view en especifico, si no existe una sesion muestra el "index.php".

ADMINISTRADOR=> Gestion de disponibilidad, Tablas[Historial de citas][Usuarios]
DOCTOR=> Gestion de disponibilidad, Seccion para Notas de cita, Citas [aceptar, cancelar, completar], Historial de Citas.
Usuario=> Panel para agendar cita, Historial de Citas con estatus, Visor de Citas disponibles.





[PHP][HTML][AJAX][JSON][JAVASCRIPT][SQL]
{Boostrap 4.1}
