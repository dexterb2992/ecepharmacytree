@extends('admin.layouts.template')
@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Clinics</h3><br/>
                <button class="btn-info btn pull-right add-edit-btn" data-modal-target="#modal-add-edit-clinic" data-target="#form_add_edit_clinic" data-action="create" data-title="clinic">
                    <i class="fa-plus fa"></i> Add New
                </button>
            </div><!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered table-hover datatable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact Number</th>
                            <th>Full Address</th>
                            <th>Doctors</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clinics->items() as $clinic )
                            <tr data-id="{{ $clinic->id }}">
                               <td>{{ $clinic->name }}</td>
                               <td>{{ $clinic->contact_no }}</td>
                                <td>{{ $clinic->full_address() }}</td>
                                <td>
                                    @foreach ($clinic->doctors as $doctor)
                                        {{$doctor->pivot->created_at}}
                                    @endforeach
                                </td>
                                <td>
                                    <div class="tools">
                                        <a href="javascript:void(0);" class="add-edit-btn" data-action="edit" data-modal-target="#modal-add-edit-clinic" data-title="product info" data-target="#form_add_edit_clinic" data-id="{{ $clinic->id }}" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <span class="action-icon remove-clinic" data-action="remove" data-title="clinic" data-urlmain="/clinics/" data-id="{{ $clinic->id }}"><i class="fa fa-trash-o"></i></span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! render_pagination($clinics) !!}
            </div><!-- /.box-body -->
        </div><!-- /.box -->

        <!-- Modal for Create/Edit clinic -->
        <div class="modal" id="modal-add-edit-clinic">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- form start -->
                    <form role="form" id="form_add_edit_clinic" data-mode="create" method="post" action="/clinics/create" data-urlmain="/clinics/">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add new Clinic</h4>
                        </div>
                        <div class="modal-body">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    
                            <div class="form-group">
                                <label for="name">Clinic Name<red>*</red></label>
                                <input type="text" class="form-control" id="name" placeholder="Clinic Name" name="name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="name">Contact Number<red>*</red></label>
                                <input type="text" class="form-control" id="contact_no" placeholder="Contact Number" name="contact_no" required>
                            </div>

                            
                            <div class="form-group">
                                <label for="address_province">Region<red>*</red></label>
                                <select class="select2" name="region_id" id="address_region">
                                    <option value="0">- Select Region - </option>
                                    @foreach($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->name.' ('.$region->code.')' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="address_province">Province<red>*</red></label>
                                <select class="select2" id="address_province" name="province_id" required>
                                    <option value="0">- Select Province -</option>
                                </select>
                                <!-- <input type="text" class="form-control" id="address_province" placeholder="Province" name="address_province" required> -->
                            </div>

                            <div class="form-group">
                                <label for="address_city_municipality">Municipality<red>*</red></label>
                                <select class="select2" name="municipality_id" id="address_city_municipality">
                                    <option value="0">- Select Municipality -</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="address_barangay">Barangay<red>*</red></label>
                                <select class="select2" name="barangay_id" id="address_barangay">
                                    <option value="0">- Select Barangay -</option>
                                </select>
                            </div>

                            <div class="form-group"> 
                                <label for="additional_address">Additional address (<i>Be more specific with the address as you can as possible</i>)</label>
                                <input type="text" class="form-control" id="additional_address" placeholder="Street" name="additional_address" required>
                            </div>

                            <div class="form-group">
                                <label for="additional_address">Set the place (Please drag the marker to the appropriate location of the branch)</label>
                                <div id="map" style="height:300px;width:100%;">Google Map</div>
                                <input type="hidden" name="google_lat" id="google_lat">
                                <input type="hidden" name="google_lng" id="google_lng">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="submit">Save changes</button>
                        </div>
                    </div><!-- /.modal-content -->
                </form><!-- /form -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        

    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('scripts')
    <!-- Google map scripts -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB1RD66hs2KpuH1tHf5MDxScCTCBVM9uk8&callback=initMap"></script>

    <script type="text/javascript">
        function initMap() {

            if($('#additional_address').val() == "" && $('#address_barangay').val() == "0") 
                $('#map').text('Fill up the address first');
            else {
                var region = $("#address_region option:selected").text();
                var province = $("#address_province option:selected").text();
                var municipality = $("#address_city_municipality option:selected").text();
                var barangay = $("#address_barangay option:selected").text();
                var additional_address = $("#additional_address").val();

                region = region.substring(0, (region.indexOf("(", 0) != -1) ? region.indexOf("(", 0) : region.length);

                province = province.substring(0, (province.indexOf("(", 0) != -1) ? province.indexOf("(", 0) : province.length);

                municipality = municipality.substring(0, (municipality.indexOf("(", 0) != -1) ? municipality.indexOf("(", 0) : municipality.length);

                barangay = barangay.substring(0, (barangay.indexOf("(", 0) != -1) ? barangay.indexOf("(", 0) : barangay.length);
                
                var url_geocode = 'https://maps.googleapis.com/maps/api/geocode/json?address='+additional_address+", "+barangay+", "+municipality+", "+province+", "+region+'&key=AIzaSyB1RD66hs2KpuH1tHf5MDxScCTCBVM9uk8';

                $.ajax({
                    url: url_geocode,
                    type: 'get',
                    dataType: 'json'
                }).done(function (data){
                    console.log(typeof(data));
                    if( typeof(data) == 'object' ){

                        var data_lat = data.results[0].geometry.location.lat;
                        var data_lng = data.results[0].geometry.location.lng;

                        var map = new google.maps.Map(document.getElementById('map'), {
                        center: {lat: data_lat, lng: data_lng},
                        zoom: 15
                        });

                        var marker = new google.maps.Marker({
                            position: {lat: data_lat, lng: data_lng},
                            map: map,
                            draggable: true,
                            title: 'Please set the marker on the location of the store'
                        });

                        marker.addListener('dragend', function(e) {
                            placeMarkerAndPanTo(e.latLng, map, marker);
                        });
                    }
                });
            }
        }

        function placeMarkerAndPanTo(latLng, map, marker) {
            marker.setPosition(latLng);
            map.panTo(latLng);

            $('#google_lat').val(latLng.lat);
            $('#google_lng').val(latLng.lng);
        }

    </script>
    @stop