<?php
// Clave para la funcionalidad de los días locales
date_default_timezone_set('America/Argentina/Buenos_Aires');

require_once __DIR__ . '/../models/Dashboard.php';

class DashboardController
{
    // Actividades del día
    public function obtenerTareasDia()
    {
        $dashboard = new Dashboard;
        $id_usuario = $_SESSION['usuario']['id_usuario'];

        $diaActual = $this->getDiaSemana();
        return $dashboard->getTasksOfDay($id_usuario, $diaActual);
    }

    // Actividad actual
    public function obtenerActividadActual()
    {
        $dashboard = new Dashboard;
        $id_usuario = $_SESSION['usuario']['id_usuario'];

        $diaActual = $this->getDiaSemana();
        $horaActual = date("H:i");

        return $dashboard->getCurrentActivity($id_usuario, $diaActual, $horaActual);
    }

    // Próxima actividad
    public function obtenerProximaActividad()
    {
        $dashboard = new Dashboard;
        $id_usuario = $_SESSION['usuario']['id_usuario'];

        $diaActual = $this->getDiaSemana();
        $horaActual = date("H:i");

        return $dashboard->getNextActivity($id_usuario, $diaActual, $horaActual);
    }

    // Actividades importantes
    public function obtenerImportantes()
    {
        $dashboard = new Dashboard;
        $id_usuario = $_SESSION['usuario']['id_usuario'];

        $diaActual = $this->getDiaSemana();
        return $dashboard->getImportantActivities($id_usuario, $diaActual);
    }

    // Horas libres vs ocupadas
    public function obtenerHoras()
    {
        $dashboard = new Dashboard;
        $id_usuario = $_SESSION['usuario']['id_usuario'];

        $diaActual = $this->getDiaSemana();
        return $dashboard->getHoursStats($id_usuario, $diaActual);
    }

    // Gráfico donut
    public function obtenerDonut()
    {
        $dashboard = new Dashboard;
        $id_usuario = $_SESSION['usuario']['id_usuario'];

        $diaActual = $this->getDiaSemana();
        return $dashboard->getDonutData($id_usuario, $diaActual);
    }

    // Traducir el día a entero
    private function getDiaSemana()
    {
        $numero = date('N');

        $dias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'];

        return $dias[$numero];
    }
}
