import Layout1 from "@/layout/Layout1";

export default [
    {
        path: "/profile/",
        component: Layout1,
        children: [
            {
                name: "profile",
                path: "",
                component: () => import("@/components/Profile")
            }
        ]
    }
];
