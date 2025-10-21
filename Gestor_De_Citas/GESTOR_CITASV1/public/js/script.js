document.addEventListener('DOMContentLoaded', function () {
    // Obtener el modal y el botón de cerrar
    var modal = document.getElementById("editUserModal");
    var span = document.getElementsByClassName("close")[0];

    // Abrir el modal al hacer clic en el botón de editar
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            var userId = this.getAttribute('data-id');
            fetchUserData(userId);
            modal.style.display = "block";
        });
    });

    // Cerrar el modal al hacer clic en el botón de cerrar
    span.onclick = function () {
        modal.style.display = "none";
    };

    // Cerrar el modal al hacer clic fuera del contenido
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
});

function fetchUserData(userId) {
    fetch(`../includes/get_user_data.php?id=${userId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('userId').value = data.id;
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('correo').value = data.correo;
            document.getElementById('rol').value = data.rol;
        })
        .catch(error => console.error('Error:', error));
}
