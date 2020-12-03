import axios from 'axios';

const state = {
    providerListMeta: {
        currentPage: 1,
        perPage: 10,
        total: 0
    },
    providerList: [],
    providerDetail: [],
};
const getters = {
    providerListMeta: state => state.providerListMeta,
    providerList: state => state.providerList,
    providerDetail: state => state.providerDetail
};

const actions = {
    async providerList({ commit, state }, payload) {
        let response = await axios.get(`/api/providers?page=${state.providerListMeta.currentPage}&perPage=${state.providerListMeta.perPage}`)
        let results = response.data.data;
        let providerListMeta = {
            total: response.data.total,
            currentPage: response.data.current_page,
            perPage: response.data.per_page,
        };
        commit("setProviderList", results);
        commit("setProviderListMeta", providerListMeta)
    },
    async resetProviderListPage({ commit, state }, payload){
        commit("setProviderListMeta", {...state.providerListMeta, ...{ currentPage: 1 }});
    },
    async providerDetail({ commit }, payload) {
        let response = await axios.get(`/api/providers/${payload.id}`)
        let providerDetail = response.data.data
        commit("setProviderDetail", providerDetail);
    },
    async providerUpdate({commit}, payload){
        return await axios.put(`/api/providers/${payload.id}`,payload.data)
    },
    async providerCreate({commit}, payload){
        return await axios.post(`/api/providers`,payload.data)
    },
    async providerDelete({commit}, payload){
        await axios.delete(`/api/providers/${payload.id}`)
        commit("deleteProvider",{ id: payload.id })
    }
};

const mutations = {
    setProviderList(state, payload) {
        state.providerList = payload
    },
    setProviderListMeta(state, payload){
        state.providerListMeta = payload;
    },
    setProviderDetail(state, payload) {
        state.providerDetail = payload
    },
    deleteProvider(state, payload){
        let index = state.providerList.findIndex(provider => provider.id == payload.id)
        state.providerList.splice(index, 1)
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
