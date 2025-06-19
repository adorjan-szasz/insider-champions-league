<template>
    <div class="league-list p-4 space-y-2">
        <ul>
            <li
                v-for="league in leagues"
                :key="league.id"
                class="border p-2 rounded hover:bg-gray-100 cursor-pointer"
                @click="goToLeague(league.id)"
            >
                {{ league.name }}
            </li>
        </ul>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';

export default {
    name: 'LeagueList',
    computed: {
        ...mapState(['leagues']),
    },
    created() {
        this.fetchLeagues();
    },
    methods: {
        ...mapActions(['fetchLeagues']),
        goToLeague(leagueId) {
            this.$router.push({ name: 'LeagueDetail', params: { id: leagueId } });
        },
    },
};
</script>

<style scoped>
.league-list {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
}
</style>
