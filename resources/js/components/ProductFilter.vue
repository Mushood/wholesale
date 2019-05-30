<template>
    <div>
        <div class="filter-widget">
            <h2 class="fw-title">Categories</h2>
            <ul class="category-menu">
                <li v-for="category in filters.categories">
                    <input name="category" :value="category" type="checkbox" class="checkbox-custom" v-model="search.categories">{{ category.name }}
                </li>
            </ul>
        </div>
        <div class="filter-widget mb-0">
            <h2 class="fw-title">refine by</h2>
            <div class="price-range-wrap">
                <h4>Price</h4>
                <vue-slider
                        :min="filters.price.min"
                        :max="filters.price.max"
                        :step-style='{"backgroundColor": "#e77771"}'
                        :bg-style="{'backgroundColor': 'grey',}"
                        :tooltipStyle='{"backgroundColor": "#e77771","borderColor": "#e77771"}'
                        :processStyle='{"backgroundColor": "#e77771"}'
                        :piecewise="true"
                ></vue-slider>
            </div>
        </div>
        <div class="filter-widget">
            <h2 class="fw-title">Brand</h2>
            <ul class="category-menu">
                <li v-for="category in filters.categories"><a href="#">{{ category.name }}</a></li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('product filter mounted.')
        },

        props: {

        },

        data() {
            return {
                filters: {
                    categories: [ {id: 1, name: 'name1'}, {id: 2, name: 'name2'}],
                    price: { min: 0, max: 100},
                    brands: [ {id: 1, name: 'name1'}, {id: 2, name: 'name2'}],
                },
                search: {
                    categories: [],
                    price: { min: 0, max: 100},
                    brands: [],
                }
            }
        },
    }
</script>
