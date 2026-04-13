/**
 * Dashboard Controller
 * Conecta DashboardModel con las vistas del dashboard
 */
'use strict';

const DashboardController = {
  init() {
    this.renderStats();
    this.renderRecentOrders();
  },

  renderStats() {
    DashboardModel.stats.forEach(stat => {
      const el = document.getElementById(stat.id);
      if (!el) return;
      const valueEl = el.querySelector('.stat-value');
      const changeEl = el.querySelector('.stat-change');
      if (valueEl) valueEl.textContent = stat.value.toLocaleString();
      if (changeEl) {
        changeEl.textContent = stat.change;
        changeEl.className = 'stat-change ' + (stat.change.startsWith('+') ? 'text-success' : 'text-danger');
      }
    });
  },

  renderRecentOrders() {
    const tbody = document.getElementById('recent-orders-body');
    if (!tbody) return;
    tbody.innerHTML = DashboardModel.recentOrders.map(order => `
      <tr>
        <td>${order.id}</td>
        <td>${order.customer}</td>
        <td>${order.product}</td>
        <td>$${order.amount}</td>
        <td><span class="badge bg-${order.status === 'Completed' ? 'success' : order.status === 'Pending' ? 'warning' : 'info'}">${order.status}</span></td>
      </tr>
    `).join('');
  },
};

document.addEventListener('DOMContentLoaded', () => DashboardController.init());
