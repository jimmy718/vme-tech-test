import Vue from 'vue'

require('./bootstrap');

require('alpinejs');

Vue.component('product-list-page', require('./components/Pages/ProductsListPage').default)

const app = new Vue({
    el: '#app'
});
