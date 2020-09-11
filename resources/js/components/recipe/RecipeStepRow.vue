<template>
    <tr>
        <td>{{row.order}}</td>
        <td>{{row.time}}</td>
        <td>{{row.title}}</td>
        <td>{{row.description}}</td>
        <td class="align-middle">
            <v-gallery
                :images="imageGallery"
                :index="indexImage"
                @close="indexImage = null"
                :id="'step-row-gallery'+index"
            />
            <img
                :src="imageGallery[0]"
                class="image-border align-middle"
                @click="indexImage = 0"
                style="max-height: 50px;"
            >
        </td>
        <td>
            <input type="button" class="btn btn-warning" @click="editStep" value="edit">
            <input type="button" class="btn btn-danger" @click="removeStep" value="remove">
        </td>
    </tr>
</template>

<script>
import StepForm from '../step/StepForm';

export default {
    data() {
        return {
            indexImage: null
        }
    },
    props: {
        row: Object,
        index: Number
    },
    computed: {
        imageGallery: function() {
            let imageGallery = [];
            if (this.row.images.length != 0) {
                for (var i = 0; i < this.row.images.length; i++) {
                    imageGallery.push('/images/'+this.row.images[i]);
                }
            } else {
                imageGallery.push('/images/0');
            }
            return imageGallery;
        },
    },
    methods: {
        editStep() {
            this.$modal.show(
                StepForm,
                {
                    id: this.row.id,
                    valueUpdate: (newValue) => {
                        this.row.order = newValue.order;
                        this.row.time = newValue.time;
                        this.row.title = newValue.title;
                        this.row.description = newValue.description;
                        this.row.images = newValue.images;
                    }
                }
            )
        },
        removeStep() {
            this.$emit('stepRemove', this.index);
        }
    }
}
</script>
