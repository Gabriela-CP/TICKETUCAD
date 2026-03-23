/**
 * User Model
 * Datos del usuario autenticado y notificaciones
 */
'use strict';

const UserModel = {
  current: {
    name: 'Aigars Silkalns',
    avatar: 'https://avatars.githubusercontent.com/u/3451398?s=200&v=4',
    role: 'Administrator',
    email: '[email]',
  },

  notifications: [
    { icon: 'nalika-tick',       title: 'Task completed',   time: '1 hour ago'  },
    { icon: 'nalika-cloud',      title: 'Backup finished',  time: '2 hours ago' },
    { icon: 'nalika-folder',     title: 'New file shared',  time: '3 hours ago' },
    { icon: 'nalika-bar-chart',  title: 'Report ready',     time: '4 hours ago' },
  ],

  messages: [
    { from: 'Aigars',      avatar: '../../assets/img/contact/1.svg', text: 'Please done this project ASAP.', date: '16 Sept' },
    { from: 'Sulaiman',    avatar: '../../assets/img/contact/4.svg', text: 'Please done this project ASAP.', date: '16 Sept' },
    { from: 'Victor Jara', avatar: '../../assets/img/contact/3.svg', text: 'Please done this project ASAP.', date: '16 Sept' },
  ],
};
