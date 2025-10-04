<?php
require_once __DIR__ . '/../settings/db.php';

class Actividad
{
    protected $db;

    public function __construct()
    {
        $connection = new db();
        $this->db = $connection->connect();
    }

    // ------------------------------ CRUD Default (Sin update) ------------------------------

    // READ - Obtener todas las actividades
    public function getByUsuario($id_usuario)
    {
        $query = "SELECT a.id_actividad, a.titulo, a.hora_inicio, a.hora_fin, a.color, a.prioridad, a.dia FROM actividades a INNER JOIN usuarios u ON a.id_usuario = u.id_usuario WHERE u.id_usuario = ? ORDER BY a.dia, a.hora_inicio";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id_usuario);
        $stmt->execute();

        if ($stmt->error) {
            return ['message' => 'Error en la lectura'];
        }

        $res = $stmt->get_result();
        $data_arr = [];

        while ($data = $res->fetch_assoc()) {
            $data_arr[] = $data;
        }

        return $data_arr ?: ['message' => 'Sin datos'];
    }

    // CREATE - Agregar nueva actividad
    public function create($data)
    {
        $query = "INSERT INTO actividades (id_usuario, titulo, hora_inicio, hora_fin, prioridad, color, dia) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param('issssss', $data['id_usuario'], $data['titulo'], $data['hora_inicio'], $data['hora_fin'], $data['prioridad'], $data['color'], $data['dia']);
        $stmt->execute();

        if ($stmt->error) {
            return ['message' => 'Error en la ejecución de la consulta.'];
        }

        return ['id_actividad' => $stmt->insert_id];
    }

    // DELETE - Eliminar actividad
    public function delete($id)
    {
        $query = "DELETE FROM actividades WHERE id_actividad=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    // ------------------------------ Consultas independientes del proyecto ------------------------------

    // Función para comprobar si existe solapamiento entre las actividades que registra el usuario
    public function existeSolapamiento($id_usuario, $dia, $hora_inicio, $hora_fin)
    {
        $query = "SELECT COUNT(*) as total FROM actividades WHERE id_usuario = ? AND dia = ? AND hora_inicio < ? AND hora_fin > ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("isss", $id_usuario, $dia, $hora_fin, $hora_inicio);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        return $res['total'] > 0;
    }
}
