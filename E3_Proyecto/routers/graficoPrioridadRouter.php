<?php
session_start();
require_once __DIR__ . '/../controllers/actividadController.php';

if (!isset($_SESSION['usuario'])) {
    header('HTTP/1.1 403 Unauthorized');
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

$controller = new ActividadController();
$data = $controller->obtenerPorPrioridad();

header('Content-Type: application/json');
echo json_encode($data);
