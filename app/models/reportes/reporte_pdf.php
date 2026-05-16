<?php
// Validar los permisos del administrador
require_once __DIR__ . "/auth_admin.php";

// Cargar la libreria de mPDF y la conexion a la base de datos
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . "/../php/conexion.php";

// Obtener los datos enviados por el formulario usando el metodo POST
$inicio = $_POST['h_inicio'] ?? '';
$fin    = $_POST['h_fin'] ?? '';
$tec    = $_POST['h_tecnico'] ?? '';
$depto  = $_POST['h_depto'] ?? '';
$estado = $_POST['h_estado'] ?? '';

// Validar que las fechas de busqueda no esten vacias
if (empty($inicio) || empty($fin)) {
    ob_clean();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Exportación Inválida - Ticket UCAD</title>
        <script src="/TICKETUCAD/recursos/libs/sweetalert2/sweetalert2.min.js"></script>
        <style>
            body { 
                background-color: #0f172a; 
                font-family: 'Segoe UI', system-ui, sans-serif; 
                margin: 0; 
                padding: 0; 
            }
        </style>
    </head>
    <body>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'warning',
                    title: '<span style="color: #ffffff; font-family: \'Segoe UI\', sans-serif;">Acción Inválida</span>',
                    html: '<p style="color: #94a3b8; font-size: 14.5px; margin-bottom: 0; font-family: \'Segoe UI\', sans-serif;">Debe seleccionar un rango de fechas y presionar <b>"Generar Vista"</b> antes de poder exportar un reporte.</p>',
                    confirmButtonColor: '#2563eb', 
                    confirmButtonText: 'Entendido',
                    background: '#111827', 
                    color: '#fff',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(() => {
                    if (window.history.length > 1) {
                        window.history.back();
                    } else {
                        window.close();
                    }
                });
            });
        </script>
    </body>
    </html>
    <?php
    exit;
}

// Limpiar las variables de texto para evitar errores en la consulta SQL
$inicioEscaped = mysqli_real_escape_string($conexion, trim($inicio));
$finEscaped    = mysqli_real_escape_string($conexion, trim($fin));
$tecEscaped    = mysqli_real_escape_string($conexion, trim($tec));
$deptoEscaped  = mysqli_real_escape_string($conexion, trim($depto));
$estadoEscaped = mysqli_real_escape_string($conexion, trim($estado));

// Consulta SQL principal con LEFT JOIN para recopilar la informacion de los tickets y calcular el SLA
$sql = "SELECT 
            t.id AS id_ticket, 
            u.nombre AS tecnico_nombre, 
            d.nombre AS departamento, 
            t.fecha_creacion, 
            est.nombre AS estado,
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

// Agregar condiciones dinamicamente a la consulta si los campos contienen valores
if (!empty($inicioEscaped) && !empty($finEscaped)) {
    $sql .= " AND t.fecha_creacion BETWEEN '{$inicioEscaped} 00:00:00' AND '{$finEscaped} 23:59:59'";
}
if (!empty($tecEscaped)) {
    $sql .= " AND t.asignado_a = '{$tecEscaped}'";
}
if (!empty($deptoEscaped)) {
    $sql .= " AND t.departamento_id = '{$deptoEscaped}'";
}
if (!empty($estadoEscaped)) {
    $sql .= " AND est.nombre = '{$estadoEscaped}'";
}

// Ordenar los registros por ID de forma descendente
$sql .= " ORDER BY t.id DESC";

// Ejecutar la consulta en la base de datos
$resultado = mysqli_query($conexion, $sql);

if (!$resultado) {
    die("Error en la consulta de impresión: " . mysqli_error($conexion));
}

// Validar si la consulta no trajo ningun registro para mostrar advertencia
if (mysqli_num_rows($resultado) === 0) {
    ob_clean();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Exportación Vacía - Ticket UCAD</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            body { 
                background-color: #0f172a; 
                font-family: 'Segoe UI', system-ui, sans-serif; 
                margin: 0; 
                padding: 0; 
            }
        </style>
    </head>
    <body>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'warning',
                    title: '<span style="color: #ffffff; font-family: \'Segoe UI\', sans-serif;">Exportación Vacía</span>',
                    html: '<p style="color: #94a3b8; font-size: 14.5px; margin-bottom: 0; font-family: \'Segoe UI\', sans-serif;">Debe seleccionar un rango de fechas y presionar <b>"Generar Vista"</b> antes de poder exportar un reporte.</p>',
                    confirmButtonColor: '#2563eb', 
                    confirmButtonText: 'Entendido',
                    background: '#111827', 
                    color: '#fff',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(() => {
                    if (window.history.length > 1) {
                        window.history.back();
                    } else {
                        window.close();
                    }
                });
            });
        </script>
    </body>
    </html>
    <?php
    exit;
}

