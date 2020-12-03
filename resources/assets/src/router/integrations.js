import Layout1 from "@/layout/Layout1";

export default [
    {
        path: "/",
        component: Layout1,
        children: [
            {
                name: 'integration-list',
                path: "integrations",
                component: () => import("@/components/integrations/IntegrationList")
            },
            {
                name: 'integration-create',
                path: "integrations/create",
                props: true,
                component: () => import("@/components/integrations/IntegrationForm")
            },
            {
                name: 'integration-edit',
                path: "integrations/:id/edit",
                props: true,
                component: () => import("@/components/integrations/IntegrationForm")
            },
            {
                name: 'integration-view',
                path: "integrations/:id",
                props: true,
                component: () => import("@/components/integrations/IntegrationView")
            }
        ]
    }
];
