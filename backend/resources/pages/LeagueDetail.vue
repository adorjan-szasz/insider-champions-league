<template>
    <div class="league-detail" v-if="selectedLeague && selectedLeague.id">
        <h1>{{ selectedLeague.name }}</h1>

        <StandingsCard :leagueId="selectedLeague.id"/>

        <SimulateControls :leagueId="selectedLeague.id" />

        <MatchList :leagueId="selectedLeague.id" />
    </div>
    <div v-else>
        Loading...
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import StandingsCard from '../js/components/StandingsCard.vue'
import SimulateControls from '../js/components/SimulateControls.vue'
import MatchList from '../js/components/MatchList.vue'

export default {
    components: {
        StandingsCard,
        SimulateControls,
        MatchList,
    },
    props: {
        id: {
            type: [String, Number],
            required: true,
        },
    },
    computed: {
        ...mapState(['selectedLeague']),
    },
    watch: {
        id(newId) {
            this.fetchLeagueDetails(newId);
        }
    },
    created() {
        this.$store.dispatch('fetchLeagueDetails', this.id);
    },
    methods: {
        ...mapActions(['fetchLeagueDetails']),
    },
}
</script>

<style scoped>
.league-detail {
    padding: 1rem;
}
</style>
