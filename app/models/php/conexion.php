<?php
// Credenciales de la base de datos remota (Clever Cloud)
$host = 'brb6t1smbpzguvmk73ch-mysql.services.clever-cloud.com';
$port = '3306'; // Agregamos la variable del puerto
$dbname = 'brb6t1smbpzguvmk73ch';
$username = 'ubs3smfytw84xd9c'; 
$password = '6XytXkRqcFF7f3GiGXZw'; 

try {
    // Intentamos establecer la conexión incluyendo "port=$port;"
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configuramos PDO para que nos muestre los errores si algo falla
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Puedes descomentar la siguiente línea para probar que funciona:
    // echo "Conectado exitosamente"; 
    
} catch(PDOException $e) {
    // Si hay un error, detenemos el proceso y mostramos qué pasó
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>