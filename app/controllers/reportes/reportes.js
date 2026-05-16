$(document).ready(function() {
    // Arreglo global para guardar los tickets en memoria y filtrarlos localmente
    let datosLocales = [];

    // =========================================================================
    // FUNCIÓN AUXILIAR: Valida si el backend devolvió un acceso restringido
    // =========================================================================
    function verificarRestriccion(res) {
        if (res && res.status === 'restricted') {
            Swal.fire({
                icon: 'error',
                title: '<span style="color: #ffffff; font-family: \'Segoe UI\', sans-serif;">Sesión Inválida</span>',
                html: `<p style="color: #94a3b8; font-size: 14.5px; margin-bottom: 0; font-family: 'Segoe UI', sans-serif;">${res.message || 'No tienes permisos para interactuar con esta sección.'}</p>`,
                confirmButtonColor: '#2563eb', // Azul Cobalto
                confirmButtonText: 'Entendido',
                background: '#111827', // Fondo Oscuro
                color: '#fff',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                // Redireccionar al panel de administracion si no hay permisos
                window.location.href = '/TICKETUCAD/app/views/pages/padmin.html';
            });
            return true; 
        }
        return false;
    }

    // =========================================================================
    // 1. CARGAR LAS OPCIONES DE LOS SELECTS AL ENTRAR A LA PÁGINA
    // =========================================================================
    function cargarSelectores() {
        $.ajax({
            url: 'app/models/reportes/get_filtros.php',
            type: 'GET',
            dataType: 'json', 
            success: function(res) {
                // Validar si el filtro devolvio acceso denegado
                if (verificarRestriccion(res)) return;

                if(res.status === 'success') {
                    // Llenar el select de los Tecnicos
                    let htmlTec = '<option value="">-- Todos --</option>';
                    res.tecnicos.forEach(t => {
                        htmlTec += `<option value="${t.id}">${t.nombre}</option>`;
                    });
                    $('#sel_tecnico').html(htmlTec);

                    // Llenar el select de los Departamentos
                    let htmlDep = '<option value="">-- Todos --</option>';
                    res.departamentos.forEach(d => {
                        htmlDep += `<option value="${d.id}">${d.nombre}</option>`;
                    });
                    $('#sel_depto').html(htmlDep);

                    // Llenar el select de los Estados de los tickets
                    let htmlEst = '<option value="">-- Todos --</option>';
                    res.estados.forEach(e => {
                        htmlEst += `<option value="${e.nombre}">${e.nombre}</option>`;
                    });
                    $('#sel_estado').html(htmlEst);
                }
            },
            error: function(xhr) {
                // Controlar error si la respuesta del servidor es una restriccion
                try {
                    let jsonErr = JSON.parse(xhr.responseText);
                    if (verificarRestriccion(jsonErr)) return;
                } catch(e) {}
                console.error('Error al inicializar filtros relacionales.');
            }
        });
    }

    // Llamar a la funcion para rellenar los selects inmediatamente
    cargarSelectores();

    // =========================================================================
    // 2. FUNCIÓN PARA DIBUJAR LAS FILAS DE LOS TICKETS EN LA TABLA HTML
    // =========================================================================
    function pintarTabla(data) {
        let rows = '';
        
        if (data && data.length > 0) {
            data.forEach(item => {
                // Formatear la fecha de creacion al estilo corto: 13 may 2026
                const fechaObj = new Date(item.fecha_creacion);
                const opcionesFecha = { day: '2-digit', month: 'short', year: 'numeric' };
                const fechaParte = fechaObj.toLocaleDateString('es-ES', opcionesFecha).replace('.', '');

                // Formatear la hora en formato de 12 horas: 11:01 PM
                const opcionesHora = { hour: '2-digit', minute: '2-digit', hour12: true };
                const horaParte = fechaObj.toLocaleTimeString('es-ES', opcionesHora).toUpperCase();

                const fechaFinal = `${fechaParte}, ${horaParte}`;

                // Definir colores e iconos dependiendo de si el SLA esta vencido o a tiempo
                const esVencido = item.sla_status === 'VENCIDO';
                const slaColor = esVencido ? '#ef4444' : '#10b981';
                const slaIcon = esVencido ? 'fa-times-circle' : 'fa-check-circle';
                const slaBackground = esVencido ? 'rgba(239, 68, 68, 0.1)' : 'rgba(16, 185, 129, 0.1)';
                const slaBorder = esVencido ? 'rgba(239, 68, 68, 0.2)' : 'rgba(16, 185, 129, 0.2)';
                
                // Definir el color del borde y texto del estado del ticket (Abierto / Finalizado)
                const colorBadge = (item.es_final == 1) 
                    ? 'border: 1px solid #10b981; color: #10b981;' 
                    : 'border: 1px solid #3b82f6; color: #3b82f6;';

                // Ir acumulando el codigo HTML de la fila
                rows += `
                    <tr>
                        <td class="text-white font-weight-bold">#${item.id_ticket}</td>
                        <td>${item.tecnico_nombre || '<span class="text-muted">Sin asignar</span>'}</td>
                        <td><small>${item.departamento}</small></td>
                        <td style="color: #94a3b8; font-size: 0.85rem;">${fechaFinal}</td>
                        <td>
                            <span class="badge" style="padding: 5px 10px; ${colorBadge}">
                                ${item.estado}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; color: ${slaColor}; background: ${slaBackground}; padding: 4px 10px; border-radius: 4px; width: fit-content; font-size: 11px; font-weight: bold; border: 1px solid ${slaBorder};">
                                <i class="fas ${slaIcon} mr-1"></i> ${item.sla_status}
                            </div>
                        </td>
                    </tr>`;
            });
        } else {
            rows = '<tr><td colspan="6" class="text-center py-4 text-muted">No hay registros para mostrar.</td></tr>';
        }
        // Inyectar las filas en el cuerpo de la tabla
        $('#tablaReportesBody').html(rows);
    }

    // =========================================================================
    // 3. BOTÓN PARA EJECUTAR LA BÚSQUEDA MEDIANTE AJAX
    // =========================================================================
    $('#btnGenerarVista').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).text('Buscando...');

        // Guardar los filtros seleccionados por el usuario
        const filtros = {
            inicio: $('#fecha_inicio').val(),
            fin: $('#fecha_fin').val(),
            tecnico: $('#sel_tecnico').val(),
            departamento: $('#sel_depto').val(),
            estado: $('#sel_estado').val()
        };

        $.ajax({
            url: 'app/models/reportes/get_reportes.php',
            type: 'GET',
            data: filtros,
            dataType: 'json',
            success: function(res) {
                // Verificar que no haya problemas de sesion o permisos
                if (verificarRestriccion(res)) {
                    btn.prop('disabled', false).text('Generar Vista');
                    return;
                }

                if (res.status === 'success') {
                    datosLocales = res.data; 
                    pintarTabla(datosLocales);
                    
                    // Actualizar los textos de las tarjetas estadisticas superiores
                    $('#countTotal').text(res.stats.total);
                    $('#countResueltos').text(res.stats.resueltos);
                    $('#countPendientes').text(res.stats.pendientes);
                    $('#countVencidos').text(res.stats.vencidos);

                    // Copiar los filtros elegidos a los inputs ocultos del formulario del PDF
                    $('#h_inicio').val(filtros.inicio);
                    $('#h_fin').val(filtros.fin);
                    $('#h_tecnico').val(filtros.tecnico);
                    $('#h_depto').val(filtros.departamento);
                    $('#h_estado').val(filtros.estado);
                }
                btn.prop('disabled', false).text('Generar Vista');
            },
            error: function(xhr) {
                btn.prop('disabled', false).text('Generar Vista');
                try {
                    let jsonErr = JSON.parse(xhr.responseText);
                    if (verificarRestriccion(jsonErr)) return;
                } catch(e) {}
                
                Swal.fire({
                    icon: 'error',
                    title: 'Falla de Comunicación',
                    text: 'Ocurrió un problem de conectividad al procesar la auditoría analítica.',
                    confirmButtonColor: '#2563eb',
                    background: '#111827',
                    color: '#fff'
                });
            }
        });
    });

    // =========================================================================
    // 4. EVENTO CLICK EN LAS TARJETAS SUPERIORES PARA FILTRAR EN MEMORIA
    // =========================================================================
    $('.stat-box').on('click', function() {
        if (!datosLocales || datosLocales.length === 0) return;

        const idStat = $(this).find('.stat-number').attr('id');
        let filtrados = [];

        // Evaluar que tarjeta fue cliqueada para filtrar usando JS local
        if (idStat === 'countTotal') {
            filtrados = datosLocales;
        } else if (idStat === 'countResueltos') {
            filtrados = datosLocales.filter(t => t.es_final == 1);
        } else if (idStat === 'countPendientes') {
            filtrados = datosLocales.filter(t => t.es_final == 0);
        } else if (idStat === 'countVencidos') {
            filtrados = datosLocales.filter(t => t.sla_status === 'VENCIDO');
        }

        // Redibujar la tabla con los elementos filtrados localmente
        pintarTabla(filtrados);
    });

    // =========================================================================
    // 5. BOTÓN PARA REINICIAR TODOS LOS CAMPOS Y CONTADORES
    // =========================================================================
    $('#btnLimpiar').on('click', function() {
        $('#fecha_inicio, #fecha_fin, #sel_tecnico, #sel_depto, #sel_estado').val('');
        $('#tablaReportesBody').html('<tr><td colspan="6" class="text-center py-4 text-muted">Seleccione filtros para ver datos</td></tr>');
        $('.stat-number').text('0');
        datosLocales = []; 
        
        // Limpiar los valores ocultos encargados de enviar los datos al PDF
        $('#h_inicio, #h_fin, #h_tecnico, #h_depto, #h_estado').val('');
    });

    // =========================================================================
    // 6. DETENER EL ENVÍO DEL PDF SI NO SE CUMPLEN LOS REQUISITOS DE FECHA O DATOS
    // =========================================================================
    $(document).on('submit', '#formExportar', function(e) {
        
        // Sincronizar de nuevo los campos para evitar cambios imprevistos del usuario
        $('#h_inicio').val($('#fecha_inicio').val());
        $('#h_fin').val($('#fecha_fin').val());
        $('#h_tecnico').val($('#sel_tecnico').val());
        $('#h_depto').val($('#sel_depto').val());
        $('#h_estado').val($('#sel_estado').val());

        // Leer los valores de control finales
        const fechaInicio = $('#h_inicio').val();
        const fechaFin = $('#h_fin').val();
        const totalTickets = parseInt($('#countTotal').text().trim()) || 0;

        // Comprobar si faltan las fechas o si no se ha devuelto ningun ticket en la tabla
        if (!fechaInicio || !fechaFin || totalTickets === 0) {
            
            // Frenar el envio del formulario nativo inmediatamente
            e.preventDefault();
            e.stopPropagation();

            let tituloAlerta = 'Exportación Bloqueada';
            let mensajeAlerta = '';

            if (!fechaInicio || !fechaFin) {
                tituloAlerta = 'Acción Inválida';
                mensajeAlerta = 'Debe seleccionar un rango de fechas y presionar <b>"Generar Vista"</b> antes de poder exportar un reporte.';
            } else {
                tituloAlerta = 'Exportación Vacía';
                mensajeAlerta = 'No se encontraron registros de auditoría en el periodo seleccionado. Modifique el rango de tiempo para generar un documento institucional válido.';
            }

            // Mostrar el aviso de advertencia con SweetAlert2
            Swal.fire({
                icon: 'warning',
                title: `<span style="color: #ffffff; font-family: 'Segoe UI', sans-serif;">${tituloAlerta}</span>`,
                html: `<p style="color: #94a3b8; font-size: 14.5px; margin-bottom: 0; font-family: 'Segoe UI', sans-serif;">${mensajeAlerta}</p>`,
                confirmButtonColor: '#2563eb', 
                confirmButtonText: 'Entendido',
                background: '#111827', 
                color: '#fff',
                allowOutsideClick: false,
                allowEscapeKey: false
            });

            return false; 
        }
    });
});