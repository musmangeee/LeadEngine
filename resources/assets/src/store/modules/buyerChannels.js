import axios from 'axios';

const state = {
    buyerChannelList: [],
    buyerChannelBuyerList: [],
    buyerChannelBuyerListMeta: {
        currentPage: 1,
        perPage: 10,
        total: 0
    },
    buyerChannelDetail: [],
};
const getters = {
    buyerChannelList: state => state.buyerChannelList,
    buyerChannelBuyerList: state => state.buyerChannelBuyerList,
    buyerChannelBuyerListMeta: state => state.buyerChannelBuyerListMeta,
    buyerChannelDetail: state => state.buyerChannelDetail
};

const actions = {
    async buyerChannelList({ commit, state }, payload) {
        let response = await axios.get(`/api/buyer-channels`)
        let results = response.data.data;
        commit("setBuyerChannelList", results);
    },
    async buyerChannelBuyerList({ commit, state }, payload) {
        let response = await axios.get(`/api/buyer-channels?buyer_id=${payload.buyer_id}&page=${state.buyerChannelBuyerListMeta.currentPage}&perPage=${state.buyerChannelBuyerListMeta.perPage}`)
        let results = response.data.data;

        let buyerChannelBuyerListMeta = {
            currentPage: response.data.current_page,
            perPage: response.data.per_page,
            total: response.data.total
        }

        commit("setBuyerChannelBuyerList", results);
        commit("setBuyerChannelBuyerListMeta", buyerChannelBuyerListMeta);
    },
    async resetbuyerChannelBuyerListPage({ commit, state }, payload){
        commit("setBuyerChannelBuyerListMeta", {...state.buyerChannelBuyerListMeta, ...{ currentPage: 1 }});
    },
    async buyerChannelDetail({ commit }, payload) {
        let response = await axios.get(`/api/buyer-channels/${payload.id}`)
        let detail = response.data.data
        commit("setBuyerChannelDetail", detail);
    },
    async buyerChannelUpdate({commit}, payload){
        return await axios.put(`/api/buyer-channels/${payload.id}`,payload.data)
    },
    async buyerChannelCreate({commit}, payload){
        return await axios.post(`/api/buyer-channels`,payload.data)
    },
    async buyerChannelDelete({commit}, payload){
        await axios.delete(`/api/buyer-channels/${payload.id}`)
        commit("deleteBuyerChannel",{ id: payload.id })
    },
    async buyerChannelBuyerDelete({commit}, payload){
        await axios.delete(`/api/buyer-channels/${payload.id}`)
        commit("deleteBuyerChannelBuyer",{ id: payload.id })
    }
};

const mutations = {
    setBuyerChannelList(state, payload) {
        state.buyerChannelList = payload
    },
    setBuyerChannelBuyerList(state, payload) {
        state.buyerChannelBuyerList = payload
    },
    setBuyerChannelBuyerListMeta(state, payload) {
        state.buyerChannelBuyerListMeta = payload
    },
    setBuyerChannelDetail(state, payload) {
        state.buyerChannelDetail = payload
    },
    deleteBuyerChannel(state, payload){
        let index = state.buyerChannelList.findIndex(channel => channel.id == payload.id)
        state.buyerChannelList.splice(index, 1)
    },
    deleteBuyerChannelBuyer(state, payload){
        let index = state.buyerChannelBuyerList.findIndex(channel => channel.id == payload.id)
        state.buyerChannelBuyerList.splice(index, 1)
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
