import Vue from "vue";
import Vuex from "vuex";

import notifications from "./modules/notifications";
import auth from "./modules/auth";
import buyers from "./modules/buyers";
import buyerChannels from "./modules/buyerChannels"
import leads from "./modules/leads"
import liveLeads from "./modules/liveLeads"
import addressBans from "./modules/addressBans"
import providers from "./modules/providers"
import integrations from "./modules/integrations"
import buyerPandaChannels from "./modules/buyerPandaChannels"
import buyerPlatChannels from "./modules/buyerPlatChannels"
import redirects from "./modules/redirects"
import redirectSettings from "./modules/redirectSettings"
import portfolios from "./modules/portfolios"


Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        auth: auth,
        notifications: notifications,
        redirects: redirects,
        redirectSettings: redirectSettings,
        leads: leads,
        liveLeads: liveLeads,
        providers: providers,
        integrations: integrations,
        portfolios: portfolios,
        addressBans: addressBans,
        buyerPandaChannels: buyerPandaChannels,
        buyerPlatChannels: buyerPlatChannels,
        buyers: buyers,
        buyerChannels: buyerChannels
    }
});
