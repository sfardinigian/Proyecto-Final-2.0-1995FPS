<?php
session_start();
require_once '../controllers/actividadController.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ../views/inicio.php?error=sesion');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    $actividadController = new actividadController();
    $actividadController->crearActividad($_POST);
}
