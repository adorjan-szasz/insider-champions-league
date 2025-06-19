<template>
  <div class="standings-card p-4 space-y-6">
      <pre>{{filteredStandings.some(t => t.champion_chance != null)}}</pre>
      <pre>{{filteredStandings}}</pre>
    <div v-if="filteredStandings.length > 0">
      <h2 class="text-xl font-semibold mb-4">Standings</h2>
      <table class="w-full text-sm border-collapse">
        <thead>
          <tr class="bg-gray-100">
            <th class="text-left px-2 py-1">#</th>
            <th class="text-left px-2 py-1">Team</th>
            <th class="text-right px-2 py-1">P</th>
            <th class="text-right px-2 py-1">W</th>
            <th class="text-right px-2 py-1">D</th>
            <th class="text-right px-2 py-1">L</th>
            <th class="text-right px-2 py-1">GF</th>
            <th class="text-right px-2 py-1">GA</th>
            <th class="text-right px-2 py-1">GD</th>
            <th class="text-right px-2 py-1">Pts</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(team, index) in filteredStandings"
            :key="team.id"
            class="hover:bg-gray-50"
          >
            <td class="px-2 py-1">{{ index + 1 }}</td>
            <td class="px-2 py-1">{{ team.team }}</td>
            <td class="text-right px-2 py-1">{{ team.played }}</td>
            <td class="text-right px-2 py-1">{{ team.won }}</td>
            <td class="text-right px-2 py-1">{{ team.drawn }}</td>
            <td class="text-right px-2 py-1">{{ team.lost }}</td>
            <td class="text-right px-2 py-1">{{ team.goals_for }}</td>
            <td class="text-right px-2 py-1">{{ team.goals_against }}</td>
            <td class="text-right px-2 py-1">{{ team.goal_difference }}</td>
            <td class="text-right px-2 py-1 font-bold">{{ team.points }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else class="text-center text-gray-500">
      No standings available.
    </div>

      <div v-if="filteredStandings.some(t => t.champion_chance != null)">
      <h2 class="text-xl font-semibold mb-4">Predictions</h2>
      <ul class="space-y-1">
        <li
          v-for="(prediction, index) in filteredStandings"
          :key="index"
          class="flex justify-between px-2 py-1 border-b"
        >
          <span>{{ prediction.team }}</span>
          <span>{{ prediction.champion_chance.toFixed(2) }}%</span>
        </li>
      </ul>
    </div>
    <div v-else class="text-center text-gray-500">
      No predictions available.
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  name: 'StandingsCard',
  props: {
    leagueId: {
      type: [String, Number],
      required: true
    }
  },
  computed: {
    ...mapState(['standings']),
    filteredStandings() {
        console.log(this.standings);
      const leagueIdNum = Number(this.leagueId);
      return this.standings.filter(s => s.leagueId === leagueIdNum);
    },
  }
}
</script>

<style scoped>
.standings-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
}
</style>
