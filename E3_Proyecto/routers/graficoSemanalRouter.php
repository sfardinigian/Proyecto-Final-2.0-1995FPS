<?php
session_start();
require_once "../models/Actividad.php";

if (!isset($_SESSION['usuario'])) {
    header('Location: ../views/inicio.php?error=sesion');
    exit;
}

// Recuperamos el ID del usuario
$id_usuario = $_SESSION['usuario']['id_usuario'];

$actividad = new Actividad();
$datos = $actividad->getActividadesSemanales($id_usuario);

// Enviamos el JSON
header('Content-Type: application/json');
echo json_encode($datos);
?>