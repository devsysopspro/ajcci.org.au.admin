<template>
    <nav v-if="isLoggedIn" class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <template v-for="section in sections">
                <sidebar-menu-section v-if="section.childs.length" :key="section.title" :section="section"></sidebar-menu-section>
                <sidebar-menu-item v-else :key="section.title" :item="section"></sidebar-menu-item>
            </template>
        </ul>
    </nav>
</template>

<script>
    import SidebarMenuSection from './SidebarMenuSection.vue';
    import SidebarMenuItem from './SidebarMenuItem.vue';

    export default {
        name: "sidebar-menu",
        components: {
            SidebarMenuItem,
            SidebarMenuSection
        },
        computed: {
            allContentModelsList() {
                let models = this.$store.getters.allContentModels;

                return models.map((model) => {
                    return Object.assign(model, {link: `/entry/${model.id}`, icon: 'fa-chevron-right'});
                });
            },
            projects() {
                let projects = this.$store.getters.projects;

                return projects.map((model) => {
                    return Object.assign(model, {link: `/project/${model.id}`, icon: 'fa-circle-o'});
                });
            },
            sections() {
                return [
                    {
                        title: 'Dashboard',
                        link: '/dashboard',
                        icon: 'fa-dashboard',
                        childs: []
                    },
                    {
                        title: 'Contents',
                        link: '/entry',
                        icon: 'fa-file-text',
                        childs: this.allContentModelsList
                    },
                    {
                        title: 'Models',
                        link: '/model',
                        icon: 'fa-cog',
                        childs: []
                    },
                    {
                        title: 'Users',
                        link: '/users',
                        icon: 'fa-users',
                        childs: []
                    }
                ]
            },
            isLoggedIn() {
                return this.$store.getters.isLoggedIn;
            }
        },
        watch: {
            isLoggedIn: function (newValue, oldValue) {
                if(newValue === true && oldValue === false) {
                    this.$store.dispatch('getAllContentModels');
                }
            }
        },
        created() {
            if(this.isLoggedIn === true) {
                this.$store.dispatch('getAllContentModels');
            }
        }
    }
</script>

<style scoped>

</style>
