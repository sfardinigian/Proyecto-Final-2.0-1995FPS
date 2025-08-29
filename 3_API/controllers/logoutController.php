<?php
session_start();

// Elimina todas las variables de sesión
$_SESSION = [];

// Destruye la sesión
session_destroy();

// Elimina las cookies de "Recordarme"
if (isset($_COOKIE['usuario_id']))
{
    setcookie('usuario_id', '', time() - 3600, "/");
}
if (isset($_COOKIE['usuario_email']))
{
    setcookie('usuario_email', '', time() - 3600, "/");
}

// Redirige al login
header("Location: ../index.php");
exit;
?>