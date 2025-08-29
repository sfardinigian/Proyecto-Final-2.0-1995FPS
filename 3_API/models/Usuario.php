<?php
require_once __DIR__ . '/../settings/db.php';

class Usuario
{
    protected $db;

    public function __construct()
    {
        $connection = new db();
        $this->db = $connection->connect();
    }

    // ------------------------------ CRUD Default ------------------------------

    // READ - Obtener todos los usuarios
    public function get()
    {
        $query = "SELECT * FROM usuarios";

        $stmt = $this->db->prepare($query);
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

    // CREATE - Agregar nuevo usuario
    public function create($data)
    {
        $query = "INSERT INTO usuarios (nombre, apellido, email, pass) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param('ssss', $data['nombre'], $data['apellido'], $data['email'], $data['pass']);

        try
        {
            $stmt->execute();
            return true;
        }
        catch (mysqli_sql_exception $e)
        {
            if ($e->getCode() == 1062)
            {
                return "emailExiste";
            }

            throw $e;
        }

        if ($stmt->error)
        {
            return ['message' => 'Error en la ejecución de la consulta.'];
        }

        return ['message' => '¡Usuario registrado con éxito!'];
    }

    // UPDATE - Editar usuario
    public function update($id, $data)
    {
        $query = "UPDATE usuarios SET nombre=?, apellido=?, pass=? WHERE id_usuario=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssi', $data['nombre'], $data['apellido'], $data['pass'], $id);

        if (!$stmt->execute())
        {
            error_log("Error en update: " . $stmt->error);
            return false;
        }
        
        return true;
    }

    // DELETE - Eliminar usuario
    public function delete($id)
    {
        $query = "DELETE FROM usuarios WHERE id_usuario=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->error) {
            return ['message' => 'Error al eliminar'];
        }

        return ['message' => '¡Usuario eliminado con éxito!'];
    }

    // ------------------------------ Consultas independientes del proyecto ------------------------------

    // MODIFICACIÓN: Obtener usuario por ID
    public function getById($id)
    {
        $query = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    // INICIAR SESIÓN - Iniciar sesión por e-mail
    public function getByEmail($email)
    {
        $query = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }
}