<?php
session_start();

if (!isset($_SESSION['usuario']))
{
    header('Location: inicio.php?error=sesion');
    exit;
}

require_once '../controllers/usuarioController.php';

$usuarioSesion = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>
        if (localStorage.getItem("theme") === "dark")
        {
            document.documentElement.classList.add("dark");
        }
    </script>

    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">

    <link rel="icon" href="../img/logo_mini.png" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    <script src="../js/script.js" defer></script>

    <title>Página principal</title>
</head>

<body>
    <header>
        <div class="logoC">
            <img src="../img/logo_mini.png" alt="" height="89px" width="89px">
        </div>

        <div class="botonesIn">
            <a href="#inicio" class="b1">Inicio</a>
            <a href="#tareas" class="b1">Tareas</a>
            <a href="#graficos" class="b1">Gráficos</a>
            <a href="#recordatorios" class="b1">Recordatorios</a>
        </div>

        <div class="cuentaC">
            <button id="cuentaBoton" class="cuentaLogo"></button>
        </div>
    </header>

    <div class="cuentaCont">
        <div id="cuentaDatos" class="cuentaDatosCss">
            <span id="cerrarMenu" class="cerrarMenu">&times;</span>

            <div class="cent">
                <h1>Cuenta</h1>
            </div>

            <p class="subrayado"><?php echo htmlspecialchars($usuarioSesion['nombre']); ?></p>
            <p class="subrayado"><?php echo htmlspecialchars($usuarioSesion['apellido']); ?></p>
            <p class="subrayado"><?php echo htmlspecialchars($usuarioSesion['email']); ?></p>

            <div class="cent2">
                <form action="modif.php" class="espaciar">
                    <input type="submit" value="Modificar datos">
                </form>

                <form action="../controllers/logoutController.php" class="espaciar">
                    <input type="submit" value="Cerrar sesión">
                </form>

                <form action="../controllers/deleteController.php" class="espaciar">
                    <input type="submit" value="Eliminar cuenta">
                </form>
            </div>

            <div class="cent3">
                <div class="modoContainer">
                    <span id="iconoSol" class="modoIcono">☀︎</span>
                    <span id="iconoLuna" class="modoIcono">🌙︎</span>
                </div>
            </div>
        </div>
    </div>

    <section class="presentacion" id="inicio">
        <h1>Hola <?php echo htmlspecialchars($usuarioSesion['nombre']); ?>, bienvenido a "Cronos"</h1>
    </section>

    <section class="tareas" id="tareas">
        <p>Tareas</p>
    </section>

    <section class="grafS" id="graficos">
        <p>Gráfico semanal</p>
    </section>

    <section class="grafD">
        <p>Gráfico diario</p>
    </section>
    
    <section class="recordatorios" id="recordatorios">
        <p>Recordatorios</p>
    </section>
</body>

</html>