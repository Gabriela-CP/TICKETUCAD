<?php
// Credenciales por defecto de XAMPP
$host = 'localhost';
$dbname = 'ucad_tiket';
$username = 'root'; // Usuario por defecto en XAMPP
$password = '';     // Contraseña por defecto en XAMPP (suele estar vacía)

try {
    // Intentamos establecer la conexión
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configuramos PDO para que nos muestre los errores si algo falla
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    // Si hay un error, detenemos el proceso y mostramos qué pasó
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>