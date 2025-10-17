<?php
session_start();

// Si está la sesión activa, redirigir al main
if (isset($_SESSION['usuario'])) {
    header('Location: ../views/main.php');
    exit;
}

// Si están las cookies activas, redirigir al main
if (!isset($_SESSION['usuario']) && isset($_COOKIE['usuario_id'], $_COOKIE['usuario_email'])) {
    require_once '../models/Usuario.php';

    $usuarioModel = new Usuario();
    $userData = $usuarioModel->getById($_COOKIE['usuario_id']);

    if ($userData && $userData['email'] === $_COOKIE['usuario_email']) {
        $_SESSION['usuario'] = ['id_usuario' => $userData['id_usuario'], 'nombre' => $userData['nombre'], 'apellido' => $userData['apellido'], 'email' => $userData['email']];

        // Redirigir porque ya está logueado
        header('Location: ../views/main.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>
        if (localStorage.getItem("theme") === "dark") {
            document.documentElement.classList.add("dark");
        }
    </script>

    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">

    <link rel="icon" href="../img/logo_minip.png" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    <script src="../js/script.js?v=<?php echo time(); ?>" defer></script>

    <script src="https://kit.fontawesome.com/7b60821cfc.js" crossorigin="anonymous"></script>

    <title>Iniciar sesión</title>
</head>

<body>
    <div class="modoContainer">
        <span id="iconoSol" class="modoIcono"><i class="fa-solid fa-sun"></i></span>
        <span id="iconoLuna" class="modoIcono"><i class="fa-solid fa-moon"></i></span>
    </div>

    <div class="contBody">
        <div class="contRegIn">
            <h1>Bienvenido</h1>

            <?php if (isset($_GET['error'])): ?>
                <div style="color: red;">
                    <?php
                    switch ($_GET['error']) {
                        case 'email':
                            echo "El correo electrónico no está registrado.<br><br>";
                            break;
                        case 'pass':
                            echo "La contraseña es incorrecta.<br><br>";
                            break;
                    }
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['ok'])): ?>
                <div style="color: rgb(0, 153, 0);">
                    <?php
                    switch ($_GET['ok']) {
                        case 'modificado':
                            echo "¡Datos modificados con éxito!<br><br>";
                            break;
                        case 'registro':
                            echo "¡Cuenta creada!<br><br>";
                            break;
                    }
                    ?>
                </div>
            <?php endif; ?>

            <form action="../controllers/loginController.php" method="post">
                <input type="email" name="email" placeholder=" Correo electrónico" required><br>
                <input type="password" name="pass" placeholder=" Contraseña" required><br>

                <br>

                <label class="recordarme">
                    <input type="checkbox" name="recordarme">Recordarme
                </label>

                <br>
                <br>

                <input type="submit" value="Ingresar" name="login">

                <p>¿No tienes cuenta?</p>

                <a href="../index.php">Crea una</a>
            </form>
        </div>
    </div>
</body>

</html>