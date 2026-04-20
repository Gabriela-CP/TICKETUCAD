<?php
// Credenciales por defecto de XAMPP
$host = 'brb6t1smbpzguvmk73ch-mysql.services.clever-cloud.com';
$dbname = 'brb6t1smbpzguvmk73ch';
$username = 'ubs3smfytw84xd9c'; // Usuario por defecto en Clever Cloud
$password = '6XytXkRqcFF7f3GiGXZw';     // Contraseña por defecto en Clever Cloud

try {
    // Intentamos establecer la conexión
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configuramos PDO para que nos muestre los errores si algo falla
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    // Si hay un error, detenemos el proceso y mostramos qué pasó
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

class Conexion {
    // Credenciales de Clever Cloud (mantenemos las tuyas)
    private $host = 'brb6t1smbpzguvmk73ch-mysql.services.clever-cloud.com';
    private $dbname = 'brb6t1smbpzguvmk73ch';
    private $username = 'ubs3smfytw84xd9c';
    private $password = '6XytXkRqcFF7f3GiGXZw';

    public function getConnection() {
        try {
            // Establecemos la conexión usando PDO
            $pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8", $this->username, $this->password);
            
            // Configuración de errores
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return $pdo;
        } catch(PDOException $e) {
            // Si hay un error, lo mostramos
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
}

?>