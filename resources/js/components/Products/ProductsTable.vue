<template>
    <div class="w-full">
        <table class="table-auto w-full">
            <thead class="border-b border-indigo-50">
            <tr>
                <th></th>
                <th class="cursor-pointer text-left" @click="updateSort('name')">
                    Name
                    <span v-if="sort.field === 'name' && sort.direction === 'asc'">▲</span>
                    <span v-if="sort.field === 'name' && sort.direction === 'desc'">▼</span>
                </th>
                <th class="cursor-pointer text-left" @click="updateSort('brand')">
                    Brand
                    <span v-if="sort.field === 'brand' && sort.direction === 'asc'">▲</span>
                    <span v-if="sort.field === 'brand' && sort.direction === 'desc'">▼</span>
                </th>
                <th class="cursor-pointer text-right" @click="updateSort('barcode')">
                    Barcode
                    <span v-if="sort.field === 'barcode' && sort.direction === 'asc'">▲</span>
                    <span v-if="sort.field === 'barcode' && sort.direction === 'desc'">▼</span>
                </th>
                <th class="cursor-pointer text-right" @click="updateSort('price')">
                    Price
                    <span v-if="sort.field === 'price' && sort.direction === 'asc'">▲</span>
                    <span v-if="sort.field === 'price' && sort.direction === 'desc'">▼</span>
                </th>
                <th class="cursor-pointer text-right" @click="updateSort('date_added')">
                    Added On
                    <span v-if="sort.field === 'date_added' && sort.direction === 'asc'">▲</span>
                    <span v-if="sort.field === 'date_added' && sort.direction === 'desc'">▼</span>
                </th>
                <th class="cursor-pointer text-right">Edit</th>
                <th class="cursor-pointer text-right">Delete</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="product in products">
                <td><img :src="product.image_url" :alt="`${product.name} image`" height="50" width="50"></td>
                <td>{{product.name}}</td>
                <td>{{product.brand ? product.brand.name : ''}}</td>
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
        <div class="flex justify-between items-center">
            <div>
                <SlidingPagination
                    class="mt-4"
                    :current="currentPage"
                    :total="totalPages"
                    @page-change="onPageChange"
                />
                <div>Current Page: {{currentPage}}</div>
            </div>
            <MailProductsButton :query-string="queryString"/>
        </div>
    </div>
</template>

<script>
import SlidingPagination from 'vue-sliding-pagination'
import BaseButton from '../Utils/Button'
import Swal from 'sweetalert2'
import MailProductsButton from './MailProductsButton'

export default {
    name: "ProductsList",
    props: ['products', 'currentPage', 'totalPages', 'queryString'],
    components: { SlidingPagination, BaseButton, MailProductsButton },
    data () {
        return {
            sort: {
                field: '',
                direction: ''
            }
        }
    },
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
        },
        updateSort (field) {
            let flippedDirection = this.sort.direction === 'asc' ? 'desc' : 'asc'

            this.sort = {
                field: field,
                direction: this.sort.field === field ? flippedDirection : 'asc'
            }

            this.$emit('sort-updated', this.sort)
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
