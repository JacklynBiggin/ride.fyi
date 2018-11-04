let startCoords = "";
let endCoords = "";
let startLoc = "";
let endLoc = "";
let hasPublicTransit = false;
let alreadyWarned = false;

$( "#searchButton" ).click(function( event ) {

  // Stop form from submitting normally
  event.preventDefault();
  // Get some values from elements on the page:
    var start = encodeURIComponent($("#start").val());
    var end = encodeURIComponent($("#end").val());


  $.getJSON( "./api/validate.php/?query="+start, ( data ) => {
    $("#result").empty().append('<div style="text-align: center;font-size:8em;width:100%;"><i class="fas fa-cog fa-spin"></i></div>');
  }).done(function(data){
    startCoords = data.coords;
    startLoc = data.location_name;
    $.getJSON( "./api/validate.php/?query="+end, ( data ) => {

    }).done(function(data){
      endCoords = data.coords;
      endLoc = data.location_name;
      $.getJSON( "./api/index.php?start="+startCoords+"&end="+endCoords, function( data ) {
        // Put the results in a div

          let content = ""
          console.log(data);
          for (var key in data) {
              travelType = "default";

              splitStart = startCoords.split(",");
              splitEnd = endCoords.split(",");

              if(data[key].name.includes("Uber")) {
                travelType = "Uber";
                data[key].directions = '<a class="btn btn-primary" href="https://m.uber.com/ul/?client_id=<CLIENT_ID>&action=setPickup&pickup[latitude]=' + splitStart[0] + '&pickup[longitude]=' + splitStart[1] + '&dropoff[latitude]=' + splitEnd[0] + '&dropoff[longitude]=' + splitStart[1] + '&product_id=a1111c8c-c720-46c3-8534-2fcdd730040d&link_text=View%20team%20roster&partner_deeplink=partner%3A%2F%2Fteam%2F9383"><i class="fab fa-fw fa-uber"></i> Order Uber</a>';
              }

              if(data[key].name.includes("Lyft")) {
                travelType = "Lyft";
                data[key].directions = '<a class="btn btn-primary" href="https://lyft.com/ride?id=lyft&pickup[latitude]=' + splitStart[0] + '&pickup[longitude]=' + splitStart[1] + '&partner=GZA6JsK6N0b9&destination[latitude]=' + splitEnd[0] + '&destination[longitude]=' + splitEnd[1] + '"><i class="fab fa-fw fa-lyft"></i> Order Lyft</a>';
              }

              if (data[key].name == "Bike" || data[key].name == "Car" || data[key].name == "Walking") {
                travelType = data[key].name;
                data[key].directions = '<a class="btn btn-primary" href="http://maps.apple.com/?saddr=' + startCoords + '&daddr=' + endCoords + '"><i class="fas fa-fw fa-map-marker-alt"></i> Navigate</a>';
              }

              if (data[key].name.includes("Transit")) {
                travelType = "Transit";
                data[key].directions = '<a class="btn btn-primary" href="https://wego.here.com/directions/publicTransport/' + startLoc + '/' + endLoc + '"><i class="fas fa-fw fa-bus"></i> Navigate</a>';
                hasPublicTransit = true;
              }

              if (data[key].name == "Bird" || data[key].name == "Mobike") {
                travelType = "Scooter";
                data[key].directions = '<a disabled class="btn disabled btn-primary" href="#"><i class="fas fa-fw fa-lock"></i> Unavailable</a>';
              }

              if (data[key].name.includes("with")) {
                travelType = "Hybrid";
                data[key].directions = '<a disabled class="btn disabled btn-primary" href="#"><i class="fas fa-fw fa-lock"></i> Unavailable</a>';
              }

              switch(data[key].currency) {
                case "CAD":
                  currencySymbol = "C$";
                  break;
                case "USD":
                  currencySymbol = "$";
                  break
                case "GBP":deeplink

                  currencySymbol = "£";
                  break;
                case "EUR":
                  currencySymbol = "€";
                  break;
              }

              content += "<div class='col-12 col-md-6'><div class='card background-" + travelType + "'><div class ='card-body'>";
              if (data.hasOwnProperty(key)) {
                 let name = data[key].name;
                 let currency = data[key].currency;
                 let price = data[key].price;
                 let distance = data[key].distance;
                 let time = data[key].time;
                 let timeInMin = Math.floor(Number(time)/60);
                 let seconds = Number(time)%60;

                 if(!currency){
                  content += "<h5 id = '" +name+"' class = 'card-title'><span class='transport-name'>"+name + "</span> ";
                  content += "<span class = 'price price-free'>FREE</span></h5>";
                  content += "<p><i class = 'far fa-fw fa-clock'></i> "+timeInMin+" minutes</p>"
                  content += "<p><i class = 'fas fa-fw fa-map-marked-alt'></i> "+distance+" miles</p>"
                  content += data[key].directions
                 }
                 else if (data[key].prices_unavailable) {
                  content += "<h5 id = '" +name+"' class = 'card-title'><span class='transport-name'>"+name + "</span> ";
                  content += " <span class = 'price price-unavailable' id='" + currency + "'>?</span></h5>";
                  content += "<p><i class = 'far fa-fw fa-clock'></i> "+timeInMin+" minutes</p>"
                  content += "<p><i class = 'fas fa-fw fa-map-marked-alt'></i> "+distance+" miles</p>"
                  content += data[key].directions
                } else {
                  content += "<h5 id = '" +name+"' class = 'card-title'><span class='transport-name'>"+name + "</span> ";
                  content += " <span class = 'price price-paid' id='" + currency + "'>"+ currencySymbol +price+"</span></h5>";
                  content += "<p><i class = 'far fa-fw fa-clock'></i> "+timeInMin+" minutes</p>"
                  content += "<p><i class = 'fas fa-fw fa-map-marked-alt'></i> "+distance+" miles</p>"
                  content += data[key].directions
                }
              }


              content += "</div></div></div>"
           }

           if(alreadyWarned == false && hasPublicTransit == false) {
             content += "<div class='col-12 col-md-6'><div class='card background-alert'><div class='card-body'>It looks like your area doesn't have reliable public transport. Have you considered <a href='https://callyourrep.co/result/?address=" + startLoc + "'>contacting your local representative?</a></div></div></div>";
           }
           alreadyWarned = true;

           $("#result").empty().append(content);
        });
    });


      // Send the data using post

    });

});


function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(queryPosition);
    } else {
        alert("Geolocation is not supported by this browser.");
    }

function queryPosition(location) {
  $.getJSON('https://reverse.geocoder.api.here.com/6.2/reversegeocode.json?prox=' + location.coords.latitude + ',' + location.coords.longitude + '&mode=retrieveAddresses&maxresults=1&gen=9&app_id=jPeL8FxtgBOBIB9ISZPZ&app_code=mXQv9GX6ULnbMkeJYR2rVQ', (data) => {

  }).done(function(data) {
    $("#start").val(data['Response']['View'][0]['Result'][0]['Location']['Address']['Label']);
  })};
}
