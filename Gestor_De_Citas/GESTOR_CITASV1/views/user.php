<?php
session_start();
require '../includes/config.php';

// Establece el locale a español
setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'Spanish_Spain', 'Spanish');

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'usuario') {
    header('Location: ../public/index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener información del usuario
$user_query = "SELECT correo, nombre, apellido, genero, telefono FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user_info = $stmt->get_result()->fetch_assoc();

// Obtener disponibilidad de citas
$availability_query = "SELECT DISTINCT fecha FROM disponibilidad_citas WHERE disponible = 1";
$availability_result = $conn->query($availability_query);
$available_dates = [];
while ($row = $availability_result->fetch_assoc()) {
    $available_dates[] = $row['fecha'];
}

// Obtener citas actuales del usuario
$appointments_query = "SELECT id, fecha, hora_inicio, estado FROM citas WHERE usuario_id = ? AND fecha >= CURDATE()";
$stmt = $conn->prepare($appointments_query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$appointments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Obtener todas las citas disponibles
$all_appointments_query = "SELECT fecha, hora_inicio, hora_fin FROM disponibilidad_citas WHERE disponible = 1";
$all_appointments_result = $conn->query($all_appointments_query);
$all_appointments = $all_appointments_result->fetch_all(MYSQLI_ASSOC);

// Procesar la cancelación de citas si se ha enviado una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_id = isset($_POST['appointment_id']) ? $_POST['appointment_id'] : '';
    $razon_cancelacion = isset($_POST['razon_cancelacion']) ? trim($_POST['razon_cancelacion']) : '';

    // Verifica si se han recibido los datos correctamente
    if (empty($appointment_id) || empty($razon_cancelacion)) {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
        exit();
    }

    // Verificar si la cita pertenece al usuario
    $query = "SELECT id FROM citas WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $appointment_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo json_encode(['status' => 'error', 'message' => 'No se encontró la cita o no tienes permiso para cancelarla.']);
        exit();
    }
}
//editr
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Citas - Usuario</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #F4F4F4;
        }
        .container {
            margin-top: 50px;
        }
        .card-header {
            background-color: #046D4D;
            color: white;
        }
        .btn-primary {
            background-color: #046D4D;
            border: none;
        }
        .btn-primary:hover {
            background-color: #035C41;
        }
        .table th {
            background-color: #046D4D;
            color: white;
        }
        .navbar {
            background-color: #046D4D;
        }
        .navbar .navbar-brand, .navbar-nav .nav-link {
            color: white;
        }
        .table th, .table td {
    text-align: center;
    vertical-align: middle;
}

.table .badge {
    font-size: 0.9rem;
}

.modal-content {
    border-radius: 0.5rem;
}

.modal-header {
    background-color: #046D4D;
    color: white;
}

.modal-footer {
    border-top: 1px solid #ddd;
}

    </style>
</head>
<body>

<!-- Header con Logout y otras opciones -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">Gestión de Citas</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#mis-citas">Mis Citas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#agendar-cita">Agendar Cita</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#citas-disponibles">Citas Disponibles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/public/views/servicios.php">Servicios</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="../includes/logout.php">Cerrar Sesión</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="row">
        <!-- Información del Usuario -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Información del Usuario</div>
                <div class="card-body">
                    <p><strong>Correo:</strong> <?php echo htmlspecialchars($user_info['correo']); ?></p>
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user_info['nombre']); ?></p>
                    <p><strong>Apellido:</strong> <?php echo htmlspecialchars($user_info['apellido']); ?></p>
                    <p><strong>Género:</strong> <?php echo htmlspecialchars($user_info['genero']); ?></p>
                    <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($user_info['telefono']); ?></p>
                </div>
            </div>
        </div>

        <!-- Agendar Cita -->
        <div class="col-md-8">
            <div id="agendar-cita" class="card mb-4">
                <div class="card-header">Agendar Cita</div>
                <div class="card-body">
                    <form id="appointment-form">
                        <div class="form-group">
                            <label for="date">Seleccione una fecha:</label>
                            <select id="date" class="form-control">
                                <option value="">Selecciona una fecha</option>
                                <?php foreach ($available_dates as $date): ?>
                                    <option value="<?php echo $date; ?>"><?php echo date("l, d F Y", strtotime($date)); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="time">Seleccione una hora:</label>
                            <select id="time" class="form-control">
                                <option value="">Selecciona una hora</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Agendar Cita</button>
                    </form>
                </div>
            </div>

