<template>
    <select v-model="brandId" @change="emitBrand" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        <option v-for="brand in brands" :value="brand.id" :key="brand.id">
            {{brand.name}}
        </option>
    </select>
</template>

<script>
export default {
    name: "BrandSelect",
    props: ['startingBrand'],
    data () {
        return {
            brandId: '',
            brands: []
        }
    },
    created () {
        if (this.startingBrand) {
            this.brandId = this.startingBrand.id
        }

        axios.get('/brands')
            .then(({ data }) => {
                this.brands = data
            })
    },
    methods: {
        emitBrand () {
            this.$emit(
                'brand-selected',
                this.brands.find(brand => brand.id === this.brandId)
            )
        }
    }
}
</script>
