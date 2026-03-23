/**
 * Dashboard Model
 * Centraliza los datos del dashboard (métricas, series de gráficos, etc.)
 */
'use strict';

const DashboardModel = {
  stats: [
    { id: 'total-sales',   label: 'Total Sales',   value: 8540,  change: '+12%', color: '#ab8ce4' },
    { id: 'total-orders',  label: 'Total Orders',  value: 3210,  change: '+8%',  color: '#00c292' },
    { id: 'total-revenue', label: 'Total Revenue', value: 52400, change: '+5%',  color: '#03a9f3' },
    { id: 'new-users',     label: 'New Users',     value: 1280,  change: '-2%',  color: '#ec4445' },
  ],

  salesChartData: {
    categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
    series: [
      { name: 'Sales',   data: [31,40,28,51,42,109,100,120,80,95,60,70] },
      { name: 'Revenue', data: [11,32,45,32,34,52,41,60,50,70,40,50] },
    ],
  },

  recentOrders: [
    { id: '#1001', customer: 'Aigars',      product: 'Laptop Pro',   amount: 1299, status: 'Completed' },
    { id: '#1002', customer: 'Sulaiman',    product: 'Wireless Mouse', amount: 49, status: 'Pending'   },
    { id: '#1003', customer: 'Victor Jara', product: 'Keyboard',     amount: 89,  status: 'Shipped'   },
  ],
};
