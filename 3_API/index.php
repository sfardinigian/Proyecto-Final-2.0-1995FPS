<?php
session_start();

// Si está la sesión activa, redirigir al main
if (isset($_SESSION['usuario']))
{
    header('Location: views/main.php');
    exit;
}

// Si están las cookies activas, redirigir al main
if (!isset($_SESSION['usuario']) && isset($_COOKIE['usuario_id'], $_COOKIE['usuario_email']))
{
    require_once 'models/Usuario.php';

    $usuarioModel = new Usuario();
    $userData = $usuarioModel->getById($_COOKIE['usuario_id']);

    if ($userData && $userData['email'] === $_COOKIE['usuario_email'])
    {
        $_SESSION['usuario'] = ['id_usuario' => $userData['id_usuario'], 'nombre' => $userData['nombre'], 'apellido' => $userData['apellido'], 'email' => $userData['email']];

        // Redirigir porque ya está logueado
        header('Location: views/main.php');
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
        if (localStorage.getItem("theme") === "dark")
        {
            document.documentElement.classList.add("dark");
        }
    </script>

    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">

    <link rel="icon" href="img/logo_minip.png" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    <script src="js/script.js" defer></script>

    <title>Registrarse</title>
</head>

<body>
    <div class="modoContainer">
        <span id="iconoSol" class="modoIcono">☀︎</span>
        <span id="iconoLuna" class="modoIcono">🌙︎</span>
    </div>

    <div class="contBody">
        <div class="contRegIn">
            <h1>Bienvenido</h1>

            <?php if (isset($_GET['error'])): ?>
                <div style="color:red;">
                    <?php
                    switch ($_GET['error'])
                    {
                        case 'nombre':
                            echo "El nombre sólo debe tener letras.<br><br>";
                            break;
                        case 'apellido':
                            echo "El apellido sólo debe tener letras.<br><br>";
                            break;
                        case 'email':
                            echo "El correo electrónico no es válido.<br><br>";
                            break;
                        case 'pass':
                            echo "La contraseña debe tener al menos 8 caracteres, incluir letras y números.<br><br>";
                            break;
                        case 'passNoCoincide':
                            echo "Las contraseñas no coinciden.<br><br>";
                            break;
                        case 'emailExiste':
                            echo "El correo electrónico ya está registrado.<br><br>";
                            break;
                        case 'desconocido':
                            echo "Ha ocurrido un error desconocido.<br><br>";
                            break;
                    }
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['ok'])): ?>
                <div style="color:green;">
                    <?php
                    switch ($_GET['ok'])
                    {
                        case 'cuentaEliminada':
                            echo "¡Cuenta eliminada con éxito!<br><br>";
                            break;
                    }
                    ?>
                </div>
            <?php endif; ?>

            <form action="controllers/usuarioController.php" method="post">
                <input type="text" name="nombre" placeholder=" Nombre" required><br>
                <input type="text" name="apellido" placeholder=" Apellido" required><br>
                <input type="email" name="email" placeholder=" Correo electrónico" required><br>

                <br>

                <input type="password" name="pass" placeholder=" Contraseña" required><br>
                <input type="password" name="pass2" placeholder=" Repita la contraseña" required><br>

                <br>

                <input type="submit" value="Registrarse" name="registrar" id="boton">

                <p>¿Ya tienes una cuenta?</p>
                
                <a href="views/inicio.php">Iniciar sesión</a>
            </form>
        </div>
    </div>
</body>

</html>