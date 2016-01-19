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
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..." />
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <!-- Note: Make sure to use a valid url for anchors' href inside the <li> tag -->
            <li class="active">
                  <a href="{{ route('dashboard').'/' }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="treeview">
                <a href="{{ route('Members::index') }}">
                    <i class="fa fa-users"></i> <span id="sidebar_members">Members</span>
                </a>
            </li>

            @if(Auth::user()->isAdmin() || Auth::user()->isBranchManager())
            <li class="treeview">
                <a href="{{ route('employees') }}">
                    <i class="fa fa-users"></i> <span id="sidebar_employees">Employees</span>
                </a>
            </li>

            <li class="treeview">
                <a href="{{ route('prescription_approval') }}">
                    <i class="fa fa-file-text-o"></i> <span id="sidebar_employees">Prescriptions</span>
                </a>
            </li>

            <li class="treeview">
                <a href="{{ route('groups') }}">
                    <i class="fa-bar-chart fa"></i> <span id="sidebar_product_groups">Product Groups</span>
                </a>
            </li>
            @endif

            <li class="treeview">
                <a href="{{ route('Products::index') }}">
                    <i class="fa-stethoscope fa"></i> <span id="sidebar_products">Products</span>
                </a>
            </li>

            <li class="treeview">
                <a href="{{ route('doctors') }}">
                    <i class="fa-user-md fa"></i><span id="sidebar_doctors">Doctors</span>
                </a>
            </li>

            <li class="treeview">
                <a href="{{ route('Promo::index') }}">
                    <i class="glyphicon glyphicon-tags"></i> <span id="sidebar_promos">Promo/Discounts</span>
                </a>
            </li>

            <li class="treeview">
                <a href="{{ route('Inventory::index') }}">
                    <i class="fa-cubes fa"></i> <span id="sidebar_stock_items">Stock Items</span>
                </a>
            </li>

            <li class="treeview">
                <a href="{{ route('orders') }}">
                    <i class="fa fa-shopping-cart"></i> <span id="sidebar_orders">Orders</span>
                </a>
            </li>

            @if(Auth::user()->isAdmin())
            <li class="treeview">
                <a href="{{ route('Branches::index') }}">
                    <i class="fa fa-building"></i> <span id="sidebar_branches">Branches</span>
                </a>
            </li>

            <li class="treeview">
                <a href="{{ route('Affiliates::index') }}">
                    <i class="fa fa-sitemap"></i> <span>Affiliates</span>
                </a>
            </li>

            <li class="treeview">
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