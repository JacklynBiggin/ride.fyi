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
    });
    $.getJSON( "./api/index.php?start="+startCoords+"&end="+endCoords, function( data ) {
      // Put the results in a div
      
        let content = ""
        alert("test");
        console.log(data);
        for (var key in data) {
            content += "<tr>"
            if (json.hasOwnProperty(key)) {
               let name = data[key].name;
               let currency = data[key].currency;
               let price = data[key].price;
               let distance = data[key].distance;
               let time = data[key].time;
               let timeInMin = Number(time)/60;
               
               if(!currency){
    
               }
               else{
                    // $("<td id = '" +name+"'>"+name+"</td>").appendTo("#result");
                    // $("<td id = '" +price+"'><p id='"+currency +"'>"+price+"</td>").appendTo("#result");
                    // $("<td>"+distance+" miles</td>").appendTo("#result");
                    // $("<td>"+timeInMin+" minutes</td>").appendTo("#result");
                    content += "<td id = '" +name+"'>"+name+"</td>";
                    content += "<td id = '" +price+"'><p id='"+currency +"'>"+price+"</td>";
                    content += "<td>"+distance+" miles</td>"
                    content += "<td>"+timeInMin+" minutes</td>"
                }
            }
            content += "</tr>"
         }
         $("#result").empty().append(content);
      });
  });

 
    // Send the data using post

 
  
});