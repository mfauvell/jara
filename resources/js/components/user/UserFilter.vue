<template>
    <div class="card">
        <div class="card-header">
            <h2>Filters</h2>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="user-filter-name">Name</label>
                <input type="text" id="user-filter-name" class="form-control" placeholder="Name" v-model="name">
                <label for="user-filter-email">Email</label>
                <input type="email" id="user-filter-email" class="form-control" placeholder="Email" v-model="email">
                <label for="user-filter-role">Role</label>
                <v-select
                    id="user-filter-role"
                    :options="selectRoles"
                    v-model="role"
                />
            </div>
        </div>
        <div class="card-footer">
            <input type="button" id="user-filter-filter" class="btn btn-primary" value='Filter' @click="filter">
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            role: null,
            name: '',
            email: ''
        }
    },
    computed: {
        selectRoles: function () {
            return this.roles.map(function (item) {
                return {code: item.id, label: item.name}
            });
        }
    },
    props: {
        roles: Array
    },
    methods: {
        filter() {
            const params = {
                name: this.name,
                email: this.email,
                role_id: this.role != null ? this.role.code : ''
            };
            axios.get(
                '/admin/users/search',
                {
                    params
                }
            ).then(
                res => {
                    let data = res.data;
                    this.$emit('userFilterHasResults', data);
                }
            ).catch(
                error => console.log(error)
            );
        }
    }
}
</script>
