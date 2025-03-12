<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => '',
    'title_prefix' => 'RT 17 PAS',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<span style="text-decoration: none; display: inline-block;"><b>RT 17 PAS</b></span>',
    'logo_img' => 'assets/img/RT17_Logo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'assets/img/RT17_Logo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'logout_method' => 'GET',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        [
            'type' => 'navbar-search',
            'text' => 'search',
            'topnav_right' => false,
        ],
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => false,
        ],

        // Sidebar items:
        
        // [
        //     'text' => 'blog',
        //     'url' => 'admin/blog',
        //     'can' => 'manage-blog',
        // ],
        // [
        //     'key' =>'pages',
        //     'text' => 'pages',
        //     'url' => 'admin/pages',
        //     'icon' => 'far fa-fw fa-file',
        //     'label' => 4,
        //     'label_color' => 'success',
        // ],
        [
            'key' =>'dashboard',
            'text' => 'DASHBOARD',
            'url' => '/',
            'icon' => 'fas fa-th nav-icon',
        ],
        [
            'text' => 'Data Warga',
            'url' => 'datas/list_warga',
            'icon' => 'fas fa-box-open nav-icon',
            'can' => ['role-admin','sect-pengurus'],
        ],
        [
            'text' => 'Keuangan',
            'icon' => 'fas fa-copy nav-icon',
            'can' => ['role-admin','sect-pengurus'],
            'submenu' => [
                [
                    'text' => 'Iuran Warga',
                    // 'url' => 'keuangan/frm_iuran_warga',
                    'url' => 'undermaintenance',
                    'icon' => 'fas fa-bullhorn nav-icon',
                ],
                [
                    'text' => 'LPJ',
                    'url' => 'keuangan/frm_lpj',
                    'icon' => 'fas fa-bullhorn nav-icon',
                    'can' => ['role-admin'],
                    'active' => ['keuangan/rpt*']
                ],
            ],
        ],
        [
            'text' => 'Data Pengurus',
            'url' => 'undermaintenance',
            'icon' => 'fas fa-box-open nav-icon',
            'can' => ['role-admin','sect-pengurus'],
        ],
        // [
        //     'text' => 'MAINTENANCE',
        //     'icon' => 'fas fa-toolbox nav-icon',
        //     'can' => ['role-admin','dept-maintenance'],
        //     'submenu' => [
        //         // [
        //         //     'text' => 'Perbaikan',
        //         //     'url' => 'maintenance/perbaikan',
        //         //     'icon' => 'fas fa-hammer nav-icon',
        //         // ],
        //         [
        //             'text' => 'Schedule',
        //             'url' => 'maintenance/schedule',
        //             'icon' => 'far fa-calendar-alt nav-icon',
        //         ],
        //         [
        //             'text' => 'Laporan',
        //             'url' => 'maintenance/laporan',
        //             'icon' => 'fas fa-chart-line nav-icon',
        //         ],
        //         [
        //             'text' => 'Mesin',
        //             'url' => 'maintenance/mesin',
        //             'icon' => 'fas fa-cogs nav-icon',
        //         ],
        //     ],
        // ],
        // [
        //     'text' => 'TECHNICAL',
        //     'icon' => 'fas fa-drafting-compass nav-icon',
        //     'can' => ['role-admin','dept-tooling'],
        //     'submenu' => [
        //         [
        //             'text' => 'Jigu Control',
        //             // 'url' => 'undermaintenance',
        //             'icon' => 'fas fa-plus-square nav-icon',
        //         ],
        //         [
        //             'text' => 'Request',
        //             'url' => 'technical/menu_tch',
        //             'icon' => 'fas fa-plus-square nav-icon',
        //         ],
        //         [
        //             'text' => 'Update Denpyou',
        //             'url' => 'technical/update',
        //             'icon' => 'fas fa-clipboard-check nav-icon',
        //         ],
        //         [
        //             'text' => 'Master Tanegata',
        //             'url' => 'technical/list_master',
        //             'icon' => 'fa fa-asterisk nav-icon',
        //         ],
        //     ],
        // ],
        // [
        //     'text' => 'QA',
        //     'icon' => 'fas fa-ruler-combined nav-icon',
        //     'can' => ['role-admin','dept-qa'],
        //     'submenu' => [
        //         [
        //             'text' => 'QA Menu',
        //             'url' => 'qa/qamenu',
        //             'icon' => 'fas fa-plus-square nav-icon',
        //         ],
        //     ],
        // ],
        // [
        //     'text' => 'DELIVERY',
        //     'icon' => 'fas fa-truck nav-icon',
        //     'submenu' => [
        //         [
        //             'text' => 'Work Result Entry',
        //             'url' => 'ppic/workresult',
        //             'icon' => 'fas fa-file-alt nav-icon',
        //             'can' => ['role-admin','dept-ppic'],
        //         ],
        //         [
        //             'text' => 'List Work Result',
        //             'url' => 'ppic/inqueryworkresult',
        //             'icon' => 'fas fa-list-ol nav-icon',
        //         ],
        //         [
        //             'text' => 'List Kantei',
        //             'url' => 'ppic/v_kantei',
        //             'icon' => 'far fa-list-alt nav-icon',
        //         ],
        //         [
        //             'text' => 'Estimasi',
        //             'url' => 'ppic/f_calculation',
        //             'icon' => 'fab fa-fedex nav-icon',
        //             'can' => ['role-admin','dept-ppic'],
        //         ],
        //         [
        //             'text' => 'Target',
        //             'url' => 'ppic/target',
        //             'icon' => 'fas fa-text-height nav-icon',
        //             'can' => ['role-admin','dept-ppic'],
        //         ],
        //         [
        //             'text' => 'Master PPIC',
        //             'url' => 'ppic/f_master',
        //             'icon' => 'far fa-circle nav-icon',
        //             'can' => ['role-admin','dept-ppic'],
        //         ],
        //         [
        //             'text' => 'Jam Kerusakan Mesin',
        //             'url' => 'ppic/jam_kerusakan',
        //             'icon' => 'fas fa-cogs nav-icon',
        //             'can' => ['role-admin','dept-ppic'],
        //         ],
        //     ],
        // ],
        // [
        //     'text' => 'BARILI',
        //     'url' => 'barili/frm_menu_barili',
        //     'icon' => 'fas fa-box-open nav-icon',
        //     'can' => ['role-admin','dept-ppic','dept-exim'],
        // ],
        // [
        //     'text' => 'HSE',
        //     'icon' => 'fab fa-envira nav-icon',
        //     'can' => ['role-admin','dept-hse'],
        //     'submenu' => [
        //         [
        //             'text' => 'HH / KY Entry',
        //             'url' => 'hse/f_hhky',
        //             'icon' => 'fas fa-h-square nav-icon',
        //         ],
        //         [
        //             'text' => 'HH / KY Inquery',
        //             'url' => 'hse/hklist',
        //             'icon' => 'fas fa-list nav-icon',
        //         ],
        //         [
        //             'text' => 'HH / KY Grafik',
        //             'url' => 'hse/hhkygrafik',
        //             'icon' => 'fas fa-chart-pie nav-icon',
        //         ],
               
        //     ],
        // ],
        // [
        //     'text' => 'ISO',
        //     'icon' => 'fas fa-globe nav-icon',
        //     'submenu' => [
        //         [
        //             'text' => 'SS Entry',
        //             'url' => 'iso/ssentry',
        //             'icon' => 'fab fa-staylinked nav-icon',
        //             'can' => ['role-admin','dept-iso'],
        //         ],
        //         [
        //             'text' => 'SS Inquery',
        //             'url' => 'iso/sslist',
        //             'icon' => 'far fa-list-alt nav-icon',
        //             'can' => ['role-admin','dept-iso'],
        //         ],
        //         [
        //             'text' => 'SS Grafik',
        //             'url' => 'iso/ssgrafik',
        //             'icon' => 'fas fa-chart-pie nav-icon',
        //             'can' => ['role-admin','dept-iso'],
        //         ],
        //         [
        //             'text' => 'SS Nilai',
        //             'url' => 'iso/sspoint',
        //             'icon' => 'fas fa-list-ol nav-icon',
        //         ],
        //         [
        //             'text' => 'Document Control',
        //             'url' => 'iso/document_control',
        //             'icon' => 'far fa-file-alt nav-icon',
        //         ],
        //     ],
        // ],
        // [
        //     'text' => 'MANAGER',
        //     'icon' => 'fas fa-user-tie nav-icon',
        //     'can' => ['role-admin','dept-manager'],
        //     'submenu' => [
        //         [
        //             'text' => 'Target Lembur',
        //             'url' => 'manager/targetlembur',
        //             'icon' => 'fas fa-address-card nav-icon',
        //         ],
        //     ],
        // ],
        // [
        //     'text' => 'PGA',
        //     'icon' => 'fas fa-edit nav-icon',
        //     'submenu' => [
        //         [
        //             'text' => 'Appraisal',
        //             'url' => 'pga/appraisal',
        //             'icon' => 'fas fa-check-circle nav-icon',
        //             'can' => ['role-admin','dept-manager','dept-pga'],
        //         ],
        //         [
        //             'text' => 'Bonus',
        //             'url' => 'pga/pgabonus',
        //             'icon' => 'fas fa-money-bill-wave nav-icon',
        //             'can' => ['role-admin','dept-manager','dept-pga'],
        //         ],
        //         [
        //             'text' => 'Doc GA',
        //             'url' => 'pga/frm_menu_pga',
        //             'icon' => 'fas fa-address-card nav-icon',
        //         ],
        //         [
        //             'text' => 'Setting',
        //             'url' => 'pga/frm_setting',
        //             'icon' => 'fas fa-cog nav-icon',
        //             'can' => ['role-admin','dept-manager','dept-pga'],
        //         ],
        //     ],
        // ],
        // [
        //     'text' => 'DOCUMENT',
        //     'icon' => 'fas fa-book nav-icon',
        //     'submenu' => [
        //         [
        //             'text' => 'My Document',
        //             'url' => 'document/inquery_document',
        //             'icon' => 'fa fa-file-contract nav-icon',
        //         ],
        //     ],
        // ],
        // [
        //     'text' => 'WAREHOUSE',
        //     'icon' => 'fas fa-warehouse nav-icon',
        //     'can' => ['role-admin','dept-manager','dept-purchasing'],
        //     'submenu' => [
        //         [
        //             'text' => 'Timbangan',
        //             'url' => 'warehouse/timbangan',
        //             'icon' => 'fas fa-balance-scale nav-icon',
        //         ],
        //     ],
        // ],
        [
            'text' => 'ADMIN',
            'icon' => 'fas fa-user nav-icon',
            'can' => 'role-admin',
            'submenu' => [
                [
                    'text' => 'Register',
                    'url' => 'admin/register',
                    'icon' => 'fas fa-user-plus nav-icon',
                ],
                [
                    'text' => 'List User',
                    'url' => 'admin/userlist',
                    'icon' => 'fas fa-portrait nav-icon',
                ],
                [
                    'text' => 'Tools',
                    'url' => 'admin/tools',
                    'icon' => 'fas fa-tools nav-icon',
                ],
                [
                    'text' => 'Akses User',
                    'url' => 'admin/frm_aksesUser',
                    'icon' => 'fas fa-cog nav-icon',
                ],
                [
                    'text' => 'List Update',
                    'url' => 'admin/list_update',
                    'icon' => 'far fa-list-alt nav-icon',
                ],
                [
                    'text' => 'Log',
                    'url' => 'admin/log',
                    'icon' => 'fas fa-clipboard-list',
                ],
            ],
        ],
        // [
        //     'text' => 'CALENDAR',
        //     'url' => 'calendar',
        //     'icon' => 'far fa-calendar-alt nav-icon',
        // ],
        // [
        //     'text' => 'HELP DESK',
        //     'url' => 'help_desk',
        //     'icon' => 'far fa-question-circle nav-icon',
        // ],
