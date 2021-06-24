import Vue from 'vue'

require('./bootstrap');

require('alpinejs');

Vue.component('product-list-page', require('./components/Products/Pages/ProductsListPage').default)
Vue.component('product-form-page', require('./components/Products/Pages/ProductFormPage').default)

const app = new Vue({
    el: '#app'
});
