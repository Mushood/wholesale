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
            const vm = this;
            console.log('products landing mounted.');

            Event.$on('submitsearch', function (search) {
                alert('asdf');
            });
        },

        props: {
            route_search: {
                required: true
            }
        },

        data() {
            return {
                products: [
                    {name: 'name'},{name: 'name'},
                ]
            }
        },

        methods: {
            fetchProducts(search) {
                const vm = this;
                axios.post(vm.route_search, {
                    search: search,
                })
                .then(function (response_axios) {
                    if (response_axios.data.code == 200) {

                    } else {

                    }
                })
                .catch(function (error) {

                });
            },
        },
    }
</script>
