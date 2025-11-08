<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../controllers/mailController.php';

// Verificamos que haya sesión activa
if (!isset($_SESSION['usuario'])) {
    header("Location: ../views/inicio.php?error=sesion");
    exit;
}

$id = $_SESSION['usuario']['id_usuario'];

$usuario = new Usuario();
$datosUsuario = $usuario->getById($id);

// Intentamos borrar el usuario
if ($usuario->delete($id)) {
    // Enviamos mail de eliminación de cuenta
    $mailController = new MailController();
    $mailController->enviarMailEliminacion($datosUsuario['email'], $datosUsuario['nombre']);

    // Borramos la sesión
    $_SESSION = [];
    session_destroy();

    // Borramos las cookies
    if (isset($_COOKIE['usuario_id'])) {
        setcookie('usuario_id', '', time() - 3600, "/");
    }
    if (isset($_COOKIE['usuario_email'])) {
        setcookie('usuario_email', '', time() - 3600, "/");
    }

    header("Location: ../index.php?ok=cuentaEliminada");
    exit;
} else {
    header("Location: ../views/main.php?error=delete");
    exit;
}
