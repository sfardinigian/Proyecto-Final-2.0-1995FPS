<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
require_once __DIR__ . '/../models/Dashboard.php';

class DashboardController
{
    /* =============================
       ACTIVIDADES DEL DÍA
       ============================= */
    public function obtenerTareasDia()
    {
        $dashboard = new Dashboard;
        $id_usuario = $_SESSION['usuario']['id_usuario'];

        $diaActual = $this->getDiaSemana();
        return $dashboard->getTasksOfDay($id_usuario, $diaActual);
    }

    /* =============================
       ACTIVIDAD ACTUAL
       ============================= */
    public function obtenerActividadActual()
    {
        $dashboard = new Dashboard;
        $id_usuario = $_SESSION['usuario']['id_usuario'];

        $diaActual = $this->getDiaSemana();
        $horaActual = date("H:i");

        return $dashboard->getCurrentActivity($id_usuario, $diaActual, $horaActual);
    }

    /* =============================
       PRÓXIMA ACTIVIDAD
       ============================= */
    public function obtenerProximaActividad()
    {
        $dashboard = new Dashboard;
        $id_usuario = $_SESSION['usuario']['id_usuario'];

        $diaActual = $this->getDiaSemana();
        $horaActual = date("H:i");

        return $dashboard->getNextActivity($id_usuario, $diaActual, $horaActual);
    }

    /* =============================
       ACTIVIDADES IMPORTANTES
       ============================= */
    public function obtenerImportantes()
    {
        $dashboard = new Dashboard;
        $id_usuario = $_SESSION['usuario']['id_usuario'];

        $diaActual = $this->getDiaSemana();
        return $dashboard->getImportantActivities($id_usuario, $diaActual);
    }

    /* =============================
       HORAS LIBRES VS OCUPADAS
       ============================= */
    public function obtenerHoras()
    {
        $dashboard = new Dashboard;
        $id_usuario = $_SESSION['usuario']['id_usuario'];

        $diaActual = $this->getDiaSemana();
        return $dashboard->getHoursStats($id_usuario, $diaActual);
    }

    /* =============================
       GRÁFICO DONUT
       ============================= */
    public function obtenerDonut()
    {
        $dashboard = new Dashboard;
        $id_usuario = $_SESSION['usuario']['id_usuario'];

        $diaActual = $this->getDiaSemana();
        return $dashboard->getDonutData($id_usuario, $diaActual);
    }

    /* =============================
       TRADUCIR EL DÍA A ENTERO
       ESTE ES CLAVE 🔥
       ============================= */
    private function getDiaSemana()
    {
        // date("N") -> 1 (lunes) ... 7 (domingo)
        $numero = date('N');

        $dias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];

        return $dias[$numero];
    }
}
