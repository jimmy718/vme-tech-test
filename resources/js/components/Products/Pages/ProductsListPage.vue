<template>
    <div>
        <ProductFilters
            @filters-selected="onFilterClicked"
            class="border-b border-indigo-50 pb-4"
        />
        <ProductsTable
            :products="products"
            :current-page="currentPage"
            :total-pages="totalPages"
            :query-string="queryString"
            @page-changed="loadPage($event)"
            @product-deleted="removeProduct"
            @sort-updated="onSortUpdated"
        />
    </div>
</template>

<script>
import ProductsTable from '../ProductsTable'
import ProductFilters from '../ProductFilters'

export default {
    name: "ProductsListPage",
    components: { ProductFilters, ProductsTable },
    data () {
        return {
            products: [],
            currentPage: 1,
            totalPages: 1,
            queryString: ''
        }
    },
    created () {
        this.loadPage(1)
    },
    methods: {
        loadPage (pageNumber) {
            axios.get('/products' + this.queryString, { params: { page: pageNumber } })
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
        },
        onFilterClicked (queryString) {
            this.queryString = queryString
            this.loadPage(1)
        },
        onSortUpdated (sort) {
            this.queryString += '&sort=' + (sort.direction === 'desc' ? '-' : '') + sort.field
            this.loadPage(1)
        }
    }
}
</script>
