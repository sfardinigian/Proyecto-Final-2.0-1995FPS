<?php
require_once __DIR__ . '/../models/Usuario.php';

class usuarioController
{
    public function obtenerUsuario()
    {
        $usuario = new Usuario;
        return $usuario->get();
    }

    public function crearUsuario($data)
    {
        // Validación de nombre
        if (!preg_match('^[a-zA-Z-áéíóúÁÉÍÓÚñÑüÜ\s]+$^', $data['nombre'])) {
            header('Location: ../index.php?error=nombre');
            exit;
        }

        // Validación de apellido
        if (!preg_match('^[a-zA-Z-áéíóúÁÉÍÓÚñÑüÜ\s]+$^', $data['apellido'])) {
            header('Location: ../index.php?error=apellido');
            exit;
        }

        // Validación de e-mail
        if (!preg_match('^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,}$^', $data['email'])) {
            header('Location: ../index.php?error=email');
            exit;
        }

        // Validación de contraseña
        if (!preg_match('^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$^', $data['pass'])) {
            header('Location: ../index.php?error=pass');
            exit;
        }

        // Que coincidan las contraseñas
        if ($data['pass'] !== $data['pass2'])
        {
            header('Location: ../index.php?error=passNoCoincide');
            exit;
        }

        // Hashear contraseña
        $data['pass'] = password_hash($data['pass'], PASSWORD_DEFAULT);

        // Crea el usuario
        $usuario = new Usuario();
        $resultado = $usuario->create($data);

        // Verificamos que el usuario sea único
        if ($resultado === true)
        {
            header('Location: ../views/inicio.php?ok=registro');
            exit;
        }
        elseif ($resultado === "emailExiste")
        {
            header('Location: ../index.php?error=emailExiste');
            exit;
        }
        else
        {
            header('Location: ../index.php?error=desconocido');
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar']))
{
    $controller = new usuarioController();
    $controller->crearUsuario($_POST);
}
