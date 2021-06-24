<template>
    <div>
        <ProductsListItem
            v-for="product in products"
            :key="product.id"
            :product="product"
        />
        <SlidingPagination
            :current="currentPage"
            :total="totalPages"
            @page-change="onPageChange"
        />
    </div>
</template>

<script>
import ProductsListItem from './ProductsListItem'
import SlidingPagination from 'vue-sliding-pagination'

export default {
    name: "ProductsList",
    props: ['products', 'currentPage', 'totalPages'],
    components: { ProductsListItem, SlidingPagination },
    methods: {
        onPageChange (newPage) {
            this.$emit('page-changed', newPage)
        }
    }
}
</script>

<style>
.c-sliding-pagination__list-element {
    display: inline-block;
    background: rgb(129, 140, 248);
    color: #fff;
    padding: .25em .5em .25em .5em;
    border-radius: .25em;
    margin: 0 .25em 0.5em 0;
    transition-duration: 300ms;
    transition-property: background,color;
}
.c-sliding-pagination__list-element--active:hover {
    color: #fff;
    background: rgba(129, 140, 248, 0.8);
    cursor: default;
}
.c-sliding-pagination__list-element:hover {
    transition-duration: 300ms;
    transition-property: background,color;
    color: rgb(31, 41, 55);
    background: rgba(129, 140, 248, 0.3);
}
.c-sliding-pagination__page {
    padding: .25em;
}
</style>
