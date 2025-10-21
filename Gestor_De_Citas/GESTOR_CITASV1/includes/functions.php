<?php
// Función para generar un token CSRF
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Función para validar un token CSRF
function validate_csrf_token($token) {
    return hash_equals($_SESSION['csrf_token'], $token);
}
?>
