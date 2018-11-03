$( "#searchForm" ).submit(function( event ) {
 
  // Stop form from submitting normally
  event.preventDefault();
  // Get some values from elements on the page:
  var $form = $( this ),
    start = $form.find( "input[name='start']" ).val(),
    end = $form.find("input[name = 'end'] ").val(),
    url = $form.attr( "action" );


  var gettingStart = $.get("https://geocoder.api.here.com/6.2/geocode.json?app_id=jPeL8FxtgBOBIB9ISZPZ&app_code=mXQv9GX6ULnbMkeJYR2rVQ&searchtext="+start);
  
  gettingStart.done(function(data){
      start = data.Response.View[0].Result[0].Location.NavigationPostion[0].Latitude;
      start += ",";
      start += data.Response.View[0].Result[0].Location.NavigationPostion[0].Longitude;
    });
    var gettingEnd =  $.get("https://geocoder.api.here.com/6.2/geocode.json?app_id=jPeL8FxtgBOBIB9ISZPZ&app_code=mXQv9GX6ULnbMkeJYR2rVQ&searchtext="+end);
 
    gettingEnd.done(function(data){
        end = data.Response.View[0].Result[0].Location.NavigationPostion[0].Latitude;
        end += ",";
        end += data.Response.View[0].Result[0].Location.NavigationPostion[0].Longitude;
    }); 
    // Send the data using post
  var posting = $.post( url, {"start": start, "end": end} );
 
  // Put the results in a div
  posting.done(function( data ) {
    let content = ""
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