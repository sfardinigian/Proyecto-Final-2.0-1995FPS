<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: inicio.php?error=sesion');
    exit;
}

require_once '../controllers/usuarioController.php';
require_once '../controllers/actividadController.php';

$actividadController = new Actividad();

$usuarioSesion = $_SESSION['usuario'];
$id_usuario = $usuarioSesion['id_usuario'];

$actividades = $actividadController->getByUsuario($id_usuario);
?>

<!DOCTYPE html>
<html>

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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="../js/script.js?v=<?php echo time(); ?>" defer></script>

    <script src="https://kit.fontawesome.com/7b60821cfc.js" crossorigin="anonymous"></script>

    <title>Cronos</title>
</head>

<body>
    <div id="fondoBlur"></div>
    <div id="particulasRelojes"></div>

    <header>
        <div class="logoC">
            <div class="logoPrincipal"></div>
        </div>

        <div class="botonesIn">
            <a href="#inicio" class="b1"><i class="fa-solid fa-house"></i> Inicio</a>
            <a href="#actividades" class="b1"><i class="fa-solid fa-clipboard"></i></i> Actividades</a>
            <a href="#graficos" class="b1"><i class="fa-solid fa-chart-simple"></i> Gráficos</a>
            <a href="#creditos" class="b1"><i class="fa-solid fa-users"></i> Créditos</a>

            <a href="#inicio" class="b2"><i class="fa-solid fa-house"></i></a>
            <a href="#actividades" class="b2"><i class="fa-solid fa-clipboard"></i></i></a>
            <a href="#graficos" class="b2"><i class="fa-solid fa-chart-simple"></i></a>
            <a href="#creditos" class="b2"><i class="fa-solid fa-users"></i></a>
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

            <div class="dataC">
                <p class="subrayado"><?php echo htmlspecialchars($usuarioSesion['nombre']); ?></p>
                <p class="subrayado"><?php echo htmlspecialchars($usuarioSesion['apellido']); ?></p>
                <p class="subrayado"><?php echo htmlspecialchars($usuarioSesion['email']); ?></p>
            </div>

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
                    <span id="iconoSol" class="modoIcono"><i class="fa-solid fa-sun"></i></span>
                    <span id="iconoLuna" class="modoIcono"><i class="fa-solid fa-moon"></i></span>
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
                <h1>Listado de actividades <i class="fa-solid fa-bars-staggered"></i></h1>
            </section>
            <section class="contenidos2">
                <div class="cargarAct">
                    <h2 class="tituloAgregar">Agregar actividad</h2>

                    <form action="../routers/agregarActRouter.php" method="post">
                        <input type="text" name="titulo" placeholder=" Nombre de la actividad" required><br>

                        <div class="alignD">
                            <div class="hiDiv">
                                <label for="hora_inicio">Hora de inicio</label>
                                <input type="time" name="hora_inicio" required>
                            </div>

                            <div class="hfDiv">
                                <label for="hora_fin">Hora de fin</label>
                                <input type="time" name="hora_fin" required>
                            </div>

                            <div class="cDiv">
                                <label for="color">Color</label>
                                <input type="color" name="color">
                            </div>

                            <select name="prioridad" required>
                                <option value="" disabled selected hidden>Prioridad</option>
                                <option value="Casual">Casual</option>
                                <option value="Normal">Normal</option>
                                <option value="Importante">Importante</option>
                            </select>

                            <select name="dia" required>
                                <option value="" disabled selected hidden>Día</option>
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miércoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sábado">Sábado</option>
                                <option value="Domingo">Domingo</option>
                            </select>
                        </div>

                        <div class="centrarAg">
                            <input type="submit" value="Agregar" name="agregar" id="boton">
                        </div>
                    </form>

                    <?php if (isset($_GET['error'])): ?>
                        <div style="color: red; text-align:center; margin-top: 25px;">
                            <?php
                            switch ($_GET['error']) {
                                case 'titulo':
                                    echo "El título es inválido.";
                                    break;
                                case 'horaInicio':
                                    echo "La hora de inicio es inválida.";
                                    break;
                                case 'horaFin':
                                    echo "La hora de fin es inválida.";
                                    break;
                                case 'horasInvalidas':
                                    echo "La hora de inicio no puede ser mayor a la de fin.";
                                    break;
                                case 'color':
                                    echo "El color es inválido.";
                                    break;
                                case 'prioridad':
                                    echo "La prioridad es inválida.";
                                    break;
                                case 'dia':
                                    echo "El día es inválido.";
                                    break;
                                case 'solapamiento':
                                    echo "Las actividades no se pueden solapar en el mismo día.";
                                    break;
                                case 'noExiste':
                                    echo "La actividad que quiere eliminar no existe.";
                                    break;
                                case 'desconocido':
                                    echo "Ha ocurrido un error desconocido.";
                                    break;
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['ok'])): ?>
                        <div style="color: rgb(0, 153, 0); text-align:center; margin-top: 25px;">
                            <?php
                            switch ($_GET['ok']) {
                                case 'actividadAgregada':
                                    echo "¡Actividad agregada con éxito!";
                                    break;
                                case 'eliminar':
                                    echo "¡Actividad eliminada con éxito!";
                                    break;
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['error']) || isset($_GET['ok'])): ?>
                        <script>
                            window.location.hash = 'actividades';
                        </script>
                    <?php endif; ?>
                </div>

                <div class="tablaCont">
                    <table>
                        <tr>
                            <th>
                                <h3 style="color: rgb(0, 153, 0);">Casuales</h3>
                            </th>
                        </tr>
                        <?php if (!empty(array_filter((array)$actividades, fn($a) => is_array($a) && $a['prioridad'] === 'Casual'))): ?>
                            <?php foreach ($actividades as $act): ?>
                                <?php if ($act['prioridad'] === 'Casual'): ?>
                                    <tr>
                                        <td>
                                            <div class="datosAct">
                                                <div class="supCont">
                                                    <div class="col" style="background-color: <?= $act['color'] ?>;"></div>
                                                    <p><?= $act['titulo'] ?></p>
                                                    <form action="../routers/eliminarActRouter.php" method="post" style="display: inline;">
                                                        <input type="hidden" name="id_actividad" value="<?= $act['id_actividad'] ?>">
                                                        <button type="submit" name="eliminar" class="del">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="infCont">
                                                    <div class="horario">
                                                        <p><?= date("H:i", strtotime($act['hora_inicio'])) ?> - <?= date("H:i", strtotime($act['hora_fin'])) ?></p>
                                                    </div>
                                                    <div class="dia">
                                                        <p><?= $act['dia'] ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td>
                                    <div class="actVac">
                                        <h4>No tiene actividades casuales.</h4>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </table>

                    <table>
                        <tr>
                            <th>
                                <h3 style="color: orange;">Normales</h3>
                            </th>
                        </tr>
                        <?php if (!empty(array_filter((array)$actividades, fn($a) => is_array($a) && $a['prioridad'] === 'Normal'))): ?>
                            <?php foreach ($actividades as $act): ?>
                                <?php if ($act['prioridad'] === 'Normal'): ?>
                                    <tr>
                                        <td>
                                            <div class="datosAct">
                                                <div class="supCont">
                                                    <div class="col" style="background-color: <?= $act['color'] ?>;"></div>
                                                    <p><?= $act['titulo'] ?></p>
                                                    <form action="../routers/eliminarActRouter.php" method="post" style="display: inline;">
                                                        <input type="hidden" name="id_actividad" value="<?= $act['id_actividad'] ?>">
                                                        <button type="submit" name="eliminar" class="del">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="infCont">
                                                    <div class="horario">
                                                        <p><?= date("H:i", strtotime($act['hora_inicio'])) ?> - <?= date("H:i", strtotime($act['hora_fin'])) ?></p>
                                                    </div>
                                                    <div class="dia">
                                                        <p><?= $act['dia'] ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td>
                                    <div class="actVac">
                                        <h4>No tiene actividades normales.</h4>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </table>

                    <table>
                        <tr>
                            <th>
                                <h3 style="color: red;">Importantes</h3>
                            </th>
                        </tr>
                        <?php if (!empty(array_filter((array)$actividades, fn($a) => is_array($a) && $a['prioridad'] === 'Importante'))): ?>
                            <?php foreach ($actividades as $act): ?>
                                <?php if ($act['prioridad'] === 'Importante'): ?>
                                    <tr>
                                        <td>
                                            <div class="datosAct">
                                                <div class="supCont">
                                                    <div class="col" style="background-color: <?= $act['color'] ?>;"></div>
                                                    <p><?= $act['titulo'] ?></p>
                                                    <form action="../routers/eliminarActRouter.php" method="post" style="display: inline;">
                                                        <input type="hidden" name="id_actividad" value="<?= $act['id_actividad'] ?>">
                                                        <button type="submit" name="eliminar" class="del">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="infCont">
                                                    <div class="horario">
                                                        <p><?= date("H:i", strtotime($act['hora_inicio'])) ?> - <?= date("H:i", strtotime($act['hora_fin'])) ?></p>
                                                    </div>
                                                    <div class="dia">
                                                        <p><?= $act['dia'] ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td>
                                    <div class="actVac">
                                        <h4>No tiene actividades importantes.</h4>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </section>
        </div>

        <div class="contenidos" id="graficos">
            <section class="contenidos1">
                <h1>Gráfico semanal <i class="fa-solid fa-calendar-days"></i></h1>
            </section>

            <section class="contenidos2">
                <?php if (!empty($actividades) && !isset($actividades['message'])) { ?>
                    <div class="graficoSiV">
                        <div id="leyendaSemanal" style="margin-top: 20px;"></div>
                        <canvas id="graficoSemanal" width="600" height="300"></canvas>
                    </div>
                    <div class="graficoNoV">
                        <h3 class="graficoAlerta">⚠️ Gire su celular para visualizar el gráfico.</h3>
                    </div>
                <?php } else { ?>
                    <div class="noActividad">
                        <div class="noActividad1">
                            <h3>🚫 No tiene actividades para mostrar en el gráfico semanal.</h3>
                        </div>
                    </div>
                <?php } ?>
            </section>
        </div>

        <div class="contenidos">
            <section class="contenidos1">
                <h1>Gráfico prioritario <i class="fa-solid fa-chart-pie"></i></h1>
            </section>

            <section class="contenidos2">
                <?php if (!empty($actividades) && !isset($actividades['message'])) { ?>
                    <br>
                    <div class="grafico-wrapper">
                        <div class="info-prioridad" id="infoPrioridad">
                            <h2 id="tituloPrioridad">Seleccioná una prioridad</h2>
                            <p id="detallePrioridad">Pasá el cursor por el gráfico para ver detalles.</p>

                            <div class="resumen-general" id="resumenGeneral">
                                <h3>Resumen semanal</h3>
                                <p id="mensajeGeneral">Analizando tus actividades...</p>
                            </div>
                        </div>

                        <div class="grafico-container">
                            <canvas id="graficoPrioridad"></canvas>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="noActividad">
                        <div class="noActividad1">
                            <h3>🚫 No tiene actividades para mostrar sus prioridades.</h3>
                        </div>
                    </div>
                <?php } ?>
            </section>
        </div>

        <div class="contenidos">
            <section class="contenidos1">
                <h1>Gráfico informativo <i class="fa-solid fa-circle-info"></i></h1>
            </section>

            <section class="contenidos2">
                <?php if (!empty($actividades) && !isset($actividades['message'])) { ?>
                    <div class="graficoSiV">
                        <div class="cont-inf">
                            <div id="resumenInformativo" class="resumen-informativo">
                                <h3 id="tituloInformativo">Resumen semanal</h3>
                                <p id="mensajeInformativo">Analizando tus actividades...</p>
                            </div>

                            <div id="contenedorInfo" class="grafico-container2">
                                <canvas id="graficoInformativo"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="graficoNoV">
                        <h3 class="graficoAlerta">⚠️ Gire su celular para visualizar el gráfico.</h3>
                    </div>
                <?php } else { ?>
                    <div class="noActividad">
                        <div class="noActividad1">
                            <h3>🚫 No tiene actividades para mostrar información.</h3>
                        </div>
                    </div>
                <?php } ?>
            </section>
        </div>
    </div>

    <footer id="creditos">
        <div class="footerContenido">
            <h3 class="footerTitulo"><i class="fa-solid fa-clock"></i> Proyecto Final - Cronos</h3>

            <p class="footerTexto">
                Desarrollado por <b>Villada Gonzalo</b> y <b>Sfardini Gian</b><br>
                Tecnicatura Superior en Análisis y Desarrollo de Software - 3er Año
            </p>

            <p class="footerDerechos">© 2025 Cronos. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>