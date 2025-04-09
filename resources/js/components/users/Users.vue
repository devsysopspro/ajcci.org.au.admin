<template>
    <div className="row">
        <div className="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List Users</h3>
                </div>
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4">

                        <table class="table table-bordered table-striped dataTable" role="grid" >
                            <thead>
                            <tr role="row">
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Membership</th>
                                <th>Created</th>
                                <th>Status</th>
                                <th style="width: 84px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-if="isLoadingFlag">
                                <tr role="row" class="odd">
                                    <td colspan="5" class="text-center"><h2>Loading...</h2></td>
                                </tr>
                            </template>
                            <template v-else-if="!items.length">
                                <tr role="row" class="odd">
                                    <td colspan="5" class="text-center"><h2>No data available</h2></td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr v-for="item in items" :key="item.id">
                                    <td>{{ item.id + 1000 }}</td>
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.email }}</td>
                                    <td>{{ `$${item.membership}` }}</td>
                                    <td>{{ item.created_at }}</td>
                                    <td>{{ item.status_text }}</td>
                                    <td>
                                        <router-link :to="{ path: `/users/show/${item.id}` }" class="btn btn btn-primary" >
                                            <i class="fa fa-eye"></i>
                                        </router-link>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>

        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</template>

<script>

export default {
    name: "users",
    data() {
        return {
            items: [],

            isLoadingFlag: true
        }
    },
    methods: {

    },
    created() {
        this.$store.state.titlePage = "Users"

        axios.get(`/api/users`).then(response => {
            this.items = response.data
            this.isLoadingFlag = false
        }).catch(error => {
            console.log(error)
        });
    }

}
</script>

<style scoped>

</style>
