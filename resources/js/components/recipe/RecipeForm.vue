<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-sd-6 col-xs-12">
                <div class="col-md-12 col-sd-12 col-xs-12">
                    <v-gallery :images="imagesGallery" :index="indexImage" @close="indexImage = null" :id="'recipe-gallery'"></v-gallery>
                    <div class="row text-center">
                        <div class="col-md-12 col-sd-12 col-xs-12 text-center align-middle">
                            <div class="align-middle" style="height: 320px; position: relative;">
                                <img
                                    class="image-border align-middle"
                                    v-show="imageSelected != null"
                                    :src="imagesGallery[imageSelected]"
                                    @click="indexImage = imageSelected"
                                    style="max-width: 300px; max-height: 300px;">
                                <input type="button" class="btn btn-danger" @click="deleteImage" value="Delete">
                            </div>
                        </div>
                        <div class="image col-md-12 col-sd-12 col-xs-12">
                            <div
                                class="image image-border"
                                v-for="(image, imageIndex) in imagesGallery"
                                :key="imageIndex"
                                @click="imageSelected = imageIndex"
                                :style="{ backgroundImage: 'url(' + image + ')', width: '50px', height: '50px' }"
                            ></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-sd-12 col-xs-12">
                    <file-selector
                        accept-extensions=".jpg,.png,.jpeg"
                        :max-file-size="5 * 1024 * 1024"
                        @changed="uploadFile"
                    >
                        Select image file
                        <div slot="top" class="section-top">
                            <p>
                                You can click the below button or drop an image into this area.
                            </p>
                            Max file size allowed: 5 MB.<br/>
                            File extensions: JPG, PNG.
                        </div>
                        <div slot="loader" class="section-loader">
                            Processing file<br/>
                            please wait...
                        </div>
                    </file-selector>
                </div>
            </div>
            <div class="col-md-6 col-sd-6 col-xs-12">
                <div class="row">
                    <div class="col-md-12 col-sd-12 col-xs-12">
                        <input type="button" class="btn btn-primary save-recipe" value="Save" @click="saveRecipe">
                    </div>
                    <div class="col-md-12 col-sd-12 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h2>Basic Data</h2>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-sd-12 col-xs-12">
                                            <label for="recipe-title">Title</label>
                                            <input type="text" id="recipe-title" class="form-control" v-model="title" required>
                                        </div>
                                        <div class="col-md-12 col-sd-12 col-xs-12">
                                            <label for="recipe-description">Description</label>
                                            <textarea  id="recipe-description" class="form-control" v-model="description" required rows="5"></textarea>
                                        </div>
                                        <div class="col-md-6 col-sd-6 col-xs-12">
                                            <label for="recipe-time">Time</label>
                                            <input type="text" id="recipe-time" class="form-control" v-model="time" required>
                                        </div>
                                        <div class="col-md-6 col-sd-6 col-xs-12">
                                            <label for="recipe-visibility">Visibility</label>
                                            <v-select
                                                id="recipe-visibility"
                                                :options="selectVisibilities"
                                                v-model="visibilityComputed"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sd-12 col-xs-12">
                <input type="button" class="btn btn-primary save-recipe" value="Save" @click="saveRecipe">
            </div>
            <div class="col-md-12 col-sd-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9 col-sd-9 col-xs-12">
                                <h2>Ingredients</h2>
                            </div>
                            <div class="col-md-3 col-sd-3 col-xs-12">
                                <input type="button" class="btn btn-success float-right mr-3" @click="addRowIngredient" value="Add">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Ingredient</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    is="ingredient-row"
                                    v-for="(row, index) in ingredientsList"
                                    :key="index"
                                    :row="row"
                                    :index="index"
                                    @deleteRow="ingredientsList.splice($event,1)"
                                    :allIngredients="allIngredients"
                                />
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sd-12 col-xs-12">
                <input type="button" class="btn btn-primary save-recipe" value="Save" @click="saveRecipe">
            </div>
            <div class="col-md-12 col-sd-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9 col-sd-9 col-xs-12">
                                <h2>Steps</h2>
                            </div>
                            <div class="col-md-3 col-sd-3 col-xs-12">
                                <input type="button" class="btn btn-success float-right mr-3" @click="addStep" value="Add">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Time</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Gallery</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    is="step-row"
                                    v-for="(row, index) in stepsList"
                                    :key="index"
                                    :row="row"
                                    :index="index"
                                    @stepRemove="deleteStep($event)"
                                />
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sd-12 col-xs-12">
                <input type="button" class="btn btn-primary save-recipe" value="Save" @click="saveRecipe">
            </div>
        </div>
    </div>
</template>

<script>
import RecipeIngredientRow from './RecipeIngredientRow';
import RecipeStepRow from './RecipeStepRow';
import StepForm from '../step/StepForm';

