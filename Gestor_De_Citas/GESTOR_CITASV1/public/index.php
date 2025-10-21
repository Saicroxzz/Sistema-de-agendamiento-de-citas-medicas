<?php
session_start();
require_once '../includes/config.php';
// Redirección según parámetros de la URL
if (isset($_GET['view'])) {
    switch ($_GET['view']) {
        case 'login':
            header('Location: ../includes/login.php');
            exit();
        case 'citas':
            header('Location: ../views/user.php'); // Asegúrate de que esta ruta sea correcta
            exit();
        default:
            header('Location: index.php');
            exit();
    }
}

// Si el usuario ya está logueado, redirige a la vista correspondiente
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] == 'administrador') {
        header('Location: ../views/admin.php');
        exit();
    } else {
        header('Location: ../views/user.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Citas Médicas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F4F4F4;
            font-family: 'Poppins', sans-serif;
        }

        header {
            background-color: #046D4D;
            padding: 15px;
            color: white;
        }

        header h1 {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
        }

        .hero {
            background-color: #F4F4F4;
            text-align: center;
            padding: 100px 0;
        }

        .hero h2 {
            color: #046D4D;
            font-size: 2.8rem;
            font-weight: 700;
        }

        .hero p {
            color: #333;
            font-size: 1.2rem;
            margin-bottom: 40px;
        }

        .hero .btn-custom {
            background-color: #046D4D;
            color: white;
            border-radius: 30px;
            padding: 12px 30px;
            font-size: 1.1rem;
            margin: 0 10px;
        }

        .hero .btn-custom:hover {
            background-color: #034f37;
        }

        .features {
            background-color: #F4F4F4;
            padding: 60px 0;
        }

        .section-title {
            color: #046D4D;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 40px;
            text-align: center;
        }

        .feature {
            text-align: center;
            margin-bottom: 40px;
        }

        .feature h4 {
            color: #046D4D;
            font-size: 1.7rem;
            margin-top: 20px;
        }

        .modal-header {
            background-color: #046D4D;
            color: white;
        }

        .modal-content {
            border-radius: 15px;
        }

        .modal-title {
            font-size: 1.5rem;
        }

        .modal-body p {
            font-size: 1.1rem;
            margin-bottom: 20px;
        }
        .btn-primary{
            background-color: #046D4D;
            border-color: #046D4D;
        }
        .btn-primary:hover{
            background-color: #034f37;
            border-color: #046D4D;
        }

        footer {
            background-color: #046D4D;
            padding: 20px;
            color: white;
            text-align: center;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .hero h2 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .feature h4 {
                font-size: 1.5rem;
            }

            .btn-custom {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Sistema de Agendamiento de Citas</h1>
    </header>

    <!-- Sección principal -->
    <section class="hero">
        <div class="container">
            <h2>Bienvenido - Sistema de Agendamiento de Citas</h2>
            <p>Organiza tus citas con facilidad en nuestra plataforma segura y confiable.</p>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="index.php?view=login" class="btn btn-custom">Iniciar Sesión</a>
            <?php endif; ?>
            <a href="#" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#agendaModal">Agende su Cita</a>
        </div>
    </section>

    <!-- Sección de características -->
    <section class="features">
    <div class="container">
        <h2 class="section-title">Nuestras Ventajas</h2>
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card mb-4" style="min-height: 250px;">
                    <div class="card-body d-flex flex-column align-items-center text-center">
                        <img src="https://img.icons8.com/color/96/000000/calendar.png" alt="Calendario" class="mb-3" width="80" style="max-width: 100%;">
                        <h4 class="card-title mb-3">Facilidad y Rapidez</h4>
                        <p class="card-text flex-grow-1">Nuestro sistema de agendamiento te permite programar, gestionar y recordar tus citas de manera eficiente.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4" style="min-height: 250px;">
                    <div class="card-body d-flex flex-column align-items-center text-center">
                        <img src="https://img.icons8.com/color/96/000000/reminder.png" alt="Recordatorio" class="mb-3" width="80" style="max-width: 100%;">
                        <h4 class="card-title mb-3">Recordatorios Automáticos</h4>
                        <p class="card-text flex-grow-1">Recibe notificaciones para que no olvides ninguna cita.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4" style="min-height: 250px;">
                    <div class="card-body d-flex flex-column align-items-center text-center">
                        <img src="https://img.icons8.com/color/96/000000/customer-support.png" alt="Soporte" class="mb-3" width="80" style="max-width: 100%;">
                        <h4 class="card-title mb-3">Soporte 24/7</h4>
                        <p class="card-text flex-grow-1">Estamos disponibles para ayudarte con cualquier duda o inconveniente.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


    <!-- Modal para agendar citas -->
    <div class="modal fade" id="agendaModal" tabindex="-1" aria-labelledby="agendaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agendaModalLabel">Agendar Cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Ya estás registrado?</p>
                    <a href="index.php?view=login" class="btn btn-primary">Iniciar Sesión</a>
                    <hr>
                    <h5>Registro</h5>
                    <form id="registerForm" action="./includes/register.php" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                        <div class="mb-3">
                            <label for="genero" class="form-label">Género</label>
                            <select class="form-select" id="genero" name="genero" required>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo Institucional</label>
                            <input type="email" class="form-control" id="correo" name="correo" pattern=".+@unitropico\.edu\.co" required>
                            <div class="form-text">Solo se permiten correos con dominio @unitropico.edu.co</div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-success">Registrarse</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Citas Médicas. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