/*        
        [
            'text' => 'change_password',
            'url' => 'admin/settings',
            'icon' => 'fas fa-fw fa-lock',
            'can' => 'user-admin',
        ],
        [
            'text' => 'multilevel',
            'icon' => 'fas fa-fw fa-share',
            'submenu' => [
                [
                    'text' => 'level_one',
                    'url' => '#',
                ],
                [
                    'text' => 'level_one',
                    'url' => '#',
                    'submenu' => [
                        [
                            'text' => 'level_two',
                            'url' => '#',
                        ],
                        [
                            'text' => 'level_two',
                            'url' => '#',
                            'submenu' => [
                                [
                                    'text' => 'level_three',
                                    'url' => '#',
                                ],
                                [
                                    'text' => 'level_three',
                                    'url' => '#',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'text' => 'level_one',
                    'url' => '#',
                ],
            ],
        ],
        ['header' => 'labels'],
        [
            'text' => 'important',
            'icon_color' => 'red',
            'url' => '#',
        ],
        [
            'text' => 'warning',
            'icon_color' => 'yellow',
            'url' => '#',
        ],
        [
            'text' => 'information',
            'icon_color' => 'cyan',
            'url' => '#',
        ],
        [
            'text' => 'Logout',
            'url' => '#',
            'id' => 'btn_logout',
            //'data' => ['csrf' => '@csrf'],
            'icon' => 'fas fa-sign-out-alt',
        ],
*/
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                // [
                //     'type' => 'js',
                //     'asset' => false,
                //     'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                // ],
                // [
                //     'type' => 'js',
                //     'asset' => false,
                //     'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                // ],
                // [
                //     'type' => 'js',
                //     'asset' => false,
                //     'location' => '//cdn.datatables.net/2.1.8/js/dataTables.js',
                // ],
                // [
                //     'type' => 'css',
                //     'asset' => false,
                //     'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                // ],
                // [
                //     'type' => 'css',
                //     'asset' => false,
                //     'location' => '//cdn.datatables.net/2.1.8/css/dataTables.dataTables.css',
                // ],

                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//code.jquery.com/jquery-3.7.1.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/2.2.2/js/dataTables.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdn.datatables.net/select/2.0.2/js/dataTables.select.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.css',
                    // 'location' => 'https://cdn.datatables.net/select/2.0.2/css/select.dataTables.css',
                ],
                // [
                //     'type' => 'css',
                //     'asset' => false,
                //     'location' => 'https://cdn.datatables.net/2.2.2/css/dataTables.css',
                //     // 'location' => 'https://cdn.datatables.net/2.2.2/css/dataTables.css',
                // ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',

                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                // [
                //     'type' => 'js',
                //     'asset' => false,
                //     'location'=>'https://code.jquery.com/jquery-3.7.1.min.js',
                // ],
                [
                    'type' => 'js',
                    'asset' => false,
                    //'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                    //'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.js',
                    'location'=>'//cdn.jsdelivr.net/npm/chart.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
        'moment' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/moment/min/moment.min.js'
                ],
            ]
        ],
        'daterangepicker' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js'
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css'
                ],
            ]
        ],
        'BsCustomFileInput' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdnjs.cloudflare.com/ajax/libs/bs-custom-file-input/1.3.4/bs-custom-file-input.min.js'
                ],
            ]
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
