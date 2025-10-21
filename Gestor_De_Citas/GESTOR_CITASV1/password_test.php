<?php
// Contraseña en texto plano
$password = 'adminpassword';

// Cifra la contraseña
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo "Contraseña en texto plano: $password<br>";
echo "Contraseña cifrada: $hashed_password<br>";

// Verifica la contraseña
if (password_verify($password, $hashed_password)) {
    echo "La contraseña es válida.";
} else {
    echo "La contraseña no es válida.";
}
?>
