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

    'title' => 'SIOM Reportes',
    'title_prefix' => '',
    'title_postfix' => '| SIOM Reportes',

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

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>SIOM</b>&nbsp;Reportes',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'SIOMReportes',

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
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

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
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
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
    'classes_sidebar' => 'sidebar-dark-info elevation-4',
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
    'sidebar_collapse' => true,
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
    'login_url' => 'login',
    'register_url' => false,
    'password_reset_url' => false, //'password/reset',
    'password_email_url' => false, //'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

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

        ['header' => 'Menu'],
        [
            'text' => 'dashboard_process',
            'url'  => 'dashboard',
            'icon' => 'nav-icon fas fa-table',
        ],
        [
            'text' => 'dashboard_mine',
            'url'  => 'dashboard_mine',
            'icon' => 'nav-icon fas fa-table',
        ],
        [
            'text' => 'Seguridad',
            'url'  => '#',
            'icon' => 'nav-icon fas fa-table',
            'can'  => 'ssoma module',
            'submenu' => 
            [
                [
                    'text' => 'Capacitacion',
                    'url'  => 'capacitacion_performance',
                    'icon' => 'nav-icon fas fa-chalkboard-teacher',
                ],
                [
                    'text' => 'ATS',
                    'url'  => 'ats',
                    'icon' => 'nav-icon fas fa-file-alt'
                ],
                [
                    'text' => 'OST',
                    'url'  => 'ost',
                    'icon' => 'nav-icon fas fa-file-alt'
                ],
                [
                    'text' => 'Inspecciones',
                    'url'  => 'inspeccion',
                    'icon' => 'nav-icon fas fa-search'
                ],
            ]
        ],
        [
            'text' => 'historial',
            'url'  => 'historial',
            'icon' => 'nav-icon fas fa-book',
        ],
        [
            'text' => 'Control por Variable',
            'url'  => 'historial_por_variable',
            'icon' => 'nav-icon fas fa-clipboard-list',
        ],
        [
            'text' => 'Historial Variables',
            'url'  => 'historialvariables',
            'icon' => 'nav-icon far fa-newspaper',
        ],
        [
            'text' => 'budget',
            'url'  => 'budget',
            'icon' => 'nav-icon fas fa-bold',
            'can'  => 'budget module'
        ],
        [
            'text' => 'forecast',
            'url'  => '#',
            'icon' => 'nav-icon fab fa-facebook-f',
            'can'  => 'forecast module',
            'submenu' => 
            [
                [
                    'text' => 'individual',
                    'url'  => 'forecast_individual',
                    'icon' => 'nav-icon far fa-circle',
                ],
                [
                    'text' => 'group',
                    'url'  => 'forecast_group',
                    'icon' => 'nav-icon far fa-circle'
                ]
            ]
        ],
        [
            'header' => 'Administración',
            'can'   => 'Admin'
        ],
        [
            'text' => 'menu',
            'url'  => '#',
            'icon' => 'fas nav-icon fa-cog',
            'can'  => 'Admin',
            'submenu' => 
            [
                [
                    'text' => 'conciliado',
                    'url'  => 'conciliado',
                    'icon' => 'nav-icon fas fa-sync-alt',
                ],
                [
                    'text' => 'area',
                    'url'  => 'area',
                    'icon' => 'nav-icon fas fa-font',
                ],
                [
                    'text' => 'categoria',
                    'url'  => 'categoria',
                    'icon' => 'nav-icon fas fa-align-justify',
                ],
                [
                    'text' => 'subcategoria',
                    'url'  => 'subcategoria',
                    'icon' => 'nav-icon fas fa-align-justify',
                ],
                [
                    'text' => 'variable',
                    'url'  => 'variable',
                    'icon' => 'nav-icon fas fa-tag',
                ],
                [
                    'text' => 'permisos',
                    'url'  => 'permisos',
                    'icon' => 'nav-icon fas fa-users-cog',
                ],
                [
                    'text' => 'comentario_area',
                    'url'  => 'comentario_area',
                    'icon' => 'nav-icon fas fa-comment-alt',
                ],
                [
                    'text' => 'empleados',
                    'url'  => 'empleados',
                    'icon' => 'nav-icon fas fa-users',
                ],
            ],
        ],
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
        'JQuery' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/JQuery/jquery-3.5.1.min.js',
                ],
            ],
        ],
        'jQuery-Validation' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/jquery-validation/jquery.validate.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/jquery-validation/additional-methods.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/jquery-validation/localization/messages_es.min.js',
                ],
            ],
        ],
        'custom' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'css/custom.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/funciones.js',
                ],
            ],
        ],
        'Toastr' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/toastr/toastr.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/toastr/toastr.min.css',
                ],
                
            ],
        ],
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/JSZip-2.5.0/jszip.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/pdfmake-0.1.36/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/pdfmake-0.1.36/vfs_fonts.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/DataTables-1.11.4/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/DataTables-1.11.4/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/Buttons-1.6.2/js/dataTables.buttons.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/Buttons-1.6.2/js/buttons.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/Buttons-1.6.2/js/buttons.colVis.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/Buttons-1.6.2/js/buttons.flash.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/Buttons-1.6.2/js/buttons.html5.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/Buttons-1.6.2/js/buttons.print.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/ColReorder-1.5.2/js/dataTables.colReorder.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/FixedColumns-3.3.1/js/dataTables.fixedColumns.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/FixedHeader-3.1.7/js/dataTables.fixedHeader.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/Scroller-2.0.2/js/dataTables.scroller.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/SearchPanes-1.1.1/js/dataTables.searchPanes.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/SearchPanes-1.1.1/js/searchPanes.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/DataTables/RowGroup-1.1.4/js/dataTables.rowGroup.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'assets/DataTables/DataTables-1.11.4/css/dataTables.bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'assets/DataTables/Buttons-1.6.2/css/buttons.bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'assets/DataTables/ColReorder-1.5.2/css/colReorder.bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'assets/DataTables/FixedColumns-3.3.1/css/fixedColumns.bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'assets/DataTables/FixedHeader-3.1.7/css/fixedHeader.bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'assets/DataTables/Scroller-2.0.2/css/scroller.bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'assets/DataTables/SearchPanes-1.1.1/css/searchPanes.bootstrap4.min.css',
                ],
            ],
        ],
        'Popper' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/popper/popper.min.js',
                ],
            ],
        ],
        'Bootstrap' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'assets/Bootstrap/bootstrap.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'assets/Bootstrap/bootstrap.min.js',
                ],
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
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/sweetalert2/sweetalert2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/sweetalert2/sweetalert2.min.css',
                ],
            ],
        ],
        'Pace' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/pace-progress/themes/black/pace-theme-minimal.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/pace-progress/pace.min.js',
                ],
            ],
        ],
        'Moment' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/moment/moment-with-locales.min.js',
                ],
            ],
        ],
        'TempusDominus' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
                ],
            ],
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
