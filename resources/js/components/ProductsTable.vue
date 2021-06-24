<template>
    <div class="w-full">
        <table class="table-auto w-full">
            <thead>
            <tr>
                <th></th>
                <th class="text-left">Name</th>
                <th class="text-left">Brand</th>
                <th class="text-right">Barcode</th>
                <th class="text-right">Price</th>
                <th class="text-right">Added On</th>
                <th class="text-right">Edit</th>
                <th class="text-right">Delete</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="product in products">
                <td><img :src="product.image_url" :alt="`${product.name} image`" height="50" width="50"></td>
                <td>{{product.name}}</td>
                <td>{{product.brand.name}}</td>
                <td class="text-right">{{product.barcode}}</td>
                <td class="text-right">{{product.price}}</td>
                <td class="text-right">{{product.date_added}}</td>
                <td class="text-right">
                    <BaseButton @click.native="handleEdit(product)">Edit</BaseButton>
                </td>
                <td class="text-right">
                    <BaseButton @click.native="handleDelete(product)">Delete</BaseButton>
                </td>
            </tr>
            </tbody>
        </table>
        <SlidingPagination
            class="mt-4"
            :current="currentPage"
            :total="totalPages"
            @page-change="onPageChange"
        />
        <div>Current Page: {{currentPage}}</div>
    </div>
</template>

<script>
import SlidingPagination from 'vue-sliding-pagination'
import BaseButton from './Utils/Button'
import Swal from 'sweetalert2'

export default {
    name: "ProductsList",
    props: ['products', 'currentPage', 'totalPages'],
    components: { SlidingPagination, BaseButton },
    methods: {
        onPageChange (newPage) {
            this.$emit('page-changed', newPage)
        },
        handleDelete (product) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/products/${product.id}`)
                        .then(() => {
                            this.$emit('product-deleted', product)
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong! Try again later...',
                            })
                        })
                }
            })
        },
        handleEdit (product) {
            window.location = `/products/${product.id}/edit`
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
    color: rgba(31, 41, 55, .5);
    background: rgba(129, 140, 248, 0.3);
}
.c-sliding-pagination__page {
    padding: .25em;
}
</style>
