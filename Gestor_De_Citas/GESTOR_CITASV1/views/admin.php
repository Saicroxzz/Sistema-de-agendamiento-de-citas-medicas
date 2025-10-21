<?php
session_start();
require '../includes/config.php';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=gestor_citas;charset=utf8mb4', 'root', ''); // Ajusta 'usuario' y 'contraseña'
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}

// Manejo de mensajes
$message = '';
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}

// Obtener datos
$usuarios = $pdo->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
$disponibilidad = $pdo->query("SELECT * FROM disponibilidad_citas WHERE disponible = 1")->fetchAll(PDO::FETCH_ASSOC);

// Obtener citas disponibles
$citasDisponibles = $pdo->query("SELECT * FROM citas WHERE estado = 'pendiente'")->fetchAll(PDO::FETCH_ASSOC);

// Obtener historial de citas
$citasHistorial = $pdo->query("SELECT * FROM citas")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F4F4F4;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #046D4D;
            color: #fff;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #034B4E;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .navbar {
            background-color: #046D4D;
        }
        .navbar-brand {
            color: #fff;
        }
        .navbar-nav .nav-link {
            color: #fff;
        }
        .navbar-nav .nav-link:hover {
            color: #cce5ff;
        }
        .card-header {
            background-color: #046D4D;
            color: #fff;
        }
        .btn-custom {
            background-color: #046D4D;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #034B4E;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="p-4">
            <h4>Panel de Administración</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="#usuarios">Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#citas">Citas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#disponibilidad">Disponibilidad</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <div class="ml-auto">
                <a href="../includes/logout.php" class="btn btn-outline-light">Cerrar sesión</a>
            </div>
        </nav>

        <?php if ($message): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Usuarios -->
        <div id="usuarios" class="card mb-4">
            <div class="card-header">
                <h5>Gestión de Usuarios</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Correo</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Rol</th>
                            <th>Género</th>
                            <th>Teléfono</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['genero']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['telefono']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Citas -->
        <div id="citas" class="card mb-4">
            <div class="card-header">
                <h5>Gestión de Citas</h5>
            </div>
            <div class="card-body">
                <h6>Historial de Citas</h6>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario ID</th>
                            <th>Fecha</th>
                            <th>Hora Inicio</th>
                            <th>Hora Fin</th>
                            <th>Estado</th>
                            <th>Razón Cancelación</th>
                            <th>Notas del Doctor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($citasHistorial as $cita): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cita['id']); ?></td>
                                <td><?php echo htmlspecialchars($cita['usuario_id']); ?></td>
                                <td><?php echo htmlspecialchars($cita['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($cita['hora_inicio']); ?></td>
                                <td><?php echo htmlspecialchars($cita['hora_fin']); ?></td>
                                <td><?php echo htmlspecialchars($cita['estado']); ?></td>
                                <td><?php echo htmlspecialchars($cita['razon_cancelacion']); ?></td>
                                <td><?php echo htmlspecialchars($cita['notas_doctor']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Disponibilidad -->
        <div id="disponibilidad" class="card mb-4">
            <div class="card-header">
                <h5>Disponibilidad</h5>
            </div>
            <div class="card-body">
                <h6>Agregar Disponibilidad</h6>
                <!-- Formulario para agregar disponibilidad -->
<form id="addAvailabilityForm">
    <div class="form-group mb-3">
        <label for="fecha">Fecha:</label>
        <input type="date" class="form-control" id="fecha" name="fecha" required>
    </div>
    <div class="form-group mb-3">
        <label for="hora_inicio">Hora Inicio:</label>
        <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
    </div>
    <div class="form-group mb-3">
        <label for="hora_fin">Hora Fin:</label>
        <input type="time" class="form-control" id="hora_fin" name="hora_fin" required>
    </div>
    <button type="submit" class="btn btn-custom">Agregar Disponibilidad</button>
</form>
                <div id="availabilityMessage" class="mt-3"></div>
                <h6 class="mt-4">Citas Disponibles</h6>
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Hora Inicio</th>
                            <th>Hora Fin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($disponibilidad as $disp): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($disp['id']); ?></td>
                                <td><?php echo htmlspecialchars($disp['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($disp['hora_inicio']); ?></td>
                                <td><?php echo htmlspecialchars($disp['hora_fin']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
    <script>
// Función para cargar la lista de disponibilidad
function loadAvailability() {
    fetch('get_availability.php') // Asegúrate de que la ruta sea correcta
        .then(response => response.json())
        .then(data => {
            let tableBody = document.querySelector('#availabilityTable tbody');
            tableBody.innerHTML = ''; // Limpia el contenido actual
            if (data.length > 0) {
                data.forEach(item => {
                    let row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.fecha}</td>
                        <td>${item.hora_inicio}</td>
                        <td>${item.hora_fin}</td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="3">No hay disponibilidad.</td></tr>';
            }
        })
        .catch(error => console.error('Error:', error));
}

document.getElementById('addAvailabilityForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Evita el envío normal del formulario

    var formData = new FormData(this);

    fetch('add_availability.php', { // Asegúrate de que la ruta sea correcta
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        var messageDiv = document.getElementById('availabilityMessage');
        if (data.status === 'success') {
            messageDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
            // Opcional: limpiar el formulario
            document.getElementById('addAvailabilityForm').reset();
            // Recargar la tabla de disponibilidad
            loadAvailability();
        } else {
            messageDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
        }
    })
    .catch(error => {
        document.getElementById('availabilityMessage').innerHTML = '<div class="alert alert-danger">Error al procesar la solicitud.</div>';
        console.error('Error:', error); // Muestra el error en la consola para depuración
    });
});

// Cargar la lista de disponibilidad cuando la página se carga
document.addEventListener('DOMContentLoaded', function() {
    loadAvailability();
});
</script>


</body>
</html>
