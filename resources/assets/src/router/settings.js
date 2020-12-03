import Layout1 from "@/layout/Layout1";

export default [
    {
        path: "/settings/",
        component: Layout1,
        children: [
            {
                name: 'ip-bans',
                path: "ip-bans/",
                component: () => import("@/components/addressBan/AddressBanList")
            },
            {
                name: 'ip-bans-create',
                path: "ip-bans/create",
                component: () => import("@/components/addressBan/AddressBanForm"),
                props: true
            },
            {
                name: 'ip-bans-edit',
                path: "ip-bans/:id/edit",
                component: () => import("@/components/addressBan/AddressBanForm"),
                props: true
            },
            {
                name: 'ip-bans-view',
                path: "ip-bans/:id/view",
                component: () => import("@/components/addressBan/AddressBanView"),
                props: true
            },
            {
                name: 'fake-lead-setting',
                path: "fake-lead",
                component: () => import("@/components/settings/FakeLeadSetting"),
                props: false
            }
        ]
    }
];
