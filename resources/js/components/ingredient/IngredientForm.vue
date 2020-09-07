<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Edit Ingredient: {{ id }}</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-sd-6 col-xs-12 text-center">
                            <img :src="imageUrl" class="img-thumbnail">
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
                        <div class="col-md-6 col-sd-6 col-xs-12">
                            <label for="ingredient-name">Name</label>
                            <input type="text" id="ingredient-name" class="form-control" v-model="name" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <input type="button" class="btn btn-primary float-right mr-3" @click="saveIngredient" value="Save">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            id: this.ingredient.id,
            name: this.ingredient.name ? this.ingredient.name : '',
            isLoading: false,
            currentImage: this.image.id ? this.image.id : 0,
            showImage: this.image,
            nextImage: 0
        };
    },
    computed: {
       imageUrl: function () {
           return '/images/'+this.showImage.id;
       }
    },
    props: {
        ingredient: Object,
        image: Object
    },
    methods: {
        saveIngredient() {
            // if (!this.validateData()){
            //     return;
            // }
            const params = {
                id: this.id,
                name: this.name,
                currentImage: this.currentImage,
                nextImage: this.nextImage
            };
            let url = '/ingredients';
            if (this.id != 0) url = url+'/'+this.id;
            axios.post(
                url,
                params
            ).then(
                res => {
                    if (res['status'] == 200 && res['data'] == 1) {
                        this.$notify({
                            group: 'app',
                            type: 'success',
                            title: 'Success!',
                            text: 'The ingredient has been saved correctly!'
                        });
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
            const params = {
                entity: 'Ingredient',
                id: this.id,
                file: files[0],
            };
            let formData = new FormData();
            formData.append('entity', 'Ingredient');
            formData.append('id', this.id);
            formData.append('file', files[0]);
            let url = '/ingredients/image/upload';
            axios.post(
                url,
                formData,
                {headers: {
                    'Content-Type': 'multipart/form-data'
                }}
            ).then(
                res => {
                    this.nextImage = res.data.id;
                    this.showImage = res.data;
                }
            ).catch(
                error => console.log(error)
            ).finally(
                () => this.isLoading = false
            );
        }
    }
}
</script>

<style lang="scss">

</style>
