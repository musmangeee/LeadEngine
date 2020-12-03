const state = {
    addressBanList: [],
    addressBanDetail: {}
};
const getters = {
    addressBanList: state => state.addressBanList,
    addressBanDetail: state => state.addressBanDetail
};

const actions = {
    async addressBanList({ commit, state }, payload) {
        let response = await axios.get(`/api/address-ban`)
        let results = response.data.data;
        commit("setAddressBanList", results);
    },
    async addressBanDetail({ commit }, payload) {
        let response = await axios.get(`/api/address-ban/${payload.id}`)
        let addressBanDetail = response.data.data
        commit("setAddressBanDetail", addressBanDetail);
    },
    async addressBanUpdate({commit}, payload){
        return await axios.put(`/api/address-ban/${payload.id}`,payload.data)
    },
    async addressBanCreate({commit}, payload){
        return await axios.post(`/api/address-ban`,payload.data)
    },
    async addressBanDelete({commit}, payload){
        await axios.delete(`/api/address-ban/${payload.id}`)
        commit("deleteAddressBan",{ id: payload.id })
    }
};

const mutations = {
    setAddressBanList(state, payload) {
        state.addressBanList = payload
    },
    setAddressBanDetail(state, payload) {
        state.addressBanDetail = payload
    },
    deleteAddressBan(state, payload){
        let index = state.addressBanList.findIndex(addressBan => addressBan.id == payload.id)
        state.addressBanList.splice(index, 1)
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
