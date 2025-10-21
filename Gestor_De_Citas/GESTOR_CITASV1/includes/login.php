<?php
session_start();
require_once 'config.php';

// Si el usuario ya está logueado, redirige según su rol
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] == 'administrador') {
        header('Location: ../views/admin.php');
        exit();
    } elseif ($_SESSION['user_role'] == 'doctor') {
        header('Location: ../views/consultorio.php');
        exit();
    } else {
        header('Location: ../views/user.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, rol, password FROM usuarios WHERE correo = ?");
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $role, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_role'] = $role;

            // Redirige al usuario según su rol
            if ($role == 'administrador') {
                header('Location: ../views/admin.php');
            } elseif ($role == 'doctor') {
                header('Location: ../views/consultorio.php');
            } else {
                header('Location: ../views/user.php');
            }
            exit();
        } else {
            $error_message = "Contraseña incorrecta.";
        }
    } else {
        $error_message = "Correo electrónico no registrado.";
    }

    if ($stmt->error) {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F4F4F4;
            font-family: Arial, sans-serif;
        }

        .login-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            border-radius: 1rem;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            background-color: #ffffff;
            padding: 2rem;
            max-width: 400px;
            width: 100%;
        }

        .login-header {
            background-color: #046D4D;
            color: white;
            border-radius: 1rem 1rem 0 0;
            padding: 1.5rem;
            text-align: center;
        }

        .login-header h1 {
            font-size: 1.8rem;
            margin: 0;
        }

        .btn-custom {
            background-color: #046D4D;
            border-color: #046D4D;
            color: white;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .btn-custom:hover {
            background-color: #035A3D;
            border-color: #035A3D;
        }

        .form-outline input {
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-outline label {
            font-size: 0.9rem;
        }

        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .login-card {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header mb-4">
                <h1>Iniciar sesión</h1>
            </div>
            <form action="" method="post">
                <?php if (isset($error_message)): ?>
                    <div class="error-message">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <div class="form-outline mb-4">
                    <label class="form-label" for="form2Example18">Correo electrónico</label>
                    <input type="email" id="form2Example18" name="email" class="form-control form-control-lg" required />
                </div>
                <div class="form-outline mb-4">
                    <label class="form-label" for="form2Example28">Contraseña</label>
                    <input type="password" id="form2Example28" name="password" class="form-control form-control-lg" required />
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-custom btn-lg">Iniciar sesión</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
