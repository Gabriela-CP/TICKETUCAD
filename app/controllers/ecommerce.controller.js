/**
 * Ecommerce Controller
 * Maneja interacciones de productos, carrito y pagos
 */
'use strict';

const EcommerceController = {
  init() {
    this.bindCartButtons();
    this.renderCartSummary();
  },

  bindCartButtons() {
    document.querySelectorAll('[data-add-to-cart]').forEach(btn => {
      btn.addEventListener('click', e => {
        const productId = parseInt(e.currentTarget.dataset.addToCart, 10);
        EcommerceModel.addToCart(productId);
        this.renderCartSummary();
        this.showToast('Product added to cart');
      });
    });

    document.querySelectorAll('[data-remove-from-cart]').forEach(btn => {
      btn.addEventListener('click', e => {
        const productId = parseInt(e.currentTarget.dataset.removeFromCart, 10);
        EcommerceModel.removeFromCart(productId);
        this.renderCartSummary();
      });
    });
  },

  renderCartSummary() {
    const totalEl = document.getElementById('cart-total');
    const countEl = document.getElementById('cart-count');
    if (totalEl) totalEl.textContent = '$' + EcommerceModel.getCartTotal().toFixed(2);
    if (countEl) countEl.textContent = EcommerceModel.cart.length;
  },

  showToast(message) {
    // Usa Bootstrap toast si está disponible
    const toastEl = document.getElementById('app-toast');
    if (!toastEl) return;
    toastEl.querySelector('.toast-body').textContent = message;
    bootstrap.Toast.getOrCreateInstance(toastEl).show();
  },
};

document.addEventListener('DOMContentLoaded', () => EcommerceController.init());
