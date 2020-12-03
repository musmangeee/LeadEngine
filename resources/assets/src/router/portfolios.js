import Layout1 from "@/layout/Layout1";

export default [
    {
        path: "/",
        component: Layout1,
        children: [
            {
                name: 'portfolio-list',
                path: "portfolios",
                component: () => import("@/components/portfolios/PortfolioList")
            },
            {
                name: 'portfolio-create',
                path: "portfolios/create",
                props: true,
                component: () => import("@/components/portfolios/PortfolioForm")
            },
            {
                name: 'portfolio-edit',
                path: "portfolios/:id/edit",
                props: true,
                component: () => import("@/components/portfolios/PortfolioForm")
            },
        ]
    }
];
