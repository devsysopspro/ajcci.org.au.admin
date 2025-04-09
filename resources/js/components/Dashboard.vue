<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Models and Contents</h3>
        </div>
        <div class="card-body">
            <div v-if="allContentModels.length == 0">
                There is no model yet. Create new <router-link :to="`/model/create`">one</router-link>.
            </div>
            <ul class="list-group">
                <li v-for="model in allContentModels" class="list-group-item">
                    {{ model.title }}
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="badge">{{ model.contents.length }}</span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <router-link :to="`/entry/${model.id}`">open</router-link>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'dashboard',
    computed: {
        ...mapGetters([
            'allContentModels',
            'isLoggedIn'
        ]),
    },
    created() {
        this.$store.commit('updateTitlePage', 'Dashboard');
        if(this.isLoggedIn === true) {
            this.$store.dispatch('getAllContentModels');
        }
    }
}
</script>
