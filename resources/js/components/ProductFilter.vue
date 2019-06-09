<template>
    <div>
        <div class="filter-widget">
            <h2 class="fw-title">Categories</h2>
            <ul class="category-menu">
                <li v-for="category in filters.categories">
                    <input
                            name="category" :value="category" type="checkbox" class="checkbox-custom"
                            v-model="search.categories" @change="submitSearch"
                    >{{ category.name }}
                </li>
            </ul>
        </div>
        <div class="filter-widget mb-0">
            <h2 class="fw-title">refine by</h2>
            <div class="price-range-wrap">
                <h4>Price</h4>
                <vue-slider
                        :min="0"
                        :max="1000"
                        v-model="filters.price"
                        :bg-style="{'backgroundColor': 'grey',}"
                        :tooltipStyle='{"backgroundColor": "#f51167","borderColor": "#f51167"}'
                        :processStyle='{"backgroundColor": "#f51167"}'
                        :process="true"
                        @change="submitSearch"
                ></vue-slider>
            </div>
        </div>
        <div class="filter-widget">
            <h2 class="fw-title">Brand</h2>
            <ul class="category-menu">
                <li v-for="brand in filters.brands">
                    <input
                            name="category" :value="brand" type="checkbox" class="checkbox-custom"
                            v-model="search.brands" @change="submitSearch"
                    >{{ brand.name }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('product filter mounted.');
        },

        props: {

        },

        data() {
            return {
                filters: {
                    name: "",
                    categories: [ {id: 1, name: 'name1'}, {id: 2, name: 'name2'}],
                    price: [ 0, 1000],
                    brands: [ {id: 1, name: 'name1'}, {id: 2, name: 'name2'}],
                },
                search: {
                    name: "",
                    categories: [],
                    price: { min: 0, max: 1000},
                    brands: [],
                }
            }
        },

        methods: {
            submitSearch: _.debounce(function () {
                this.search.price.min = this.filters.price[0];
                this.search.price.max = this.filters.price[1];
                Event.$emit('submitsearch', this.search);
            }, 500),
        },
    }
</script>
