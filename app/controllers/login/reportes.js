// Datos
const datos = [
  {id:'1', tecnico:'Carlos', depto:'San Salvador', fecha:'2025-01-10', estado:'Completado', avance:100},
  {id:'2', tecnico:'Ana', depto:'Santa Ana', fecha:'2025-02-10', estado:'Pendiente', avance:20},
];

// Badge
function badge(estado){
  return {
    'Completado':'badge-completado',
    'Pendiente':'badge-pendiente',
    'En progreso':'badge-progreso',
    'Cancelado':'badge-cancelado'
  }[estado];
}

// Render tabla
function renderTabla(rows){
  document.getElementById('table-body').innerHTML = rows.map(r=>`
    <tr>
      <td>${r.id}</td>
      <td>${r.tecnico}</td>
      <td>${r.depto}</td>
      <td>${r.fecha}</td>
      <td><span class="badge-custom ${badge(r.estado)}">${r.estado}</span></td>
      <td>
        <div class="progress">
          <div class="progress-bar" style="width:${r.avance}%"></div>
        </div>
      </td>
    </tr>
  `).join('');
}

// Filtros
function aplicarFiltros(){
  renderTabla(datos);
  actualizarStats(datos);
}

function resetFiltros(){
  renderTabla(datos);
  actualizarStats(datos);
}

// Stats
function actualizarStats(rows){
  document.getElementById('s-total').textContent = rows.length;
  document.getElementById('s-comp').textContent = rows.filter(r=>r.estado==='Completado').length;
  document.getElementById('s-pend').textContent = rows.filter(r=>r.estado==='Pendiente').length;
  document.getElementById('s-canc').textContent = rows.filter(r=>r.estado==='Cancelado').length;
}

// Export
function exportarReporte(){
  $('.toast').toast('show');
}

// Init
document.addEventListener("DOMContentLoaded", function(){
  renderTabla(datos);
  actualizarStats(datos);
});