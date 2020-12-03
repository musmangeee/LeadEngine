const state = {
    integrationList: [],
    integrationDetail: [],
    applicationColumns: [],
};
const getters = {
    integrationList: state => state.integrationList,
    integrationDetail: state => state.integrationDetail,
    applicationColumns: state => state.applicationColumns
};

const actions = {
    async integrationList({ commit, state }, payload) {
        let response = await axios.get(`/api/integrations`)
        console.log(response)
        let results = response.data.data;
        commit("setIntegrationList", results);
    },
    async integrationDetail({ commit }, payload) {
        let response = await axios.get(`/api/integrations/${payload.id}`)
        let integrationDetail = response.data.data
        commit("setIntegrationDetail", integrationDetail);
    },
    async integrationUpdate({commit}, payload){
        return await axios.put(`/api/integrations/${payload.id}`,payload.data)
    },
    async integrationCreate({commit}, payload){
        return await axios.post(`/api/integrations`,payload.data)
    },
    async integrationDelete({commit}, payload){
        await axios.delete(`/api/integrations/${payload.id}`)
        commit("deleteIntegration",{ id: payload.id })
    },
    async getApplicationColumns({commit}, payload){
        let response = await axios.get('/api/integrations/get-application-columns')
        commit('setApplicationColumns', response.data)
    }
};

const mutations = {
    setIntegrationList(state, payload) {
        state.integrationList = payload
    },
    setIntegrationDetail(state, payload) {
        state.integrationDetail = payload
    },
    deleteIntegration(state, payload){
        let index = state.integrationList.findIndex(integration => integration.id == payload.id)
        state.integrationList.splice(index, 1)
    },
    setApplicationColumns(state, payload){
        state.applicationColumns = payload
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
