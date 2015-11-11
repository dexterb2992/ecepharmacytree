<?php use Illuminate\Support\Str; ?>
@extends('admin.layouts.template')

@section('title', 'Dexter Gwapo')

@section('content')

	<!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>150</h3>
            <p>New Orders</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3>53<sup style="font-size: 20px">%</sup></h3>
            <p>Bounce Rate</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>44</h3>
            <p>User Registrations</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="inner">
            <h3>65</h3>
            <p>Unique Visitors</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
            <li><a href="#sales-chart" data-toggle="tab">Donut</a></li>
            <li class="pull-left header"><i class="fa fa-inbox"></i> Sales</li>
          </ul>
          <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>
            <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div>
          </div>
        </div><!-- /.nav-tabs-custom -->

        <!-- Chat box -->
        <div class="box box-success">
          <div class="box-header">
            <i class="fa fa-comments-o"></i>
            <h3 class="box-title">Chat</h3>
            <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
              <div class="btn-group" data-toggle="btn-toggle" >
                <button type="button" class="btn btn-default btn-sm active"><i class="fa fa-square text-green"></i></button>
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-square text-red"></i></button>
              </div>
            </div>
          </div>
          <div class="box-body chat" id="chat-box">
            <!-- chat item -->
            <div class="item">
              <img src="/dist/img/user4-128x128.jpg" alt="user image" class="online" />
              <p class="message">
                <a href="#" class="name">
                  <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> 2:15</small>
                  Mike Doe
                </a>
                I would like to meet you to discuss the latest news about
                the arrival of the new theme. They say it is going to be one the
                best themes on the market
              </p>
              <div class="attachment">
                <h4>Attachments:</h4>
                <p class="filename">
                  Theme-thumbnail-image.jpg
                </p>
                <div class="pull-right">
                  <button class="btn btn-primary btn-sm btn-flat">Open</button>
                </div>
              </div><!-- /.attachment -->
            </div><!-- /.item -->
            <!-- chat item -->
            <div class="item">
              <img src="/dist/img/user3-128x128.jpg" alt="user image" class="offline" />
              <p class="message">
                <a href="#" class="name">
                  <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> 5:15</small>
                  Alexander Pierce
                </a>
                I would like to meet you to discuss the latest news about
                the arrival of the new theme. They say it is going to be one the
                best themes on the market
              </p>
            </div><!-- /.item -->
            <!-- chat item -->
            <div class="item">
              <img src="/dist/img/user2-160x160.jpg" alt="user image" class="offline" />
              <p class="message">
                <a href="#" class="name">
                  <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> 5:30</small>
                  Susan Doe
                </a>
                I would like to meet you to discuss the latest news about
                the arrival of the new theme. They say it is going to be one the
                best themes on the market
              </p>
            </div><!-- /.item -->
          </div><!-- /.chat -->
          <div class="box-footer">
            <div class="input-group">
              <input class="form-control" placeholder="Type message..." />
              <div class="input-group-btn">
                <button class="btn btn-success"><i class="fa fa-plus"></i></button>
              </div>
            </div>
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