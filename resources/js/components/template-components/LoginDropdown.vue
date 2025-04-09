<template>
    <li v-if="currentUser" class="nav-item dropdown" :class="{show: isDropDownActive}">
        <a @click.prevent="toggleDropdown" class="nav-link dropdown-toggle" href="#">
            {{ currentUser.name }} <span class="caret"></span>
        </a>






        <div :class="{ show: isOpened }" :style="{ display: this.isOpened ? 'block' : 'none' }"
            class="modal fade">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form>
                        <div class="modal-header">
                            <h5 class="modal-title">Change Password</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <input v-model="currentPassword" type="password" required
                                               class="form-control"
                                               :class="errors && errors.currentPassword ? 'is-invalid' : ''"
                                               placeholder="Current password">
                                        <div v-if="errors && errors.currentPassword" class="invalid-feedback">{{ errors.currentPassword[0] }}</div>
                                    </div>
                                    <div class="form-group">
                                        <input v-model="newPassword" type="password" required
                                               class="form-control"
                                               :class="errors && errors.newPassword ? 'is-invalid' : ''"
                                               placeholder="New password">
                                        <div v-if="errors && errors.newPassword" class="invalid-feedback">{{ errors.newPassword[0] }}</div>
                                    </div>
                                    <div class="form-group">
                                        <input v-model="newPasswordConfirmation" type="password" required
                                               class="form-control"
                                               :class="errors && errors.newPasswordConfirmation ? 'is-invalid' : ''"
                                               placeholder="New password confirmation">
                                        <div v-if="errors && errors.newPasswordConfirmation" class="invalid-feedback">{{ errors.newPasswordConfirmation[0] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input @click.prevent="submitChangePassword()" class="btn btn-warning" type="submit" value="Change password">
                            <button @click="closeModal()" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>




        <div class="dropdown-menu" :class="{show: isDropDownActive}">
            <a href="#" @click.prevent="showPasswordChangeModal" class="dropdown-item">Change password</a>
            <a href="#" @click.prevent="logout()" class="dropdown-item">Logout</a>
        </div>

    </li>
</template>

<script>
    import {setAuthorization} from "../../helpers/general";

    export default {
        name: "LoginDropdown",
        data() {
            return {
                isDropDownActive: false,
                isOpened: false,
                currentPassword: null,
                newPassword: null,
                newPasswordConfirmation: null,

                errors: null
            }
        },
        methods: {
            submitChangePassword() {
                axios.post('/api/auth/changePassword', {
                    currentPassword: this.currentPassword,
                    newPassword: this.newPassword,
                    newPassword_confirmation: this.newPasswordConfirmation,
                })
                    .then((response) => {
                        this.closeModal()
                        this.$store.commit('updateSuccessMessage', "Password has been changed successfully");
                    })
                    .catch((e) => {
                        this.errors = e.response.data.errors
                    })


            },
            resetFieldsAndErrors() {
                this.currentPassword = null
                this.newPassword = null
                this.newPasswordConfirmation = null
                this.errors = null
            },
            showPasswordChangeModal() {
                this.resetFieldsAndErrors()
                this.isOpened = true
                this.isDropDownActive = false
            },
            closeModal() {
                this.resetFieldsAndErrors()
                this.isOpened = false
            },
            toggleDropdown() {
                this.isDropDownActive = !this.isDropDownActive;
            },
            logout() {
                this.$store.commit('logout');
                this.$router.push('/login');
            }
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser
            }
        }
    }
</script>

<style scoped>

</style>
