<template>
    <div class="flex justify-between items-end">
        <div>
            <div>
                <label for="search">Search</label>
            </div>
            <BaseInput v-model="search" type="text" id="search"/>
        </div>
        <div class="flex">
            <div class="mr-2">
                <div>
                    <label for="minPrice">Min Price</label>
                </div>
                <BaseInput v-model="minPrice" type="number" step="0.01" id="minPrice"/>
            </div>
            <div>
                <div>
                    <label for="maxPrice">Max Price</label>
                </div>
                <BaseInput v-model="maxPrice" type="number" step="0.01" id="maxPrice"/>
            </div>
        </div>
        <div>
            <div>
                <label for="brand">Brand</label>
            </div>
            <BrandSelect id="brand" @brand-selected="brand = $event"/>
        </div>
        <div class="flex-col">
            <BaseButton @click.native="resetFilters">Reset</BaseButton>
        </div>
        <div class="flex-col">
            <BaseButton @click.native="emitFilters">Filter</BaseButton>
        </div>
    </div>
</template>

<script>
import BaseInput from '../Utils/Input'
import BaseButton from '../Utils/Button'
import BrandSelect from '../Utils/BrandSelect'

export default {
    name: "ProductFilters",
    components: { BrandSelect, BaseInput, BaseButton },
    data () {
        return {
            search: '',
            minPrice: 0.00,
            maxPrice: 999.99,
            brand: { id: 0, name: '' },
            brands: [],
        }
    },
    created () {
        this.emitFilters()
    },
    methods: {
        emitFilters () {
            this.$emit('filters-selected', this.queryString)
        },
        resetFilters () {
            this.search = ''
            this.minPrice = 0.00
            this.maxPrice = 999.99
            this.brand = { id: 0, name: '' }

            this.emitFilters()
        }
    },
    computed: {
        queryString () {
            const search = 'filter[search]=' + this.search
            const price = 'filter[price-range]=' + this.minPrice + '-' + this.maxPrice
            const brand = 'filter[brand]=' + this.brand.name

            return `?${search}&${price}&${brand}`
        }
    }
}
</script>

<style scoped>

</style>
