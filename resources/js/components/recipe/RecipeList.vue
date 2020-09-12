<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12 col-sd-12 col-xs-12 mb-5">
                <recipe-filter
                    @recipeFilterHasResults="recipesList = $event"
                />
            </div>

            <div class="col-md-12 col-sd-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9 col-sd-9 col-xs-12">
                                <h2>Recipes</h2>
                            </div>
                            <div class="col-md-3 col-sd-3 col-xs-12">
                                <input type="button" class="btn btn-success float-right mr-3" @click="createRecipe" value="+ Recipe">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sd-6 col-xs-12"
                                is="recipe"
                                v-for="(recipe, index) in pageOfRecipes"
                                :key="index"
                                :recipe="recipe"
                            />
                        </div>
                    </div>
                    <div class="card-footer">
                        <jw-pagination
                            :items="recipesList"
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
import RecipeFilter from './RecipeFilter.vue';
import RecipeItemList from './RecipeItemList.vue';

export default {
    data() {
        return {
            recipesList: this.recipes,
            pageOfRecipes: []
        }
    },
    props: {
        recipes: Array
    },
    components: {
        recipeFilter: RecipeFilter,
        recipe: RecipeItemList
    },
    methods: {
        onChangePage(pageItems) {
            this.pageOfRecipes = pageItems;
        },
        createRecipe() {
            window.location = '/recipes/create';
        }
    }
}
</script>
