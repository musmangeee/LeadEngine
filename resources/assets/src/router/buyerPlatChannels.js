import Layout1 from "@/layout/Layout1";

export default [
    {
        path: "/",
        component: Layout1,
        children: [
            {
                name: 'buyer-plat-channel-list',
                path: "buyer-plat-channels",
                component: () => import("@/components/buyerPlatChannels/PlatChannelList")
            },
            {
                name: 'buyer-plat-channel-create',
                path: "buyer-plat-channels/create",
                props: true,
                component: () => import("@/components/buyerPlatChannels/PlatChannelForm")
            },
            {
                name: 'buyer-plat-channel-edit',
                path: "buyer-plat-channels/:id/edit",
                props: true,
                component: () => import("@/components/buyerPlatChannels/PlatChannelForm")
            }
        ]
    }
];
