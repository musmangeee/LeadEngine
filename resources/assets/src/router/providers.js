import Layout1 from "@/layout/Layout1";

export default [
    {
        path: "/",
        component: Layout1,
        children: [
            {
                name: 'provider-list',
                path: "providers",
                component: () => import("@/components/providers/ProviderList")
            },
            {
                name: 'provider-create',
                path: "providers/create",
                props: true,
                component: () => import("@/components/providers/ProviderForm")
            },
            {
                name: 'provider-edit',
                path: "providers/:id/edit",
                props: true,
                component: () => import("@/components/providers/ProviderForm")
            },
            {
                name: 'provider-view',
                path: "providers/:id",
                props: true,
                component: () => import("@/components/providers/ProviderView")
            },
        ]
    },
    {
        name: 'provider-post-instruction-pdf',
        path: "/providers/:id/post-instruction-pdf",
        props: true,
        component: () => import("@/components/providers/PostInstructionPdf")
    }
];
