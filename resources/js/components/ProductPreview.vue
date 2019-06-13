<template>
    <div class="product-item">
        <div class="pi-pic">
            <div class="tag-sale">ON SALE</div>
            <img src="/img/product/6.jpg" alt="">
            <div class="pi-links">
                <a class="add-card" @click="addProductToCart(product.id)"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
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
            this.product = this.product_original;
        },

        props: {
            product_original: {
                required: true,
            }
        },

        data() {
            return {
                product: {},
            }
        },

        methods: {
            addProductToCart(id) {
                Event.$emit('add_product_to_cart', {
                    'product'   : id,
                    'quantity'  : 1
                });
            },
        },
    }
</script>
