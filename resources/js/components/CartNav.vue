<template>
    <div class="up-item">
        <div class="shopping-card">
            <i class="flaticon-bag"></i>
            <span>0</span>
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

            vm.cart         = vm.cart_original;
            vm.route_cart   = vm.route_cart_original;
            vm.route_fetch  = vm.route_fetch_original;
        },

        props: {
            cart_original: {
                required: true
            },
            route_cart_original: {
                required: true
            },
            route_fetch_original: {
                required: true
            },
        },

        data() {
            return {
                cart: {},
                route_cart: "",
                route_fetch: ""
            }
        },

        methods: {
            addCart(product) {
                const vm = this;
                vm.route_cart = vm.route_cart.replace("product_id", product.product);
                axios.get(vm.route_cart)
                .then(function (response_axios) {
                    console.log(response_axios.status);
                    if (response_axios.status === 200) {
                        vm.cart = response_axios.data.data;
                    }
                    vm.route_cart_original = vm.route_cart;
                })
                .catch(function (error) {

                });
            },
        },
    }
</script>
