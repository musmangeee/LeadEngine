import Layout1 from "@/layout/Layout1";

export default [
    {
        path: "/",
        component: Layout1,
        children: [
            {
                name: 'buyer-vertical-channel-list',
                path: "buyer-vertical-channels",
                component: () => import("@/components/buyerChannels/BuyerChannelList")
            },
            {
                name: 'buyer-vertical-channel-create',
                path: "buyer-vertical-channels/create",
                props: true,
                component: () => import("@/components/buyerChannels/BuyerChannelForm")
            },
            {
                name: 'buyer-vertical-channel-edit',
                path: "buyer-vertical-channels/:id/buyer",
                props: true,
                component: () => import("@/components/buyerChannels/BuyerChannelForm")
            }
        ]
    }
];
