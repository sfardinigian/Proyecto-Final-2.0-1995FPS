<?php
session_start();
require_once '../controllers/actividadController.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ../views/inicio.php?error=sesion');
    exit;
}

$controller = new ActividadController();
$data = $controller->obtenerPorPrioridad();

// Enviamos el JSON
header('Content-Type: application/json');
echo json_encode($data);
