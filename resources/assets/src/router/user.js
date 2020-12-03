import Layout1 from "@/layout/Layout1";

export default [
    {
        path: "/user/",
        component: Layout1,
        children: [
            {
                name: "user-list",
                path: "list",
                component: () => import("@/components/user/UsersList")
            },
            {
                name: "user-create",
                path: "create",
                component: () => import("@/components/user/UsersAdd")
            },
            {
                name: "user-edit",
                path: "edit/:id",
                props: true,
                component: () => import("@/components/user/UsersEdit")
            }
        ]
    }
];
