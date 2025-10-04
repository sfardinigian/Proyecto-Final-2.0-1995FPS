<?php
require_once __DIR__ . '/../models/Actividad.php';

class actividadController
{
    public function obtenerActividad()
    {
        $actividad = new Actividad;

        // Conseguimos el usuario de la sesión activa
        $id_usuario = $_SESSION['usuario']['id_usuario'];

        return $actividad->getByUsuario($id_usuario);
    }

    public function crearActividad($data)
    {
        // Validación de título
        if (!preg_match('/^[\p{L}\p{N}\s\-\.,:;()¡!¿?@#&]+$/u', $data['titulo'])) {
            header('Location: ../views/main.php?error=titulo');
            exit;
        }

        // Validación de hora de inicio (Por si el usuario manipula el frontend)
        if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $data['hora_inicio'])) {
            header('Location: ../views/main.php?error=horaInicio');
            exit;
        }

        // Validación de hora de fin (Por si el usuario manipula el frontend)
        if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $data['hora_fin'])) {
            header('Location: ../views/main.php?error=horaFin');
            exit;
        }

        // Validación de que la hora de fin sea posterior a la hora de inicio
        if (strtotime($data['hora_fin']) <= strtotime($data['hora_inicio'])) {
            header('Location: ../views/main.php?error=horasInvalidas');
            exit;
        }

        // Validación de color (Por si el usuario manipula el frontend)
        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $data['color'])) {
            header('Location: ../views/main.php?error=color');
            exit;
        }

        // Validación de prioridad (Por si el usuario manipula el frontend)
        $validPrioridades = ['Casual', 'Normal', 'Importante'];
        if (!in_array($data['prioridad'], $validPrioridades)) {
            header('Location: ../views/main.php?error=prioridad');
            exit;
        }

        // Validación de día (Por si el usuario manipula el frontend)
        $validDias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'domingo'];
        if (!in_array($data['dia'], $validDias)) {
            header('Location: ../views/main.php?error=dia');
            exit;
        }

        $actividad = new Actividad;

        // Validar si existe solapamiento de actividades
        if ($actividad->existeSolapamiento($_SESSION['usuario']['id_usuario'], $data['dia'], $data['hora_inicio'], $data['hora_fin'])) {
            header('Location: ../views/main.php?error=solapamiento');
            exit;
        }

        // Insertamos si pasan todas las comprobaciones
        $resultado = $actividad->create(['id_usuario' => $_SESSION['usuario']['id_usuario'], 'titulo' => $data['titulo'], 'hora_inicio' => $data['hora_inicio'], 'hora_fin' => $data['hora_fin'], 'prioridad' => $data['prioridad'], 'color' => $data['color'], 'dia' => $data['dia']]);

        // Redirigimos con un mensaje según el resultado
        if (isset($resultado['id_actividad'])) {
            header('Location: ../views/main.php?ok=actividadAgregada');
            exit;
        } else {
            header('Location: ../views/main.php?error=desconocido');
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    $actividadController = new actividadController();
    $actividadController->crearActividad($_POST);
}
