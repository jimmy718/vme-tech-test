<template>
    <div>
        <div class="mb-4">
            <label for="name">Name</label>
            <BaseInput v-model="name" type="text" id="name"/>
            <p v-if="hasErrors('name')" class="text-red-400">{{getErrors('name')}}</p>
        </div>

        <div class="mb-4">
            <label for="barcode">Barcode</label>
            <BaseInput v-model="barcode" type="text" id="barcode"/>
            <p v-if="hasErrors('barcode')" class="text-red-400">{{getErrors('barcode')}}</p>
        </div>

        <div class="mb-4">
            <label for="price">Price</label>
            <BaseInput v-model="price" type="number" min="0.00" step="0.01" id="price"/>
            <p v-if="hasErrors('price')" class="text-red-400">{{getErrors('price')}}</p>
        </div>

        <div class="mb-4">
            <label for="brand">Brand</label>
            <BrandSelect @brand-selected="brand = $event" id="brand" class="block w-full"/>
            <p v-if="hasErrors('brand')" class="text-red-400">{{getErrors('brand')}}</p>
        </div>

        <div class="mb-4">
            <label for="image">Image</label>
            <BaseInput v-model="image" type="file" id="image"/>
            <p v-if="hasErrors('image')" class="text-red-400">{{getErrors('image')}}</p>
        </div>

        <div class="flex justify-end">
            <BaseButton @click.native="save">Submit</BaseButton>
        </div>
    </div>
</template>

<script>
import BaseInput from '../../Utils/Input'
import BrandSelect from '../../Utils/BrandSelect'
import BaseButton from '../../Utils/Button'
import Swal from 'sweetalert2'

export default {
    name: "ProductForm",
    props: ['product'],
    components: { BrandSelect, BaseInput, BaseButton },
    data () {
        return {
            name: '',
            barcode: '',
            price: '',
            brand: '',
            image: '',
            headers: { headers: {'Content-Type': 'multipart/form-data' } },
            errors: {}
        }
    },
    created () {
        if (this.product) {
            this.name = this.product.name
            this.barcode = this.product.barcode
            this.price = this.product.price
            this.brand = this.product.brand ? this.product.brand.name : ''
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
            axios.post('/products', this.getFormData(), this.headers)
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
            formData.append('brand', this.brand ? this.brand.name : '')
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
            if (error.response.status === 422) {
                this.errors = error.response.data.errors
            } else {
                this.genericError()
            }

        }
    }
}
</script>

<style scoped>

</style>
