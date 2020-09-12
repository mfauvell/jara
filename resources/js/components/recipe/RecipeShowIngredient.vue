<template>
    <tr>
        <td class="w-40 align-middle">
            <v-gallery
                :images="imageGallery"
                :index="indexImage"
                @close="indexImage = null"
                :id="'ingredient-row-gallery'+ingredient.id"
            />
            <img :src="'/images/'+(ingredient.images ? ingredient.images.id : 0)" height="50px" @click="indexImage = 0">
        </td>
        <td class="w-30 align-middle">
            <h4>{{ingredient.name}}</h4>
        </td>
        <td class="w-30 align-middle text-left">
            <span>{{ingredient.pivot.quantity}} {{ingredient.pivot.unit}}</span>
        </td>
    </tr>
</template>

<script>
export default {
    data() {
        return {
            indexImage: null
        }
    },
    props: {
        ingredient: Object
    },
    computed: {
        imageGallery: function() {
            if (!this.ingredient.images) {
                return [
                    '/images/0'
                ];
            } else {
                return [
                    '/images/'+this.ingredient.images.id
                ];
            }
        }
    }
}
</script>
