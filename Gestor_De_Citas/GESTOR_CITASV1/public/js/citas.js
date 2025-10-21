// js/citas.js

function confirmCancel(appointmentId) {
    const confirmation = confirm("¿Estás seguro de que deseas cancelar esta cita? La razón será registrada como 'Cancelado por el usuario'.");

    if (confirmation) {
        fetch('../includes/ca.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                appointment_id: appointmentId,
                razon_cancelacion: 'Cancelado por el usuario'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                location.reload(); // Recargar la página para actualizar la lista de citas
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cancelar la cita.');
        });
    }
}
