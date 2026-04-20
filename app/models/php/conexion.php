<?php

class Conexion {
    private $host = 'brb6t1smbpzguvmk73ch-mysql.services.clever-cloud.com';
    private $dbname = 'brb6t1smbpzguvmk73ch';
    private $user = 'ubs3smfytw84xd9c';
    private $pass = '6XytXkRqcFF7f3GiGXZw';

    public function getConnection() {
        try {
            // Instancia de PDO para conexión remota
            $pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8", $this->user, $this->pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return $pdo;
        } catch(PDOException $e) {
            // Log de error en caso de fallo de red o credenciales
            error_log("Fallo de conexión: " . $e->getMessage());
            return null;
        }
    }
}