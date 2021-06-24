<template>
    <ProductsList
        :products="products"
        :current-page="currentPage"
        :total-pages="totalPages"
        @page-changed="loadPage($event)"
    />
</template>

<script>

import ProductsList from '../ProductsList'

export default {
    name: "ProductsListPage",
    components: { ProductsList },
    data () {
        return {
            products: [],
            currentPage: 1,
            totalPages: 1
        }
    },
    created () {
        this.loadPage(1)
    },
    methods: {
        loadPage(pageNumber) {
            axios.get('/products', { params: { page: pageNumber } })
                .then(({ data }) => {
                    this.products = data.data
                    this.currentPage = data.meta.current_page
                    this.totalPages = data.meta.last_page
                })
        }
    }
}
</script>
