/*=========================================================================================
  File Name: sidebarItems.js
  Description: Sidebar Items list. Add / Remove menu items from here.
  Strucutre:
          url     => router path
          name    => name to display in sidebar
          slug    => router path name
          icon    => Feather Icon component/icon name
          tag     => text to display on badge
          tagColor  => class to apply on badge element
          i18n    => Internationalization
          submenu   => submenu of current item (current item will become dropdown )
                NOTE: Submenu don't have any icon(you can add icon if u want to display)
          isDisabled  => disable sidebar item/group
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

export default [{
        url: '/dashboard',
        name: 'Dashboard',
        tagColor: 'warning',
        icon: 'HomeIcon',
        i18n: 'Dashboard'
    },
    {
        url: '/notifications',
        name: 'Notifications',
        slug: 'notifications',
        icon: 'BellIcon',
        i18n: 'Notifications'
    },
    {
        header: 'Sales Managment',
        icon: 'PackageIcon',
        i18n: 'salesManagment',
        items: [
            {
                url: '/proposals',
                name: 'Proposals',
                slug: 'Proposals',
                icon: 'FileIcon',
                i18n: 'Proposals'
            },
            {
                url: '/projects/list',
                name: 'Projects',
                slug: 'projects',
                icon: 'PackageIcon',
                i18n: 'Projects'
            },
            {
                url: '/sales',
                name: 'Sales',
                slug: 'sales',
                icon: 'TrendingUpIcon',
                i18n: 'Sales'
            }
        ]
    },
    {
        header: 'Finance Managment',
        icon: 'PieChartIcon',
        i18n: 'financeManagment',
        items: [{
                url: '/accounts',
                name: 'Accounts',
                slug: 'accounts',
                icon: 'UsersIcon',
                i18n: 'Accounts'
            },
            {
                url: '/transactions',
                name: 'Transactions',
                slug: 'transactions',
                icon: 'RefreshCwIcon',
                tagColor: 'success',
                i18n: 'Transactions'
            },
            {
                url: '/expenses',
                name: 'Expenses',
                slug: 'expenses',
                icon: 'DollarSignIcon',
                i18n: 'Expenses'
            }
        ]
    },
    {
        header: 'Resource Managment',
        icon: 'PieChartIcon',
        i18n: 'resourceManagment',
        items: [{
                url: '/inventory',
                name: 'Inventory',
                slug: 'inventory',
                icon: 'DatabaseIcon',
                i18n: 'Inventory'
            },
            {
                url: '/procurment',
                name: 'Procurment',
                slug: 'procurment',
                icon: 'TruckIcon',
                i18n: 'Procurment'
            }
        ]
    },
    {
        header: 'Doctuments Managment',
        icon: 'PieChartIcon',
        i18n: 'doctumentsManagment',
        items: [{
                url: '/archive',
                name: 'Archive',
                slug: 'archive',
                icon: 'ArchiveIcon',
                i18n: 'Archive'
            },
            {
                url: '/reports',
                name: 'Reports',
                slug: 'reports',
                icon: 'FileTextIcon',
                i18n: 'Reports'
            }
        ]
    },
    {
        header: 'System Managment',
        icon: 'PieChartIcon',
        i18n: 'systemManagment',
        items: [{
                url: '/settings',
                name: 'Settings',
                slug: 'settings',
                icon: 'SettingsIcon',
                i18n: 'Settings'
            },
            {
                url: { name: 'user-management' },
                name: 'user-management',
                icon: 'UserIcon',
                i18n: 'Profile'
            }
        ]
    }
]