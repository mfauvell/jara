<template>
    <div>
        <div class="card mb-4">
            <div class="card-body text-center">
                <div>
                    <img :src="imageUrl" class="img-thumbnail">
                    <h3>{{recipe.title}}</h3>
                    <!-- <p>{{recipe.description}}</p> -->
                    <span>Time: {{recipe.time}} min.</span>
                </div>

                <input type="button" class="btn btn-primary" @click="viewRecipe" value="Open">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            indexImage: 0,
        }
    },
    props: {
        recipe: Object,
    },
    computed: {
        imageUrl: function() {
            let id = this.recipe.images.length > 0 ? this.recipe.images[this.indexImage].id : 0
            return '/images/'+id;
        }
    },
    methods: {
        editRecipe() {
            window.location = '/recipes/'+this.recipe.id+'/edit';
        },
        viewRecipe() {
            window.location = '/recipes/'+this.recipe.id;
        },
        changeImage() {
            if (this.recipe.images.length > 0) {
                if ((this.indexImage + 1) == this.recipe.images.length) {
                    this.indexImage = 0;
                } else {
                    this.indexImage += 1;
                }
            }
        }
    },
    mounted: function () {
        window.setInterval(() => {
            this.changeImage()
        }, 10000)
    }
}
</script>

<style scoped>
    .img-thumbnail {
        max-height: 250px;
        max-width: 250px;
    }

    .card {
        height: 400px;
    }

</style>
