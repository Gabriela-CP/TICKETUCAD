/**
 * Ecommerce Model
 * Datos de productos, carrito y pagos
 */
'use strict';

const EcommerceModel = {
  products: [
    { id: 1, name: 'Laptop Pro',      price: 1299, stock: 15, category: 'Electronics', img: '../../assets/img/product/1.svg' },
    { id: 2, name: 'Wireless Mouse',  price: 49,   stock: 80, category: 'Accessories', img: '../../assets/img/product/2.svg' },
    { id: 3, name: 'Mechanical Keyboard', price: 89, stock: 40, category: 'Accessories', img: '../../assets/img/product/3.svg' },
  ],

  cart: [],

  addToCart(productId) {
    const product = this.products.find(p => p.id === productId);
    if (!product) return;
    const existing = this.cart.find(i => i.id === productId);
    if (existing) {
      existing.qty++;
    } else {
      this.cart.push({ ...product, qty: 1 });
    }
  },

  removeFromCart(productId) {
    this.cart = this.cart.filter(i => i.id !== productId);
  },

  getCartTotal() {
    return this.cart.reduce((sum, item) => sum + item.price * item.qty, 0);
  },
};