// Configurar el tamaño de pagina horizontal (A4-L) y los margenes del PDF
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8', 
    'format' => 'A4-L',
    'margin_top' => 15,
    'margin_bottom' => 15,
    'margin_left' => 15,
    'margin_right' => 15
]);

// Crear el diseño general y estilos CSS del reporte en una variable HTML
$html = '
<style>
    body { font-family: "Helvetica", Arial, sans-serif; color: #1e293b; }
    .header { text-align: center; border-bottom: 3px solid #1e40af; padding-bottom: 10px; margin-bottom: 20px; }
    .titulo { font-size: 22px; font-weight: bold; color: #1e3a8a; text-transform: uppercase; }
    .subtitulo { font-size: 14px; color: #64748b; margin-top: 5px; }
    .rango { background: #f1f5f9; padding: 5px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; margin-top: 10px; display: inline-block; }
    
    table { width: 100%; border-collapse: collapse; }
    th { background-color: #1e40af; color: #ffffff; padding: 12px 8px; text-align: left; font-size: 10px; text-transform: uppercase; }
    td { border-bottom: 1px solid #e2e8f0; padding: 10px 8px; font-size: 10px; }
    tr:nth-child(even) { background-color: #f8fafc; }
    
    .vencido { color: #dc2626; font-weight: bold; }
    .atiempo { color: #16a34a; font-weight: bold; }
    .badge-estado { border: 1px solid #cbd5e1; padding: 2px 5px; border-radius: 3px; background: #fff; font-size: 9px; }
    .footer { text-align: right; font-size: 9px; color: #94a3b8; margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 5px; }
</style>

<div class="header">
    <div class="titulo">Universidad Cristiana de las Asambleas de Dios</div>
    <div class="subtitulo">Facultad de Ciencias Económicas · Soporte IT</div>
    <div class="rango">PERIODO: ' . ($inicio ? htmlspecialchars($inicio, ENT_QUOTES, 'UTF-8') : 'HISTÓRICO') . ' AL ' . ($fin ? htmlspecialchars($fin, ENT_QUOTES, 'UTF-8') : date('d/m/Y')) . '</div>
</div>

<table>
    <thead>
        <tr>
            <th width="7%">ID</th>
            <th width="22%">Técnico</th>
            <th width="18%">Departamento</th>
            <th width="23%">Fecha y Hora</th>
            <th width="15%">Estado</th>
            <th width="15%">SLA</th>
        </tr>
    </thead>
    <tbody>';

// Recorrer las filas de la base de datos con un bucle while e introducirlas en la tabla
$totalFilas = 0;
while ($t = mysqli_fetch_assoc($resultado)) {
    $totalFilas++;
    $timestamp = strtotime($t['fecha_creacion']);
    $meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
    $fechaFormateada = date("d ", $timestamp) . $meses[date("n", $timestamp)-1] . date(" Y, h:i A", $timestamp);

    $classSLA = ($t['sla_status'] === 'VENCIDO') ? 'vencido' : 'atiempo';
    
    $html .= '<tr>
                <td style="font-weight: bold;">#' . $t['id_ticket'] . '</td>
                <td>' . htmlspecialchars($t['tecnico_nombre'] ?? 'Sin asignar', ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($t['departamento'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</td>
                <td style="color: #475569;">' . $fechaFormateada . '</td>
                <td><span class="badge-estado">' . strtoupper(htmlspecialchars($t['estado'], ENT_QUOTES, 'UTF-8')) . '</span></td>
                <td class="' . $classSLA . '">' . $t['sla_status'] . '</td>
              </tr>';
}

$html .= '</tbody></table>
<div class="footer">Cantidad total de tickets: ' . $totalFilas . ' | Generado por Ticket UCAD el ' . date('d/m/Y h:i A') . '</div>';

// Pasar el codigo HTML a mPDF y forzar la descarga del reporte
$mpdf->WriteHTML($html);
$mpdf->Output('Reporte_Ticket_UCAD.pdf', 'D');
exit;
?>