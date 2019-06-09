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

            vm.fetchProducts(vm.default_search);
            Event.$on('submitsearch', function (search) {
                vm.fetchProducts(search);
            });
        },

        props: {
            route_search: {
                required: true
            }
        },

        data() {
            return {
                products: [],

                default_search: {
                    name: "",
                    categories: [],
                    price: { min: 0, max: 1000},
                    brands: [],
                }
            }
        },

        methods: {
            fetchProducts(search) {
                const vm = this;
                axios.post(vm.route_search, {
                    search: search,
                })
                .then(function (response_axios) {
                    if (response_axios.status === 200) {
                        vm.products = response_axios.data.data;
                    } else {
                        vm.fetchProducts(vm.default_search);
                    }
                })
                .catch(function (error) {

                });
            },
        },
    }
</script>
