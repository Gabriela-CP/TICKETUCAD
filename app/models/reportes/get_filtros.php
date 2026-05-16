<?php
// Limpiar el buffer de salida y definir que la respuesta sera un JSON
ob_start();
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Validar que el usuario sea administrador antes de continuar
require_once __DIR__ . "/auth_admin.php";

// Incluir el archivo de conexion a la base de datos
require_once __DIR__ . "/../php/conexion.php";

// Validar si la conexion con MySQLi fallo
if (!$conexion) {
    ob_clean();
    echo json_encode(['status' => 'error', 'message' => 'Error de infraestructura: No se pudo conectar a la base de datos']);
    exit;
}

// Arreglos vacios para almacenar la informacion de las consultas
$tecnicos = [];
$deptos = [];
$estados = [];

// Cargar la lista de los tecnicos (usuarios con rol_id igual a 2)
$res_tec = mysqli_query($conexion, "SELECT id, nombre FROM usuarios WHERE rol_id = 2 ORDER BY nombre ASC");
if ($res_tec) {
    while ($row = mysqli_fetch_assoc($res_tec)) {
        $tecnicos[] = $row;
    }
    mysqli_free_result($res_tec); // Liberar memoria del resultado de tecnicos
}

// Cargar la lista de todos los departamentos
$res_dep = mysqli_query($conexion, "SELECT id, nombre FROM departamentos ORDER BY nombre ASC");
if ($res_dep) {
    while ($row = mysqli_fetch_assoc($res_dep)) {
        $deptos[] = $row;
    }
    mysqli_free_result($res_dep); // Liberar memoria del resultado de departamentos
}

// Cargar la lista de los estados disponibles para los tickets
$res_est = mysqli_query($conexion, "SELECT nombre FROM estados_ticket ORDER BY id ASC");
if ($res_est) {
    while ($row = mysqli_fetch_assoc($res_est)) {
        $estados[] = $row;
    }
    mysqli_free_result($res_est); // Liberar memoria del resultado de estados
}

// Limpiar cualquier salida anterior y enviar los datos cargados en formato JSON
ob_clean();
echo json_encode([
    'status' => 'success',
    'tecnicos' => $tecnicos,
    'departamentos' => $deptos,
    'estados' => $estados
]);
exit;
?>