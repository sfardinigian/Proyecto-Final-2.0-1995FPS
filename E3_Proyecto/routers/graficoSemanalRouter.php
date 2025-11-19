<?php
session_start();
require_once "../controllers/actividadController.php";

if (!isset($_SESSION['usuario'])) {
    header('Location: ../views/inicio.php?error=sesion');
    exit;
}

// Obtenemos el ID del usuario desde la sesión
$id_usuario = $_SESSION['usuario']['id_usuario'];

$controller = new actividadController();
$data = $controller->obtenerActividadesSemanales($id_usuario);

header('Content-Type: application/json');
echo json_encode($data);
