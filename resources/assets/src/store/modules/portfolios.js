import axios from 'axios';

const state = {
    portfolioList: [],
    portfolioListMeta: {
        currentPage: 1,
        perPage: 10,
        total: 0
    },
    portfolioDetail: {},
    portfolioDetailRouting: {},
    portfolioRouting: []
};
const getters = {
    portfolioList: state => state.portfolioList,
    portfolioListMeta: state => state.portfolioListMeta,
    portfolioDetail: state => state.portfolioDetail,
    portfolioRouting: state => state.portfolioRouting,
    portfolioDetailRouting: state => state.portfolioDetailRouting
};

const actions = {
    async portfolioList({ commit, state }, payload) {
        
        let response = await axios.get(`/api/portfolios?page=${state.portfolioListMeta.currentPage}&perPage=${state.portfolioListMeta.perPage}`)
        let results = response.data.data;

        let portfolioListMeta = {
            total: response.data.total,
            currentPage: response.data.current_page,
            perPage: response.data.per_page,
        };

        commit("setPortfolioList", results);
        commit("setPortfolioListMeta", portfolioListMeta);
    },

    async resetPortfolioListPage({ commit, state }, payload){
        commit("setPortfolioListMeta", {...state.portfolioListMeta, ...{ currentPage: 1 }});
    },

    async portfolioRouting({ commit, state }, payload) {
        let response = await axios.get(`/api/portfolios/routings`)
        let results = response.data.data;
        commit("setPortfolioRouting", results);
    },

    async portfolioDetail({ commit }, payload) {
        let response = await axios.get(`/api/portfolios/${payload.id}`)
        let portfolioDetail = response.data.data
        commit("setPortfolioDetail", portfolioDetail);
    },
    
    async portfolioDetailRouting({ commit }, payload) {
        let response = await axios.get(`/api/portfolios/${payload.id}/routings`)
        let portfolioDetailRouting = response.data.data
        commit("setPortfolioDetailRouting", portfolioDetailRouting);
    },

    async portfolioUpdate({commit}, payload){
        return await axios.put(`/api/portfolios/${payload.id}`,payload.data)
    },
    async portfolioCreate({commit}, payload){
        return await axios.post(`/api/portfolios`,payload.data)
    },
    async portfolioDelete({commit}, payload){
        await axios.delete(`/api/portfolios/${payload.id}`)
        commit("deletePortfolio",{ id: payload.id })
    }
};

const mutations = {
    setPortfolioList(state, payload) {
        state.portfolioList = payload
    },
    setPortfolioListMeta(state, payload) {
        state.portfolioListMeta = payload
    },
    setPortfolioRouting(state, payload) {
        state.portfolioRouting = payload
    },
    setPortfolioDetail(state, payload) {
        state.portfolioDetail = payload
    },
    setPortfolioDetailRouting(state, payload) {
        state.portfolioDetailRouting = payload
    },
    deletePortfolio(state, payload){
        let index = state.portfolioList.findIndex(portfolio => portfolio.id == payload.id)
        state.portfolioList.splice(index, 1)
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
