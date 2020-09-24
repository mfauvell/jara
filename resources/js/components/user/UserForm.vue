<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Edit User: {{ id }}</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-sd-6 col-xs-12">
                            <label for="user-name">Name</label>
                            <input type="text" id="user-name" class="form-control" v-model="name" required>
                        </div>
                        <div class="col-md-6 col-sd-6 col-xs-12">
                            <label for="user-email">Email</label>
                            <input type="email" id="user-email" class="form-control" v-model="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sd-6 col-xs-12">
                            <label for="user-password">Password</label>
                            <input type="password" id="user-password" class="form-control" v-model="password" required>
                        </div>
                        <div class="col-md-6 col-sd-6 col-xs-12">
                            <label for="user-confirm password">Confirm Password</label>
                            <input type="password" id="user-confirm-password" class="form-control" v-model="password2" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sd-6 col-xs-12">
                            <label for="user-role">Role</label>
                            <v-select
                                id="user-role"
                                :options="selectRoles"
                                v-model="roleComputed"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <input type="button" class="btn btn-primary float-right mr-3" @click="saveUser" value="Save">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            id: this.user.id,
            name: this.user.name ? this.user.name : '',
            email: this.user.email ? this.user.email : '',
            role : this.user.role_id ? this.user.role_id : '',
            password: '',
            password2: ''
        }
    },
    computed: {
        selectRoles: function () {
            return this.roles.reduce(function (map,item) {
                map[item.id] = {code: item.id, label: item.name}
                return map;
            }, []);
        },
        roleComputed: {
            get() {
                return this.selectRoles[this.role];
            },
            set(value) {
                this.role = value.code;
            }
        },
    },
    props: {
        roles: Array,
        user: Object
    },
    methods: {
        saveUser() {
            if (!this.validateData()){
                return;
            }
            const params = {
                id: this.id,
                name: this.name,
                email: this.email,
                role_id: this.role,
                password: this.password
            };
            let url = '/admin/users';
            if (this.id != 0) url = url+'/'+this.id;
            axios.post(
                url,
                params
            ).then(
                res => {
                    if (res['status'] == 200 && res['data'].id != 0) {
                        this.$notify({
                            group: 'app',
                            type: 'success',
                            title: 'Success!',
                            text: 'Thes user has been saved correctly!'
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
        validateData() {
            let result = true;

            if (this.name == '') {
                this.$notify({
                        group: 'app',
                        type: 'error',
                        title: 'Error!',
                        text: 'The name of user is required!'
                });
                result = false;
            }

            if (this.email == '') {
                this.$notify({
                        group: 'app',
                        type: 'error',
                        title: 'Error!',
                        text: 'The email of user is required!'
                });
                result = false;
            }

            if (this.role == '') {
                this.$notify({
                        group: 'app',
                        type: 'error',
                        title: 'Error!',
                        text: 'The role of user is required!'
                });
                result = false;
            }

            if (this.id == 0 && this.password == ''){
                this.$notify({
                        group: 'app',
                        type: 'error',
                        title: 'Error!',
                        text: 'The passwords are requireds!'
                });
                result = false;
            }

            if (this.password != this.password2){
                this.$notify({
                        group: 'app',
                        type: 'error',
                        title: 'Error!',
                        text: 'The passwords must be equals!'
                });
                result = false;
            }

            return result;
        }
    }
}
</script>
