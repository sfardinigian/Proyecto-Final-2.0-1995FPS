<?php
session_start();
require_once "../controllers/DashboardController.php";

$controller = new DashboardController();
$data = $controller->obtenerProximaActividad();

header('Content-Type: application/json');
echo json_encode($data);
