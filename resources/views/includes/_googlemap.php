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
