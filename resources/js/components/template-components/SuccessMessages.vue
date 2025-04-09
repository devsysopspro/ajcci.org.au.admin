<template>
    <div v-if="successMessage" class="alert alert-fixed alert-success alert-dismissible">
        <button @click.prevent="deleteMessage" type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fa fa-check"></i> Success!</h5>
        {{ successMessage }}
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'SuccessMessages',
    computed: {
        ...mapGetters(['successMessage'])
    },
    watch: {
        successMessage: function(updated) {
            if (updated) {
                setTimeout(_ => {
                    this.deleteMessage();
                }, 1400);
            }
        }
    },
    methods: {
        deleteMessage() {
            this.$store.commit('updateSuccessMessage', '');
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
