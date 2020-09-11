<template>
    <div class="card">
        <div class="card-header">
            <h3>Edit Step</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-sd-6 col-xs-12">
                    <div class="row">
                        <div class="col-md-6 col-sd-6 col-xs-12">
                            <label for="step-order">Order</label>
                            <input type="text" id="step-order" class="form-control" v-model="order" required>
                        </div>
                        <div class="col-md-6 col-sd-6 col-xs-12">
                            <label for="step-time">Time</label>
                            <input type="text" id="step-time" class="form-control" v-model="time" required>
                        </div>
                        <div class="col-md-12 col-sd-12 col-xs-12">
                            <label for="step-title">Title</label>
                            <input type="text" id="step-title" class="form-control" v-model="title" required>
                        </div>
                        <div class="col-md-12 col-sd-12 col-xs-12">
                            <label for="step-description">Description</label>
                            <textarea id="step-description" class="form-control" v-model="description" required rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sd-6 col-xs-12">
                    <div class="col-md-12 col-sd-12 col-xs-12">
                        <v-gallery :images="imagesGallery" :index="indexImage" @close="indexImage = null" :id="'recipe-gallery'"></v-gallery>
                        <div class="row text-center">
                            <div class="col-md-12 col-sd-12 col-xs-12 text-center align-middle">
                                <div class="align-middle" style="height: 220px; position: relative;">
                                    <img
                                        class="image-border align-middle"
                                        v-show="imageSelected != null"
                                        :src="imagesGallery[imageSelected]"
                                        @click="indexImage = imageSelected"
                                        style="max-width: 200px; max-height: 200px;">
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
                        </file-selector>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <input type="button" class="btn btn-danger" value="Cancel" @click="$emit('close')">
            <input type="button" class="btn btn-success" value="Save" style="float:right" @click="save">
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            title: '',
            description: '',
            order: '',
            time: '',
            indexImage: null,
            imageSelected: null,
            imagesGallery: [],
            currentImages: [],
            nextImages:[]
        }
    },
    props: {
        id: Number,
        valueUpdate: Function
    },
    methods: {
        save() {
            console.log('saved');
            // if (!this.validateData()){
            //     return;
            // }
            const params = {
                id: this.id,
                title: this.title,
                description: this.description,
                time: this.time,
                order: this.order,
                currentImages: this.currentImages,
                nextImages: this.nextImages
            };
            let url = '/steps';
            if (this.id != 0) url = url+'/'+this.id;
            axios.post(
                url,
                params
            ).then(
                res => {
                    if (res['status'] == 200 ) {
                        this.$notify({
                            group: 'app',
                            type: 'success',
                            title: 'Success!',
                            text: 'The step has been saved correctly!'
                        });
                        let row = {
                            id: res.data,
                            order: this.order,
                            time: this.time,
                            title: this.title,
                            description: this.description,
                            images: this.nextImages
                        };
                        this.valueUpdate(row);
                        this.$emit('close');
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
        uploadFile(files) {
            this.isLoading = true;
            let formData = new FormData();
            formData.append('file', files[0]);
            let url = '/steps/image/upload';
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
        deleteImage() {
            if (this.imagesGallery.length > 0 && this.imageSelected != null) {
                let idImage = this.imagesGallery[this.imageSelected].split('/')[2];
                this.nextImages = this.nextImages.filter(e => e != idImage);
                this.imagesGallery.splice(this.imageSelected,1);
                this.imageSelected = this.imagesGallery.length > 0 ? 0 : null;
            }
        }
    },
    created() {
        if (this.id == 0) {
            this.title = '';
            this.description = '';
            this.order = '';
            this.time = '';
            this.imagesGallery = [];
            this.currentImages = [];
            this.nextImages = [];
            this.imageSelected = null;
        } else {
            let url = '/steps/getStep/'+this.id;
            axios.get(
                url
            ).then(
                res => {
                    if (res['status'] == 200 ) {
                        this.title = res.data.title;
                        this.description = res.data.description;
                        this.order = res.data.order;
                        this.time = res.data.time;
                        this.imagesGallery = [];
                        this.currentImages = [];
                        this.nextImages = [];
                        for (var i = 0; i < res.data.images.length; i++) {
                            let image = res.data.images[i];
                            this.imagesGallery.push('/images/'+image.id);
                            this.currentImages.push(image.id);
                            this.nextImages.push(image.id);
                        }
                        this.imageSelected = this.imagesGallery.length > 0 ? 0 : null;
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
</style>
