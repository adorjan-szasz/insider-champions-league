<template>
    <div class="match-detail" v-if="match">
        <h1>Match Detail</h1>
        <p>{{ match.home_team.name }} vs {{ match.away_team.name }}</p>
        <p>Score: {{ match.home_score }} - {{ match.away_score }}</p>
        <p>Week: {{ match.week }}</p>
    </div>
    <div v-else>
        Loading...
    </div>
</template>

<script>

import { mapState, mapActions } from 'vuex'

export default {
    data() {
        return {
            match: null,
        }
    },
    created() {
        this.fetchMatch(this.$route.params.id)
    },
    methods: {
        async fetchMatch(matchId) {
            const res = await this.$axios.get(`/api/matches/${matchId}`)
            this.match = res.data
        },
    },
}
</script>

    <style scoped>
    .match-detail {
        padding: 1rem;
    }

</style>
