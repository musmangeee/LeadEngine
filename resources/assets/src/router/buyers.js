import Layout1 from "@/layout/Layout1";

export default [
    {
        path: "/",
        component: Layout1,
        children: [
            {
                name: 'buyer-list',
                path: "buyers",
                component: () => import("@/components/buyers/BuyerList")
            },
            {
                name: 'buyer-create',
                path: "buyers/create",
                props: true,
                component: () => import("@/components/buyers/BuyerForm")
            },
            {
                name: 'buyer-edit',
                path: "buyers/:id/edit",
                props: true,
                component: () => import("@/components/buyers/BuyerForm")
            }
        ]
    }
];
