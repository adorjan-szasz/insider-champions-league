<template>
    <div class="league-table">
        <h2 class="text-xl font-bold mb-4">League Table</h2>

        <table class="table-auto w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Team</th>
                    <th class="px-4 py-2 text-center">W</th>
                    <th class="px-4 py-2 text-center">D</th>
                    <th class="px-4 py-2 text-center">L</th>
                    <th class="px-4 py-2 text-center">GF</th>
                    <th class="px-4 py-2 text-center">GA</th>
                    <th class="px-4 py-2 text-center">Pts</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(team, index) in sortedStandings" :key="team.id" class="border-t">
                    <td class="px-4 py-2">{{ index + 1 }}</td>
                    <td class="px-4 py-2">{{ team.name }}</td>
                    <td class="px-4 py-2 text-center">{{ team.wins }}</td>
                    <td class="px-4 py-2 text-center">{{ team.draws }}</td>
                    <td class="px-4 py-2 text-center">{{ team.losses }}</td>
                    <td class="px-4 py-2 text-center">{{ team.goalsFor }}</td>
                    <td class="px-4 py-2 text-center">{{ team.goalsAgainst }}</td>
                    <td class="px-4 py-2 text-center">{{ team.points }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'LeagueTable',
    computed: {
        ...mapState(['standings']),
        sortedStandings() {
            return [...(this.standings || [])]
                .sort((a, b) => {
                    if (b.points !== a.points) {
                        return b.points - a.points;
                    }

                    const goalDiffA = (a.goalsFor ?? 0) - (a.goalsAgainst ?? 0);
                    const goalDiffB = (b.goalsFor ?? 0) - (b.goalsAgainst ?? 0);

                    return goalDiffB - goalDiffA;
                });
        }
    },
};
</script>

<style scoped>
.league-table table {
    border-collapse: collapse;
}
.league-table th,
.league-table td {
    border: 1px solid #ccc;
}
</style>
