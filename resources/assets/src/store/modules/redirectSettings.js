const state = {
    redirectSettingList: [],
    redirectSettingDetail: {}
};
const getters = {
    redirectSettingList: state => state.redirectSettingList,
    redirectSettingDetail: state => state.redirectSettingDetail
};

const actions = {
    async redirectSettingList({ commit, state }, payload) {
        let response = await axios.get(`/api/redirect-settings`)
        let results = response.data.data;
        commit("setRedirectSettingList", results);
    },
    async redirectSettingDetail({ commit }, payload) {
        let response = await axios.get(`/api/redirect-settings/${payload.id}`)
        let redirectSettingDetail = response.data.data
        commit("setRedirectSettingDetail", redirectSettingDetail);
    },
    async redirectSettingUpdate({commit}, payload){
        return await axios.put(`/api/redirect-settings/${payload.id}`,payload.data)
    },
    async redirectSettingCreate({commit}, payload){
        return await axios.post(`/api/redirect-settings`,payload.data)
    },
    async redirectSettingDelete({commit}, payload){
        await axios.delete(`/api/redirect-settings/${payload.id}`)
        commit("deleteRedirectSetting",{ id: payload.id })
    }
};

const mutations = {
    setRedirectSettingList(state, payload) {
        state.redirectSettingList = payload
    },
    setRedirectSettingDetail(state, payload) {
        state.redirectSettingDetail = payload
    },
    deleteRedirectSetting(state, payload){
        let index = state.redirectSettingList.findIndex(redirectSetting => redirectSetting.id == payload.id)
        state.redirectSettingList.splice(index, 1)
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
