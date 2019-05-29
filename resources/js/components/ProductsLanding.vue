<template>
    <div class="row">
        <div class="col-lg-4 col-sm-6" v-for="product in products">
            <product-preview :product="product"></product-preview>
        </div>
        <div class="text-center w-100 pt-3">
            <button class="site-btn sb-line sb-dark">LOAD MORE</button>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('products landing mounted.')
        },

        data() {
            return {
                products: [
                    {name: 'name'},{name: 'name'},
                ]
            }
        },
    }
</script>
