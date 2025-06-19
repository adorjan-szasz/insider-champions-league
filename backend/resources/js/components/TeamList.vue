<template>
    <div class="team-list p-4 space-y-2">
        <h2 class="text-xl font-semibold">Teams</h2>
        <ul>
            <li
                v-for="team in teams"
                :key="team.id"
                class="border p-2 rounded hover:bg-gray-100"
            >
                {{ team.name }}
            </li>
        </ul>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
    name: 'TeamList',
    props: {
        leagueId: {
            type: [Number, String],
            required: true
        }
    },
    computed: {
        ...mapState(['teams']),
        filteredTeams() {
            return this.teams.filter(team => team.league_id === Number(this.leagueId))
        },
        teams() {
            return this.filteredTeams
        }
    },
    created() {
        this.fetchTeams(this.leagueId)
    },
    methods: {
        ...mapActions(['fetchTeams'])
    }
}
</script>

<style scoped>
.team-list {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
}
</style>
