var map;
var geocoder;
// Function to load the map and initialize the geocoder
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
// Function to geocode addresses to latitude and longitude coordinates
function codeAddress(cdata) {
  // Iterates through each data item in cdata
  Array.prototype.forEach.call(cdata, function(data){
     // Combines the name and address to form a full address
     var address = data.name + ' ' + data.address;
     // Performs geocoding to get geographic coordinates from the address
     geocoder.geocode( { 'address': address}, function(results, status) {
       if (status == 'OK') {
        // If geocoding is successful, centers the map on the new location
         map.setCenter(results[0].geometry.location);
         // Prepares data with the new coordinates
         var points = {};
         points.id = data.id;
         points.lat = map.getCenter().lat();
         points.lng = map.getCenter().lng();
         updateLibraryWithLatLng(points);
       } else {
        // Alerts the user if geocoding fails
         alert('Geocode was not successful for the following reason: ' + status);
       }
     });
 });
}
// Function to send updated coordinates back to the server
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
// Function to display markers for all libraries on the map
function showAllLibraries(allData) {
  // Creates a new Google Maps InfoWindow instance
  var infoWind = new google.maps.InfoWindow();
  // Iterates through each library data
  allData.forEach(function(data) {
      // Creates a content div to hold the text
      var content = document.createElement('div');
      
      // Creates a strong element for the library name, adds it to the content div
      var strong = document.createElement('strong');
      strong.textContent = data.name;
      content.appendChild(strong);
      
      // Adds a line break between the name and the address
      content.appendChild(document.createElement('br'));
      
      // Creates a text node for the address and adds it to the content div
      var address = document.createTextNode(data.address);
      content.appendChild(address);
      // Creates a marker at the given latitude and longitude
      var marker = new google.maps.Marker({
          position: new google.maps.LatLng(data.lat, data.lng),
          map: map
      });
      // Adds a mouseover event to each marker to display the info window with the content
      marker.addListener('mouseover', function() {
          infoWind.setContent(content);
          infoWind.open(map, marker);
      });
  });

}