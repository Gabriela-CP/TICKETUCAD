<?php
require_once("../php/conexion.php");

try {
    $database = new Conexion();
    $db = $database->getConnection();

    // Catalogos para filtros
    $stmtDept = $db->query("SELECT id, nombre FROM departamentos ORDER BY nombre ASC");
    
    // Filtro de técnicos basado en rol_id
    $stmtTec = $db->query("SELECT id, nombre FROM usuarios WHERE rol_id = 2 AND estado = 'activo'");
    
    $stmtEst = $db->query("SELECT id, nombre FROM estados_ticket ORDER BY nombre ASC");

} catch (PDOException $e) {
    error_log("Error en carga de catálogos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - TICKETUCAD</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --c1: #06142f;
            --c2: #08346b;
            --c3: #2d5f9a;
            --c4: #6196d1;
            --c5: #99c8f8;
        }

        body {
            background-color: #f0f7ff;
            color: var(--c1);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            font-size: 0.9rem;
        }

        .stat-box {
            background-color: var(--c2);
            color: #ffffff;
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
        }

        .table-custom thead th {
            background-color: #f0f7ff;
            color: var(--c3);
            border-bottom: 2px solid var(--c5);
            font-size: 0.75rem;
        }

        .btn-export {
            background-color: var(--c2);
            color: #fff;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
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
            <button type="button" class="btn btn-sm btn-outline-light mr-2" onclick="location.reload();">
                <i class="bi bi-arrow-counterclockwise mr-1"></i> Limpiar Filtros
            </button>
            <button type="submit" form="formFiltros" class="btn btn-sm btn-light font-weight-bold">
                <i class="bi bi-search mr-1"></i> Aplicar Filtros
            </button>
        </div>
    </div>

    <form id="formFiltros" method="GET">
        <div class="row">
            <div class="col-md-5">
                <div class="card card-custom p-4">
                    <div class="section-label">Rango de Fecha</div>
                    <div class="row">
                        <div class="col-6">
                            <label class="small font-weight-bold">Desde</label>
                            <input type="date" name="inicio" class="form-control form-control-custom">
                        </div>
                        <div class="col-6">
                            <label class="small font-weight-bold">Hasta</label>
                            <input type="date" name="fin" class="form-control form-control-custom">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card card-custom p-4">
                    <div class="section-label">Filtros Avanzados</div>
                    <div class="row">
                        <div class="col-4">
                            <label class="small font-weight-bold">Técnico</label>
                            <select name="tecnico" class="form-control form-control-custom">
                                <option value="">Todos</option>
                                <?php while($row = $stmtTec->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="small font-weight-bold">Departamento</label>
                            <select name="departamento" class="form-control form-control-custom">
                                <option value="">Todos</option>
                                <?php while($row = $stmtDept->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="small font-weight-bold">Estado</label>
                            <select name="estado" class="form-control form-control-custom">
                                <option value="">Todos</option>
                                <?php while($row = $stmtEst->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row mb-4 text-uppercase">
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-box"> <small>Total</small> <span class="d-block h4 mb-0">0</span> </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-box"> <small>Resueltos</small> <span class="d-block h4 mb-0">0</span> </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-box"> <small>Pendientes</small> <span class="d-block h4 mb-0">0</span> </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-box"> <small>Vencidos</small> <span class="d-block h4 mb-0">0</span> </div>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-header bg-white py-3">
            <div class="section-label mb-0">Vista previa del reporte</div>
        </div>
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID Ticket</th>
                        <th>Técnico</th>
                        <th>Departamento</th>
                        <th>Creación</th>
                        <th>Estado</th>
                        <th>SLA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-info-circle mr-2"></i> Seleccione filtros para generar resultados.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="d-flex align-items-center">
            <label class="mb-0 mr-3 small font-weight-bold">EXPORTAR A:</label>
            <select class="form-control form-control-sm form-control-custom" style="width: 120px;">
                <option>PDF</option>
                <option>EXCEL</option>
            </select>
        </div>
        <button class="btn btn-export">
            <i class="bi bi-download mr-2"></i> GENERAR
        </button>
    </div>
</div>

</body>
</html>