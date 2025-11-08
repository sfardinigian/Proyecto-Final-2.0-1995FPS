<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: inicio.php?error=sesion');
    exit;
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
            <h1>Modificación</h1>

            <?php if (isset($_GET['error'])): ?>
                <div style="color: red;">
                    <?php
                    switch ($_GET['error']) {
                        case 'nombre':
                            echo "El nombre sólo debe tener letras.<br><br>";
                            break;
                        case 'apellido':
                            echo "El apellido sólo debe tener letras.<br><br>";
                            break;
                        case 'pass':
                            echo "La contraseña debe tener al menos 8 caracteres, incluir letras y números.<br><br>";
                            break;
                        case 'passNoCoincide':
                            echo "Las contraseñas no coinciden.<br><br>";
                            break;
                        case 'faltaAnterior':
                            echo "Ingrese su contraseña anterior.<br><br>";
                            break;
                        case 'passAnterior':
                            echo "La contraseña anterior no coincide.<br><br>";
                            break;
                        case 'desconocido':
                            echo "Ha ocurrido un error desconocido.<br><br>";
                            break;
                    }
                    ?>
                </div>
            <?php endif; ?>

            <form action="../controllers/modifController.php" method="post">
                <input type="text" name="nombre" placeholder=" Nombre" required><br>
                <input type="text" name="apellido" placeholder=" Apellido" required><br>

                <br>

                <input type="password" name="passAnterior" placeholder=" Contraseña anterior" required><br>

                <br>

                <input id="contrasenia" type="password" name="passNueva" placeholder=" Contraseña nueva (opcional)"><br>
                <input type="password" name="passNueva2" placeholder=" Repita la contraseña"><br>

                <div class="cen">
                    <div class="validPass">
                        <div class="validPassH">
                            <h4>La contraseña debe contener</h4>
                        </div>
                        
                        <div class="validPassP">
                            <div id="longitud" class="requerimiento"><i class="fa-solid fa-circle-xmark"></i> 8 caracteres</div>
                            <div id="numero" class="requerimiento"><i class="fa-solid fa-circle-xmark"></i> 1 número</div>
                            <div id="letra" class="requerimiento"><i class="fa-solid fa-circle-xmark"></i> 1 letra</div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['usuario']['id_usuario']; ?>">

                <input type="submit" value="Modificar" name="modificar">

                <br>
                <br>

                <a href="main.php">Volver</a>
            </form>
        </div>
    </div>
</body>

</html>