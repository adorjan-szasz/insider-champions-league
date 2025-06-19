<template>
    <div class="week-list p-4 space-y-2">
        <h2 class="text-xl font-semibold">Weeks</h2>
        <ul class="flex flex-wrap gap-2">
            <li
                v-for="week in weeksInLeague"
                :key="week"
                @click="selectWeek(week)"
                class="px-3 py-1 border rounded cursor-pointer hover:bg-gray-100"
                :class="{ 'bg-blue-500 text-white': selectedWeek === week }"
            >
                Week {{ week }}
            </li>
        </ul>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
    name: 'WeekList',
    props: {
        leagueId: {
            type: [String, Number],
            required: true
        }
    },
    computed: {
        ...mapState(['matches', 'selectedWeek']),
        weeksInLeague() {
            const weeksSet = new Set(
                this.matches
                    .filter(m => m.league_id === Number(this.leagueId))
                    .map(m => m.week)
            )
            return Array.from(weeksSet).sort((a, b) => a - b)
        }
    },
    created() {
        this.fetchMatches(this.leagueId)
    },
    methods: {
        ...mapActions(['fetchMatches', 'setSelectedWeek']),
        selectWeek(week) {
            this.setSelectedWeek(week)
        }
    }
}
</script>

<style scoped>
.week-list {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
}
</style>