export default {
    data() {
        return {
            id: this.recipe.id ? this.recipe.id : 0,
            title: this.recipe.title ? this.recipe.title : '',
            description: this.recipe.description ? this.recipe.description : '',
            time: this.recipe.time ? this.recipe.time : '',
            visibility: this.recipe.visibility_id ? this.recipe.visibility_id : '',
            indexImage: null,
            imageSelected: null,
            isLoading: false,
            currentImages: null,
            nextImages: null,
            imagesGallery: [],
            ingredientsList: [],
            allIngredients: null,
            stepsList: [],
            currentSteps: null,
            nextSteps: null
        }
    },
    computed: {
        selectVisibilities: function () {
            return this.visibilities.reduce(function (map,item) {
                map[item.id] = {code: item.id, label: item.name}
                return map;
            }, []);
        },
        visibilityComputed: {
            get() {
                return this.selectVisibilities[this.visibility];
            },
            set(value) {
                this.visibility = value.code;
            }
        },
    },
    props: {
        recipe: Object,
        images: Array,
        ingredients: Array,
        visibilities: Array,
        steps: Array
    },
    components: {
        ingredientRow: RecipeIngredientRow,
        stepRow: RecipeStepRow
    },
    methods: {
        deleteImage() {
            if (this.imagesGallery.length > 0 && this.imageSelected != null) {
                let idImage = this.imagesGallery[this.imageSelected].split('/')[2];
                this.nextImages = this.nextImages.filter(e => e != idImage);
                this.imagesGallery.splice(this.imageSelected,1);
                this.imageSelected = this.imagesGallery.length > 0 ? 0 : null;
            }
        },
        deleteStep(index) {
            let stepId = this.stepsList[index].id;
            this.nextSteps = this.nextSteps.filter(e => e != stepId);
            this.stepsList.splice(index,1)
        },
        uploadFile(files) {
            this.isLoading = true;
            let formData = new FormData();
            formData.append('file', files[0]);
            let url = '/recipes/image/upload';
            axios.post(
                url,
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then(
                res => {
                    let id = res.data.id;
                    this.imagesGallery.push('/images/'+id);
                    this.nextImages.push(id);
                    this.imageSelected = this.imagesGallery.length -1;
                }
            ).catch(
                error => console.log(error)
            ).finally(
                () => this.isLoading = false
            );
        },
        saveRecipe() {
            // if (!this.validateData()){
            //     return;
            // }
            let oldId = this.id;
            const params = {
                id: this.id,
                title: this.title,
                description: this.description,
                time: this.time,
                visibility: this.visibility,
                currentImages: this.currentImages,
                nextImages: this.nextImages,
                ingredientsList: this.ingredientsList,
                currentSteps: this.currentSteps,
                nextSteps: this.nextSteps
            };
            let url = '/recipes';
            if (this.id != 0) url = url+'/'+this.id;
            axios.post(
                url,
                params
            ).then(
                res => {
                    if (res['status'] == 200 && res['data'] != 0) {
                        this.$notify({
                            group: 'app',
                            type: 'success',
                            title: 'Success!',
                            text: 'The recipe has been saved correctly!'
                        });
                        if (oldId == 0) {
                            this.sleep(2000).then(() => {
                                window.location = '/recipes/'+res['data']+'/edit';
                            });
                        }
                    } else {
                        this.$notify({
                            group: 'app',
                            type: 'error',
                            title: 'Error!',
                            text: 'Something went wrong!'
                        });
                    }
                }
            ).catch(
                error => console.log(error)
            );
        },
        getIngredient(id) {
            return new Promise(
                (resolve, reject) => {
                    let url = '/ingredients/getIngredient/'+id;
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
        addRowIngredient() {
            this.ingredientsList.push({
                id: 0,
                name: '',
                image: 0,
                quantity: '',
                unit: ''
            })
        },
        addStep() {
            this.$modal.show(
                StepForm,
                {
                    id: 0,
                    valueUpdate: (newValue) => {
                        // this.$emit('stepCreateRow',newValue);
                        this.stepsList.push(newValue);
                        this.nextSteps.push(newValue.id);
                    }
                },
            )
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
        sleep(time) {
            return new Promise((resolve) => setTimeout(resolve, time));
        }
    },
    created: function() {
        //Images
        this.imagesGallery = [];
        this.currentImages = [];
        this.nextImages = [];
        for (var i = 0; i < this.images.length; i++) {
            let image = this.images[i];
            this.imagesGallery.push('/images/'+image.id);
            this.currentImages.push(image.id);
            this.nextImages.push(image.id);
        }
        this.imageSelected = this.imagesGallery.length > 0 ? 0 : null;
        //Ingredients
        this.ingredientsList = [];
        for (let i = 0; i < this.ingredients.length; i++) {
            this.getIngredient(this.ingredients[i].pivot.ingredient_id).then( ingredientData => {
                const row ={
                    id: ingredientData['ingredient'].id,
                    name: ingredientData['ingredient'].name,
                    image: ingredientData['image'] ? ingredientData['image'].id : 0,
                    quantity: this.ingredients[i].pivot.quantity,
                    unit: this.ingredients[i].pivot.unit,
                };
                this.ingredientsList.push(row);
            });
        }
        this.getAllIngredient().then( allIngredients => {
            this.allIngredients = allIngredients;
        });
        //Steps
        this.stepsList = [];
        this.currentSteps = [];
        this.nextSteps = [];
        for (var i = 0; i < this.steps.length; i++) {
            let step = this.steps[i];
            let stepGallery = [];
            for (var j = 0; j < step.images.length; j++) {
                stepGallery.push(step.images[j].id);
            }
            this.stepsList.push({
                id: step.id,
                order: step.order,
                time: step.time,
                title: step.title,
                description: step.description,
                images: stepGallery
            })
            this.currentSteps.push(step.id);
            this.nextSteps.push(step.id);
        }
    },
}
</script>

<style scoped>
  .image {
    float: left;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
  }

  .image-border {
    border: 1px solid #ebebeb;
    margin: 5px;
  }

  .save-recipe {
      margin-bottom: 10px;
      margin-top: 10px;
      margin-right: 20px;
      float: right;
  }
</style>
