<?php
// 1. Conexión a la Base de Datos
// Ajusta la ruta según la ubicación real de tu archivo de conexión
require_once("../php/conexion.php");

$database = new Conexion();
$db = $database->getConnection();

// 2. Consultas para llenar los filtros dinámicamente
try {
    // Obtener Departamentos
    $queryDept = "SELECT id, nombre FROM departamentos ORDER BY nombre ASC";
    $stmtDept = $db->prepare($queryDept);
    $stmtDept->execute();

    // Obtener Técnicos (Usuarios con rol de técnico)
    // Nota: Ajusta el ID o nombre del rol según tu tabla 'roles'
    $queryTec = "SELECT id, nombre FROM usuarios WHERE rol_id = (SELECT id FROM roles WHERE nombre LIKE '%Técnico%' LIMIT 1) AND estado = 'activo'";
    $stmtTec = $db->prepare($queryTec);
    $stmtTec->execute();

    // Obtener Estados
    $queryEst = "SELECT id, nombre FROM estados_ticket ORDER BY nombre ASC";
    $stmtEst = $db->prepare($queryEst);
    $stmtEst->execute();

} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes del Sistema</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"> //De momento se usa cdn, luego se puede descargar y alojar localmente
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">// De momento se usa cdn, luego se puede descargar y alojar localmente

    <style>
        :root {
            --c1: #06142f; /* Extra Dark */
            --c2: #08346b; /* Dark */
            --c3: #2d5f9a; /* Medium Dark */
            --c4: #6196d1; /* Medium */
            --c5: #99c8f8; /* Light */
        }

        body {
            background-color: #f0f7ff;
            color: var(--c1);
            font-family: 'Segoe UI', Roboto, sans-serif;
        }

        .header-banner {
            background-color: var(--c1);
            color: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-bottom: 5px solid var(--c3);
        }

        .card-custom {
            border: 1px solid var(--c5);
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            margin-bottom: 1.5rem;
        }

        .section-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--c2);
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .section-label::before {
            content: "";
            display: inline-block;
            width: 4px;
            height: 16px;
            background: var(--c3);
            margin-right: 8px;
            border-radius: 2px;
        }

        .form-control-custom {
            background-color: #f8fbff;
            border: 1px solid var(--c4);
            border-radius: 8px;
            color: var(--c1);
            font-size: 0.9rem;
        }

        .stat-box {
            background-color: var(--c2);
            color: #ffffff;
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            display: block;
        }

        .stat-label {
            font-size: 0.7rem;
            color: var(--c5);
            text-transform: uppercase;
        }

        .table-custom thead th {
            background-color: #f0f7ff;
            color: var(--c3);
            border-bottom: 2px solid var(--c5);
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .badge-custom {
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.75rem;
        }

        .badge-comp { background: #e8f4ff; color: var(--c2); }
        .badge-prog { background: #ddeeff; color: var(--c3); }

        .btn-export {
            background-color: var(--c2);
            color: #fff;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-export:hover {
            background-color: var(--c3);
            color: #fff;
        }

        .progress-mini {
            height: 6px;
            background-color: #ddeeff;
            border-radius: 3px;
        }
    </style>
</head>
<body>

<div class="container py-5">
    
    <div class="header-banner d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h2 class="mb-0 font-weight-bold text-white">Reportes del Sistema</h2>
            <p class="mb-0 opacity-75 small">Módulo de Auditoría y Control Estadístico</p>
        </div>
        <div class="mt-2 mt-md-0">
            <button type="button" class="btn btn-sm btn-outline-light mr-2 shadow-sm" onclick="location.reload();">
                <i class="bi bi-arrow-counterclockwise mr-1"></i> Limpiar Filtros
            </button>
            <button type="submit" form="formFiltros" class="btn btn-sm btn-light font-weight-bold shadow-sm">
                <i class="bi bi-search mr-1"></i> Aplicar Filtros
            </button>
        </div>
    </div>

    <form id="formFiltros">
        <div class="row">
            <div class="col-md-5">
                <div class="card card-custom p-4">
                    <div class="section-label">Filtros de Fecha</div>
                    <div class="row">
                        <div class="col-6">
                            <label class="small font-weight-bold">Desde</label>
                            <input type="date" name="fecha_inicio" class="form-control form-control-custom">
                        </div>
                        <div class="col-6">
                            <label class="small font-weight-bold">Hasta</label>
                            <input type="date" name="fecha_fin" class="form-control form-control-custom">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card card-custom p-4">
                    <div class="section-label">Filtros de Entidad</div>
                    <div class="row">
                        <div class="col-4">
                            <label class="small font-weight-bold">Técnico</label>
                            <select name="tecnico" class="form-control form-control-custom">
                                <option value="">Todos</option>
                                <?php while($row = $stmtTec->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nombre']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="small font-weight-bold">Departamento</label>
                            <select name="departamento" class="form-control form-control-custom">
                                <option value="">Todos</option>
                                <?php while($row = $stmtDept->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nombre']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="small font-weight-bold">Estado</label>
                            <select name="estado" class="form-control form-control-custom">
                                <option value="">Todos</option>
                                <?php while($row = $stmtEst->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nombre']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-box shadow-sm">
                <span class="stat-label">Total Registros</span>
                <span class="stat-value">--</span>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-box shadow-sm">
                <span class="stat-label">Completados</span>
                <span class="stat-value">--</span>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-box shadow-sm">
                <span class="stat-label">Pendientes</span>
                <span class="stat-value">--</span>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-box shadow-sm">
                <span class="stat-label">Vencidos</span>
                <span class="stat-value">--</span>
            </div>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <div class="section-label mb-0">Vista previa del reporte</div>
            <span class="badge badge-light border text-muted px-3">Esperando filtros...</span>
        </div>
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th class="border-0">ID Ticket</th>
                        <th class="border-0">Técnico Asignado</th>
                        <th class="border-0">Departamento</th>
                        <th class="border-0">Fecha Creación</th>
                        <th class="border-0">Estado Actual</th>
                        <th class="border-0">SLA</th>
                    </tr>
                </thead>
                <tbody id="tabla-reportes">
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-info-circle mr-2"></i> Use los filtros superiores para generar el reporte.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap mt-4">
        <div class="d-flex align-items-center mb-3 mb-md-0">
            <label class="mb-0 mr-3 small font-weight-bold text-muted text-uppercase">Formato de Salida:</label>
            <select class="form-control form-control-sm form-control-custom" style="width: 150px;">
                <option>PDF (.pdf)</option>
                <option>Excel (.xlsx)</option>
            </select>
        </div>
        <button class="btn btn-export shadow">
            <i class="bi bi-download mr-2"></i> Generar Documento
        </button>
    </div>

</div>

</body>
</html>