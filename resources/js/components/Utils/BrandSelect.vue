<template>
    <select v-model="brand" @change="emitBrand" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        <option v-for="brand in brands" :value="brand" :key="brand.id">
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
            brand: '',
            brands: []
        }
    },
    created () {
        if (this.startingBrand) {
            this.brand = brand
            this.emitBrand()
        }

        axios.get('/brands')
            .then(({ data }) => {
                this.brands = data
            })
    },
    methods: {
        emitBrand () {
            this.$emit('brand-selected', brand)
        }
    }
}
</script>

<style scoped>

</style>
