<?php
session_start();
require_once "../controllers/DashboardController.php";

$controller = new DashboardController();
$data = $controller->obtenerHoras();

header('Content-Type: application/json');
echo json_encode($data);
