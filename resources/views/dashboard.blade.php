<?php use Illuminate\Support\Str; ?>
@extends('admin.layouts.template')

@section('content')

	<!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>{{ get_new_orders() }}</h3>
            <p>New Orders</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
          <a href="{{ route('orders') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3>{{ get_all_products() }}</h3>
            <p>Total Products</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="{{ route('Products::index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>{{ get_all_users() }}</h3>
            <p>User Registrations</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="{{ route('Members::index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="inner">
            <h3>{{ get_all_doctors() }}</h3>
            <p>Registered Doctors</p>
          </div>
          <div class="icon">
            <i class="fa fa-user-md"></i>
          </div>
          <a href="{{ route('doctors') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div><!-- ./col -->
    </div><!-- /.row -->
    <!-- Main row -->
    <div class="row">
      <!-- Left col -->
      <section class="col-lg-7 connectedSortable">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="nav-tabs-custom">
          <!-- Tabs within a box -->
          <ul class="nav nav-tabs pull-right">
            <li class="active"><a href="#revenue-chart" data-toggle="tab">Area</a></li>
            <li class="pull-left header"><i class="fa  fa-shopping-cart"></i> Sales</li>
          </ul>
          <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>
          </div>
        </div><!-- /.nav-tabs-custom -->

        <!-- Chat box -->
        <div class="box box-success">
          <div class="box-header">
            <i class="fa fa-comments-o"></i>
            <h3 class="box-title">News Feed</h3>
          </div>
          <div class="box-body chat" id="chat-box">
            <div class="fb-page" data-href="https://www.facebook.com/ECE-Marketing-592693060878683" data-tabs="timeline" data-small-header="false" data-width="500" data-adapt-container-width="false" data-hide-cover="false" data-show-facepile="true"></div>
          </div><!-- /.chat -->
          <div class="box-footer">
          </div>
        </div><!-- /.box (chat box) -->
      </section><!-- /.Left col -->
      <!-- right col (We are only adding the ID to make the widgets sortable)-->
      <section class="col-lg-5 connectedSortable">

        <!-- PRODUCT LIST -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Recently Added Products</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div><!-- /.box-header -->
          <div class="box-body">
            <ul class="products-list product-list-in-box">

              @foreach($recently_added_products as $product)
                <li class="item">
                  <div class="product-img">
                    <img src="{{ !empty($product->galleries[0]) ? url('images/50x50/'.$product->galleries[0]->filename) : url('images/50x50/nophoto.jpg') }}" alt="Product Image" />
                  </div>
                  <div class="product-info">
                    <a href="{{ route('Products::index').'?q='.$product->name }}" class="product-title">
                      {{ $product->name }}
                      <span class="label label-success pull-right">&#x20B1; {{ $product->price." / ".$product->unit }}</span>
                    </a>
                    <span class="product-description">
                      {!! Str::limit(rn2br($product->description), 150) !!}
                    </span>
                  </div>
                </li><!-- /.item -->
              @endforeach
            </ul>
          </div><!-- /.box-body -->
          <div class="box-footer text-center">
            <a href="{{ route('Products::index') }}" class="uppercase">View All Products</a>
          </div><!-- /.box-footer -->
        </div><!-- /.box -->

      </section><!-- right col -->
    </div><!-- /.row (main row) -->
@stop

@section('scripts')
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5&appId=1393062867654378";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>
@stop