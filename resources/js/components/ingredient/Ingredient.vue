<template>
    <div>
        <div class="card mb-4">
            <div class="card-body text-center">
                <img :src="imageUrl" class="img-thumbnail">
                <h3>{{ingredient.name}}</h3>
                <input type="button" class="btn btn-primary" @click="openIngredient" value="Open">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        ingredient: Object,
        image: Object
    },
    computed: {
        imageUrl: function() {
            let id = this.image ? this.image.id : 0;
            return 'images/'+id;
        }
    },
    methods: {
        openIngredient() {
            window.location = '/ingredients/'+this.ingredient.id;
        }
    }
}
</script>

<style scoped>
    .img-thumbnail {
        max-height: 300px;
        max-width: 300px;
    }

    .card {
        height: 400px;
    }

</style>
