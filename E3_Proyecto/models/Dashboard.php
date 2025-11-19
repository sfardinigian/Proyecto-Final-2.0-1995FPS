<?php
require_once __DIR__ . '/../settings/db.php';

class Dashboard
{
    protected $db;

    public function __construct()
    {
        $connection = new db();
        $this->db = $connection->connect();
    }

    // Obtener todas las actividades del día actual
    public function getTasksOfDay($id_usuario, $dia)
    {
        $query = "SELECT id_actividad, titulo, hora_inicio, hora_fin, color, prioridad
                         FROM actividades
                         WHERE id_usuario = ? AND dia = ?
                         ORDER BY hora_inicio ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('is', $id_usuario, $dia);
        $stmt->execute();

        $res = $stmt->get_result();
        $data = [];

        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }

        return $data ?: [];
    }

    // Actividad actual
    public function getCurrentActivity($id_usuario, $dia, $horaActual)
    {
        $query = "SELECT id_actividad, titulo, hora_inicio, hora_fin, color, prioridad
                         FROM actividades
                         WHERE id_usuario = ?
                         AND dia = ?
                         AND hora_inicio <= ?
                         AND hora_fin >= ?
                         LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iiss', $id_usuario, $dia, $horaActual, $horaActual);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        return $result ?: null;
    }

    // Próxima actividad
    public function getNextActivity($id_usuario, $dia, $horaActual)
    {
        $query = "SELECT id_actividad, titulo, hora_inicio, hora_fin, color, prioridad
                         FROM actividades
                         WHERE id_usuario = ?
                         AND dia = ?
                         AND hora_inicio > ?
                         ORDER BY hora_inicio ASC
                         LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iss', $id_usuario, $dia, $horaActual);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    // Actividades importantes
    public function getImportantActivities($id_usuario, $dia)
    {
        $prioridad = "importante";

        $query = "SELECT id_actividad, titulo, hora_inicio, hora_fin, color, prioridad
                         FROM actividades
                         WHERE id_usuario = ?
                         AND dia = ?
                         AND prioridad = ?
                         ORDER BY hora_inicio ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iss', $id_usuario, $dia, $prioridad);
        $stmt->execute();

        $res = $stmt->get_result();
        $data = [];

        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }

        return $data ?: [];
    }

    // Horas libres y ocupadas
    public function getHoursStats($id_usuario, $dia)
    {
        $query = "SELECT hora_inicio, hora_fin
                         FROM actividades
                         WHERE id_usuario = ? AND dia = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('is', $id_usuario, $dia);
        $stmt->execute();

        $res = $stmt->get_result();

        $totalMinutosDia = 24 * 60;
        $ocupados = 0;

        while ($row = $res->fetch_assoc()) {
            $inicio = strtotime($row['hora_inicio']);
            $fin = strtotime($row['hora_fin']);
            $ocupados += ($fin - $inicio) / 60;
        }

        return ['ocupados' => $ocupados, 'libres' => $totalMinutosDia - $ocupados];
    }

    // Datos para el gráfico de donut
    public function getDonutData($id_usuario, $dia)
    {
        $query = "SELECT titulo, color,
                         SUM(TIMESTAMPDIFF(MINUTE, hora_inicio, hora_fin)) AS minutos
                         FROM actividades
                         WHERE id_usuario = ? AND dia = ?
                         GROUP BY titulo";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('is', $id_usuario, $dia);
        $stmt->execute();

        $res = $stmt->get_result();
        $data = [];

        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }

        return $data ?: [];
    }
}
