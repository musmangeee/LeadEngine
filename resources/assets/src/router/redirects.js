import Layout1 from "@/layout/Layout1";

export default [
    {
        path: "/",
        component: Layout1,
        children: [
            {
                name: 'redirect-list',
                path: "redirects/",
                component: () => import("@/components/redirects/RedirectList")
            },
        ]
    }
];
