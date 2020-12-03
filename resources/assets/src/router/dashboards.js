import Layout1 from "@/layout/Layout1";

export default [
    {
        path: "/",
        component: Layout1,
        children: [
            {
                path: "dashboard",
                component: () => import("@/components/dashboards/Dashboard1")
            }
        ]
    }
];
