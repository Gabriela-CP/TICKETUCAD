/**
 * UI Controller
 * Maneja sidebar, header, notificaciones y comportamiento global de la UI
 * Delega a main.js para la inicialización de plugins
 */
'use strict';

const UIController = {
  init() {
    this.renderUserInfo();
    this.renderNotifications();
    this.renderMessages();
    this.bindSearch();
  },

  renderUserInfo() {
    const nameEls = document.querySelectorAll('.nk-user-name, .profile-name');
    nameEls.forEach(el => { el.textContent = UserModel.current.name.split(' ')[0]; });

    const avatarEls = document.querySelectorAll('.profile-avatar');
    avatarEls.forEach(el => { el.src = UserModel.current.avatar; });
  },

  renderNotifications() {
    const container = document.querySelector('.nk-dd-body[data-type="notifications"]');
    if (!container) return;
    container.innerHTML = UserModel.notifications.map(n => `
      <a href="#" class="nk-dd-item">
        <div class="nk-notif-icon"><i class="icon ${n.icon}"></i></div>
        <div class="nk-dd-item-body">
          <h6>${n.title}</h6>
          <small>${n.time}</small>
        </div>
      </a>
    `).join('');
  },

  renderMessages() {
    const container = document.querySelector('.nk-dd-body[data-type="messages"]');
    if (!container) return;
    container.innerHTML = UserModel.messages.map(m => `
      <a href="#" class="nk-dd-item">
        <img src="${m.avatar}" alt="${m.from}">
        <div class="nk-dd-item-body">
          <div class="d-flex justify-content-between"><h6>${m.from}</h6><span class="nk-dd-date">${m.date}</span></div>
          <p>${m.text}</p>
        </div>
      </a>
    `).join('');
  },

  bindSearch() {
    const input = document.querySelector('.nk-search-input');
    if (!input) return;
    input.addEventListener('keydown', e => {
      if (e.key === 'Enter') {
        const query = input.value.trim();
        if (query) console.log('[Search]', query); // reemplazar con lógica real
      }
    });
  },
};

document.addEventListener('DOMContentLoaded', () => UIController.init());
