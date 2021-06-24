<template>
    <ProductForm :product="product"/>
</template>

<script>
import ProductForm from '../Forms/ProductForm'

export default {
    name: "ProductFormPage",
    components: { ProductForm },
    props: ['product']
}
</script>

<style scoped>

</style>
