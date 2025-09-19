<?php
require_once __DIR__ . '/../models/Usuario.php';

// Se consulta iniciar la sesión
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login']))
{
    $email = trim($_POST['email']);
    $pass  = trim($_POST['pass']);

    $recordarme = isset($_POST['recordarme']);

    $usuario = new Usuario();
    $data = $usuario->getByEmail($email);

    // Verificamos si se encontró el usuario
    if (!$data)
    {
        header('Location: ../views/inicio.php?error=email');
        exit;
    }

    // Verificamos si la contraseña es correcta
    if (!password_verify($pass, $data['pass']))
    {
        header('Location: ../views/inicio.php?error=pass');
        exit;
    }

    $_SESSION['usuario'] = ['id_usuario' => $data['id_usuario'], 'nombre' => $data['nombre'], 'apellido' => $data['apellido'], 'email' => $data['email']];

    // Manejo de cookies
    if ($recordarme)
    {
        // Guardar cookies por 30 días
        setcookie('usuario_id', $data['id_usuario'], time() + (30*24*60*60), "/");
        setcookie('usuario_email', $data['email'], time() + (30*24*60*60), "/");
    }
    else
    {
        // Si no marcó, borrar cookies previas
        if (isset($_COOKIE['usuario_id']))
        {
            setcookie('usuario_id', '', time() - 3600, "/");
        }
        if (isset($_COOKIE['usuario_email']))
        {
            setcookie('usuario_email', '', time() - 3600, "/");
        }
    }

    header('Location: ../views/main.php');
    exit;
}
?>