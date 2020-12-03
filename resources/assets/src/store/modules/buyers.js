import axios from 'axios';

const state = {
    buyerList: [],
    buyerListMeta: {
        currentPage: 1,
        perPage: 10,
        total: 0
    },
    buyerDetail: [],
};
const getters = {
    buyerListMeta: state => state.buyerListMeta,
    buyerList: state => state.buyerList,
    buyerDetail: state => state.buyerDetail
};

const actions = {
    async buyerList({ commit, state }, payload) {
        let response = await axios.get(`/api/buyers?page=${state.buyerListMeta.currentPage}&perPage=${state.buyerListMeta.perPage}`)
        let results = response.data.data;
        
        let buyerListMeta = {
            total: response.data.total,
            currentPage: response.data.current_page,
            perPage: response.data.per_page,
        };
        commit("setBuyerList", results);
        commit("setBuyerListMeta", buyerListMeta);
    },
    async resetBuyerListPage({ commit, state }, payload){
        commit("setBuyerListMeta", {...state.buyerListMeta, ...{ currentPage: 1 }});
    },
    async buyerDetail({ commit }, payload) {
        let response = await axios.get(`/api/buyers/${payload.id}`)
        let detail = response.data.data
        commit("setBuyerDetail", detail);
    },
    async buyerUpdate({commit}, payload){
        return await axios.put(`/api/buyers/${payload.id}`,payload.data)
    },
    async buyerCreate({commit}, payload){
        return await axios.post(`/api/buyers`,payload.data)
    },
    async buyerDelete({commit}, payload){
        await axios.delete(`/api/buyers/${payload.id}`)
        commit("deleteBuyer",{ id: payload.id })
    }
};

const mutations = {
    setBuyerList(state, payload) {
        state.buyerList = payload
    },
    setBuyerListMeta(state, payload){
        state.buyerListMeta = payload;
    },
    setBuyerDetail(state, payload) {
        state.buyerDetail = payload
    },
    deleteBuyer(state, payload){
        let index = state.buyerList.findIndex(provider => provider.id == payload.id)
        state.buyerList.splice(index, 1)
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
