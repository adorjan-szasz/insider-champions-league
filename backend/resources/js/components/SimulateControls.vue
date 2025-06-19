<template>
    <div class="simulate-controls p-4 space-y-3">
        <h2 class="text-xl font-semibold">Simulation Controls</h2>
        <div class="flex flex-col sm:flex-row gap-2">
            <button
                @click="simulateWeek"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
                Simulate Next Week
            </button>
            <button
                @click="simulateAll"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
            >
                Simulate All Weeks
            </button>
            <button
                @click="resetLeague"
                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
            >
                Reset League
            </button>
        </div>
    </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    name: 'SimulateControls',
    props: {
        leagueId: {
            type: [String, Number],
            required: true
        }
    },
    methods: {
        ...mapActions(['simulateWeek', 'simulateAllWeeks', 'resetLeagueData']),
        simulateWeek() {
            this.simulateWeek({ leagueId: this.leagueId })
        },
        simulateAll() {
            this.simulateAllWeeks({ leagueId: this.leagueId })
        },
        resetLeague() {
            this.resetLeagueData({ leagueId: this.leagueId })
        }
    }
}
</script>

<style scoped>
.simulate-controls {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
}
</style>
