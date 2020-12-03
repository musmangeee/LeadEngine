import Layout1 from "@/layout/Layout1";

export default [
    {
        path: "/",
        component: Layout1,
        children: [
            {
                name: 'lead-list',
                path: "leads/",
                component: () => import("@/components/leads/LeadList")
            },
            {
                name: 'lead-edit',
                path: "leads/:id/edit",
                component: () => import("@/components/leads/LeadEdit"),
                props: true
            },
            {
                name: 'lead-view',
                path: "leads/:id/view",
                component: () => import("@/components/leads/LeadView"),
                props: true

            },
            {
                path: "leads/live-stream",
                component: () => import("@/components/leads/LiveLeadStream")
            },
            {
                path: "leads/failed-list",
                component: () => import("@/components/leads/FailedLead")
            },
            {
                name: 'failed-lead-view',
                path: "leads/:id/failed-view",
                component: () => import("@/components/leads/FailedLeadView"),
                props: true

            }
        ]
    }
];
