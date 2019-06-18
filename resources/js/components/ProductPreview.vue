<template>
    <div class="product-item">
        <div class="pi-pic">
            <div class="tag-sale">ON SALE</div>
            <img src="/img/product/6.jpg" alt="">
            <div class="pi-links">
                <a class="add-card" @click="addProductToCart(product.id)" v-show="!in_cart">
                    <i class="flaticon-bag"></i><span>ADD TO CART</span>
                </a>
                <a class="add-card" @click="removeProductFromCart(product.id)" v-show="in_cart">
                    <i class="flaticon-cancel"></i><span>REMOVE</span>
                </a>
                <a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
                <a href="/product" class="wishlist-btn"><i class="flaticon-gallery"></i></a>
            </div>
        </div>
        <div class="pi-text">
            <h6>{{ product.price }}</h6>
            <p>{{ product.title }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('product preview mounted.');
            const vm = this;
            vm.product = vm.product_original;
            Event.$on('cart_updated', function (cart) {
                vm.checkIfInCart(cart);
            });

            Event.$emit('get_cart');
        },

        props: {
            product_original: {
                required: true,
            }
        },

        data() {
            return {
                product: {},
                in_cart: false,
                found: false,
            }
        },

        methods: {
            addProductToCart(id) {
                Event.$emit('add_product_to_cart', {
                    'product'   : id,
                    'quantity'  : 1
                });
            },

            removeProductFromCart(id) {
                Event.$emit('remove_product_from_cart', {
                    'product'   : id,
                    'quantity'  : 1
                });
            },

            checkIfInCart(cart) {
                const vm = this;
                vm.found = false;
                if (cart.items) {
                    cart.items.forEach(function(item, index) {
                        if (item['product'] == vm.product.title) {
                            vm.found = true;
                        }
                    });
                }
                vm.in_cart = vm.found;
            },
        },
    }
</script>

<style>
    .add-card{
        cursor: pointer;
    }
</style>