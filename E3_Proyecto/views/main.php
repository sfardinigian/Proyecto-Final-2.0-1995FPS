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

    <link rel="icon" href="../img/logo_minip.png" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    <script src="../js/script.js" defer></script>

    <script src="https://kit.fontawesome.com/7b60821cfc.js" crossorigin="anonymous"></script>

    <title>Cronos</title>
</head>

<body>
    <header>
        <div class="logoC">
            <div class="logoPrincipal"></div>
        </div>

        <div class="botonesIn">
            <a href="#inicio" class="b1"><i class="fa-solid fa-house"></i> Inicio</a>
            <a href="#actividades" class="b1"><i class="fa-solid fa-bars"></i> Actividades</a>
            <a href="#graficos" class="b1"><i class="fa-solid fa-chart-simple"></i> Gráficos</a>
            <a href="#recordatorios" class="b1"><i class="fa-solid fa-bell"></i> Recordatorios</a>

            <a class="b2" id="botonMenu"><i class="fa-solid fa-bars"></i></a>
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

                <form action="../controllers/logoutController.php" class="espaciar" onsubmit="return confirm('¿Seguro que quieres cerrar sesión?');">
                    <input type="submit" value="Cerrar sesión">
                </form>

                <form action="../controllers/deleteController.php" class="espaciar" onsubmit="return confirm('⚠️ ¿Seguro que quieres eliminar tu cuenta? Esta acción no se puede deshacer.');">
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

    <div class="contInv">
        <section class="contenidos" id="inicio">
            <div class="contenidos1">
                <h1>Hola <?php echo htmlspecialchars($usuarioSesion['nombre']); ?>, bienvenido a Cronos <i class="fa-solid fa-alarm-clock"></i></h1>
            </div>
            <div class="contenidos2">
                <h3>¿Sentís que el día no te alcanza o que a veces olvidás tareas importantes?</h3>
                <p><b>¡Bienvenido a Cronos, tu asistente personal de organización!</b></p>
                <p>En un mundo lleno de compromisos, estudiar, trabajar y tener tiempo personal puede parecer un desafío. Cronos nace para ayudarte a planificar tus actividades de forma simple, visual y eficiente, dándote el control de tu tiempo.</p>
                <p><b>Con Cronos vas a poder:</b></p>
                <ul>
                    <li>📅 Agendar tus actividades diarias indicando hora de inicio y de finalización.</li>
                    <li>⏰ Configurar recordatorios inteligentes para que nunca olvides una reunión, una entrega o incluso un momento personal importante.</li>
                    <li>📊 Visualizar tu carga horaria con gráficos intuitivos, que te muestran de un vistazo cómo estás distribuyendo tu tiempo.</li>
                    <li>🔔 Recibir notificaciones claras y oportunas, para mantenerte siempre al día sin estrés.</li>
                    <li>🎯 Planificar tu semana de manera equilibrada, combinando estudio, trabajo y ocio.</li>
                </ul>
                <p>Ya sea que quieras organizar tus estudios, tus proyectos laborales o simplemente tu día a día, Cronos es tu aliado para:</p>
                <ul>
                    <li><b>Aprovechar mejor cada jornada.</b></li>
                    <li><b>Mantener un orden claro y sin complicaciones.</b></li>
                    <li><b>Alcanzar tus metas con más tranquilidad y confianza.</b></li>
                </ul>
                <p>✨ Con Cronos, la organización deja de ser una carga y se convierte en una herramienta que te da más tiempo para lo que realmente importa.</p>
            </div>
        </section>

        <div class="contenidos" id="actividades">
            <section class="contenidos1">
                <h1>Listado de actividades <i class="fa-solid fa-clipboard-list"></i></h1>
            </section>
            <section class="contenidos2">
                <table>
                    <tr>
                        <th><h3>Casuales 🟢</h3></th>
                        <th><h3>Normales 🟡</h3></th>
                        <th><h3>Importantes 🔴</h3></th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>2</td>
                        <td>3</td>
                    </tr>
                </table>
            </section>
        </div>

        <div class="contenidos" id="graficos">
            <section class="contenidos1">
                <h1>Gráfico semanal <i class="fa-solid fa-calendar-days"></i></i></h1>
            </section>
            <section class="contenidos2">
                <p>Contenido</p>
            </section>
        </div>

        <div class="contenidos">
            <section class="contenidos1">
                <h1>Gráfico diario <i class="fa-solid fa-calendar-day"></i></h1>
            </section>
            <section class="contenidos2">
                <p>Contenido</p>
            </section>
        </div>

        <div class="contenidos" id="recordatorios">
            <section class="contenidos1">
                <h1>Recordatorios <i class="fa-solid fa-comment"></i></h1>
            </section>
            <section class="contenidos2">
                <p>Contenido</p>
            </section>
        </div>
    </div>
</body>

</html>