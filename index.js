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
              content += "<div class='row align-items-start'>"
              if (data.hasOwnProperty(key)) {
                 let name = data[key].name;
                 let currency = data[key].currency;
                 let price = data[key].price;
                 let distance = data[key].distance;
                 let time = data[key].time;
                 let timeInMin = Number(time)/60;
                 
                 if(!currency){
                  content += "<div id = '" +name+"' class = 'col'>"+name+"</div>";
                  content += "<div id = '" +price+"' class = 'col'><p id='"+currency +"'>Free</p></div>";
                  content += "<div class = 'col'>"+distance+" miles</div>"
                  content += "<div class = 'col'>"+timeInMin+" minutes</div>"
                 }
                 else{
                      // $("<td id = '" +name+"'>"+name+"</td>").appendTo("#result");
                      // $("<td id = '" +price+"'><p id='"+currency +"'>"+price+"</td>").appendTo("#result");
                      // $("<td>"+distance+" miles</td>").appendTo("#result");
                      // $("<td>"+timeInMin+" minutes</td>").appendTo("#result");
                      content += "<div id = '" +name+"' class = 'col'>"+name+"</div>";
                      content += "<div id = '" +price+"' class = 'col'><p id='"+currency +"'>"+price+"</p></div>";
                      content += "<div class = 'col'>"+distance+" miles</div>"
                      content += "<div class = 'col'>"+timeInMin+" minutes</div>"
                  }
              }
              content += "</div>"
           }
           $("#result").empty().append(content);
        });
    });
  
   
      // Send the data using post
  
    });
    
 
  
});