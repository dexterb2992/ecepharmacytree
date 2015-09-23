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
              <p>Alexander Pierce</p>
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
              <a href="#">
                <i class="fa-sitemap fa"></i> <span>Categories</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('ProductCategory::index') }}"><i class="fa fa-medkit"></i> Product Categories</a></li>
                <li><a href="{{ route('DoctorSpecialty::index') }}"><i class="fa fa-stethoscope"></i> Doctor Specialties</a></li>
              </ul>
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
              <a href="#/affiliates">
                <i class="fa fa-bar-chart"></i> <span>Affiliates</span>
              </a>
            </li>
            
            <li class="treeview">
              <a href="#">
                <i class="fa fa-folder"></i> <span>Examples</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
                <li><a href="pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>
                <li><a href="pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>
                <li><a href="pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
                <li><a href="pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
                <li><a href="pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
                <li><a href="pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
              </ul>
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