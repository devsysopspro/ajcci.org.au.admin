<template>
    <div v-if="errorMessage.length" class="alert alert-fixed alert-danger alert-dismissible">
        <button @click.prevent="deleteMessages" type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fa fa-ban"></i> Error!</h5>
        <ul>
            <li v-for="error in errorMessage">{{ error }}</li>
        </ul>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: "ErrorMessages",
    computed: {
        ...mapGetters(['errorMessage'])
    },
    watch: {
        errorMessage: function(updated) {
            if (updated.length > 0) {
                setTimeout(_ => {
                    this.deleteMessage();
                }, 1400);
            }
        }
    },
    methods: {
        deleteMessages() {
            this.$store.commit('updateErrorMessage', []);
        }
    },
    created() {
        if (this.errorMessage.length > 0) {
            setTimeout(this.deleteMessage, 1000);
        }
    }
}
</script>

<style scoped>
.alert.alert-fixed {
    position: fixed;
    z-index: 7000;
    right: 20px;
    top: 40px;
}
</style>
