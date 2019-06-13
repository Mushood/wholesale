<template>
    <div class="row">
        <div class="col-lg-4 col-sm-6" v-for="product in products">
            <product-preview :product_original="product"></product-preview>
        </div>
        <div class="text-center w-100 pt-3" v-show="show_loadmore">
            <button class="site-btn sb-line sb-dark" @click="fetchProducts(current_search)">LOAD MORE</button>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            const vm = this;
            console.log('products landing mounted.');

            vm.route_paginated_search = vm.route_search;
            vm.fetchProducts(vm.default_search);

            Event.$on('submitsearch', function (search) {
                vm.route_paginated_search = vm.route_search;
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
                },

                show_loadmore: false,
                route_paginated_search : "",
                current_search: {},
            }
        },

        methods: {
            fetchProducts(search) {
                const vm = this;
                vm.current_search = search;
                axios.post(vm.route_paginated_search, {
                    search: search,
                })
                .then(function (response_axios) {
                    if (response_axios.status === 200) {
                        if (vm.route_paginated_search == vm.route_search) {
                            vm.products = response_axios.data.data;
                        } else {
                            response_axios.data.data.forEach(function(item, index) {
                                vm.products.push(item);
                            });
                        }
                        if (response_axios.data.next_page_url) {
                            vm.route_paginated_search = response_axios.data.next_page_url;
                            vm.show_loadmore = true;
                        } else {
                            vm.show_loadmore = false;
                        }

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
