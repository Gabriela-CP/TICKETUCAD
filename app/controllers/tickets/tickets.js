function extraerTickets() {
    // 1. CONTROL DE EXISTENCIA: Si el contenedor de la tabla no existe en la vista actual,
    // abortamos silenciosamente el flujo de este script para no romper otras secciones (como Reportes).
    let tablaTickets = document.getElementById('tabla_tickets'); 
    if (!tablaTickets) {
        return; 
    }
    
    fetch('/TICKETUCAD/app/models/php/tickets.php')
        .then(response => response.json()) // Convierte el paquete recibido en algo que JS entiende
        .then(data => {
            // Verificar si el servidor devolvió una restricción de seguridad inesperada
            if (data && data.status === 'restricted') {
                console.warn("Acceso restringido detectado en la carga de tickets.");
                return;
            }

            // OPTIMIZACIÓN DE RENDIMIENTO: Acumular las filas en una variable string en memoria 
            // en lugar de renderizar y alterar el DOM de la tabla en cada iteración del bucle.
            let filasAcumuladas = "";
                
            for (var i = 0; i < data.length; i++) {
                filasAcumuladas +=
                `<tr onclick="verDetalleTicket(${data[i]['#']})" style="cursor: pointer;">
                    <td style="padding: 10px;">${data[i]['#']}</td>
                    <td>${data[i].titulo}</td>
                    <td>${data[i].estado_nombre}</td>
                    <td>${data[i].prioridad_id}</td>
                    <td>${data[i].categoria_id}</td>
                    <td>${data[i].correo}</td>
                    <td>${data[i].asignado_nombre || 'Pendiente'}</td>
                </tr>`;
            }

            // Una única inyección al DOM (Más rápido y eficiente para el navegador)
            tablaTickets.innerHTML = filasAcumuladas;
        })
        .catch(error => console.error("Error al cargar:", error));
}

function verDetalleTicket(idTicket) {
    sessionStorage.setItem('ucad_ticket_id', String(idTicket));
    window.location.href = '/TICKETUCAD/vista-ticket';
}

// Ejecución segura
extraerTickets();
