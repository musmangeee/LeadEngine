import Layout1 from "@/layout/Layout1";

export default [
    {
        path: "/",
        component: Layout1,
        children: [
            {
                name: 'redirect-settings',
                path: "redirect-settings/",
                component: () => import("@/components/redirectSettings/RedirectSettingList")
            },
            {
                name: 'redirect-settings-create',
                path: "redirect-settings/create",
                props: true,
                component: () => import("@/components/redirectSettings/RedirectSettingForm")
            },
            {
                name: 'redirect-settings-edit',
                path: "redirect-settings/:id/edit",
                props: true,
                component: () => import("@/components/redirectSettings/RedirectSettingForm")
            },
        ]
    }
];
