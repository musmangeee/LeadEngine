import Layout1 from "@/layout/Layout1";

export default [
    {
        path: "/reports",
        component: Layout1,
        children: [
            {
                path: "daily-lead-report",
                component: () => import("@/components/reports/DailyLeadReport")
            },
            {
                path: "daily-count-by-provider",
                component: () => import("@/components/reports/DailyCountByProvider")
            },
            {
                path: "daily-count-by-portfolio",
                component: () => import("@/components/reports/DailyCountByPortfolio")
            },
            {
                path: "count-by-buyer",
                component: () => import("@/components/reports/CountByBuyer")
            }
        ]
    }
];
