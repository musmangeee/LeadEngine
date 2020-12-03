import Vue from "vue";
import bugsnag from "@bugsnag/js";
import bugsnagVue from "@bugsnag/plugin-vue";

const bugsnagClient = bugsnag({
    apiKey: "08147762a6cb22540611e5260759b821",
    notifyReleaseStages: ["production", "staging"],
    releaseStage: process.env.NODE_ENV
}).use(bugsnagVue, Vue);

export default bugsnagClient;
