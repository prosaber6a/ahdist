<div class="hover-scroll-overlay-y my-2 py-5 py-lg-8" id="kt_aside_menu_wrapper" data-kt-scroll="true"
     data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
     data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer"
     data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
    <!--begin::Menu-->
    <div
        class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
        id="#kt_aside_menu" data-kt-menu="true">
        <div class="menu-item">
            <div class="menu-content pb-2">
                <span class="menu-section text-muted text-uppercase fs-8 ls-1">Dashboard</span>
            </div>
        </div>
        <div class="menu-item">
            <a class="menu-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <span class="menu-icon">
                    <i class="bi bi-grid fs-3"></i>
                </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </div>
        <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('party*') ? 'show' : '' }}">
            <span class="menu-link">
                <span class="menu-icon">
                    <i class="bi bi-people fs-3"></i>
                </span>
                <span class="menu-title">Party</span>
                <span class="menu-arrow"></span>
            </span>

            <div class="menu-sub menu-sub-accordion menu-active-bg">
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('create_party') ? 'active' : '' }}"
                       href="{{ route('create_party') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Add New</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('parties') ? 'active' : '' }}"
                       href="{{ route('parties') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">All Parites</span>
                    </a>
                </div>
            </div>
        </div>
        <div data-kt-menu-trigger="click"
             class="menu-item menu-accordion {{ request()->is('product*') ? 'show' : '' }}">
            <span class="menu-link">
                <span class="menu-icon">
                    <i class="bi bi-box-seam fs-3"></i>
                </span>
                <span class="menu-title">Product</span>
                <span class="menu-arrow"></span>
            </span>

            <div class="menu-sub menu-sub-accordion menu-active-bg">
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('create_product') ? 'active' : '' }}"
                       href="{{ route('create_product') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Add New</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('products') ? 'active' : '' }}"
                       href="{{ route('products') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">All Product</span>
                    </a>
                </div>
            </div>
        </div>

        <div data-kt-menu-trigger="click"
             class="menu-item menu-accordion {{ request()->is('purchase*') ? 'show' : '' }}">
            <span class="menu-link">
                <span class="menu-icon">
                    <i class="bi bi-cart-plus fs-3"></i>
                </span>
                <span class="menu-title">Purchase</span>
                <span class="menu-arrow"></span>
            </span>

            <div class="menu-sub menu-sub-accordion menu-active-bg">
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('create_purchase') ? 'active' : '' }}"
                       href="{{ route('create_purchase') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Add New</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('purchases') ? 'active' : '' }}"
                       href="{{ route('purchases') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">All Purchase</span>
                    </a>
                </div>
            </div>
        </div>


        <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('sale*') ? 'show' : '' }}">
            <span class="menu-link">
                <span class="menu-icon">
                    <i class="bi bi-cart-dash fs-3"></i>
                </span>
                <span class="menu-title">Sale</span>
                <span class="menu-arrow"></span>
            </span>

            <div class="menu-sub menu-sub-accordion menu-active-bg">
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('create_sale') ? 'active' : '' }}"
                       href="{{ route('create_sale') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Add New</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('sales') ? 'active' : '' }}"
                       href="{{ route('sales') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">All Sale</span>
                    </a>
                </div>
            </div>
        </div>


        <div data-kt-menu-trigger="click"
             class="menu-item menu-accordion {{ (request()->is('account*') || request()->is('transaction*')) ? 'show' : '' }}">
            <span class="menu-link">
                <span class="menu-icon">
                    <i class="bi bi-bank fs-3"></i>
                </span>
                <span class="menu-title">Accounting</span>
                <span class="menu-arrow"></span>
            </span>

            <div class="menu-sub menu-sub-accordion {{ request()->is('account*') ? 'menu-active-bg' : ''}}">
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{ request()->is('account*') ? 'show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Accounts</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion menu-active-bg">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('create_account') ? 'active' : '' }}"
                               href="{{ route('create_account') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Add New</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('accounts') ? 'active' : '' }}"
                               href="{{ route('accounts') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">All Account</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="menu-sub menu-sub-accordion {{ request()->is('transaction*') ? 'menu-active-bg' : '' }}">
                <div data-kt-menu-trigger="click"
                     class="menu-item menu-accordion {{ request()->is('transaction*') ? 'show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Transaction</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion menu-active-bg">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('create_deposit') ? 'active' : '' }}"
                               href="{{ route('create_deposit') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Add New Deposit</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('create_expense') ? 'active' : '' }}"
                               href="{{ route('create_expense') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Add New Expense</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('create_transfer') ? 'active' : '' }}"
                               href="{{ route('create_transfer') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Add New Transfer</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('transactions') ? 'active' : '' }}"
                               href="{{ route('transactions') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">All Transaction</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>

</div>
