<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-3 col-sd-3 col-xs-12">
                <user-filter
                    ref="userFilter"
                    :roles="roles"
                    @userFilterHasResults="rows = $event"
                />
            </div>
            <div class="col-md-9 col-sd-9 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                        <div class="col-md-9 col-sd-9 col-xs-12">
                            <h2>Usuarios</h2>
                        </div>
                        <div class="col-md-3 col-sd-3 col-xs-12">
                            <input type="button" class="btn btn-success float-right mr-3" @click="createUser" value="+ User">
                        </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <vue-good-table
                            :columns="columns"
                            :rows="rows"
                            :line-numbers="true"
                            :pagination-options="{
                                enabled: true,
                                mode: 'records',
                                perPage: 10,
                                position: 'bottom',
                                perPageDropdown: [25, 50, 100],
                                dropdownAllowAll: false,
                                nextLabel: 'next',
                                prevLabel: 'prev',
                                rowsPerPageLabel: 'Rows per page',
                                ofLabel: 'of',
                                pageLabel: 'page', // for 'pages' mode
                                allLabel: 'All',
                            }"
                        >
                            <template slot="table-row" slot-scope="props">
                                <span v-if="props.column.field == 'action'">
                                    <input type="button" class="btn btn-warning" value="Edit" @click="editUser(props.row.id)">
                                    <input type="button" class="btn btn-danger" value="Delete" @click="deleteUser(props.row.id)">
                                </span>
                                <span v-else>
                                    {{props.formattedRow[props.column.field]}}
                                </span>
                            </template>
                        </vue-good-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import UserFilter from './UserFilter.vue';

export default {
    props: {
        roles: Array
    },
    data() {
        return {
            columns: [
                {
                    label: 'ID',
                    field: 'id',
                    type: 'number'
                },
                {
                    label: 'Name',
                    field: 'name'
                },
                {
                    label: 'Email',
                    field: 'email'
                },
                {
                    label: 'Role',
                    field: 'role_id',
                    formatFn: this.convertRoleToName,
                    type: 'string',
                    sortable: false
                },
                {
                    label: '',
                    field: 'action',
                    width: '150px'
                }
            ],
            rows: []
        }
    },
    computed: {
        roleNames: function() {
            let roleNames = {};
            this.roles.forEach(element => {
                roleNames[element.id] = element.name;
            });
            return roleNames;
        }
    },
    components: {
        userFilter : UserFilter
    },
    methods: {
        convertRoleToName(value) {
            return this.roleNames[value];
        },
        editUser(id) {
            window.location = '/admin/users/'+id;
        },
        createUser() {
            window.location = '/admin/users/create';
        },
        deleteUser(id) {
            axios.post(
                '/admin/users/'+id+'/delete'
            ).then(
                res => {
                    if (res['status'] == 200 && res['data'] == 1) {
                        this.$refs.userFilter.filter();
                        this.$notify({
                            group: 'app',
                            type: 'success',
                            title: 'Success!',
                            text: 'Thes user has been deleted correctly!'
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
        }
    }
}
</script>
