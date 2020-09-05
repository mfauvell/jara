<template>
    <div class="card">
        <div class="card-header">
            <h2>Filters</h2>
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-3 col-sd-6 col-xs-12">
                        <label for="ingredient-filter-name">Name</label>
                        <input type="text" id="ingredient-filter-name" class="form-control" placeholder="Name" v-model="name">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <input type="button" id="ingredient-filter-filter" class="btn btn-primary" value='Filter' @click="filter">
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: ''
        }
    },
    methods: {
        filter() {
            const params = {
                name: this.name,
            };
            axios.get(
                '/ingredients/search',
                {
                    params
                }
            ).then(
                res => {
                    let data = res.data;
                    this.$emit('ingredientFilterHasResults', data);
                }
            ).catch(
                error => console.log(error)
            );
        }
    }
}
</script>
