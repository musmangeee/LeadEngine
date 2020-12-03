import axios from 'axios';

const state = {
    buyerPandaChannelList: [],
    buyerPandaChannelBuyerList: [],
    buyerPandaChannelBuyerListMeta: {
        currentPage: 1,
        perPage: 10,
        total: 0
    },
    buyerPandaChannelDetail: [],
};
const getters = {
    buyerPandaChannelList: state => state.buyerPandaChannelList,
    buyerPandaChannelBuyerList: state => state.buyerPandaChannelBuyerList,
    buyerPandaChannelBuyerListMeta: state => state.buyerPandaChannelBuyerListMeta,
    buyerPandaChannelDetail: state => state.buyerPandaChannelDetail
};

const actions = {
    async buyerPandaChannelList({ commit, state }, payload) {
        let response = await axios.get(`/api/buyer-panda-channels`)
        let results = response.data.data;
        commit("setBuyerPandaChannelList", results);
    },
    async buyerPandaChannelBuyerList({ commit, state }, payload) {
        let response = await axios.get(`/api/buyer-panda-channels?buyer_id=${payload.buyer_id}&page=${state.buyerPandaChannelBuyerListMeta.currentPage}&perPage=${state.buyerPandaChannelBuyerListMeta.perPage}`)
        let results = response.data.data;

        let buyerPandaChannelBuyerListMeta = {
            currentPage: response.data.current_page,
            perPage: response.data.per_page,
            total: response.data.total
        }

        commit("setBuyerPandaChannelBuyerList", results);
        commit("setBuyerPandaChannelBuyerListMeta", buyerPandaChannelBuyerListMeta);
    },
    async resetBuyerPandaChannelBuyerListPage({ commit, state }, payload){
        commit("setBuyerPandaChannelBuyerListMeta", {...state.buyerPandaChannelBuyerListMeta, ...{ currentPage: 1 }});
    },
    async buyerPandaChannelDetail({ commit }, payload) {
        let response = await axios.get(`/api/buyer-panda-channels/${payload.id}`)
        let buyerPandaChannelDetail = response.data.data
        commit("setBuyerPandaChannelDetail", buyerPandaChannelDetail);
    },
    async buyerPandaChannelUpdate({commit}, payload){
        return await axios.put(`/api/buyer-panda-channels/${payload.id}`,payload.data)
    },
    async buyerPandaChannelCreate({commit}, payload){
        return await axios.post(`/api/buyer-panda-channels`,payload.data)
    },
    async buyerPandaChannelDelete({commit}, payload){
        await axios.delete(`/api/buyer-panda-channels/${payload.id}`)
        commit("deleteBuyerPandaChannel",{ id: payload.id })
    },
    async buyerPandaChannelBuyerDelete({commit}, payload){
        await axios.delete(`/api/buyer-panda-channels/${payload.id}`)
        commit("deleteBuyerPandaChannelBuyer",{ id: payload.id })
    }
};

const mutations = {
    setBuyerPandaChannelList(state, payload) {
        state.buyerPandaChannelList = payload
    },
    setBuyerPandaChannelBuyerList(state, payload) {
        state.buyerPandaChannelBuyerList = payload
    },
    setBuyerPandaChannelBuyerListMeta(state, payload) {
        state.buyerPandaChannelBuyerListMeta = payload
    },
    setBuyerPandaChannelDetail(state, payload) {
        state.buyerPandaChannelDetail = payload
    },
    deleteBuyerPandaChannel(state, payload){
        let index = state.buyerPandaChannelList.findIndex(channel => channel.id == payload.id)
        state.buyerPandaChannelList.splice(index, 1)
    },
    deleteBuyerPandaChannelBuyer(state, payload){
        let index = state.buyerPandaChannelBuyerList.findIndex(channel => channel.id == payload.id)
        state.buyerPandaChannelBuyerList.splice(index, 1)
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
