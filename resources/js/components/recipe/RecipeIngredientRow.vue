<template>
    <tr>
       <td class="text-center">
            <img :src="imgUrl" style="max-width: 75px; max-height: 75px;"></td>
       <td class="align-middle">
            <!-- <input type="text" class="form-control" v-model="row.name"> -->
            <v-autocomplete
                :items="items"
                v-model="item"
                :get-label="getLabelAutocomplete"
                @update-items="updateAutocomplete"
                :component-item="template"
                @change="resetRow"
                :input-attrs="{class:'form-control'}"
            />
       </td>
       <td class="align-middle">
            <input type="text" class="form-control" v-model="row.quantity">
       </td>
       <td class="align-middle">
            <input type="text" class="form-control" v-model="row.unit">
       </td>
       <td class="align-middle">
            <input type="button" class="btn btn-danger" @click="removeRow()" value="Remove">
       </td>
    </tr>
</template>

<script>
import RecipeIngredientItemAutocomplete from './RecipeIngredientItemAutocomplete';

export default {
    data() {
        return {
            // item: null,
            items: this.allIngredients,
            template: RecipeIngredientItemAutocomplete
        }
    },
    computed: {
        imgUrl : function() {
            return '/images/'+this.row.image;
        },
        item: {
            get() {
                if (this.items !== null) {
                    let value = this.items.filter((ingredient) => {
                        return ingredient.ingredient.id == this.row.id;
                    })
                    return value[0];
                }
                return null;
            },
            set(item) {
                if (item) {
                    this.row.id = item.ingredient.id;
                    this.row.image = item.image ? item.image.id : 0;
                    return item;
                }
            }
        }
    },
    watch: {
        allIngredients: function(val) {
            this.items = val;
        }
    },
    props: {
        row: Object,
        index: Number,
        allIngredients: Array
    },
    methods: {
        removeRow() {
            this.$emit('deleteRow', this.index);
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
            this.row.id = null;
            this.row.image = 0;
        }
    },
}
</script>

<style>
.v-autocomplete .v-autocomplete-input-group .v-autocomplete-input {
	font-size: 1.5em;
	padding: 10px 15px;
	box-shadow: none;
	border: 1px solid #157977;
	width: calc(100% - 32px);
	outline: none;
	background-color: #eee;
}

.v-autocomplete .v-autocomplete-input-group.v-autocomplete-selected .v-autocomplete-input {
	color: #008000;
	background-color: #f2fff2;
}

.v-autocomplete .v-autocomplete-list {
	width: 100%;
	text-align: left;
	border: none;
	border-top: none;
	max-height: 400px;
	overflow-y: auto;
    border-bottom: 1px solid #157977;
    z-index: 999;
}

.v-autocomplete .v-autocomplete-list .v-autocomplete-list-item {
	cursor: pointer;
	background-color: #fff;
	padding: 10px;
	border-bottom: 1px solid #157977;
	border-left: 1px solid #157977;
	border-right: 1px solid #157977;
}

.v-autocomplete .v-autocomplete-list .v-autocomplete-list-item:last-child {
	border-bottom: none;
}

.v-autocomplete .v-autocomplete-list .v-autocomplete-list-item:hover {
	background-color: #eee;
}

.v-autocomplete .v-autocomplete-list .v-autocomplete-list-item abbr {
	opacity: 0.8;
	font-size: 0.8em;
	display: block;
	font-family: sans-serif;
}
</style>
