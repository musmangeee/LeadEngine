const state = {
    leadList: [],
    leadListMeta: {
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
    failedLeadList: [],
    failedLeadListMeta: {
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
    postList: [],
    postListMeta: {
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
    leadListStat: {},
    leadDetail: {},
    postDetail: {},
    failedLeadDetail: {}
};
const getters = {
    leadListMeta: state => state.leadListMeta,
    postListMeta: state => state.leadListMeta,
    failedLeadListMeta: state => state.failedLeadListMeta,
    leadList: state => state.leadList,
    postList: state => state.leadList,
    failedLeadList: state => state.failedLeadList,
    leadListStat: state => state.leadListStat,
    leadDetail: state => state.leadDetail,
    postDetail: state => state.leadDetail,
    failedLeadDetail: state => state.failedLeadDetail
};

const actions = {
    async leadListStat({ commit, state }, payload) {
        let queryString = Object.keys(payload).map(key => key + '=' + payload[key]).join('&');
        let response = await axios.get(`/api/leads/stat?${queryString}`)
        commit("setLeadListStat", response.data.data);
    },
    async leadList({ commit, state }, payload) {
        let queryString = Object.keys(state.leadListMeta).map(key => key + '=' + state.leadListMeta[key]).join('&');

        let response = await axios.get(`/api/leads?${queryString}`)

        let results = {
            leadList: response.data.data,
            leadListMeta: {
                totalItem: response.data.total,
                lastPage: response.data.last_page,
                total: response.data.total,
                totalError: response.data.total_error,
                totalReject: response.data.total_reject,
                totalSold: response.data.total_sold,
            }
        }
        commit("setLeadList", results);
    },
    async postList({ commit, state }, payload) {
        let queryString = Object.keys(state.postListMeta).map(key => key + '=' + state.postListMeta[key]).join('&');

        let response = await axios.get(`/api/leads/status?${queryString}`)

        let results = {
            postList: response.data.data,
            postListMeta: {
                totalItem: response.data.total,
                lastPage: response.data.last_page,
                total: response.data.total,
                totalError: response.data.total_error,
                totalReject: response.data.total_reject,
                totalSold: response.data.total_sold,
            }
        }
        commit("setPostList", results);
    },
    async failedLeadList({ commit, state }, payload) {
        let queryString = Object.keys(state.failedLeadListMeta).map(key => key + '=' + state.failedLeadListMeta[key]).join('&');

        let response = await axios.get(`/api/leads/failed-applications?${queryString}`)

        let results = {
            failedLeadList: response.data.data,
            failedLeadListMeta: {
                totalItem: response.data.total,
                lastPage: response.data.last_page,
                total: response.data.total,
            }
        }
        commit("setFailedLeadList", results);
    },
    async leadDetail({ commit }, payload) {
        let response = await axios.get(`/api/leads/${payload.id}`)
        let leadDetail = response.data.data
        commit("setLeadDetail", leadDetail);
    },
    async postDetail({ commit }, payload) {
        let response = await axios.get(`/api/leads/status/${payload.id}`)
        let postDetail = response.data.data
        commit("setPostDetail", postDetail);
    },
    async failedLeadDetail({ commit }, payload) {
        let response = await axios.get(`/api/leads/failed/${payload.id}`)
        let failedLeadDetail = response.data.data
        commit("setFailedLeadDetail", failedLeadDetail);
    },
    async leadUpdate({commit}, payload){
        return await axios.put(`/api/leads/${payload.id}`,payload.data)
    },
    async leadDelete({commit}, payload){
        await axios.delete(`/api/leads/${payload.id}`)
        commit("deleteLead",{ id: payload.id })
    }
};

const mutations = {
    setLeadListStat(state, payload) {
        if(payload){
            state.leadListStat = payload
        }else{
            state.leadListStat = {
                'all_redirected' : 0,
                'success_redirected' : 0,
                'avg_time_redirected' : 0,
                'total_time_redirected' : 0,
                'avg_time_redirected' : 0,
                'avg_redirected_minute' : 0,
                'avg_redirected_hour' : 0,
                'not_redirected' : 0,
                'error_redirected' : 0,
                'all_range' : 0,
                'accepted_range' : 0,
                'rejected_range' : 0,
                'error_range' : 0,
                'total' : 0,
                'total_sold' : 0,
                'total_reject' : 0,
                'total_error' : 0
            }
        }

    },
    setLeadList(state, payload) {
        state.leadList = payload.leadList
        state.leadListMeta = { ...state.leadListMeta, ...payload.leadListMeta }
    },
    setPostList(state, payload) {
        state.postList = payload.postList
        state.postListMeta = { ...state.postListMeta, ...payload.postListMeta }
    },
    setFailedLeadList(state, payload) {
        state.failedLeadList = payload.failedLeadList
        state.failedLeadListMeta = { ...state.failedLeadListMeta, ...payload.failedLeadListMeta }
    },
    setLeadDetail(state, payload) {
        state.leadDetail = payload
    },
    setPostDetail(state, payload) {
        state.postDetail = payload
    },
    setFailedLeadDetail(state, payload) {
        state.failedLeadDetail = payload
    },
    deleteLead(state, payload){
        let index = state.leadList.findIndex(lead => lead.id == payload.id)
        state.leadList.splice(index, 1)
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
