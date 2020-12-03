import axios from 'axios';

const state = {
    buyerPlatChannelList: [],
    buyerPlatChannelBuyerList: [],
    buyerPlatChannelBuyerListMeta: {
        currentPage: 1,
        perPage: 10,
        total: 0
    },
    buyerPlatChannelDetail: [],
};
const getters = {
    buyerPlatChannelList: state => state.buyerPlatChannelList,
    buyerPlatChannelBuyerList: state => state.buyerPlatChannelBuyerList,
    buyerPlatChannelBuyerListMeta: state => state.buyerPlatChannelBuyerListMeta,
    buyerPlatChannelDetail: state => state.buyerPlatChannelDetail
};

const actions = {
    async buyerPlatChannelList({ commit, state }, payload) {
        let response = await axios.get(`/api/buyer-plat-channels`)
        let results = response.data.data;
        commit("setBuyerPlatChannelList", results);
    },
    async buyerPlatChannelBuyerList({ commit, state }, payload) {
        let response = await axios.get(`/api/buyer-plat-channels?buyer_id=${payload.buyer_id}&page=${state.buyerPlatChannelBuyerListMeta.currentPage}&perPage=${state.buyerPlatChannelBuyerListMeta.perPage}`)
        let results = response.data.data;
        
        let buyerPlatChannelBuyerListMeta = {
            currentPage: response.data.current_page,
            perPage: response.data.per_page,
            total: response.data.total
        }

        commit("setBuyerPlatChannelBuyerList", results);
        commit("setBuyerPlatChannelBuyerListMeta", buyerPlatChannelBuyerListMeta);
    },

    async resetBuyerPlatChannelBuyerListPage({ commit, state }, payload){
        commit("setBuyerPlatChannelBuyerListMeta", {...state.buyerPlatChannelBuyerListMeta, ...{ currentPage: 1 }});
    },

    async buyerPlatChannelDetail({ commit }, payload) {
        let response = await axios.get(`/api/buyer-plat-channels/${payload.id}`)
        let buyerPlatChannelDetail = response.data.data
        commit("setBuyerPlatChannelDetail", buyerPlatChannelDetail);
    },
    async buyerPlatChannelUpdate({commit}, payload){
        return await axios.put(`/api/buyer-plat-channels/${payload.id}`,payload.data)
    },
    async buyerPlatChannelCreate({commit}, payload){
        return await axios.post(`/api/buyer-plat-channels`,payload.data)
    },
    async buyerPlatChannelDelete({commit}, payload){
        await axios.delete(`/api/buyer-plat-channels/${payload.id}`)
        commit("deleteBuyerPlatChannel",{ id: payload.id })
    },
    async buyerPlatChannelBuyerDelete({commit}, payload){
        await axios.delete(`/api/buyer-plat-channels/${payload.id}`)
        commit("deleteBuyerPlatChannelBuyer",{ id: payload.id })
    }
};

const mutations = {
    setBuyerPlatChannelList(state, payload) {
        state.buyerPlatChannelList = payload
    },
    setBuyerPlatChannelBuyerList(state, payload) {
        state.buyerPlatChannelBuyerList = payload
    },
    setBuyerPlatChannelBuyerListMeta(state, payload) {
        state.buyerPlatChannelBuyerListMeta = payload
    },
    setBuyerPlatChannelDetail(state, payload) {
        state.buyerPlatChannelDetail = payload
    },
    deleteBuyerPlatChannel(state, payload){
        let index = state.buyerPlatChannelList.findIndex(channel => channel.id == payload.id)
        state.buyerPlatChannelList.splice(index, 1)
    },
    deleteBuyerPlatChannelBuyer(state, payload){
        let index = state.buyerPlatChannelBuyerList.findIndex(channel => channel.id == payload.id)
        state.buyerPlatChannelBuyerList.splice(index, 1)
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