<!-- Mis Citas -->
<div id="mis-citas" class="card mb-4">
    <div class="card-header">
        <h4>Historial de Citas</h4>
    </div>
    <div class="card-body">
        <?php if (empty($appointments)): ?>
            <p class="text-center">No tienes citas agendadas.</p>
        <?php else: ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?php echo date("l, d F Y", strtotime($appointment['fecha'])); ?></td>
                            <td><?php echo date("h:i A", strtotime($appointment['hora_inicio'])); ?></td>
                            <td>
                                <?php
                                $estado = ucfirst($appointment['estado']);
                                if ($estado === 'Cancelada') {
                                    echo "<span class='badge bg-danger'>$estado</span>";
                                } elseif ($estado === 'Pendiente') {
                                    echo "<span class='badge bg-warning text-dark'>$estado</span>";
                                } else {
                                    echo "<span class='badge bg-success'>$estado</span>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($appointment['estado'] === 'cancelada' && !empty($appointment['razon_cancelacion'])): ?>
                                    <button type="button" class="btn btn-info" onclick="alert('<?php echo htmlspecialchars($appointment['razon_cancelacion']); ?>')">
                                        Ver Razón
                                    </button>
                                <?php elseif ($appointment['estado'] === 'pendiente'): ?>
                                    <button class="btn btn-danger" onclick="confirmCancel(<?php echo $appointment['id']; ?>)">Cancelar Cita</button>
                                <?php else: ?>
                                    <button class="btn btn-secondary" disabled>Sin Acción</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>



            <!-- Todas las citas disponibles -->
            <div id="citas-disponibles" class="card">
                <div class="card-header">Citas Disponibles</div>
                <div class="card-body">
                    <?php if (empty($all_appointments)): ?>
                        <p>No hay citas disponibles.</p>
                    <?php else: ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora de Inicio</th>
                                    <th>Hora de Fin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all_appointments as $appointment): ?>
                                    <tr>
                                        <td><?php echo date("l, d F Y", strtotime($appointment['fecha'])); ?></td>
                                        <td><?php echo date("h:i A", strtotime($appointment['hora_inicio'])); ?></td>
                                        <td><?php echo date("h:i A", strtotime($appointment['hora_fin'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    $('#date').change(function() {
        var selectedDate = $(this).val();
        if (selectedDate) {
            $.ajax({
                url: '../includes/get_times.php',
                type: 'GET',
                data: { date: selectedDate },
                dataType: 'json',
                success: function(data) {
                    var $timeSelect = $('#time');
                    $timeSelect.empty();
                    $timeSelect.append('<option value="">Selecciona una hora</option>');
                    $.each(data, function(index, item) {
                        $timeSelect.append('<option value="' + item.hora_inicio + '">' + item.hora_inicio + '</option>');
                    });
                },
                error: function() {
                    alert('Error al obtener las horas disponibles.');
                }
            });
        }
    });

    $('#appointment-form').submit(function(e) {
        e.preventDefault();
        var date = $('#date').val();
        var time = $('#time').val();
        if (date && time) {
            $.ajax({
                url: '../includes/schedule_appointment.php',
                type: 'POST',
                data: { date: date, time: time },
                success: function(response) {
                    alert('Cita agendada con éxito.');
                    location.reload();
                },
                error: function() {
                    alert('Error al agendar la cita.');
                }
            });
        } else {
            alert('Por favor, seleccione una fecha y una hora.');
        }
    });

    $('#cancel-form').submit(function(e) {
        e.preventDefault();
        var appointmentId = $('#appointment-id').val();
        var cancelReason = $('#razon_cancelacion').val();
        if (appointmentId && cancelReason) {
            $.ajax({
                url: '../includes/cancel_appointment.php',
                type: 'POST',
                data: { appointment_id: appointmentId, cancel_reason: cancelReason },
                success: function(response) {
                    alert('Cita cancelada con éxito.');
                    $('#cancelModal').modal('hide');
                    location.reload();
                },
                error: function() {
                    alert('Error al cancelar la cita.');
                }
            });
        } else {
            alert('Por favor, proporcione una razón para la cancelación.');
        }
    });

    $('.cancel-appointment').click(function() {
        var appointmentId = $(this).data('id');
        $('#appointment-id').val(appointmentId);
    });
});

// Código para inicializar el modal con el ID de la cita seleccionada
var cancelModal = document.getElementById('cancelModal');
    cancelModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Botón que abre el modal
        var appointmentId = button.getAttribute('data-id');
        var modal = cancelModal.querySelector('.modal-body #appointment-id');
        modal.value = appointmentId;
    });
    
    // Código para manejar el envío del formulario de cancelación
    document.getElementById('cancel-form').addEventListener('submit', function (event) {
        event.preventDefault();
        var appointmentId = document.getElementById('appointment-id').value;
        var reason = document.getElementById('cancel-reason').value;
        
        fetch('../includes/cancel_appointment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'id': appointmentId,
                'reason': reason
            })
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    });
</script>

<script src="../public/js/citas.js"></script>

</body>
</html>
