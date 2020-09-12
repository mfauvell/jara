<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sd-12 col-xs-12 text-center">
                <h1 class="title">{{recipe.title.toUpperCase()}}</h1>
            </div>
            <div class="col-md-12 col-sd-12 col-xs-12 text-center mb-5">
                <carousel
                    :scrollPerPage="true"
                    :perPageCustom="[[480, 3], [768, 5]]"
                    :centerMode="true"
                >
                    <slide
                        class="slide"
                        v-for="(image, index) in imagesGallery"
                        :key="index"
                        :image="image"
                    >
                        <img :src="'/images/'+image" style="width: 300px; max-width: 100%;">
                    </slide>
                </carousel>
            </div>
            <div class="col-md-8 offset-md-2 col-sd-8 offset-sd2 col-xs-8 offset-xd-2 mb-5">
                <input v-if="deletable == 1" type="button" class="btn btn-danger ml-3" value="Delete" @click="deleteRecipe" style="float: right;">
                <input v-if="editable == 1" type="button" class="btn btn-warning" value="Edit" @click="editRecipe" style="float: right;">
            </div>
            <div class="col-md-12 col-sd-12 col-xs-12 text-center">
                <div class="row">
                    <div class="col-md-4 offset-md-2 col-sd-4 offset-sd-2 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-sd-4 col-xs-12 text-left">
                                        <h3 class="ml-2"><span class="badge badge-secondary">{{recipe.visibility.name}}</span></h3>
                                    </div>
                                    <div class="col-md-4 offset-md-4 col-sd-4 offset-sd-4 col-xs-12">
                                        <h3 class="d-inline"><span class="badge badge-primary">Time:</span></h3>  <span class="time">{{recipe.time}} min.</span>
                                    </div>
                                </div>
                                <div>
                                    <p class="description text-justify mt-4">{{recipe.description}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sd-4 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table">
                                <tr
                                    is="ingredient"
                                    v-for="(ingredient, index) in recipe.ingredients"
                                    :key="index"
                                    :ingredient="ingredient"
                                />
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sd-8 offset-sd-2 col-xs-12 text-center mt-5">
                <div class="row">
                    <div
                        class="col-md-12 col-sd-12 col-xs-12"
                        is="step"
                        v-for="(step, index) in recipe.steps"
                        :key="index"
                        :step="step"
                        :index="index"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import RecipeShowIngredient from './RecipeShowIngredient.vue';
import RecipeShowStep from './RecipeShowStep.vue';

export default {
    props: {
        recipe: Object,
        editable: Number,
        deletable: Number
    },
    computed: {
        imagesGallery: function() {
            if (this.recipe.images.length > 0) {
                let images = []
                for (var image in this.recipe.images) {
                    images.push(this.recipe.images[image].id);
                }
                return images;
            } else {
                return [0];
            }
        }
    },
    components: {
        ingredient: RecipeShowIngredient,
        step: RecipeShowStep
    },
    methods: {
        editRecipe() {
            window.location = '/recipes/'+this.recipe.id+'/edit';
        },
        deleteRecipe() {
            window.location = '/recipes/'+this.recipe.id+'/delete';
        }
    }
}
</script>

<style scoped>
    .time {
        font-size: 16px;
        font-weight: 600;
    }

    .description {
        font-style: italic;
        font-size: 20px;
        color: grey;
    }

    .title {
        font-size: 4rem;
        margin: 30px 0 30px;
        font-weight: bold;
    }
</style>
