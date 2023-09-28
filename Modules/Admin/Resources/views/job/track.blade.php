@extends('admin::layouts.master')
@section('admin::content')
<style>
       /* Set the size of the div element that contains the map */
      #map {
        height: 400px;  /* The height is 400 pixels */
        width: 100%;  /* The width is the width of the web page */
       }
    </style>
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
            <h1 class="h3 m-0">Track</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Job List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Track</li>
                    </ol>
                </nav>
                <!-- <a href="{{ url('admin/service/add') }}" class="btn btn-primary " style="float:right;">Add Service</a> -->
            </div>
            <!-- <div class="col-md-12 text-right mt-3">
           <a href="{{ url('admin/tax/add') }}" class="btn btn-sm btn-primary" >Add Taxes</a>
           </div> -->
        </div>
    </div>
    
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12 mb-4">
            <div id="map"></div>
             <div id="msg"></div>

            <?php
            
               $user_lat = $data->user->lat ?? '';
               $user_long = $data->user->long ?? '';
              $provider_lat = $data->assine->user->lat ?? '';
               $provider_long = $data->assine->user->long ?? '';
            ?>
            </div>
            <!-- <div class="col-sm-12 copyright">
                <p>2000 - {{date('Y')}} ©  <a href="#">Eventrol</a></p>
            </div> -->
        </div>
    </div>
</div>  
    



@endsection
@section('admin::custom_js')
<script type="text/javascript">
// var locations = [
//     ['Customer',{{$data->user->lat}}, {{$data->user->long}}],
//     ['Service Provider', {{$data->assine->user->lat}}, {{$data->assine->user->long}}]
//   ];
var localtionData = "";
var roomsCustomerLat = "";
var roomsCustomerLong = "";
var roomsCroviderLat = "";
var roomsCroviderLong = "";
function get_fb(){
    $.ajax({
        type: "GET",
        headers:{
                    _token:'{{ csrf_token() }}'
                },
        url: "{{url('admin/job/reyaltime',$id)}}",
        async: false,
        success: function (rooms) { 
            roomsCustomerLat = rooms.customer_lat ;
            roomsCustomerLong = rooms.customer_long ;
            roomsCroviderLat = rooms.provider_lat ;
            roomsCroviderLong = rooms.provider_long ;
        }
    });
}

setInterval( function(){ 
    get_fb();
}, 1000 );

setInterval( function(){ 
    //initMap();
    deleteMarkers();
    addMarker();

}, 10000 );


// In the following example, markers appear when the user clicks on the map.
// The markers are stored in an array.
// The user can then click an option to hide, show or delete the markers.
let map;
let markers = [];

function initMap() {
    if(roomsCustomerLat == ""){
        roomsCustomerLat = {{$data->user->lat}};
        roomsCustomerLong = {{$data->user->long}};
        roomsCroviderLat = {{$data->assine->user->lat}};
        roomsCroviderLong = {{$data->assine->user->long}};
    }
    const center = {lat: parseFloat(roomsCustomerLat), lng: parseFloat(roomsCustomerLong)};

  const options = {zoom: 14, scaleControl: true, center: center};
    var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
    map = new google.maps.Map(
        document.getElementById('map'), options);
  addMarker(center);
}
var line = '';
// Adds a marker to the map and push to the array.
function addMarker(location) {
    const customer = {lat: parseFloat(roomsCustomerLat), lng:parseFloat(roomsCustomerLong)};
    const provider = {lat: parseFloat(roomsCroviderLat), lng: parseFloat(roomsCroviderLong)};
    // The markers for The Dakota and The Frick Collection
    var mk1 = new google.maps.Marker({position: customer, map: map,label: {text: 'Customer',color: 'green',}});
    var mk2 = new google.maps.Marker({position: provider, map: map,label:{text: 'Provider',color: 'blue',}});
     line = new google.maps.Polyline({path: [provider, customer], map: map});
  markers.push(mk1);
  markers.push(mk2);
}

// Sets the map on all markers in the array.
function setMapOnAll(map) {
  for (let i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setMapOnAll(null);
}

// Shows any markers currently in the array.
function showMarkers() {
  setMapOnAll(map);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
  clearMarkers();
  markers = [];
   line.getPath().removeAt();
//  console.log(line);
}


// function haversine_distance(mk1, mk2) {
//       var R = 3958.8; // Radius of the Earth in miles
//       var rlat1 = mk1.position.lat() * (Math.PI/180); // Convert degrees to radians
//       var rlat2 = mk2.position.lat() * (Math.PI/180); // Convert degrees to radians
//       var difflat = rlat2-rlat1; // Radian difference (latitudes)
//       var difflon = (mk2.position.lng()-mk1.position.lng()) * (Math.PI/180); // Radian difference (longitudes)

//       var d = 2 * R * Math.asin(Math.sqrt(Math.sin(difflat/2)*Math.sin(difflat/2)+Math.cos(rlat1)*Math.cos(rlat2)*Math.sin(difflon/2)*Math.sin(difflon/2)));
//       return d;
//     }

// var map;
// function initMap() {
//     if(roomsCustomerLat == ""){
//         roomsCustomerLat = {{$data->user->lat}};
//         roomsCustomerLong = {{$data->user->long}};
//         roomsCroviderLat = {{$data->assine->user->lat}};
//         roomsCroviderLong = {{$data->assine->user->long}};
//     }

//         setInterval( function(){ 
//             console.log(roomsCustomerLat);
//             roomsCustomerLong;
//             roomsCroviderLat;
//             console.log(roomsCroviderLong);
//         }, 20000 );
   
//     // The map, centered on Central Park
//     const center = {lat: parseFloat(roomsCustomerLat), lng: parseFloat(roomsCustomerLong)};
    
//     const options = {zoom: 14, scaleControl: true, center: center};
//     var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
//     map = new google.maps.Map(
//         document.getElementById('map'), options);
//     // Locations of landmarks
//     const customer = {lat: parseFloat(roomsCustomerLat), lng:parseFloat(roomsCustomerLong)};
//     const provider = {lat: parseFloat(roomsCroviderLat), lng: parseFloat(roomsCroviderLong)};
//     // The markers for The Dakota and The Frick Collection
//     var mk1 = new google.maps.Marker({position: customer, map: map,label: {text: 'Customer',color: 'green',},icon: iconBase + 'info-i_maps.png'});
//     var mk2 = new google.maps.Marker({position: provider, map: map,label:{text: 'Provider',color: 'blue',}});
//     var line = new google.maps.Polyline({path: [provider, customer], map: map});
    
//     var distance = haversine_distance(mk1, mk2);
//     //   document.getElementById('msg').innerHTML = "Distance between markers: " + distance.toFixed(2);
// }


</script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPt0Rcopq7I-pxf8lNvvmcG2dNFomhols&callback=initMap">
    </script>
@endsection 