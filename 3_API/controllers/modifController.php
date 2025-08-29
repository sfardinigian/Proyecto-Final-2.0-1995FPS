<?php
require_once __DIR__ . '/../models/Usuario.php';

class modifController
{
    public function modificarUsuario($id, $data)
    {
        // Validación de nombre
        if (!preg_match('^[a-zA-Z-áéíóúÁÉÍÓÚñÑüÜ\s]+$^', $data['nombre']))
        {
            header('Location: ../views/modif.php?error=nombre');
            exit;
        }

        // Validación de apellido
        if (!preg_match('^[a-zA-Z-áéíóúÁÉÍÓÚñÑüÜ\s]+$^', $data['apellido']))
        {
            header('Location: ../views/modif.php?error=apellido');
            exit;
        }

        $usuario = new Usuario();
        $userData = $usuario->getById($id);

        // Validación de que la contraseña anterior coincida
        if (!password_verify($data['passAnterior'], $userData['pass']))
        {
            header('Location: ../views/modif.php?error=passAnterior');
            exit;
        }

        if (!empty($data['passNueva']) || !empty($data['passNueva2']))
        {
            // Validación de que haya ingresado la contraseña anterior
            if (empty($data['passAnterior']))
            {
                header('Location: ../views/modif.php?error=faltaAnterior');
                exit;
            }

            // Validación de que las nuevas coincidan
            if ($data['passNueva'] !== $data['passNueva2'])
            {
                header('Location: ../views/modif.php?error=passNoCoincide');
                exit;
            }

            // Validación de contraseña
            if (!preg_match('^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$^', $data['passNueva'])){
                header('Location: ../views/modif.php?error=pass');
                exit;
            }

            // Hashear la nueva contraseña
            $data['pass'] = password_hash($data['passNueva'], PASSWORD_DEFAULT);
        } 
        else 
        {
            // Si no cambia la contraseña, dejar la actual
            $data['pass'] = $userData['pass'];
        }

        // Guardar cambios
        $resultado = $usuario->update($id, $data);

        if ($resultado)
        {
            session_start();
            session_destroy();

            setcookie('usuario_id', '', time() - 3600, "/");
            setcookie('usuario_email', '', time() - 3600, "/");

            header('Location: ../views/inicio.php?ok=modificado');
            exit;
        }
        else
        {
            header('Location: ../views/modif.php?error=desconocido');
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar']))
{
    $controller = new modifController();
    $controller->modificarUsuario($_POST['id_usuario'], $_POST);
}