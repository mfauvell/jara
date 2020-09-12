<template>
    <div class="card mb-2">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 col-sd-12 col-xs-12 text-left">
                    <div class="row">
                        <div class="col-md-1 col-sd-1 col-xs-1">
                            <h1><span class="badge badge-dark">{{index+1}}</span></h1>
                        </div>
                        <div class="col-md-8 offset-md-1 col-sd-8 offset-sd-1 col-xs-8 offset-xs-1">
                            <h1>{{step.title}}</h1>
                        </div>
                        <div class="col-md-2 col-sd-2 col-xs-2">
                            <h3><span class="badge badge-primary">{{step.time}} min.</span></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-sd-12 col-xs-12 mt-3">
                    <div class="row">
                        <div class="col-md-6 col-sd-6 col-xs-12">
                            <v-gallery :images="imagesGallery" :index="indexImage" @close="indexImage = null" :id="'step-gallery'+index"></v-gallery>
                            <div class="row text-center">
                                <div class="col-md-12 col-sd-12 col-xs-12 text-center align-middle">
                                    <div class="align-middle" style="height: 320px; position: relative;">
                                        <img
                                            class="image-border align-middle"
                                            v-show="imageSelected != null"
                                            :src="imagesGallery[imageSelected]"
                                            @click="indexImage = imageSelected"
                                            style="max-width: 300px; max-height: 300px;">
                                    </div>
                                </div>
                                <div class="image col-md-12 col-sd-12 col-xs-12">
                                    <div
                                        class="image image-border"
                                        v-for="(image, imageIndex) in imagesGallery"
                                        :key="imageIndex"
                                        @click="imageSelected = imageIndex"
                                        :style="{ backgroundImage: 'url(' + image + ')', width: '50px', height: '50px' }"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sd-6 col-xs-12">
                            <p class="description text-justify mt-4">{{step.description}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            indexImage: null,
            imageSelected: 0,
            // imagesGallery: [],
        }
    },
    props: {
        step: Object,
        index: Number
    },
    computed: {
        imagesGallery: function() {
            if (this.step.images.length == 0) {
                return [
                    '/images/0'
                ];
            } else {
                let images = [];
                for (var image in this.step.images) {
                    images.push('/images/'+this.step.images[image].id);
                }
                return images;
            }
        }
    }
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

    .description {
        font-style: italic;
        font-size: 20px;
        color: grey;
    }
</style>
