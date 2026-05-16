<?php
// Limpiar el buffer de salida y definir la respuesta como JSON
ob_start();
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Validar que el usuario sea administrador antes de continuar
require_once __DIR__ . "/auth_admin.php";

// Incluir el archivo de conexion a la base de datos
require_once __DIR__ . "/../php/conexion.php";

// Validar si la conexion con la base de datos fallo
if (!$conexion) {
    ob_clean();
    echo json_encode(['status' => 'error', 'message' => 'Error crítico: No se pudo establecer conexión con el servidor de base de datos.']);
    exit;
}

// Capturar los filtros enviados por el metodo GET desde la URL
$inicio        = isset($_GET['inicio']) ? trim($_GET['inicio']) : '';
$fin           = isset($_GET['fin']) ? trim($_GET['fin']) : '';
$tec           = isset($_GET['tecnico']) ? trim($_GET['tecnico']) : '';
$depto         = isset($_GET['departamento']) ? trim($_GET['departamento']) : '';
$estado_filtro = isset($_GET['estado']) ? trim($_GET['estado']) : '';

// Limpiar los parametros recibidos para evitar inyeccion SQL en MySQLi
$inicioEscaped = mysqli_real_escape_string($conexion, $inicio);
$finEscaped    = mysqli_real_escape_string($conexion, $fin);
$tecEscaped    = mysqli_real_escape_string($conexion, $tec);
$deptoEscaped  = mysqli_real_escape_string($conexion, $depto);
$estEscaped    = mysqli_real_escape_string($conexion, $estado_filtro);

// Consulta SQL con LEFT JOINs para obtener los datos detallados de los tickets y evaluar el estado del SLA
$sql = "SELECT 
            t.id AS id_ticket, 
            u.nombre AS tecnico_nombre, 
            d.nombre AS departamento, 
            t.fecha_creacion, 
            est.nombre AS estado,
            est.es_final,
            CASE 
                WHEN sla.fecha_limite_resolucion < NOW() AND est.es_final = 0 THEN 'VENCIDO'
                ELSE 'A TIEMPO'
            END AS sla_status
        FROM tickets t
        LEFT JOIN usuarios u ON t.asignado_a = u.id 
        LEFT JOIN departamentos d ON t.departamento_id = d.id
        LEFT JOIN estados_ticket est ON t.estado_id = est.id
        LEFT JOIN sla_ticket sla ON t.id = sla.ticket_id
        WHERE t.eliminado_en IS NULL";

// Agregar condiciones a la consulta segun los filtros seleccionados
if ($inicioEscaped != '' && $finEscaped != '') {
    $sql .= " AND t.fecha_creacion BETWEEN '{$inicioEscaped} 00:00:00' AND '{$finEscaped} 23:59:59'";
}

if ($tecEscaped != '') { 
    $sql .= " AND t.asignado_a = '{$tecEscaped}'"; 
}

if ($deptoEscaped != '') { 
    $sql .= " AND t.departamento_id = '{$deptoEscaped}'"; 
}

if ($estEscaped != '') { 
    $sql .= " AND est.nombre = '{$estEscaped}'"; 
}

// Ordenar los resultados por ID en forma descendente
$sql .= " ORDER BY t.id DESC";

// Ejecutar la consulta SQL en la base de datos
$query = mysqli_query($conexion, $sql);

if (!$query) {
    ob_clean();
    echo json_encode(['status' => 'error', 'message' => 'Falla en la consulta: ' . mysqli_error($conexion)]);
    exit;
}

$tickets = [];
$stats = ['total' => 0, 'resueltos' => 0, 'pendientes' => 0, 'vencidos' => 0];

// Recorrer los registros devueltos para guardarlos en el arreglo e incrementar los contadores
while ($row = mysqli_fetch_assoc($query)) {
    // Validar si el ticket ya esta finalizado o sigue abierto
    if ($row['es_final'] == 1) {
        $stats['resueltos']++;
    } else {
        $stats['pendientes']++;
    }
    
    // Validar si el ticket ya sobrepaso el tiempo limite del SLA
    if ($row['sla_status'] === 'VENCIDO') {
        $stats['vencidos']++;
    }
    
    $tickets[] = $row;
}

// Contar el total de tickets encontrados
$stats['total'] = count($tickets);

// Limpiar el buffer y devolver los datos en formato JSON para el frontend
ob_clean();
echo json_encode([
    'status' => 'success', 
    'data' => $tickets, 
    'stats' => $stats
]);
exit;
?>