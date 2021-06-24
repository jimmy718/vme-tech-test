<template>
    <ProductsTable
        :products="products"
        :current-page="currentPage"
        :total-pages="totalPages"
        @page-changed="loadPage($event)"
        @product-deleted="removeProduct"
    />
</template>

<script>

import ProductsTable from '../ProductsTable'

export default {
    name: "ProductsListPage",
    components: { ProductsTable },
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
        loadPage (pageNumber) {
            axios.get('/products', { params: { page: pageNumber } })
                .then(({ data }) => {
                    this.products = data.data
                    this.currentPage = data.meta.current_page
                    this.totalPages = data.meta.last_page
                })
        },
        removeProduct (productToRemove) {
            this.products = this.products
                .filter(product => {
                    return product.id !== productToRemove.id
                })
        }
    }
}
</script>
