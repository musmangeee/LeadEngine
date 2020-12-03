const state = {
    liveLeadList: [],
    liveLeadMeta: {
        perPage: 10,
        sortBy: 'created_at',
        sortDesc: true,
        total: 0,
        totalSold: 0,
        totalReject: 0,
        totalError: 0
    }
};

const getters = {
    liveLeadList: state => state.liveLeadList,
};

const actions = {
    async liveLeadList({ commit, state }, payload) {
        let queryString = Object.keys(state.liveLeadMeta).map(key => key + '=' + state.liveLeadMeta[key]).join('&');
        queryString += '&live=1' + '&timezone=' + Intl.DateTimeFormat().resolvedOptions().timeZone;
        let response = await axios.get(`/api/leads?${queryString}`)
        let results = {
            liveLeadList: response.data.data,
            liveLeadMeta: {
                total: response.data.total_lead,
                totalSold: response.data.total_sold,
                totalReject: response.data.total_reject,
                totalError: response.data.total_error
            }
        };
        commit("setLiveLeadList", results);
    },
};

const mutations = {
    setLiveLeadList(state, payload) {
        state.liveLeadList = payload.liveLeadList
        state.liveLeadMeta = { ...state.liveLeadMeta, ...payload.liveLeadMeta }
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
