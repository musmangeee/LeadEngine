const state = {
    redirectList: [],
    redirectListMeta: {
        totalItem: 0,
        lastPage: 0,
        page: 1,
        perPage: 10,
        sortBy: 'created_at',
        sortDesc: true,
        filter: '',
        status: '',
        startDate: '',
        endDate: ''
    },
    redirectDetail: {},
    redirectWidgetData: {
        totalApplication: 0,
        totalRedirected: 0,
        totalNotRedirected: 0
    }
};
const getters = {
    redirectListMeta: state => state.redirectListMeta,
    redirectList: state => state.redirectList,
    redirectDetail: state => state.redirectDetail,
    redirectWidgetData: state => state.redirectWidgetData
};

const actions = {
    async redirectList({ commit, state }, payload) {
        let queryString = Object.keys(state.redirectListMeta).map(key => key + '=' + state.redirectListMeta[key]).join('&');
        let responseWidgetData = await axios.get(`/api/redirects/widget-data`)
        let response = await axios.get(`/api/redirects?${queryString}`)
        let results = {
            redirectList: response.data.data,
            redirectListMeta: {
                totalItem: response.data.total,
                lastPage: response.data.last_page
            },
            redirectWidgetData: responseWidgetData.data
        }
        commit("setRedirectList", results);
    },
    async redirectDetail({ commit }, payload) {
        let response = await axios.get(`/api/redirects/${payload.id}`)
        let redirectDetail = response.data.data
        commit("setRedirectDetail", redirectDetail);
    },
    async redirectUpdate({commit}, payload){
        return await axios.put(`/api/redirects/${payload.id}`,payload.data)
    },
    async redirectDelete({commit}, payload){
        await axios.delete(`/api/redirects/${payload.id}`)
        commit("deleteRedirect",{ id: payload.id })
    },
};

const mutations = {
    setRedirectList(state, payload) {
        state.redirectList = payload.redirectList
        state.redirectWidgetData = payload.redirectWidgetData
        state.redirectListMeta = { ...state.redirectListMeta, ...payload.redirectListMeta }
    },
    setRedirectDetail(state, payload) {
        state.redirectDetail = payload
    },
    deleteRedirect(state, payload){
        let index = state.redirectList.findIndex(redirect => redirect.id == payload.id)
        state.redirectList.splice(index, 1)
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
