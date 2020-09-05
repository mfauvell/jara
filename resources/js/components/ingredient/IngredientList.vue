<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12 col-sd-12 col-xs-12 mb-5">
                <ingredient-filter
                    @ingredientFilterHasResults="ingredientsList = $event"
                />
            </div>

            <div class="col-md-12 col-sd-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9 col-sd-9 col-xs-12">
                                <h2>Ingredientes</h2>
                            </div>
                            <div class="col-md-3 col-sd-3 col-xs-12">
                                <input type="button" class="btn btn-success float-right mr-3" @click="createIngredient" value="+ Ingredient">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sd-6 col-xs-12"
                                is="ingredient"
                                v-for="(ingredient, index) in pageOfIngredients"
                                :key="index"
                                :ingredient="ingredient['ingredient']"
                                :image="ingredient['image']"
                            />
                        </div>
                    </div>
                    <div class="card-footer">
                        <jw-pagination
                            :items="ingredientsList"
                            @changePage="onChangePage"
                            :pageSize="12"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import IngredientFilter from './IngredientFilter.vue';
import Ingredient from './Ingredient.vue';

export default {
    data() {
        return {
            ingredientsList: this.ingredients,
            pageOfIngredients: []
        }
    },
    props: {
        ingredients: Array
    },
    components: {
        ingredientFilter: IngredientFilter,
        ingredient: Ingredient
    },
    methods: {
        onChangePage(pageItems) {
            this.pageOfIngredients = pageItems;
        },
        createIngredient() {
            window.location = 'ingredients/create';
        }
    }
}
</script>
