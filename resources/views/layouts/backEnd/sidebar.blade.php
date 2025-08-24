<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    @php
        $sitting = App\Models\AdminSitting::where('company_code',auth()->user()->company_code)->first();
    @endphp
    <!-- Brand Logo -->
    <a href="{{ route('backEnd.dashBoard') }}" class="brand-link">
        <img src="{{ asset('/assets/backEnd/images/' . $sitting->photo)}}"" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ $sitting->system_name }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">

                <img src="{{ asset('/assets/backEnd/images/' . $sitting->photo)}}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
            <a href="{{ route('backEnd.dashBoard') }}" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- DASHBOARD  الرئيسية -->
                <li class="nav-item has-treeview @if (Request::segment(1) == 'admin' && Request::segment(2) == 'dashBoard') menu-open @endif">
                    <a href="#" class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(2) == 'dashBoard') active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            الرئيسية
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./index.html" class="nav-link active">
                                <i class="far fa-circle nav-icon"></i>
                                <p>الرئيسية</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @can('عرض التفاصيل حركات النظام')
                    <li class="nav-item">
                        <a href="{{ route('actionHistory.index') }}"
                            class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(2) == 'actionHistory') menu-open @endif">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                ارشيف حركات النظام
                                <span class="right badge badge-danger">New</span>
                            </p>
                        </a>
                    </li>
                @endcan







                {{-- ADMIN SITTINGS اعدادات الادمن بانيل  --}}
                @can('عرض الاعدادات')
                    <li class="nav-item has-treeview @if (Request::segment(1) == 'admin' && Request::segment(2) == 'adminSittings') menu-open @endif">
                        <a href="#" class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(2) == 'adminSittings') active @endif">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>
                                الاعدادات
                                <i class="fas fa-angle-left right"></i>
                                <span class="badge badge-info right">6</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('عرض الاعدادات')
                                <li class="nav-item">
                                    <a href="{{ route('adminSittings.index') }}"
                                        class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(2) == 'adminSittings') active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>كل الاعدادات</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan



                {{-- الخزن و شيفتات الموظفين --}}
                @can('عرض الخزن و الشيفتات')
                    <li class="nav-item has-treeview @if (Request::segment(1) == 'admin' && Request::segment(2) == 'Treasuries_Shifts') menu-open @endif">
                        <a href="#" class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(2) == 'Treasuries_Shifts') active @endif">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                الخزن و الشيفتات
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            {{-- TREASURIES الخزن الادمن بانيل  --}}
                            @can('عرض الخزن')
                                <li class="nav-item has-treeview @if (Request::segment(3) == 'treasuries') menu-open @endif">
                                    <a href="#" class="nav-link @if (Request::segment(3) == 'treasuries') active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            الخزن
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('treasuries.index') }}"
                                                class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'treasuries' && Request::segment(4) == '') active @endif">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>كل الخزن</p>
                                            </a>
                                        </li>

                                        @can('عرض اضافة خزن لنفس الموظف')
                                            <li class="nav-item">
                                                <a href="{{ route('treasuries.add_emplyee_treasuries') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' &&
                                                            Request::segment(3) == 'treasuries' &&
                                                            Request::segment(4) == 'add_emplyee_treasuries') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>اضافة خزن للموظف</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('عرض الخزن المحزوفة')
                                            <li class="nav-item">
                                                <a href="{{ route('treasuries.softDelete') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'treasuries' && Request::segment(4) == 'softDelete') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p> الخزن المحذوفة</p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcan





                            {{-- شيفتات المستخدم SHIFTS  --}}
                            @can('الشيفتات')
                                <li class="nav-item has-treeview @if (Request::segment(3) == 'Treasuries_Shifts') menu-open @endif">
                                    <a href="#" class="nav-link @if (Request::segment(3) == 'Treasuries_Shifts') active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            الشيفتات
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <!-- Nested Submenu under Suppliers -->
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            @can('الشيفتات')
                                                <a href="{{ route('shifts.index') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'shifts' && Request::segment(4) == '') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل شيفتات المستخدم</p>
                                                </a>
                                            @endcan
                                        </li>
                                    </ul>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan










                {{-- الاصناف و المخازن --}}
                @can('الاصناف و المخازن')
                    <li class="nav-item has-treeview @if (Request::segment(1) == 'admin' && Request::segment(2) == 'items&stores') menu-open @endif">
                        <a href="#" class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(2) == 'items&stores') active @endif">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                الاصناف و المخازن
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <!-- فئات الاصناف Item units  -->
                            @can('فئات الاصناف')
                                <li class="nav-item has-treeview @if (Request::segment(3) == 'itemCategories') menu-open @endif">
                                    <a href="#" class="nav-link @if (Request::segment(3) == 'itemCategories') active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            فئات الاصناف
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        @can('عرض فئات الاصناف')
                                            <li class="nav-item">
                                                <a href="{{ route('itemCategories.index') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'itemCategories' && Request::segment(4) == '') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل فئات الاصناف</p>
                                                </a>
                                            </li>
                                        @endcan


                                        @can('عرض فئات الاصناف المحزوفة')
                                            <li class="nav-item">
                                                <a href="{{ route('itemCategories.softDelete') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'itemCategories' && Request::segment(4) == 'softDelete') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل فئات الاصناف المحزوفة</p>
                                                </a>
                                            </li>
                                        @endcan

                                    </ul>
                                </li>
                            @endcan



                            <!-- وحدات قياس الاصناف Item units  -->
                            @can('وحدات الاصناف')
                                <li class="nav-item has-treeview @if (Request::segment(3) == 'itemUnits') menu-open @endif">
                                    <a href="#" class="nav-link @if (Request::segment(3) == 'itemUnits') active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            وحدات الاصناف
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <!-- Nested Submenu under Suppliers -->
                                    <ul class="nav nav-treeview">
                                        @can('عرض وحدات الاصناف')
                                            <li class="nav-item">
                                                <a href="{{ route('itemUnits.index') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'itemUnits' && Request::segment(4) == '') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل وحدات الاصناف</p>
                                                </a>
                                            </li>
                                        @endcan



                                        @can('عرض وحدات الاصناف المحذوفة')
                                            <li class="nav-item">
                                                <a href="{{ route('itemUnits.softDelete') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'itemUnits' && Request::segment(4) == 'softDelete') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل وحدات الاصناف المحذوفة</p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcan


                            <!-- الاصناف Item  -->

                            <li class="nav-item has-treeview @if (Request::segment(3) == 'items') menu-open @endif">
                                <a href="#" class="nav-link @if (Request::segment(3) == 'items') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        الاصناف
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>

                                <!-- Nested Submenu under Suppliers -->
                                <ul class="nav nav-treeview">
                                    @can('عرض الاصناف')
                                        <li class="nav-item">
                                            <a href="{{ route('items.index') }}"
                                                class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'items' && Request::segment(4) == '') active @endif">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>كل الاصناف</p>
                                            </a>
                                        </li>
                                    @endcan


                                    @can('عرض الاصناف المحزوفة')
                                        <li class="nav-item">
                                            <a href="{{ route('items.softDelete') }}"
                                                class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'items' && Request::segment(4) == 'softDelete') active @endif">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>كل الاصناف المحذوفة</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>


                            <!-- المخازن Stores  -->
                            <li class="nav-item has-treeview @if (Request::segment(3) == 'stores' && Request::segment(3) == 'stores') menu-open @endif">
                                <a href="#" class="nav-link @if (Request::segment(3) == 'stores') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        المخازن
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>

                                <!-- Nested Submenu under Suppliers -->
                                <ul class="nav nav-treeview">
                                    @can('عرض المخازن')
                                        <li class="nav-item">
                                            <a href="{{ route('stores.index') }}"
                                                class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'stores' && Request::segment(4) == '') active @endif">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>كل المخازن</p>
                                            </a>
                                        </li>
                                    @endcan

                                    @can('عرض المخازن المحذوفة')
                                        <li class="nav-item">
                                            <a href="{{ route('stores.softDelete') }}"
                                                class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'stores' && Request::segment(4) == 'softDelete') active @endif">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>كل المخازن المحذوفة </p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>


                            @can('حركات الاصناف')
                                <li class="nav-header"> حركات الاصناف</li>

                                <!-- فئات حركات الاصناف ItemCardMovementCategory  -->
                                @can('فئات حركات الاصناف')
                                    <li class="nav-item has-treeview @if (Request::segment(3) == 'ItemCardMovementCategory') menu-open @endif">
                                        <a href="#" class="nav-link @if (Request::segment(3) == 'ItemCardMovementCategory') active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                فئات حركات الاصناف
                                                <i class="fas fa-angle-left right"></i>
                                            </p>
                                        </a>

                                        <ul class="nav nav-treeview">
                                            @can('عرض فئات حركات الاصناف')
                                                <li class="nav-item">
                                                    <a href="{{ route('ItemCardMovementCategory.index') }}"
                                                        class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'ItemCardMovementCategory' && Request::segment(4) == '') active @endif">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p>كل فئات حركات الاصناف</p>
                                                    </a>
                                                </li>
                                            @endcan


                                            {{-- @can('عرض فئات حركات الاصناف المحذوفة')
                                            <li class="nav-item">
                                                <a href="{{ route('ItemCardMovementCategory.softDelete') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'ItemCardMovementCategory' && Request::segment(4) == 'softDelete') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل فئات حركات الاصناف المحذوفة</p>
                                                </a>
                                            </li>
                                        @endcan --}}
                                        </ul>
                                    </li>
                                @endcan

                                <!-- انواع حركات الاصناف ItemCardMovementTypes  -->
                                @can('انواع حركات الاصناف')
                                    <li class="nav-item has-treeview @if (Request::segment(3) == 'ItemCardMovementTypes') menu-open @endif">
                                        <a href="#" class="nav-link @if (Request::segment(3) == 'ItemCardMovementTypes') active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                انواع حركات الاصناف
                                                <i class="fas fa-angle-left right"></i>
                                            </p>
                                        </a>

                                        <ul class="nav nav-treeview">
                                            <li class="nav-item">
                                                <a href="{{ route('ItemCardMovementTypes.index') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'ItemCardMovementTypes') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل انواع حركات الاصناف</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                @endcan
                            @endcan

                        </ul>
                    </li>
                @endcan






                {{-- حركات النقدية --}}
                @can('حركات النقدية')
                    <li class="nav-item has-treeview @if (Request::segment(1) == 'admin' && Request::segment(2) == 'Cash') menu-open @endif">
                        <a href="#" class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(2) == 'Cash') active @endif">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                حركات النقدية
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            {{-- MONY TRANATIONS حركات النقدية  --}}
                            <li class="nav-item has-treeview @if (Request::segment(3) == 'move_types') menu-open @endif">
                                <a href="#" class="nav-link @if (Request::segment(3) == 'move_types') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        انواع الحركات
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>

                                <ul class="nav nav-treeview">
                                    @can('عرض انواع حركات النقدية')
                                        <li class="nav-item">
                                            <a href="{{ route('move_types.index') }}"
                                                class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'move_types' && Request::segment(4) == '') active @endif">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>كل انواع الحركات</p>
                                            </a>
                                        </li>
                                    @endcan

                                    @can('عرض انواع حركات النقدية المحذوفة')
                                        <li class="nav-item">
                                            <a href="{{ route('move_types.softDelete') }}"
                                                class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'move_types' && Request::segment(4) == 'softDelete') active @endif">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>كل انواع الحركات المحذوفة</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>


                            {{-- حركات تحصيل treasury_transations  --}}
                            @can('حركات تحصيل النقدية')
                                <li class="nav-item has-treeview @if (Request::segment(3) == 'treasury_transations' && Request::segment(4) == '') menu-open @endif">
                                    <a href="#" class="nav-link @if (Request::segment(3) == 'treasury_transations' && Request::segment(4) == '') active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            حركات تحصيل النقدية
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <!-- Nested Submenu under Suppliers -->
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('treasury_transations.index') }}"
                                                class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'treasury_transations' && Request::segment(4) == '') active @endif">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>كل حركات تحصيل النقدية</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endcan




                            {{-- حركات صرف treasury_transations  --}}
                           @can('حركات صرف النقدية')
                                <li class="nav-item has-treeview @if (Request::segment(4) == 'index_pay') menu-open @endif">
                                <a href="#" class="nav-link @if (Request::segment(4) == 'index_pay') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        حركات صرف النقدية
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>

                                <!-- Nested Submenu under Suppliers -->
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('treasury_transations.index_pay') }}"
                                            class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(4) == 'index_pay') active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>كل حركات صرف النقدية</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                           @endcan
                        </ul>
                    </li>
                @endcan












                {{-- الحسابات --}}
                @can('الحسابات')
                    <li class="nav-item has-treeview @if (Request::segment(1) == 'admin' && Request::segment(2) == 'Accounts') menu-open @endif">
                        <a href="#" class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(2) == 'Accounts') active @endif">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                الحسابات
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            {{-- ACCOUNTS TYPES  انواع الحسابات --}}
                            @can('انواع الحسابات')
                                <li class="nav-item has-treeview @if (Request::segment(3) == 'accounts_types') menu-open @endif">
                                    <a href="#" class="nav-link @if (Request::segment(3) == 'accounts_types') active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            انواع الحسابات
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        @can('عرض انواع الحسابات')
                                            <li class="nav-item @if (Request::segment(1) == 'admin' && Request::segment(3) == 'accounts_types' && Request::segment(4) == '') active @endif">
                                                <a href="{{ route('accounts_types.index') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'accounts_types' && Request::segment(4) == '') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل الحسابات</p>
                                                </a>
                                            </li>
                                        @endcan


                                        @can('عرض انواع الحسابات المحذوفة')
                                            <li class="nav-item @if (Request::segment(1) == 'admin' && Request::segment(4) == 'softDelete') active @endif">
                                                <a href="{{ route('accounts_types.softDelete') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'accounts_types' && Request::segment(4) == 'softDelete') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل انواع الحسابات المحذوفة</p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcan


                            <!-- ACCOUNTS   الحسابات  -->
                            @can('الحسابات')
                                <li class="nav-item has-treeview @if (Request::segment(3) == 'accounts' && Request::segment(4) == '') menu-open @endif">
                                    <a href="#" class="nav-link @if (Request::segment(3) == 'accounts' && Request::segment(4) == '') active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            الحسابات
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <!-- Nested Submenu under Suppliers -->
                                    <ul class="nav nav-treeview">
                                        @can('عرض كل الحسابات')
                                            <li class="nav-item @if (Request::segment(1) == 'admin' && Request::segment(3) == 'accounts' && Request::segment(4) == '') active @endif">
                                                <a href="{{ route('accounts.index') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'accounts' && Request::segment(4) == '') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل الحسابات</p>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('عرض كل الحسابات المحذوفة')
                                            <li class="nav-item @if (Request::segment(1) == 'admin' && Request::segment(3) == 'accounts' && Request::segment(4) == 'softDelete') active @endif">
                                                <a href="{{ route('accounts.softDelete') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'accounts' && Request::segment(4) == 'softDelete') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل الحسابات المحذوفة</p>
                                                </a>
                                            </li>
                                        @endcan

                                    </ul>
                                </li>
                            @endcan


                            <!-- suppliersCategory   اقسام الموردين  -->
                            @can('اقسام الموردين')
                                <li class="nav-item has-treeview @if (Request::segment(3) == 'suppliersCategory') menu-open @endif">
                                    <a href="#" class="nav-link @if (Request::segment(3) == 'suppliersCategory') active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            اقسام الموردين
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        @can('عرض اقسام الموردين')
                                            <li class="nav-item @if (Request::segment(1) == 'admin' && Request::segment(3) == 'suppliersCategory' && Request::segment(4) == '') active @endif">
                                                <a href="{{ route('suppliersCategory.index') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'suppliersCategory' && Request::segment(4) == '') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل اقسام الموردين</p>
                                                </a>
                                            </li>
                                        @endcan


                                        @can('عرض اقسام الموردين المحذوفة')
                                            <li class="nav-item @if (Request::segment(1) == 'admin' && Request::segment(3) == 'suppliersCategory' && Request::segment(4) == 'softDelete') active @endif">
                                                <a href="{{ route('suppliersCategory.softDelete') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'suppliersCategory' && Request::segment(4) == 'softDelete') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل اقسام الموردين المحذوفة</p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcan


                            <!-- suppliers    الموردين  -->
                            @can('الموردين')
                                <li class="nav-item has-treeview @if (Request::segment(3) == 'suppliers' && Request::segment(4) == '') menu-open @endif">
                                    <a href="#" class="nav-link @if (Request::segment(3) == 'suppliers' && Request::segment(4) == '') active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            الموردين
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        @can('عرض كل الموردين')
                                            <li class="nav-item @if (Request::segment(1) == 'admin' && Request::segment(3) == 'suppliers' && Request::segment(4) == '') active @endif">
                                                <a href="{{ route('suppliers.index') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'suppliers' && Request::segment(4) == '') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل الموردين</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('عرض كل الموردين المحذوفة')
                                            <li class="nav-item @if (Request::segment(1) == 'admin' && Request::segment(3) == 'suppliers' && Request::segment(4) == 'softDelete') active @endif">
                                                <a href="{{ route('suppliers.softDelete') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'suppliers' && Request::segment(4) == 'softDelete') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>عرض كل الموردين المحذوفة</p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcan



                            <!-- customers    العملاء  -->
                            @can('العملاء')
                                <li class="nav-item has-treeview @if (Request::segment(3) == 'customers' && Request::segment(4) == '') menu-open @endif">
                                    <a href="#" class="nav-link @if (Request::segment(3) == 'customers' && Request::segment(4) == '') active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            العملاء
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        @can('عرض كل العملاء')
                                            <li class="nav-item @if (Request::segment(1) == 'admin' && Request::segment(3) == 'customers' && Request::segment(4) == '') active @endif">
                                                <a href="{{ route('customers.index') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'customers' && Request::segment(4) == '') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل العملاء</p>
                                                </a>
                                            </li>
                                        @endcan


                                        @can('عرض العملاء المحذوفين')
                                            <li class="nav-item @if (Request::segment(1) == 'admin' && Request::segment(3) == 'customers' && Request::segment(4) == 'softDelete') active @endif">
                                                <a href="{{ route('customers.softDelete') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'customers' && Request::segment(4) == 'softDelete') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل العملاء المحذوفة</p>
                                                </a>
                                            </li>
                                        @endcan

                                    </ul>
                                </li>
                            @endcan




                            <!-- servants    المناديب  -->
                            @can('المناديب')
                                <li class="nav-item has-treeview @if (Request::segment(3) == 'servants' && Request::segment(4) == '') menu-open @endif">
                                    <a href="#" class="nav-link @if (Request::segment(3) == 'servants' && Request::segment(4) == '') active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            المناديب
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        @can('عرض كل المناديب')
                                            <li class="nav-item @if (Request::segment(1) == 'admin' && Request::segment(3) == 'servants' && Request::segment(4) == '') active @endif">
                                                <a href="{{ route('servants.index') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'servants' && Request::segment(4) == '') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل المناديب</p>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('عرض المناديب المحذوفين')
                                            <li class="nav-item @if (Request::segment(1) == 'admin' && Request::segment(3) == 'servants' && Request::segment(4) == 'softDelete') active @endif">
                                                <a href="{{ route('servants.softDelete') }}"
                                                    class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'servants' && Request::segment(4) == 'softDelete') active @endif">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>كل المناديب المحذوفين</p>
                                                </a>
                                            </li>
                                        @endcan

                                    </ul>
                                </li>
                            @endcan



                        </ul>
                    </li>
                @endcan












                <!-- ACCOUNTS TYPES و الموظفين الصلاحيات  -->
                <li class="nav-item has-treeview @if (Request::segment(1) == 'admin' && Request::segment(2) == 'Permissions') menu-open @endif">
                    <a href="#" class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(2) == 'Permissions') active @endif">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            الصلاحيات و الموظفين
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">


                        @can('الموظفين')
                            <li class="nav-item has-treeview @if (Request::segment(3) == 'emoloyees' && Request::segment(4) == '') menu-open @endif">
                                <a href="#" class="nav-link @if (Request::segment(3) == 'emoloyees' && Request::segment(4) == '') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        الموظفين
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>

                                <ul class="nav nav-treeview">
                                    @can('عرض كل الموظفين')
                                        <li class="nav-item @if (Request::segment(1) == 'admin' && Request::segment(3) == 'emoloyees' && Request::segment(4) == '') active @endif">
                                            <a href="{{ route('emoloyees.index') }}"
                                                class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'emoloyees' && Request::segment(4) == '') active @endif">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p> الموظفين</p>
                                            </a>
                                        </li>
                                    @endcan

                                    @can('عرض الموظفين المحذوفة')
                                        <li class="nav-item @if (Request::segment(1) == 'admin' && Request::segment(3) == 'emoloyees' && Request::segment(4) == 'softDelete') active @endif">
                                            <a href="{{ route('emoloyees.softDelete') }}"
                                                class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'emoloyees' && Request::segment(4) == 'softDelete') active @endif">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>عرض كل الموظفين المحذوفة</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan











                        <li class="nav-item @if (Request::segment(1) == 'admin' && Request::segment(2) == 'Permissions' && Request::segment(3) == '') active @endif">
                            <a href="{{ route('permissions.index') }}"
                                class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == '') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p> الصلاحيات</p>
                            </a>
                        </li>


                        <li class="nav-item @if (Request::segment(1) == 'admin' && Request::segment(2) == 'Permissions' && Request::segment(3) == 'roles') active @endif">
                            <a href="{{ route('roles.index') }}"
                                class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(3) == 'roles') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p> الادوار</p>
                            </a>
                        </li>
                    </ul>
                </li>

















                <!-- INVOICES   الفواتير  -->
                @can('الفواتير')
                    <li class="nav-item has-treeview @if (Request::segment(1) == 'admin' && Request::segment(2) == 'Invoices') menu-open @endif">
                        <a href="#" class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(2) == 'Invoices') active @endif">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                الفواتير
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            {{-- اقسام الفواتير material-types --}}
                            <li class="nav-item has-treeview @if (Request::segment(3) == 'matrial_types') menu-open @endif">
                                <a href="#" class="nav-link @if (Request::segment(3) == 'matrial_types') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        اقسام الفواتير
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>

                                <!-- Nested Submenu under Suppliers -->
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('matrial_types.index') }}" class="nav-link">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>كل اقسام الفواتير </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            {{-- فواتير المشتريات  --}}
                            @can('عرض فواتير المشتريات')
                                <li class="nav-item has-treeview @if (Request::segment(3) == 'purchaseOrders') menu-open @endif">
                                    <a href="#" class="nav-link @if (Request::segment(3) == 'purchaseOrders') active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            فواتير المشتريات
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <!-- Nested Submenu under Suppliers -->
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('purchaseOrders.index') }}" class="nav-link {{ request()->routeIs('purchaseOrders.index') ? 'active' : '' }}">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>كل فواتير المشتريات </p>
                                            </a>
                                        </li>

                                        <li class="nav-item {{ request()->routeIs('purchaseOrders.index_returns') ? 'active' : '' }}">
                                            <a href="{{ route('purchaseOrders.index_returns') }}" class="nav-link {{ request()->routeIs('purchaseOrders.index_returns') ? 'active' : '' }}">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>كل فواتير مرتجع المشتريات</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                             @endcan

                            {{-- فواتير المبيعات   --}}
                            @can('عرض فواتير المبيعات')
                                <li class="nav-item has-treeview @if (Request::segment(3) == 'salesOrder') menu-open @endif">
                                    <a href="#" class="nav-link @if (Request::segment(3) == 'salesOrder') active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            فواتير المبيعات
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <!-- Nested Submenu under Suppliers -->
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('salesOrder.index') }}" class="nav-link">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>كل فواتير المبيعات </p>
                                            </a>
                                        </li>

                                         <li class="nav-item {{ request()->routeIs('sales.index_returns') ? 'active' : '' }}">
                                            <a href="{{ route('salesOrder.index_returns') }}" class="nav-link {{ request()->routeIs('salesOrder.index_returns') ? 'active' : '' }}">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>كل فواتير مرتجع المبيعات</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan







                <!-- REBORTS   التقارير  -->
                <li class="nav-item has-treeview @if (Request::segment(1) == 'admin' && Request::segment(2) == 'Reborts') menu-open @endif">
                    <a href="#" class="nav-link @if (Request::segment(1) == 'admin' && Request::segment(2) == 'Reborts') active @endif">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            التقارير
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        {{-- تفارير الحسابات accounts REBORTS  - --}}
                        <li class="nav-item has-treeview @if (Request::segment(3) == 'suppliers') menu-open @endif">
                            <a href="#" class="nav-link @if (Request::segment(3) == 'suppliers') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    كشف حساب
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>

                            <!-- Nested Submenu under Suppliers -->
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('Reborts.suppliers.index') }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p> كشف حساب الموردين </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('Reborts.customers.index') }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p> كشف حساب العملاء </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('Reborts.servants.index') }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p> كشف حساب المناديب </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('Reborts.employees.index') }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p> كشف حساب الموظفين </p>
                                    </a>
                                </li>
                            </ul>
                        </li>


                         {{-- تفارير المخازن STORES REBORTS  - --}}
                        <li class="nav-item has-treeview @if (Request::segment(3) == 'suppliers') menu-open @endif">
                            <a href="#" class="nav-link @if (Request::segment(3) == 'suppliers') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    المخازن و الاصناف
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>

                            <!-- Nested Submenu under Suppliers -->
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('Reborts.items.index') }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>تقارير الاصناف</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('Reborts.stores.index') }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>جرد المخازن</p>
                                    </a>
                                </li>
                            </ul>
                        </li>



                    </ul>
                </li>






            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
