<template>
    <BaseButton @click.native="onClick" class="bg-indigo-400">Mail Labels</BaseButton>
</template>

<script>
import BaseButton from '../Utils/Button'
import Swal from 'sweetalert2'

export default {
    name: "MailProductsButton",
    props: ['queryString'],
    components: { BaseButton },
    methods: {
        onClick () {
            axios.post('/exports/products/mail' + this.queryString)
                .then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'All done!',
                        text: 'Products labels mailed to shop floor staff for printing',
                    })
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong and we couldn\'t mail your labels, try again later...',
                    })
                })
        }
    }
}
</script>

<style scoped>

</style>
