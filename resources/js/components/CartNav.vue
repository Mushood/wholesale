<template>
    <div class="up-item">
        <div class="shopping-card">
            <i class="flaticon-bag"></i>
            <span v-if="cart.items">{{ cart.items.length }}</span>
        </div>
        <a href="/cart">Shopping Cart</a>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('cart nav mounted.');
            const vm = this;
            Event.$on('add_product_to_cart', function (product) {
                vm.addCart(product);
            });
            Event.$on('remove_product_from_cart', function (product) {
                vm.removeCart(product);
            });
            Event.$on('get_cart', function () {
                Event.$emit('cart_updated', vm.cart);
            });

            vm.route_add        = vm.route_add_original;
            vm.route_remove     = vm.route_remove_original;
            vm.route_fetch      = vm.route_fetch_original;
            vm.fetchCart();
        },

        props: {
            route_add_original: {
                required: true
            },
            route_remove_original: {
                required: true
            },
            route_fetch_original: {
                required: true
            },
        },

        data() {
            return {
                cart: {},
                route_add: "",
                route_fetch: ""
            }
        },

        methods: {
            addCart(product) {
                const vm = this;
                vm.route_add = vm.route_add.replace("product_id", product.product);
                axios.get(vm.route_add)
                .then(function (response_axios) {
                    console.log(response_axios.status);
                    if (response_axios.status === 200) {
                        vm.cart = response_axios.data.data;
                        Event.$emit('cart_updated', vm.cart);
                    }
                    vm.route_add = vm.route_add_original;
                })
                .catch(function (error) {

                });
            },

            fetchCart() {
                const vm = this;
                axios.get(vm.route_fetch)
                    .then(function (response_axios) {
                        console.log(response_axios.status);
                        if (response_axios.status === 200) {
                            vm.cart = response_axios.data.data;
                            Event.$emit('cart_updated', vm.cart);
                        }
                    })
                    .catch(function (error) {

                    });
            },

            removeCart(product) {
                const vm = this;
                vm.route_remove = vm.route_remove.replace("product_id", product.product);
                axios.get(vm.route_remove)
                    .then(function (response_axios) {
                        console.log(response_axios.status);
                        if (response_axios.status === 200) {
                            vm.cart = response_axios.data.data;
                            Event.$emit('cart_updated', vm.cart);
                        }
                        vm.route_remove = vm.route_remove_original;
                    })
                    .catch(function (error) {

                    });
            },
        },
    }
</script>
