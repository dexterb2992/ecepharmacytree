<!-- Left side column. contains the logo and sidebar -->
@if(Auth::check())
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url('images/128x128/'.Auth::user()->photo) }}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->fname." ".Auth::user()->lname }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <?php 
            $currentURI = pathinfo(Request::url());
        ?>
        <form action="/search/products" method="get" class="sidebar-form" id="sidebar_search_form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search for products..." />
                <span class="input-group-btn">
                    <a class="btn-flat btn" href="javascript:void(0);" id="btn_sidebar_search_form" name="search">
                        <i class="fa fa-search"></i>
                    </a>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <!-- Note: Make sure to use a valid url for anchors' href inside the <li> tag -->
            <li class="{!! Route::is('dashboard') ? 'active':'treeview' !!}">
                  <a href="{{ route('dashboard').'/' }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="{!! Route::is('Members::index') ? 'active':'treeview' !!}" >
                <a href="{{ route('Members::index') }}">
                    <i class="fa fa-users"></i> <span id="sidebar_members">Members</span>
                </a>
            </li>

            @if(Auth::user()->isAdmin() || Auth::user()->isBranchAdmin())
            <li class="{!! Route::is('employees') ? 'active':'treeview' !!}">
                <a href="{{ route('employees') }}">
                    <i class="fa fa-users"></i> <span id="sidebar_employees">Employees</span>
                </a>
            </li>

            <!-- <li class="treeview">
                <a href="{{ route('prescription_approval') }}">
                    <i class="fa fa-file-text-o"></i> <span id="sidebar_employees">Prescriptions</span>
                </a>
            </li> -->

            <li class="{!! Route::is('groups') ? 'active':'treeview' !!}">
                <a href="{{ route('groups') }}">
                    <i class="fa-bar-chart fa"></i> <span id="sidebar_product_groups">Product Groups</span>
                </a>
            </li>
            @endif

            <li class="{!! Route::is('Products::index') ? 'active':'treeview' !!}">
                <a href="{{ route('Products::index') }}">
                    <i class="fa-stethoscope fa"></i> <span id="sidebar_products">Products</span>
                </a>
            </li>

           

            <li class="{!! Route::is('Promo::index') ? 'active':'treeview' !!}">
                <a href="{{ route('Promo::index') }}">
                    <i class="glyphicon glyphicon-tags"></i> <span id="sidebar_promos">Promo/Discounts</span>
                </a>
            </li>

            <li class="{!! Route::is('Inventory::index') ? 'active':'treeview' !!}">
                <a href="{{ route('Inventory::index') }}">
                    <i class="fa-cubes fa"></i> <span id="sidebar_stock_items">Stock Items</span>
                </a>
            </li>

            <li class="{!! Route::is('orders') ? 'active':'treeview' !!}">
                <a href="{{ route('orders') }}">
                    <i class="fa fa-shopping-cart"></i> <span id="sidebar_orders">Orders</span>
                </a>
            </li>

            @if(Auth::user()->isAdmin())
            <li class="{!! Route::is('doctors') ? 'active':'treeview' !!}">
                <a href="{{ route('doctors') }}">
                    <i class="fa-user-md fa"></i><span id="sidebar_doctors">Doctors</span>
                </a>
            </li>

            <li class="{!! Route::is('clinics') ? 'active':'treeview' !!}">
                <a href="{{ route('clinics') }}">
                    <i class="fa-hospital-o fa"></i><span id="sidebar_clinics">Clinics</span>
                </a>
            </li>
            
            <li class="{!! Route::is('Branches::index') ? 'active':'treeview' !!}">
                <a href="{{ route('Branches::index') }}">
                    <i class="fa fa-building"></i> <span id="sidebar_branches">Branches</span>
                </a>
            </li>

            <li class="{!! Route::is('Affiliates::index') ? 'active':'treeview' !!}">
                <a href="{{ route('Affiliates::index') }}">
                    <i class="fa fa-sitemap"></i> <span>Affiliates</span>
                </a>
            </li>

            <li class="{!! Route::is('Settings::index') ? 'active':'treeview' !!}">
                <a href="{{ route('Settings::index') }}">
                    <i class="fa fa-sliders"></i> <span>Settings</span>
                </a>
            </li>
            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
@endif