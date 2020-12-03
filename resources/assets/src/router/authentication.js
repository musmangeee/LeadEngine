import LayoutBlank from "@/layout/LayoutBlank";

export default [
    {
        // Blank layout
        path: "/",
        component: LayoutBlank,
        children: [
            {
                path: "password-reset",
                component: () =>
                    import(
                        "@/components/authentication/AuthenticationPasswordReset"
                    )
            },
            {
                path: "password-change",
                component: () =>
                    import("@/components/authentication/ChangePassword")
            }
        ]
    }
];
