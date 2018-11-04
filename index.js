window.onload = function() {
  const urlParams = new URLSearchParams(window.location.search);
  const myParam = urlParams.get('myParam');
  var isset = getParameterByName('go');
  if(isset != ""){
    endPlaceholder = document.getElementById('end');
    endPlaceholder.setAttribute("placeholder", isset); 
  }
  return;
};

function getParameterByName(name, url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, '\\$&');
  var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
      results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

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
              content += "<div class='col-12 col-md-6'><div class='card'><div class ='card-body'>"
              if (data.hasOwnProperty(key)) {
                 let name = data[key].name;
                 let currency = data[key].currency;
                 let price = data[key].price;
                 let distance = data[key].distance;
                 let time = data[key].time;
                 let timeInMin = Math.floor(Number(time)/60);
                 let seconds = Number(time)%60;
                 
                 if(!currency){
                  content += "<h5 id = '" +name+"' class = 'card-title'>"+name;
                  content += "<span class = 'price'> Free </span></h5>";
                  content += "<p><i class = 'far fa-clock'></i>"+timeInMin+" minutes "+ seconds + " seconds</p>"
                  content += "<p><i class = 'fas fa-map-marked-alt'></i>"+distance+" miles</p>"
                 }
                 else{
                  content += "<h5 id = '" +name+"' class = 'card-title'>"+name;
                  content += "<span class = 'price' id='"+currency+"'> "+price+" </span></h5>";
                  content += "<p><i class = 'far fa-clock'></i>"+timeInMin+" minutes "+ seconds + " seconds</p>"
                  content += "<p><i class = 'fas fa-map-marked-alt'></i>"+distance+" miles</p>"
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