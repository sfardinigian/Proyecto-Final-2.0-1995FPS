<?php
session_start();
require_once "../controllers/actividadController.php";

$controlador = new actividadController();
$id_usuario = $_SESSION['usuario']['id_usuario'];

$datos = $controlador->obtenerActividadesPorDia($id_usuario);

header('Content-Type: application/json');
echo json_encode($datos);
