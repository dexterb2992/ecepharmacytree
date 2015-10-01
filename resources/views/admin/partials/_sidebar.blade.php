<!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
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
            <li class="active">
              <a href="{{ route('dashboard') }}">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a>
            </li>

            <li class="treeview">
              <a href="{{ route('Members::index') }}">
                <i class="fa fa-users"></i> <span>Members</span>
              </a>
            </li>

            <li class="treeview">
              <a href="{{ route('Products::index') }}">
                <i class="fa-stethoscope fa"></i> <span>Products</span>
              </a>
            </li>

            <li class="treeview">
              <a href="{{ route('doctors') }}">
                <i class="fa-user-md fa"></i><span>Doctors</span>
              </a>
            </li>

            <li class="treeview">
              <a href="{{ route('Promo::index') }}">
                <i class="glyphicon glyphicon-tags"></i> <span>Promo/Discounts</span>
              </a>
            </li>


            <li class="treeview">
              <a href="{{ route('Inventory::index') }}">
                <i class="fa-cubes fa"></i> <span>Inventory</span>
              </a>
            </li>

            <li class="treeview">
              <a href="#/orders">
                <i class="fa fa-shopping-cart"></i> <span>Orders</span>
              </a>
            </li>

            <li class="treeview">
              <a href="#/sales">
                <i class="fa fa-pie-chart"></i> <span>Sales</span>
              </a>
            </li>

            <li class="treeview">
              <a href="#/payments">
                <i class="fa fa-money"></i> <span>Payments</span>
              </a>
            </li>

            <li class="treeview">
              <a href="#/manage-delivery">
                <i class="fa fa-truck"></i> <span>Manage Delivery</span>
              </a>
            </li>

            <li class="treeview">
              <a href="{{ route('Branches::index') }}">
                <i class="fa fa-building"></i> <span>Branches</span>
              </a>
            </li>

            <li class="treeview">
              <a href="{{ route('Affiliates::index') }}">
                <i class="fa fa-bar-chart"></i> <span>Affiliates</span>
              </a>
            </li>
             
             <li class="treeview">
              <a href="{{ route('Settings::index') }}">
                <i class="fa fa-sliders"></i> <span>Settings</span>
              </a>
            </li>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>