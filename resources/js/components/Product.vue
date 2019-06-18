<template>
    <section class="product-section">
        <div class="container">
            <div class="back-link">
                <a href="./category.html"> &lt;&lt; Back to Category</a>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="product-pic-zoom">
                        <img class="product-big-img" src="/img/single-product/1.jpg" alt="">
                    </div>
                    <div class="product-thumbs" tabindex="1" style="overflow: hidden; outline: none;">
                        <div class="product-thumbs-track">
                            <div class="pt active" data-imgbigurl="img/single-product/1.jpg"><img src="/img/single-product/thumb-1.jpg" alt=""></div>
                            <div class="pt" data-imgbigurl="/img/single-product/2.jpg"><img src="img/single-product/thumb-2.jpg" alt=""></div>
                            <div class="pt" data-imgbigurl="/img/single-product/3.jpg"><img src="img/single-product/thumb-3.jpg" alt=""></div>
                            <div class="pt" data-imgbigurl="/img/single-product/4.jpg"><img src="img/single-product/thumb-4.jpg" alt=""></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 product-details">
                    <h2 class="p-title">{{ product.title }}</h2>
                    <h3 class="p-price" v-if="product.prices">{{ product.prices[0].price }}</h3>
                    <!--<h4 class="p-stock">Available: <span>In Stock</span></h4>
                    <div class="p-rating">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star-o fa-fade"></i>
                    </div>
                    <div class="p-review">
                        <a href="">3 reviews</a>|<a href="">Add your review</a>
                    </div>
                    <div class="fw-size-choose">
                        <p>Size</p>
                        <div class="sc-item">
                            <input type="radio" name="sc" id="xs-size">
                            <label for="xs-size">32</label>
                        </div>
                        <div class="sc-item">
                            <input type="radio" name="sc" id="s-size">
                            <label for="s-size">34</label>
                        </div>
                        <div class="sc-item">
                            <input type="radio" name="sc" id="m-size" checked="">
                            <label for="m-size">36</label>
                        </div>
                        <div class="sc-item">
                            <input type="radio" name="sc" id="l-size">
                            <label for="l-size">38</label>
                        </div>
                        <div class="sc-item disable">
                            <input type="radio" name="sc" id="xl-size" disabled>
                            <label for="xl-size">40</label>
                        </div>
                        <div class="sc-item">
                            <input type="radio" name="sc" id="xxl-size">
                            <label for="xxl-size">42</label>
                        </div>
                    </div>-->
                    <div class="quantity">
                        <p>Quantity</p>
                        <div class="pro-qty">
                            <span class="dec qtybtn" @click="quantity--" v-visible="quantity > 1">-</span>
                            <input type="text" v-model="quantity">
                            <span class="inc qtybtn" @click="quantity++">+</span>
                        </div>
                    </div>
                    <a class="site-btn" @click="addProductToCart(product.id)" v-show="!in_cart">SHOP NOW</a>
                    <a class="site-btn site-btn-remove" @click="removeProductFromCart(product.id)" v-show="in_cart">REMOVE</a>
                    <div id="accordion" class="accordion-area">
                        <div class="panel">
                            <div class="panel-header" id="headingOne">
                                <button class="panel-link active" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">introduction</button>
                            </div>
                            <div id="collapse1" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="panel-body" v-html="product.introduction"></div>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-header" id="headingTwo">
                                <button class="panel-link" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">description </button>
                            </div>
                            <div id="collapse2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="panel-body" v-html="product.body"></div>
                            </div>
                        </div>
                        <!--<div class="panel">
                            <div class="panel-header" id="headingThree">
                                <button class="panel-link" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">shipping & Returns</button>
                            </div>
                            <div id="collapse3" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                <div class="panel-body">
                                    <h4>7 Days Returns</h4>
                                    <p>Cash on Delivery Available<br>Home Delivery <span>3 - 4 days</span></p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin pharetra tempor so dales. Phasellus sagittis auctor gravida. Integer bibendum sodales arcu id te mpus. Ut consectetur lacus leo, non scelerisque nulla euismod nec.</p>
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <!--<div class="social-sharing">
                        <a href=""><i class="fa fa-google-plus"></i></a>
                        <a href=""><i class="fa fa-pinterest"></i></a>
                        <a href=""><i class="fa fa-facebook"></i></a>
                        <a href=""><i class="fa fa-twitter"></i></a>
                        <a href=""><i class="fa fa-youtube"></i></a>
                    </div>-->
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        mounted() {
            console.log('product filter mounted.');
            const vm = this;
            vm.product = vm.product_original;
            Event.$on('cart_updated', function (cart) {
                vm.checkIfInCart(cart);
            });

            Event.$emit('get_cart');
        },

        props: {
            product_original: {
                required: true
            }
        },
        
        data() {
            return {
                product: {},
                in_cart: false,
                found: false,
                updated: false,
                quantity: 1,
            }
        },

        watch: {
            'quantity': function(newVal, oldVal) {
                if (this.updated) {
                    this.setProductToCart(this.product.id);
                } else {
                    this.updated = true;
                }
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

            setProductToCart: _.debounce(function (id) {
                const vm = this;
                if (vm.quantity > 0) {
                    Event.$emit('set_product_to_cart', {
                        'product'   : id,
                        'quantity'  : vm.quantity
                    });
                    vm.updated = false;
                }
            }, 500),

            checkIfInCart(cart) {
                const vm = this;
                vm.found = false;
                if (cart.items) {
                    cart.items.forEach(function(item, index) {
                        console.log(vm.product.title == item['product']);
                        if (item['product'] == vm.product.title) {
                            vm.found = true;
                            vm.quantity = item['quantity'];
                        }
                    });
                }
                vm.in_cart = vm.found;
            },
        },
    }
</script>

<style>
    .site-btn{
        cursor: pointer;
    }

    .site-btn-remove{
        background-color: #fff;
        border: 1px solid #414141;
    }
</style>
