<template>
    <div>
        <h1>Leagues</h1>

        <div>
            <h2>Click on a league to proceed further</h2>
        </div>

        <LeagueList :leagues="leagues"/>
    </div>
</template>

<script>

import LeagueList from '../js/components/LeagueList.vue';
import { mapState } from 'vuex';

export default {
    components: {
        LeagueList,
    },
    computed: {
        ...mapState(['leagues']),
    },
    created() {
        this.$store.dispatch('fetchLeagues');
    },
};

</script>
