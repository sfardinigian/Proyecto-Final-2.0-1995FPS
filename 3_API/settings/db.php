<?php
class db
{
    protected $host;
    protected $user;
    protected $password;
    protected $dbName;

    public function __construct()
    {
        $this->host = "localhost";
        $this->user = "root";
        $this->password = "";
        $this->dbName = "gestoract";
    }

    public function connect()
    {
        $connection = new mysqli($this->host, $this->user, $this->password, $this->dbName) or die ("Error en la conexión.");

        return $connection;
    }
}
?>