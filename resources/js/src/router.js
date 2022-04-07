import Vue from "vue";
import Router from "vue-router";

Vue.use(Router);

const router = new Router({
    mode: "history",
    base: "/",
    meta: {
        rule: "editor"
    },
    scrollBehavior() {
        return { x: 0, y: 0 };
    },
    routes: [
        {
            // =============================================================================
            // MAIN LAYOUT ROUTES
            // =============================================================================
            path: "",
            component: () => import("./layouts/main/Main.vue"),
            children: [
                // =============================================================================
                // Theme Routes
                // =============================================================================
                {
                    path: "/",
                    name: "home",
                    redirect: "/dashboard",
                    meta: {
                        // permission: ['dashboard'],
                        breadcrumb: [{ title: "Dashboard", active: true }],
                        rule: "editor"
                    }
                },
                {
                    path: "/dashboard",
                    name: "dashboard",
                    component: () => import("./views/DashboardAnalytics.vue"),
                    meta: {
                        // permission: ['dashboard'],
                        breadcrumb: [{ title: "Dashboard", active: true }],
                        rule: "editor"
                    }
                },
                {
                    path: "notifications",
                    name: "dashboard-notifications",
                    component: () =>
                        import("./views/DashboardNotifications.vue"),
                    meta: {
                        permission: ['dashboard_notifications'],
                        breadcrumb: [
                            { title: "Home", url: "/" },
                            {
                                title: "یادآوری ها",
                                url: "/notifications",
                                active: true
                            }
                            // { title: 'Import/Export', url: '/'},
                            // { title: 'Export Selected', active: true }
                        ],
                        rule: "admin"
                    }
                },

                // =============================================================================
                // Application Routes
                // =============================================================================
                {
                    path: "/projects",
                    redirect: "/projects/list",
                    name: "projects"
                },
                {
                    path: "/projects/list",
                    component: () =>
                        import("./views/apps/projects/Project.vue"),
                    meta: {
                        permission: ['manage_contracts', 'manage_proposals'],
                        breadcrumb: [
                            { title: "Home", url: "/" },
                            {
                                title: "پروژه ها و قراردادها",
                                url: "/projects/list",
                                active: true
                            }
                        ],
                        rule: "editor",
                        parent: "projects",
                        no_scroll: true
                    }
                },
                {
                    path: "/projects/add",
                    name: "project-add",
                    component: () =>
                        import("./views/apps/projects/ProjectAdd.vue"),
                    meta: {
                        permission: ['contract_add'],
                        breadcrumb: [
                            { title: "Home", url: "/" },
                            {
                                title: "پروژه ها و قراردادها",
                                url: "/projects/list"
                            },
                            {
                                title: "افزودن پروژه جدید و لست قرار دادها",
                                active: true
                            }
                            // { title: 'Export Selected', active: true }
                        ],
                        rule: "editor",
                        // parent: 'projects',
                        no_scroll: false
                    }
                },
                {
                    path: "/projects/project/:id",
                    name: "project-view",
                    component: () =>
                        import("./views/apps/projects/ProjectView.vue"),
                    props: true,
                    meta: {
                        permission: ['contract_view'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "پروژه ها و قراردادها",
                                url: "/projects/list"
                            },
                            {
                                dyTitle: true
                            }
                        ],
                        rule: "editor",
                        parent: "projects",
                        no_scroll: true
                    }
                },
                {
                    path: "/projects/project/:id/edit",
                    name: "project-edit",
                    component: () =>
                        import("./views/apps/projects/ProjectEdit.vue"),
                    props: true,
                    meta: {
                        permission: ['contract_edit'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "پروژه ها و قراردادها",
                                url: "/projects/list"
                            },
                            {
                                dyTitle: true
                            }
                        ],
                        rule: "editor",
                        parent: "projects",
                        no_scroll: true
                    }
                },
                {
                    path: "/projects/proposal",
                    name: "proposal",
                    component: () =>
                        import("./views/apps/projects/proposals/Proposal.vue"),
                    meta: {
                        permission: ['manage_proposals'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "پروژه ها و قراردادها",
                                url: "/projects/list"
                            },
                            {
                                title: "ثبت اعلان جدید",
                                active: true
                            }
                        ],

                        rule: "editor"
                    }
                },
                {
                    path: "/proposal/:id",
                    name: "proposal-view",
                    component: () =>
                        import(
                            "./views/apps/projects/proposals/ProposalView.vue"
                        ),
                    meta: {
                        permission: ['proposal_view'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "پروژه ها و قراردادها",
                                url: "/projects/list"
                            },
                            {
                                title: "معلومات اعلان",
                                active: true
                            }
                        ],

                        rule: "editor"
                    }
                },
                {
                    path: "/proposals",
                    name: "proposallist",
                    component: () =>
                        import(
                            "./views/apps/projects/proposals/ProposalList.vue"
                        ),
                    meta: {
                        permission: ['manage_proposals'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            // {
                            //     title: 'پروژه ها و قراردادها',
                            //     url: '/projects/list'
                            // },
                            {
                                title: "لست تمام آفرها",
                                active: true
                            }
                        ],
                        rule: "editor"
                    }
                },
                {
                    path: "/projects/proposal/:id/edit",
                    name: "proposal-edit",
                    component: () =>
                        import(
                            "./views/apps/projects/proposals/EditProposal.vue"
                        ),
                    meta: {
                        permission: ['proposal_edit'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "اعلانات",
                                url: "/projects/proposal"
                            },
                            {
                                title: "ویرایش اعلان",
                                active: true
                            }
                        ],

                        rule: "editor"
                    }
                },
                {
                    path: "sales",
                    name: "sales",
                    component: () => import("./views/apps/sales/Sale.vue"),
                    meta: {
                        permission: ['manage_sales'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "فروشات",
                                active: true
                            }
                        ],

                        rule: "editor"
                    }
                },
                {
                    path: "sales/sale/:id",
                    name: "sales-view",
                    component: () =>
                        import("./views/apps/sales/view/SaleView.vue"),
                    meta: {
                        permission: ['sales1_view', 'sales2_view', 'sales3_view', 'sales4_view'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "فروشات",
                                url: "/sales"
                            },
                            {
                                title: "معلومات فروشات",
                                active: true
                            }
                        ],

                        rule: "editor"
                    }
                },
                {
                    path: "sales/sale/:id/edit/s1",
                    name: "sales-edit-1",
                    component: () =>
                        import(
                            "./views/apps/sales/sections/editforms/EditOmdeQarardadi.vue"
                        ),
                    meta: {
                        permission: ['sales1_edit'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "فروشات",
                                url: "/sales"
                            },
                            {
                                title: "لست فروشات",
                                url: "/sales?tab=1"
                            },
                            {
                                title: "ویرایش فروشات",
                                active: true
                            }
                        ],
                        rule: "editor"
                    }
                },
                {
                    path: "sales/sale/:id/edit/s2",
                    name: "sales-edit-2",
                    component: () =>
                        import(
                            "./views/apps/sales/sections/editforms/EditOmdeStandard.vue"
                        ),
                    meta: {
                        permission: ['sales2_edit'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "فروشات",
                                url: "/sales"
                            },
                            {
                                title: "لست فروشات",
                                url: "/sales?tab=1"
                            },
                            {
                                title: "ویرایش فروشات",
                                active: true
                            }
                        ],
                        rule: "editor"
                    }
                },
                {
                    path: "sales/sale/:id/edit/s3",
                    name: "sales-edit-3",
                    component: () =>
                        import(
                            "./views/apps/sales/sections/editforms/EditParchonQarardadi.vue"
                        ),
                    meta: {
                        permission: ['sales3_edit'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "فروشات",
                                url: "/sales"
                            },
                            {
                                title: "لست فروشات",
                                url: "/sales?tab=1"
                            },
                            {
                                title: "ویرایش فروشات",
                                active: true
                            }
                        ],
                        rule: "editor"
                    }
                },
                {
                    path: "sales/sale/:id/edit/s4",
                    name: "sales-edit-4",
                    component: () =>
                        import(
                            "./views/apps/sales/sections/editforms/EditParchonStandard.vue"
                        ),
                    meta: {
                        permission: ['sales4_edit'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "فروشات",
                                url: "/sales"
                            },
                            {
                                title: "لست فروشات",
                                url: "/sales?tab=1"
                            },
                            {
                                title: "ویرایش فروشات",
                                active: true
                            }
                        ],
                        rule: "editor"
                    }
                },
                {
                    path: "/transactions",
                    name: "transactions",
                    component: () =>
                        import("./views/apps/transactions/Transaction.vue"),
                    meta: {
                        permission: ['manage_transactions'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "معاملات",
                                active: true
                            }
                        ],
                        rule: "editor"
                    }
                },
                {
                    path: "/transactions/edit/:id",
                    name: "transaction_edit",
                    component: () =>
                        import("./views/apps/transactions/TransactionEdit.vue"),
                    meta: {
                        permission: ['transaction_edit'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "ویرایش معاملات",
                                active: true
                            }
                        ],
                        rule: "editor"
                    }
                },
                {
                    path: "/tr/:id",
                    component: () =>
                        import("./views/apps/transactions/TransactionView.vue"),
                    meta: {
                        permission: ['transaction_view'],
                        rule: "editor",
                        parent: "email",
                        no_scroll: true
                    }
                },
                {
                    path: "/accounts",
                    name: "accounts",
                    component: () =>
                        import("./views/apps/accounts/Account.vue"),
                    meta: {
                        permission: ['manage_accounts'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "حسابات",
                                active: true
                            }
                        ],
                        rule: "editor"
                    }
                },
                {
                    path: "/accounts/edit/:id",
                    name: "accounts-edit",
                    component: () =>
                        import("./views/apps/accounts/AccountEdit.vue"),
                    meta: {
                        permission: ['account_edit'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "حسابات",
                                url: "/accounts"
                            },
                            {
                                title: "ویرایش حسابات",
                                active: true
                            }
                        ],
                        rule: "editor"
                    }
                },

                {
                    path: "/expenses",
                    name: "expenses",
                    component: () =>
                        import("./views/apps/expenses/Expense.vue"),
                    meta: {
                        permission: ['manage_expenses'],
                        breadcrumb: [
                            { title: "Home", url: "/" },
                            { title: "مخارج", active: true }
                        ],
                        pageTitle: "",
                        rule: "editor"
                    }
                },
                {
                    path: "/expense/:id",
                    name: "view-expense",
                    component: () =>
                        import("./views/apps/expenses/ExpenseView.vue"),
                    meta: {
                        permission: ['expense_view'],
                        breadcrumb: [
                            { title: "Home", url: "/" },
                            { title: "مخارج", active: true }
                        ],
                        pageTitle: "",
                        rule: "editor"
                    }
                },
                {
                    path: "/expense-edit/:id",
                    name: "expense-edit",
                    component: () =>
                        import("./views/apps/expenses/ExpenseEdit.vue"),
                    meta: {
                        permission: ['expense_edit'],
                        breadcrumb: [
                            { title: "Home", url: "/" },
                            { title: "مخارج", url: "/expenses" },
                            { title: "ویرایش مخارج", active: true }
                        ],
                        pageTitle: "",
                        rule: "editor"
                    }
                },
                {
                    path: "/inventory",
                    name: "inventory",
                    component: () =>
                        import("./views/apps/inventory/Inventory.vue"),
                    meta: {
                        permission: ['manage_fuel', 'manage_godams', 'manage_transfers', 'manage_storages'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "دخایر و تانک تیل",
                                active: true
                            }
                        ],
                        rule: "editor"
                    }
                },
                {
                    path: "/inventory/fuel-station",
                    name: "fuel-station",
                    component: () =>
                        import("./views/apps/inventory/add/FuelStation.vue"),
                    meta: {
                        permission: ['manage_fuel'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "دخایر و تانک تیل",
                                url: "/inventory"
                            },
                            {
                                title: "تانک تیل",
                                active: true
                            }
                        ],
                        rule: "editor",
                        parent: "inventory"
                    }
                },
                {
                    path: "/inventory/goods",
                    name: "goods",
                    component: () =>
                        import("./views/apps/inventory/Goods/Goods_list.vue"),
                    meta: {
                        permission: ['manage_products'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "دخایر و تانک تیل",
                                url: "/inventory"
                            },
                            {
                                title: "اجناس و محصولات",
                                active: true
                            }
                        ],
                        rule: "editor",
                        parent: "inventory"
                    }
                },
                {
                    path: "/inventory/godams",
                    name: "godams",
                    component: () =>
                        import("./views/apps/inventory/Godams/Godams_list.vue"),
                    meta: {
                        permission: ['manage_godams'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "دخایر و تانک تیل",
                                url: "/inventory"
                            },
                            {
                                title: "گدام ها",
                                active: true
                            }
                        ],
                        rule: "editor",
                        parent: "inventory"
                    }
                },
                {
                    path: "/procurment",
                    name: "procurment",
                    component: () =>
                        import("./views/apps/procurments/Procurment.vue"),
                    meta: {
                        permission: ['manage_purchases'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "خریداری",
                                active: true
                            }
                            // { title: 'Export Selected', active: true }
                        ],
                        rule: "editor"
                    }
                },
                {
                    path: "/view/procurment/:id",
                    name: "view-procurment",
                    component: () =>
                        import("./views/apps/procurments/ProcurmentView.vue"),
                    meta: {
                        permission: ['purchase_view'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "خریداری",
                                active: true
                            }
                            // { title: 'Export Selected', active: true }
                        ],
                        rule: "editor"
                    }
                },

                {
                    path: "/edit/procurment/:id",
                    name: "procurment-edit",
                    component: () =>
                        import("./views/apps/procurments/Procurmentedit.vue"),
                    meta: {
                        permission: ['purchase_edit'],
                        breadcrumb: [
                            { title: "Home", url: "/" },
                            {
                                title: "مدیریت خریداری ها",
                                url: { name: "procurment" }
                            },
                            { title: "ویرایش خریداری", active: true }
                        ],
                        rule: "editor",
                        no_scroll: true
                    }
                },

                {
                    path: "/archive",
                    name: "archive",
                    component: () =>
                        import("./views/apps/archives/Archive.vue"),
                    meta: {
                        permission: ['manage_archives'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "آرشیف",
                                active: true
                            }
                            // { title: 'Export Selected', active: true }
                        ],
                        rule: "editor"
                    }
                },
                {
                    path: "/archive/files",
                    name: "archivefiles",
                    component: () =>
                        import("./views/apps/archives/ArchiveFiles.vue"),
                    meta: {
                        permission: ['manage_archives'],
                        breadcrumb: [
                            {
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "فایل های آرشیف",
                                active: true
                            }
                            // { title: 'Export Selected', active: true }
                        ],
                        rule: "editor"
                    }
                },
                {
                    path: "/reports",
                    name: "report",
                    component: () => import("./views/apps/reports/Report.vue"),
                    meta: {
                        breadcrumb: [
                            { title: "Home", url: "/" },
                            { title: "راپورها", active: true }
                        ],
                        permission: ['manage_reports'],
                        rule: "editor"
                    }
                },
                {
                    path: "/settings",
                    name: "setting",
                    component: () =>
                        import("./views/apps/settings/Setting.vue"),
                    meta: {
                        permission: ['manage_setting'],
                        breadcrumb: [
                            { title: "Home", url: "/" },
                            { title: " تنظیمات عمومی", active: true }
                        ],
                        rule: "editor",
                        no_scroll: false
                    }
                },
                {
                    path: "/user/management",
                    name: "user-management",
                    component: () => import("./views/apps/user/Profile.vue"),
                    meta: {
                        permission: ['manage_users'],
                        breadcrumb: [
                            { title: "Home", url: "/" },
                            { title: "مدیریت کاربر", active: true }
                        ],
                        rule: "editor",
                        no_scroll: false
                    }
                },
                {
                    path: "/user/edit/:user_id",
                    name: "user-profile-edit",
                    component: () =>
                        import("./views/apps/user/sub/UserEdit.vue"),
                    meta: {
                        permission: ['user_edit'],
                        breadcrumb: [
                            { title: "Home", url: "/" },
                            {
                                title: "مدیریت کاربر",
                                url: { name: "user-management" }
                            },
                            { title: "ویرایش کاربر", active: true }
                        ],
                        rule: "editor",
                        no_scroll: true
                    }
                }
            ]
        },
        // =============================================================================
        // FULL PAGE LAYOUTS
        // =============================================================================
        {
            path: "",
            component: () => import("@/layouts/full-page/FullPage.vue"),
            children: [
                // =============================================================================
                // PAGES
                // =============================================================================
                {
                    path: "/login",
                    redirect:
                        localStorage.getItem("token") === null
                            ? null
                            : "/dashboard",
                    name: "login",
                    component: () => import("@/views/pages/login/Login.vue"),
                    meta: {
                        permission: ['dashboard'],
                        rule: "editor"
                    }
                }
            ]
        },
        {
            path: "/mailto",
            name: "mailto",
            redirect: () => window.location.href = 'mailto:a@gmail.com',
            rule: "editor"
        },
        {
            path: "/logout",
            name: "logout",
            redirect: "/login"
        },
        {
            path: "/webmail",
            name: "webmail",
            redirect: "https://shaiqalimi.com/webmail",
            ex_url: "https://shaiqalimi.com/webmail"
        },
        // Redirect to 404 page, if no match found
        {
            path: "*",
            redirect: "/pages/error-404"
        }
    ]
});

router.afterEach(() => {
    // Remove initial loading
    // setTimeout(() => {
    //     const appLoading = document.getElementById("loading-bg");
    //     if (appLoading) {
    //         appLoading.style.display = "none";
    //     }
    // }, 1000)
});

router.beforeEach((to, from, next) => {
    const appLoading = document.getElementById("loading-bg");
    if (appLoading) {
        appLoading.style.display = "block";
    }
    if (to.meta.permission) {
        let user_permissions = localStorage.getItem("user_permissions");
        if (
            user_permissions &&
            !to.meta.permission.every(v => user_permissions.includes(v))
        ) {
            router.push({ path: "/" });
        }
    }
    // If auth required, check login. If login fails redirect to login page
    if (to.name !== "login") {
        if (!localStorage.getItem("token")) {
            router.push({ path: "/login" });
        }
    }

    return next();
    // Specify the current path as the customState parameter, meaning it
    // will be returned to the application after auth
    // auth.login({ target: to.path });
    // })
});
export default router;
