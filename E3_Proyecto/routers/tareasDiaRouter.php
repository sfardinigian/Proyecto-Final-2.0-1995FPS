<?php
session_start();
require_once "../controllers/dashboardController.php";

if (!isset($_SESSION['usuario'])) {
    header('Location: ../views/inicio.php?error=sesion');
    exit;
}

$controller = new DashboardController();
$data = $controller->obtenerTareasDia();

header('Content-Type: application/json');
echo json_encode($data);
