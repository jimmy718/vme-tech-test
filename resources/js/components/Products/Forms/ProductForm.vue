<template>
    <div>
        <BaseInput v-model="name" type="text"/>
        <p v-if="hasErrors('name')" class="text-red-400">{{getErrors('name')}}</p>

        <BaseInput v-model="barcode" type="text"/>
        <p v-if="hasErrors('barcode')" class="text-red-400">{{getErrors('barcode')}}</p>

        <BaseInput v-model="price" type="number" min="0.00" step="0.01"/>
        <p v-if="hasErrors('price')" class="text-red-400">{{getErrors('price')}}</p>

        <BrandSelect @brand-selected="brand = $event"/>
        <p v-if="hasErrors('brand')" class="text-red-400">{{getErrors('brand')}}</p>

        <BaseInput v-model="image" type="file"/>
        <p v-if="hasErrors('image')" class="text-red-400">{{getErrors('image')}}</p>
    </div>
</template>

<script>
import BaseInput from '../../Utils/Input'
import BrandSelect from '../../Utils/BrandSelect'
import Swal from 'sweetalert2'

export default {
    name: "ProductForm",
    props: ['product'],
    components: { BrandSelect, BaseInput },
    data () {
        return {
            name: '',
            barcode: '',
            price: '',
            brand: '',
            image: '',
            headers: { headers: {'Content-Type': 'multipart/form-data' } },
            errors: null
        }
    },
    created () {
        if (this.product) {
            this.name = this.product.name
            this.barcode = this.product.barcode
            this.price = this.product.price
            this.brand = this.product.brand.name
        }
    },
    methods: {
        save () {
            this.product ? this.update() : this.create()
        },

        update () {
            axios.put(`/products/${this.product.id}`, this.getFormData(), this.headers)
                .then(() => {
                    window.location = '/dashboard'
                })
                .catch(error => {
                    this.handleHttpError(error)
                })
        },

        create () {
            axios.put('/products', this.getFormData(), this.headers)
                .then(() => {
                    window.location = '/dashboard'
                })
                .catch(error => {
                    this.handleHttpError(error)
                })
        },

        getFormData () {
            let formData = new FormData()

            formData.append('name', this.name)
            formData.append('barcode', this.barcode)
            formData.append('price', this.price)
            formData.append('brand', this.brand.name)
            formData.append('image', this.image)

            return formData
        },

        genericError () {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong, try again later',
            })
        },

        hasErrors (field) {
            return this.errors.hasOwnProperty(field)
        },

        getErrors (field) {
            return this.errors[field].join(' ')
        },

        handleHttpError (error) {
            if (error.respose.status == 422) {
                // validation errors
            } else {
                this.genericError()
            }

        }
    }
}
</script>

<style scoped>

</style>
