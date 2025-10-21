<?php
session_start();
require '../includes/config.php';

// Verificar si el usuario es un doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'doctor') {
    header('Location: ../public/consultorio.php');
    exit();
}

// Obtener el ID del doctor desde la sesión
$doctor_id = $_SESSION['user_id'];

// Consultar las citas del día
$query_citas_dia = $conn->prepare("SELECT c.fecha, c.hora_inicio, u.nombre, u.apellido 
                                   FROM citas c 
                                   JOIN usuarios u ON c.usuario_id = u.id 
                                   WHERE c.fecha = CURDATE() AND c.estado = 'pendiente'");
$query_citas_dia->execute();
$result_citas_dia = $query_citas_dia->get_result();

// Consultar citas pendientes para el doctor
$query_citas_pendientes = $conn->prepare("SELECT c.id, c.fecha, c.hora_inicio, u.nombre, u.apellido, u.correo, u.telefono
                                          FROM citas c 
                                          JOIN usuarios u ON c.usuario_id = u.id 
                                          WHERE c.estado = 'pendiente'");
$query_citas_pendientes->execute();
$result_citas_pendientes = $query_citas_pendientes->get_result();

// Consultar historial de citas
$query_historial = $conn->prepare("SELECT c.id, c.fecha, c.hora_inicio, c.hora_fin, c.estado, c.notas_doctor, c.razon_cancelacion, u.nombre, u.apellido
                                   FROM citas c
                                   JOIN usuarios u ON c.usuario_id = u.id
                                   WHERE c.estado IN ('completada', 'cancelada', 'pendiente')");
$query_historial->execute();
$result_historial = $query_historial->get_result();


// Función para convertir la fecha a formato en español
function formato_fecha_es($fecha) {
    setlocale(LC_TIME, 'es_ES.UTF-8');
    return strftime('%A %d de %B %Y', strtotime($fecha));
}

// Agrupar citas por fecha y ordenar las fechas
$citas_por_fecha = [];
while ($cita = $result_historial->fetch_assoc()) {
    $fecha = $cita['fecha'];
    if ($cita['estado'] !== 'pendiente') {
        if (!isset($citas_por_fecha[$fecha])) {
            $citas_por_fecha[$fecha] = [];
        }
        $citas_por_fecha[$fecha][] = $cita;
    }
}
krsort($citas_por_fecha); // Ordenar fechas de más recientes a más antiguas
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultorio - Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F4F4F4;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .header {
    background-color: #046D4D;
    color: white;
    padding: 15px 30px;
    border-radius: 5px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.header-text h1 {
    margin: 0;
    font-size: 24px;
    font-weight: bold;
}

.header-text p {
    margin: 0;
    font-size: 16px;
}

.header a.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    font-size: 16px;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s, border-color 0.3s;
}

.header a.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}
        .tab-content {
            margin-top: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .btn-primary, .btn-success{
            background-color: #046D4D !important;
            color: white;
        }
        .btn-danger{
            background-color: #dc3545 !important;
            color: white;
        }
        .btn-info{
            background-color: #f6a700;
            color: white;
            border-color: #f6a700;
        }
        .btn-info:hover{
            background-color: #7ea700;
            color: white;
        }
        .btn-primary:hover, .btn-success:hover, .btn-danger:hover {
            background-color: #034d3e;
            color: white;
        }
        .modal-header {
            background-color: #046D4D;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
<header class="header d-flex justify-content-between align-items-center">
    <div class="header-text">
        <h1>Consultorio del Doctor</h1>
        <p>Gestione sus citas y disponibilidad</p>
    </div>
    <a href="../includes/logout.php" class="btn btn-danger">Cerrar sesión</a>
</header>

    <!-- Pestañas -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="citas-dia-tab" data-bs-toggle="tab" href="#citas-dia" role="tab" aria-controls="citas-dia" aria-selected="true">Citas de Hoy</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="notas-tab" data-bs-toggle="tab" href="#notas" role="tab" aria-controls="notas" aria-selected="false">Notas del Doctor</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="citas-pendientes-tab" data-bs-toggle="tab" href="#citas-pendientes" role="tab" aria-controls="citas-pendientes" aria-selected="false">Citas Pendientes</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="horarios-tab" data-bs-toggle="tab" href="#horarios" role="tab" aria-controls="horarios" aria-selected="false">Habilitar Horarios</a>
        </li>
         <!-- Nuevo Tab para Historial de Citas -->
        <li class="nav-item" role="presentation">
        <a class="nav-link" id="historial-citas-tab" data-bs-toggle="tab" href="#historial-citas" role="tab" aria-controls="historial-citas" aria-selected="false">Historial de Citas</a>
        </li>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content" id="myTabContent">
        <!-- Citas de Hoy -->
        <div class="tab-pane fade show active" id="citas-dia" role="tabpanel" aria-labelledby="citas-dia-tab">
            <div class="card">
                <div class="card-header">
                    <h3>Citas de Hoy</h3>
                </div>
                <div class="card-body">
                    <?php if ($result_citas_dia->num_rows > 0): ?>
                        <ul class="list-group">
                            <?php while ($cita = $result_citas_dia->fetch_assoc()): ?>
                                <li class="list-group-item">
                                    <strong>Paciente:</strong> <?= $cita['nombre'] . " " . $cita['apellido'] ?>
                                    <br>
                                    <strong>Hora:</strong> <?= $cita['hora_inicio'] ?>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>No hay citas programadas para hoy.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Notas del Doctor -->
        <div class="tab-pane fade" id="notas" role="tabpanel" aria-labelledby="notas-tab">
            <div class="card">
                <div class="card-header">
                    <h3>Notas del Doctor</h3>
                </div>
                <div class="card-body">
                    <form id="notes-form">
                        <div class="mb-3">
                            <label for="appointment-id" class="form-label">Cita ID:</label>
                            <input type="number" class="form-control" id="appointment-id" required>
                        </div>
                        <div class="mb-3">
                            <label for="doctor-notes" class="form-label">Nota:</label>
                            <textarea class="form-control" id="doctor-notes" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Guardar Nota</button>
                    </form>
                </div>
            </div>
        </div>



        <!-- Historial -->
        <div class="tab-pane fade" id="historial-citas" role="tabpanel" aria-labelledby="historial-citas-tab">
            <div class="card">
                <div class="card-header">
                    <h3>Historial</h3>
                </div>
                <div class="card-body">
            <?php if (!empty($citas_por_fecha)): ?>
                <?php foreach ($citas_por_fecha as $fecha => $citas): ?>
                    <div class="mb-4">
                        <h4 class="text-primary"><?= formato_fecha_es($fecha) ?></h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Hora Inicio</th>
                                        <th>Paciente</th>
                                        <th>Estado</th>
                                        <th>Notas del Doctor</th>
                                        <th>Razón de Cancelación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($citas as $cita): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($cita['id']) ?></td>
                                            <td>
                                                <?php
                                                $hora_inicio = date('g:i A', strtotime($cita['hora_inicio']));
                                                echo htmlspecialchars($hora_inicio);
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars($cita['nombre'] . " " . $cita['apellido']) ?></td>
                                            <td>
                                                <?php
                                                switch ($cita['estado']) {
                                                    case 'completada':
                                                        echo '<span class="badge bg-success">Completada</span>';
                                                        break;
                                                    case 'cancelada':
                                                        echo '<span class="badge bg-danger">Cancelada</span>';
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars($cita['notas_doctor'] ?? 'No disponible') ?></td>
                                            <td>
                                                <?php if ($cita['estado'] === 'cancelada'): ?>
                                                    <?= htmlspecialchars($cita['razon_cancelacion'] ?? 'No disponible') ?>
                                                <?php else: ?>
                                                    No aplica
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                    No hay historial de citas.
                </div>
            <?php endif; ?>
        </div>
            </div>
        </div>


        <!-- Citas Pendientes -->
        <div class="tab-pane fade" id="citas-pendientes" role="tabpanel" aria-labelledby="citas-pendientes-tab">
            <div class="card">
                <div class="card-header">
                    <h3>Gestión de Citas Pendientes</h3>
                </div>
                <div class="card-body">
                    <?php if ($result_citas_pendientes->num_rows > 0): ?>
                        <ul id="pending-appointments" class="list-group">
                            <?php while ($cita = $result_citas_pendientes->fetch_assoc()): ?>
                                <li class="list-group-item">
                                    <strong>Paciente:</strong> <?= $cita['nombre'] . " " . $cita['apellido'] ?>
                                    <br>
                                    <strong>Fecha:</strong> <?= $cita['fecha'] ?>
                                    <br>
                                    <strong>Hora:</strong> <?= $cita['hora_inicio'] ?>
                                    <br>
                                    <strong>ID CITA:</strong> <?= $cita['id'] ?>
                                    <br>
                                    <button class="btn btn-info btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#patientInfoModal" data-id="<?= $cita['id'] ?>" data-nombre="<?= $cita['nombre'] ?>" data-apellido="<?= $cita['apellido'] ?>" data-correo="<?= $cita['correo'] ?>" data-telefono="<?= $cita['telefono'] ?>">Ver Información del Paciente</button>
                                    <button class="btn btn-success btn-sm mt-2" onclick="manageAppointment(<?= $cita['id'] ?>, 'accept')">Completada</button>
                                    <button class="btn btn-danger btn-sm mt-2" onclick="manageAppointment(<?= $cita['id'] ?>, 'reject')">Cancelar</button>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>No hay citas pendientes.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
<!-- Modal para cancelar la cita -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cancelModalLabel">Cancelar Cita</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="cancel-form">
            <div class="form-group">
                <label for="cancel-reason">Razón de la cancelación:</label>
                <textarea class="form-control" id="cancel-reason" rows="3" required></textarea>
                <input type="hidden" id="appointment-id">
            </div>
            <button type="submit" class="btn btn-danger">Cancelar Cita</button>
        </form>
      </div>
    </div>
  </div>
</div>

        <!-- Habilitar Horarios -->
        <div class="tab-pane fade" id="horarios" role="tabpanel" aria-labelledby="horarios-tab">
            <div class="card">
                <div class="card-header">
                    <h3>Habilitar Horarios</h3>
                </div>
                <div class="card-body">
                    <form id="availability-form">
                        <div class="mb-3">
                            <label for="availability-date" class="form-label">Fecha:</label>
                            <input type="date" class="form-control" id="availability-date" required>
                        </div>
                        <div class="mb-3">
                            <label for="start-time" class="form-label">Hora Inicio:</label>
                            <input type="time" class="form-control" id="start-time" required>
                        </div>
                        <div class="mb-3">
                            <label for="end-time" class="form-label">Hora Fin:</label>
                            <input type="time" class="form-control" id="end-time" required>
                        </div>
                        <button type="submit" class="btn btn-success">Habilitar Horario</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Información del Paciente -->
    <div class="modal fade" id="patientInfoModal" tabindex="-1" aria-labelledby="patientInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="patientInfoModalLabel">Información del Paciente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nombre:</strong> <span id="patient-name"></span></p>
                    <p><strong>Apellido:</strong> <span id="patient-lastname"></span></p>
                    <p><strong>Correo:</strong> <span id="patient-email"></span></p>
                    <p><strong>Teléfono:</strong> <span id="patient-phone"></span></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Mostrar citas en el calendario
    $('#calendar-date').on('change', function() {
        const date = $(this).val();
        $.get('../includes/get_available_times.php', { fecha: date }, function(response) {
            const times = JSON.parse(response);
            let html = '<ul class="list-group">';
            if (times.length > 0) {
                times.forEach(time => {
                    html += `<li class="list-group-item">${time.hora_inicio} - ${time.hora_fin}</li>`;
                });
            } else {
                html += '<li class="list-group-item">No hay horarios disponibles.</li>';
            }
            html += '</ul>';
            $('#calendar-appointments').html(html);
        });
    });

    // Guardar notas del doctor
    $('#notes-form').on('submit', function(event) {
        event.preventDefault();
        $.post('../includes/save_notes.php', {
            appointment_id: $('#appointment-id').val(),
            notes: $('#doctor-notes').val()
        }, function(response) {
            alert(response);
            $('#notes-form')[0].reset();
        });
    });

    // Habilitar horarios disponibles
    $('#availability-form').on('submit', function(event) {
        event.preventDefault();
        $.post('../includes/set_availability.php', {
            fecha: $('#availability-date').val(),
            hora_inicio: $('#start-time').val(),
            hora_fin: $('#end-time').val()
        }, function(response) {
            alert(response);
            $('#availability-form')[0].reset();
        });
    });
    // Gestionar citas pendientes
function manageAppointment(appointmentId, action) {
    if (action === 'reject') {
        // Mostrar el modal para la razón de cancelación
        $('#appointment-id').val(appointmentId); // Pasar el ID al campo oculto
        $('#cancelModal').modal('show');
    } else {
        // Aceptar la cita sin razón de cancelación
        $.post('../includes/manage_appointments.php', {
            id: appointmentId,
            action: 'aceptar'
        }, function(response) {
            let result = JSON.parse(response);
            alert(result.message);
            if (result.status === 'success') {
                location.reload();
            }
        });
    }
}

// Enviar el formulario de cancelación
$('#cancel-form').submit(function(event) {
    event.preventDefault(); // Evitar el envío estándar del formulario
    const appointmentId = $('#appointment-id').val();
    const reason = $('#cancel-reason').val();

    $.post('../includes/manage_appointments.php', {
        id: appointmentId,
        action: 'rechazar',
        razon_cancelacion: reason
    }, function(response) {
        let result = JSON.parse(response);
        alert(result.message);
        if (result.status === 'success') {
            location.reload();
        }
    });
});
    // Mostrar información del paciente en el modal
    $('#patientInfoModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var nombre = button.data('nombre');
        var apellido = button.data('apellido');
        var correo = button.data('correo');
        var telefono = button.data('telefono');
        var modal = $(this);
        modal.find('#patient-name').text(nombre);
        modal.find('#patient-lastname').text(apellido);
        modal.find('#patient-email').text(correo);
        modal.find('#patient-phone').text(telefono);
    });
</script>
</body>
</html>
