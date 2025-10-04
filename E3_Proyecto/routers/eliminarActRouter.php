<?php
session_start();
require_once __DIR__ . '/../models/Actividad.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ../views/inicio.php?error=sesion');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $actividad = new Actividad();

    // Verificamos que venga un ID válido
    $id = intval($_POST['id_actividad']);
    if ($id <= 0) {
        header('Location: ../views/main.php?error=noExiste#actividades');
        exit;
    }

    $resultado = $actividad->delete($id);

    if ($resultado) {
        header('Location: ../views/main.php?ok=eliminar#actividades');
    } else {
        header('Location: ../views/main.php?error=desconocido#actividades');
    }
    exit;
}
