	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
<!-- Make sure you put this AFTER Leaflet's CSS -->
 	<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
   crossorigin=""></script>

   <!-- Load Esri Leaflet from CDN -->
  <script src="https://unpkg.com/esri-leaflet@3.0.8/dist/esri-leaflet.js"
    integrity="sha512-E0DKVahIg0p1UHR2Kf9NX7x7TUewJb30mxkxEm2qOYTVJObgsAGpEol9F6iK6oefCbkJiA4/i6fnTHzM6H1kEA=="
    crossorigin=""></script>


  <!-- Load Esri Leaflet Geocoder from CDN -->
  <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.3.2/dist/esri-leaflet-geocoder.css"
    integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g=="
    crossorigin="">
  <script src="https://unpkg.com/esri-leaflet-geocoder@2.3.2/dist/esri-leaflet-geocoder.js"
    integrity="sha512-8twnXcrOGP3WfMvjB0jS5pNigFuIWj4ALwWEgxhZ+mxvjF5/FBPVd5uAxqT8dd2kUmTVK9+yQJ4CmTmSg/sXAQ=="
    crossorigin=""></script>
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
	<script src="<?=base_url('assets/js/leaflet.ajax.js')?>"></script>

   <script type="text/javascript">
   	var latInput=document.querySelector("[name=lat]");
   	var lngInput=document.querySelector("[name=lng]");
   	var lokasiInput=document.querySelector("[name=lokasi]");
   	var idKecamatanInput=document.querySelector("[name=id_kecamatan]");
   	var geocodeService = L.esri.Geocoding.geocodeService();
   	var marker;
   	var map = L.map('map').setView([-6.6355092773554585, 106.7861490878395], 14);

   	var Layer=L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
	    maxZoom: 18,
	    id: 'mapbox.streets',
	    accessToken: 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw'
	});
	map.addLayer(Layer);

	///
	map.on("click",function(e){
		var lat=e.latlng.lat;
		var lng=e.latlng.lng;
		if(!marker){
			marker = L.marker(e.latlng).addTo(map)
		}
		else{
			marker.setLatLng(e.latlng);
		}


		latInput.value=lat;
		lngInput.value=lng;

		$.ajax({
			url:"https://nominatim.openstreetmap.org/reverse",
			data:"lat="+lat+
				"&lon="+lng+
				"&format=json",
			dataType:"JSON",
			success:function(data){
				console.log(data);
			    lokasiInput.value=data.display_name;
			}
		})



	  	geocodeService.reverse().latlng(e.latlng).run(function (error, result) {
	      if (error) {
	        return;
	      }
	      console.log(result);
	      var find = false;
	      var district=result.address.District;
		  idKecamatanInput.selectedIndex=0;
		  console.log('ini distrik'+ district);
	      for(i=0; i<idKecamatanInput.options.length; i++){
	      	if(idKecamatanInput.options[i].text == district){
	      		idKecamatanInput.selectedIndex=i;
	      		find = true;
	      		break;
	      	}
	      }
	//       if(find == false){
	//         $('#kecamatan').append($('<option selected>', {
    //     value: 1,
    //     text: ''
	// }));
	//       }
	    });
	});

	// draw
	// FeatureGroup is to store editable layers

	 var drawnItems = new L.FeatureGroup();
	 map.addLayer(drawnItems);
	 if($("[name=polygon]").val()!=""){
		var latlngs = JSON.parse($("[name=polygon]").val());
		var polygon = L.polygon(latlngs, {color: 'red'}).addTo(drawnItems);
	 }

     var drawControl = new L.Control.Draw({
     	draw:{
     		polyline:false,
     		rectangle:false,
     		circle:false,
     		marker:false,
     		circlemarker:false
     	},
         edit: {
             featureGroup: drawnItems
         }
     });
     map.addControl(drawControl);
     map.on('draw:created', function (e) {
     	console.log("Created")
	   var type = e.layerType,
	       layer = e.layer;
	   var latLng=layer.getLatLngs();
	    console.log(latLng);

	    $("[name=polygon]").val(JSON.stringify(latLng));
	   // if (type === 'marker') {
	   //     // Do marker specific actions
	   // }
	   // Do whatever else you need to. (save to db; add to map etc)
	   drawnItems.addLayer(layer);
	});

     map.on('draw:edited',function(e){
     	console.log('edited');
     	var latLng=e.layers.getLayers()[0].getLatLngs();
     	
	    $("[name=polygon]").val(JSON.stringify(latLng));
     })
     map.on('draw:deleted',function(e){
     	console.log('deleted');
     	
	    $("[name=polygon]").val("");
     })
   </script>