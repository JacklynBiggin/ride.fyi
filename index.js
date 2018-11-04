let startCoords = "";
let endCoords = "";

$( "#searchButton" ).click(function( event ) {

  // Stop form from submitting normally
  event.preventDefault();
  // Get some values from elements on the page:
    var start = encodeURIComponent($("#start").val());
    var end = encodeURIComponent($("#end").val());


  $.getJSON( "./api/validate.php/?query="+start, ( data ) => {

  }).done(function(data){
    startCoords = data.coords;
    $.getJSON( "./api/validate.php/?query="+end, ( data ) => {

    }).done(function(data){
      endCoords = data.coords;
      $.getJSON( "./api/index.php?start="+startCoords+"&end="+endCoords, function( data ) {
        // Put the results in a div

          let content = ""
          alert("test");
          console.log(data);
          for (var key in data) {
              travelType = "default";
              if(data[key].name.includes("Uber")) {
                travelType = "Uber";
              }

              if(data[key].name.includes("Lyft")) {
                travelType = "Lyft";
              }

              if (data[key].name == "Bike" || data[key].name == "Car" || data[key].name == "Walking") {
                travelType = data[key].name;
              }

              if (data[key].name.includes("Transit")) {
                travelType = "Transit";
              }

              if (data[key].name.includes("with")) {
                travelType = "Hybrid";
              }

              switch(data[key].currency) {
                case "CAD":
                  currencySymbol = "C$";
                  break;
                case "USD":
                  currencySymbol = "$";
                  break
                case "GBP":
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
                  content += "<p><i class = 'far fa-clock'></i>"+timeInMin+" minutes "+ seconds + " seconds</p>"
                  content += "<p><i class = 'fas fa-map-marked-alt'></i>"+distance+" miles</p>"
                 }
                 else if (data[key].prices_unavailable) {
                  content += "<h5 id = '" +name+"' class = 'card-title'><span class='transport-name'>"+name + "</span> ";
                  content += " <span class = 'price price-unavailable' id='" + currency + "'>?</span></h5>";
                  content += "<p><i class = 'far fa-clock'></i> "+timeInMin+" minutes</p>"
                  content += "<p><i class = 'fas fa-map-marked-alt'></i> "+distance+" miles</p>"
                } else {
                  content += "<h5 id = '" +name+"' class = 'card-title'><span class='transport-name'>"+name + "</span> ";
                  content += " <span class = 'price price-paid' id='" + currency + "'>"+ currencySymbol +price+"</span></h5>";
                  content += "<p><i class = 'far fa-clock'></i> "+timeInMin+" minutes</p>"
                  content += "<p><i class = 'fas fa-map-marked-alt'></i> "+distance+" miles</p>"
                }
              }
              content += "</div></div></div>"
           }
           $("#result").empty().append(content);
        });
    });


      // Send the data using post

    });



});
