<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12 col-sd-12 col-xs-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h2>Latest Private Recipes</h2>
                    </div>
                    <div class="card-body">
                        <carousel>
                            <slide
                                class="slide"
                                is="item-recipe"
                                v-for="(recipe, index) in lastprivaterecipes"
                                :key="index"
                                :recipe="recipe"
                            />
                        </carousel>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sd-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h2>Latest Public Recipes</h2>
                    </div>
                    <div class="card-body">
                        <carousel>
                            <slide
                                class="slide"
                                is="item-recipe"
                                v-for="(recipe, index) in lastpublicrecipes"
                                :key="index"
                                :recipe="recipe"
                            />
                        </carousel>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import RecipeItemList from './recipe/RecipeItemList.vue';

export default {
    props: {
        lastpublicrecipes: Array,
        lastprivaterecipes: Array
    },
    components: {
        itemRecipe: RecipeItemList
    }
}
</script>

<style scoped>
    .slide {
        margin-left: 10px;
        margin-right: 10px;
    }
</style>
