<template>
    <input @input="$emit('input', $event.target.value)" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full" :value="value">
</template>

<script>
export default {
    name: "Input",
    props: ['value']
}
</script>

<style scoped>

</style>
