<template>
    <div className="row">
        <div className="col-12">
            <div v-if="item" class="card">
<!--                <div class="card-header">-->
<!--                    <h3 class="card-title">User</h3>-->
<!--                </div>-->
                <div class="card-body">

                    <div style="margin-bottom: 10px;">
                        <span style="font-style: italic;">ID: {{ item.id + 1000 }}</span>
                    </div>

                    <div style="margin-bottom: 10px;">
                        <span style="font-style: italic;">Status:</span>
                        <span style="font-size: 16px;" class="badge" :class="getClassForStatus()">{{ item.status_text }}</span>
                    </div>

                    <div style="margin-bottom: 10px;">
                        <span style="font-style: italic;">Membership: {{ item.membership_text }} </span>
                    </div>

                    <div>
                        <ul class="profile-detail-list">
                            <li>Business name: <span>{{ item.name }}</span></li>
                            <li>Business address: <span>{{ item.address }}</span></li>
                            <li>Business industry: <span>{{ item.industry }}</span></li>
                            <li>Business Website: <span>{{ item.website }}</span></li>
                            <li>Contact phone number: <span>{{ item.phone }}</span></li>
                            <li>Contact person: <span>{{ item.person }}</span></li>
                            <li>ABN: <span>{{ item.abn_text }}</span></li>
                            <li>Business Email: <span>{{ item.email }}</span></li>
                        </ul>
                    </div>

                    <div style="display: flex;">
                        <div v-if="item.status === 10" style="display: flex;">
                            <a @click.prevent="processMember('approve')" href="#" class="btn btn-success" style="margin-right: 10px;">Approve</a>
                            <a @click.prevent="processMember('decline')" href="#" class="btn btn-danger" style="margin-right: 10px;">Decline</a>
                        </div>

                        <div>
                            <a @click.prevent="deleteMember(item)" href="#" class="btn btn-dark">Delete member</a>
                        </div>
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
    name: "detail",
    data() {
        return {
            item: null
        }
    },
    methods: {
        deleteMember(member) {
            if (!confirm('Delete member?')) {
                return false
            }

            this.item = null
            this.$store.state.titlePage = "Loading..."

            axios.post(`/api/users/${this.$route.params.id}/delete`).then(response => {
                this.$router.push({ path: `/users` })
            }).catch(error => {
                console.log(error)
            });

        },
        processMember(r) {
            this.item = null
            this.$store.state.titlePage = "Loading..."

            axios.post(`/api/users/${this.$route.params.id}/approve`, {
                approve: r === 'approve'
            }).then(response => {
                this.item = response.data
                this.$store.state.titlePage = this.item.name
            }).catch(error => {
                console.log(error)
            });

        },
        getClassForStatus() {
            let result = `badge-light`
            switch (this.item.status) {
                case 20:
                    result = `badge-success`
                    break
                case 10:
                    result = `badge-warning`
                    break
                case 30:
                case 40:
                case 50:
                    result = `badge-danger`
                    break
            }
            return result
        }
    },
    created() {
        this.$store.state.titlePage = "Loading..."

        window.scrollTo({ top: 0, behavior: 'smooth' })

        axios.get(`/api/users/${this.$route.params.id}`).then(response => {
            this.item = response.data
            this.$store.state.titlePage = this.item.name
        }).catch(error => {
            console.log(error)
        });
    }

}
</script>

<style scoped>
    .profile-detail-list {
        list-style-type: none;
        padding: 0;
    }

    .profile-detail-list li {
        font-weight: bold;
    }

    .profile-detail-list span {
        font-weight: normal;
        margin-left: 10px;
    }
</style>
