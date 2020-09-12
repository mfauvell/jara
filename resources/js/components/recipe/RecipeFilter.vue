<template>
    <div class="card">
        <div class="card-header">
            <h2>Filters</h2>
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-3 col-sd-6 col-xs-12">
                        <label for="recipe-filter-title">Title</label>
                        <input type="text" id="recipe-filter-title" class="form-control" placeholder="Title" v-model="title">
                    </div>
                    <div class="col-md-3 col-sd-6 col-xs-12">
                        <label for="recipe-filter-ingredient">Ingredient</label>
                        <v-autocomplete
                            id="recipe-filter-ingredient"
                            :items="items"
                            v-model="ingredient"
                            :get-label="getLabelAutocomplete"
                            @update-items="updateAutocomplete"
                            :component-item="template"
                            @change="resetRow"
                            :input-attrs="{class:'form-control'}"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <input type="button" id="recipe-filter-filter" class="btn btn-primary" value='Filter' @click="filter">
        </div>
    </div>
</template>

<script>
import RecipeIngredientItemAutocomplete from './RecipeIngredientItemAutocomplete';

export default {
    data() {
        return {
            title: '',
            ingredient: '',
            allIngredients: [],
            items: this.allIngredients,
            template: RecipeIngredientItemAutocomplete
        }
    },
    watch: {
        allIngredients: function(val) {
            this.items = val;
        }
    },
    methods: {
        filter() {
            const params = {
                title: this.title,
                ingredient: this.ingredient ? this.ingredient.ingredient.id : ''
            };
            axios.get(
                '/recipes/search',
                {
                    params
                }
            ).then(
                res => {
                    let data = res.data;
                    this.$emit('recipeFilterHasResults', data);
                }
            ).catch(
                error => console.log(error)
            );
        },
        getAllIngredient() {
            return new Promise(
                (resolve, reject) => {
                    let url = '/ingredients/search';
                    axios.get(
                        url
                    ).then(
                        res => {
                            if (res['status'] == 200 ) {
                                resolve(res.data);
                            } else {
                                reject([]);
                            }
                        }
                    ).catch(
                        error => console.log(error)
                    );
                }
            );
        },
        getLabelAutocomplete(item) {
            if (item) {
                return item.ingredient.name;
            }
            return '';
        },
        updateAutocomplete (text) {
            this.items = this.allIngredients.filter((ingredient) => {
                return (new RegExp(text.toLowerCase())).test(ingredient.ingredient.name.toLowerCase())
            })
        },
        resetRow(){
            this.ingredient = null;
        }
    },
    created() {
        this.getAllIngredient().then( allIngredients => {
            this.allIngredients = allIngredients;
        });
    }
}
</script>
