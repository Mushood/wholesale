<template>
    <div class="product-item">
        <div class="pi-pic">
            <div class="tag-sale">ON SALE</div>
            <img src="/img/product/6.jpg" alt="">
            <div class="pi-links">
                <a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
                <a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
                <a href="/product" class="wishlist-btn"><i class="flaticon-gallery"></i></a>
            </div>
        </div>
        <div class="pi-text">
            <h6>$35,00</h6>
            <p>{{ product.name }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },

        props: {
            product: {
                required: true,
            }
        }
    }
</script>
