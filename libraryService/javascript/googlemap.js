var map;
var geocoder;
function loadMap() {
	var pune = {lat: 36.166340, lng: -86.779068};
    map = new google.maps.Map(document.getElementById('map'), {
      zoom: 9,
      center: pune
    });

    var marker = new google.maps.Marker({
      position: pune,
      map: map
    });

   // Initialize the Geocoder
   geocoder = new google.maps.Geocoder();  

   // Fetch all the library data encoded in JSON within the 'allData' div
   var allData = JSON.parse(document.getElementById('allData').innerHTML);
   
   // Use the data to show all libraries
   showAllLibraries(allData);

   
}

function codeAddress(cdata) {
  Array.prototype.forEach.call(cdata, function(data){
     var address = data.name + ' ' + data.address;
     geocoder.geocode( { 'address': address}, function(results, status) {
       if (status == 'OK') {
         map.setCenter(results[0].geometry.location);
         var points = {};
         points.id = data.id;
         points.lat = map.getCenter().lat();
         points.lng = map.getCenter().lng();
         updateLibraryWithLatLng(points);
       } else {
         alert('Geocode was not successful for the following reason: ' + status);
       }
     });
 });
}

function updateLibraryWithLatLng(points){
  $.ajax({
    url: "actions.php",
    type: "POST",
    data: points,
    success: function(res){
      console.log(data);
    }
  });

  
}
function showAllLibraries(allData) {
  var infoWind = new google.maps.InfoWindow();
  allData.forEach(function(data) {
      var content = document.createElement('div');
      
      // Name of the library
      var strong = document.createElement('strong');
      strong.textContent = data.name;
      content.appendChild(strong);
      
      // Line break between name and address
      content.appendChild(document.createElement('br'));
      
      // Address of the library
      var address = document.createTextNode(data.address);
      content.appendChild(address);

      var marker = new google.maps.Marker({
          position: new google.maps.LatLng(data.lat, data.lng),
          map: map
      });

      marker.addListener('mouseover', function() {
          infoWind.setContent(content);
          infoWind.open(map, marker);
      });
  });

}