import { createStore } from 'vuex';
import axios from 'axios';

const store = createStore({
    state() {
        return {
            leagues: [],
            selectedLeague: null,
            teams: [],
            matches: [],
            currentWeek: 1,
            totalWeeks: 0,
            standings: [],
            weeks: [],
        };
    },
    mutations: {
        SET_LEAGUES(state, leagues) {
            state.leagues = leagues;
        },
        SET_SELECTED_LEAGUE(state, league) {
            state.selectedLeague = league;
        },
        SET_TEAMS(state, teams) {
            state.teams = teams;
        },
        SET_MATCHES(state, matches) {
            state.matches = matches;
        },
        SET_WEEKS(state, weeks) {
            state.weeks = weeks;
        },
        SET_CURRENT_WEEK(state, week) {
            state.currentWeek = week;
        },
        SET_TOTAL_WEEKS(state, total) {
            state.totalWeeks = total;
        },
        SET_STANDINGS(state, standings) {
            state.standings = standings;
        },
    },
    actions: {
        async fetchLeagues({ commit }) {
            const response = await axios.get('/api/leagues');
            commit('SET_LEAGUES', response.data.data);
        },
        async fetchLeagueDetails({ commit }, leagueId) {
            const response = await axios.get(`/api/leagues/${leagueId}`);
            const data = response.data;

            commit('SET_STANDINGS', data.standings);

            commit('SET_SELECTED_LEAGUE', data.league);
            commit('SET_WEEKS', data.weeks || []);
        },
        async fetchMatches({ commit }, leagueId) {
            try {
                const response = await axios.get(`/api/leagues/${leagueId}/weeks/current/matches`);
                commit('SET_MATCHES', response.data);
            } catch (error) {
                console.error('Failed to fetch matches:', error);
            }
        },
        async simulateWeek({ commit, dispatch, getters, state }) {
            const weekId = getters.currentWeekId;
            if (!weekId) {
                throw new Error('Current Week ID not found!');
            }
            await axios.post(`/api/weeks/${weekId}/simulate-week`);
            await dispatch('fetchLeagueDetails', state.selectedLeague.id);
            commit('SET_CURRENT_WEEK', state.currentWeek + 1);
        },
        async simulateAll({ dispatch, state }) {
            await axios.post(`/api/leagues/${state.selectedLeague.id}/simulate`);
            await dispatch('fetchLeagueDetails', state.selectedLeague.id);
        },
        async fetchStandings({ commit }, leagueId) {
            const response = await axios.get(`/api/leagues/${leagueId}`);
            commit('SET_STANDINGS', response.data.data);
        },
    },
    getters: {
        getLeagueById: (state) => (id) => {
            return state.leagues.find((league) => league.id === id);
        },
        filteredMatchesByWeek: (state) => (week) => {
            return state.matches.filter((match) => match.week === week);
        },
        getStandingsByLeague: (state) => (leagueId) => {
            return state.standings.filter(s => s.leagueId === parseInt(leagueId));
        },
        currentWeekId(state) {
            if (!state.selectedLeague) return null;
            const currentWeekObj = state.weeks.find(
                w => w.leagueId === state.selectedLeague.id && w.number === state.currentWeek
            );
            return currentWeekObj ? currentWeekObj.id : null;
        }
    },
});

export default store;
