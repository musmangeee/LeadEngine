import Layout1 from "@/layout/Layout1";

export default [
    {
        path: "/",
        component: Layout1,
        children: [
            {
                name: 'buyer-panda-channel-list',
                path: "buyer-panda-channels",
                component: () => import("@/components/buyerPandaChannels/PandaChannelList")
            },
            {
                name: 'buyer-panda-channel-create',
                path: "buyer-panda-channels/create",
                props: true,
                component: () => import("@/components/buyerPandaChannels/PandaChannelForm")
            },
            {
                name: 'buyer-panda-channel-edit',
                path: "buyer-panda-channels/:id/edit",
                props: true,
                component: () => import("@/components/buyerPandaChannels/PandaChannelForm")
            }
        ]
    }
];
