<template>
    <div class="match-list p-4 space-y-2">
        <h2 class="text-xl font-semibold">Matches</h2>
        <ul>
            <li
                v-for="match in filteredMatches"
                :key="match.id"
                class="border p-2 rounded hover:bg-gray-100 cursor-pointer"
                @click="goToMatch(match.id)"
            >
                <div class="flex justify-between">
                    <span>{{ match.home_team.name }} vs {{ match.away_team.name }}</span>
                    <span>
            {{ match.home_score }} - {{ match.away_score }}
          </span>
                </div>
                <div class="text-sm text-gray-500">
                    Week {{ match.week }} | {{ formatDate(match.date) }}
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { useRouter } from 'vue-router'

export default {
    name: 'MatchList',
    props: {
        leagueId: {
            type: [String, Number],
            required: true
        }
    },
    computed: {
        ...mapState(['matches']),
        filteredMatches() {
            return this.matches.filter(match => match.league_id === Number(this.leagueId))
        }
    },
    methods: {
        ...mapActions(['fetchMatches']),
        goToMatch(matchId) {
            this.$router.push({ name: 'MatchDetail', params: { id: matchId } });
        },
        formatDate(dateStr) {
            return new Date(dateStr).toLocaleDateString();
        }
    },
    created() {
        this.fetchMatches(this.leagueId);
    },
    watch: {
        leagueId(newId) {
            this.fetchMatches(newId);
        }
    }
}
</script>

<style scoped>
.match-list {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
}
</style>
