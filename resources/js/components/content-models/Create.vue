<template>
    <div>
        <form @submit.prevent="saveModel">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Model</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="title-field">Title</label>
                                            <input v-model="fields.title" id="title-field" class="form-control"
                                                   :class="errors && errors.title ? 'is-invalid' : ''">
                                            <div v-if="errors && errors.title" class="invalid-feedback">{{ errors.title[0] }}</div>
                                        </div>
                                        <div class="form-group">
                                            <label for="id-field">Slug</label>
                                            <input v-model="fields.slug" id="id-field" class="form-control"
                                                   :class="errors && errors.slug ? 'is-invalid' : ''">
                                            <div v-if="errors && errors.slug" class="invalid-feedback">{{ errors.slug[0] }}</div>
                                        </div>
                                        <div class="form-group">
                                            <label for="desc-field">Description</label>
                                            <textarea v-model="fields.desc" id="desc-field" class="form-control"
                                                      :class="errors && errors.desc ? 'is-invalid' : ''"
                                                      rows="5">
                                        </textarea>
                                            <div v-if="errors && errors.desc" class="invalid-feedback">{{ errors.desc[0] }}</div>
                                        </div>
                                        <publish-toggle :published.sync="fields.published"></publish-toggle>
                                    </div>
                                    <div class="col-5 offset-1">
                                        <div class="clearfix mb-3">
                                            <add-field-btn></add-field-btn>
                                        </div>
                                        <fields-list></fields-list>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="btn-group pull-right" role="group" aria-label="...">
                        <input class="btn btn-success" name="save" type="submit" value="Save">
                        <router-link to="/model" class="btn btn-default">Close</router-link>
                    </div>
                </div>
                <!-- /.card-footer-->
            </div>
        </form>
        <field-modal></field-modal>
    </div>
</template>

<script>
    import FunctionsMixin from '../../mixins/CreateAndUpdateContentModel';

    export default {
        name: "Create",
        mixins: [FunctionsMixin],
        methods: {
            saveModel() {
                this.fields.contentFields = this.contentFields;

                axios.post('/api/meta-content', this.fields).then(response => {
                    this.$store.commit('updateErrorMessage', []);
                    this.$store.commit('updateSuccessMessage', this.fields.title + " was created");
                    this.$store.commit('resetContentFields');
                    this.errors = [];
                    this.fields = {};
                    this.$router.push(`/model/edit/${response.data.id}`);
                    this.$store.dispatch('getAllContentModels');
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                        this.$store.commit('updateSuccessMessage', "");
                        this.$store.commit('updateErrorMessage', ["Model wasn't created"]);
                    } else {
                        this.errors = [];
                        this.$store.commit('updateSuccessMessage', "");
                        this.$store.commit('updateErrorMessage', ["Error occurred while saving new model"]);
                    }
                });
            }
        },
        created() {
            this.$store.commit('updateTitlePage', 'Create New Model');
        }
    }
</script>

<style lang="scss">

</style>
