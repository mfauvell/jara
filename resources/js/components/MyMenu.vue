<template>
    <div>
        <sidebar-menu
            :menu="menu"
            :collapsed="collapsed"
            @toggle-collapse="onToggleCollapse"
        >
        </sidebar-menu>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex';

    const spacer = {
        template: `<hr style="border-color: rgba(0, 0, 0, 0.1); margin: 25px;">`
    }
    const separator = {
        template: `<hr style="border-color: rgba(255, 255, 255, 0.1); margin: 10px;">`
    }

    export default {
        data() {
            return {

            }
        },
        computed: {
            ...mapGetters({
                collapsed: 'collapsed',
                isOnMobile: 'onMobile',
                isAdmin: 'isAdmin'
            }),
            menu: function () {
                return [
                    {
                        component: spacer
                    },
                    {
                        header: true,
                        title: 'Jara',
                        hiddenOnCollapse: true
                    },
                    {
                        href: '/',
                        title: 'Dashboard',
                        icon: 'fa fa-user'
                    },
                    {
                        href: '/recipes',
                        title: 'Recipes',
                        icon: 'fa fa-user'
                    },
                    {
                        href: '/ingredients',
                        title: 'Ingredients',
                        icon: 'fa fa-user'
                    },
                    {
                        component: separator
                    },
                    {
                        title: 'Admin',
                        icon: 'fa fa-chart-area',
                        child: [
                            {
                                href: '/admin/users',
                                title: 'Users'
                            }
                        ],
                        hidden: !this.isAdmin,
                    }
                ];
            },
        },
        mounted () {
            this.onResize()
            window.addEventListener('resize', this.onResize)
        },
        methods: {
            ...mapActions({
                setCollapsed: 'setCollapsed',
                setOnMobile: 'setOnMobile'
            }),
            onResize () {
                if (window.innerWidth <= 767) {
                    this.setCollapsed(true);
                    this.setOnMobile(true);
                }
            },
            onToggleCollapse (collapsed) {
                console.log(collapsed)
                this.setCollapsed(collapsed);
            },
        }
    }
</script>

<style scoped>
    .sidebar-overlay {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: #000;
        opacity: 0.5;
        z-index: 900;
    }
</style>
