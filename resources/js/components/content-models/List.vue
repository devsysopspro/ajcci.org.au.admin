<template>
    <div>
        <table class="table table-bordered table-striped dataTable" role="grid" >
            <thead>
                <tr role="row">
                    <table-header-cell :searchParams="searchParams" label="ID" parameter="id" />
                    <table-header-cell :searchParams="searchParams" label="Title" parameter="title" />
                    <table-header-cell :searchParams="searchParams" label="Code" parameter="slug" />
                    <table-header-cell :searchParams="searchParams" label="Published" parameter="published" />
                    <table-header-cell :searchParams="searchParams" label="Date update" parameter="updated_at" />
                    <th style="width: 84px">Action</th>
                </tr>
            </thead>
            <tbody>
                <template v-if="!isLoaded">
                    <tr role="row" class="odd">
                        <td colspan="5" class="text-center"><h2>Loading...</h2></td>
                    </tr>
                </template>
                <template v-else-if="!rows.data || !rows.data.length">
                    <tr role="row" class="odd">
                        <td colspan="5" class="text-center"><h2>No data available</h2></td>
                    </tr>
                </template>
                <template v-else>
                    <tr v-for="model in rows.data" :key="model.id">
                        <td>{{ model.id }}</td>
                        <td>{{ model.title }}</td>
                        <td>{{ model.slug }}</td>
                        <td>{{ model.published ? 'Published' : 'Not published' }}</td>
                        <td>{{ model.created_at }}</td>
                        <td>
                            <router-link :to="{ path: `/model/edit/${model.id}` }" class="btn btn btn-primary" >
                                <i class="fa fa-edit"></i>
                            </router-link>
                            <a @click.prevent="deleteRow(model)" href="#" title="Delete" class="btn btn btn-danger">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                </template>
            </tbody>
            <tfoot>
                <tr>
                    <th style="width: 40px">ID</th>
                    <th>Title</th>
                    <th>Code</th>
                    <th>Published</th>
                    <th>Date create</th>
                    <th style="width: 84px">Action</th>
                </tr>
            </tfoot>
        </table>
        <pagination :currentPage="searchParams.page" :lastPage="rows.last_page"></pagination>
    </div>
</template>

<script>
    import TableHeaderCell from '../template-components/TableHeaderCell'
    import FunctionsMixin from '../../mixins/Listing';

    export default {
        name: 'list',
        components: { TableHeaderCell },
        mixins: [FunctionsMixin],
        data() {
            return {
                base_path: '/model',
                base_path_api: '/api/meta-content/',
                searchParams: {
                    column: this.$route.query.column ? this.$route.query.column : 'created_at',
                    sort: this.$route.query.sort ? this.$route.query.sort : 'desc',
                    search: this.$route.query.search ? this.$route.query.search : '',
                    page: this.$route.query.page ? +this.$route.query.page : 1
                }
            }
        },
        computed: {
            rows() {
                console.log('contentModels', this.$store.getters.contentModels);
                return this.$store.getters.contentModels;
            },
        },
        methods: {
            getRows(query) {
                Object.assign(this.searchParams, query);
                this.$store.dispatch('getContentModels', this.searchParams).then(
                    resolve => {
                        this.isLoaded = true;
                    },
                    reject => {

                    }
                );
            },
        },
        beforeRouteUpdate (to, from, next) {
            this.getRows(to.query);
            next();
        },
        mounted() {
            this.$store.commit('updateTitlePage', 'Models');
            this.getRows({});
        }
    }
</script>

<style scoped>
    table thead th {
        user-select: none;
    }
</style>
