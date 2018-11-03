$( "#searchForm" ).submit(function( event ) {
 
  // Stop form from submitting normally
  event.preventDefault();
  // Get some values from elements on the page:
    var $form = $( this );
    let start = encodeURIComponent($form.find("input[name='start']" ).val());
    let end = encodeURIComponent($form.find("input[name = 'end'] ").val());
    let url = $form.attr("action");


  var gettingStart = $.get(url+"/?query="+start);
  
  gettingStart.done(function(data){
    start = data.coords;
  });

  var gettingEnd = $.get(url+"/?query="+end);
  
  gettingEnd.done(function(data){
    end = data.coords;
  });

    // Send the data using post
  var posting = $.post ("/api/index.php", {"start": start, "end": end} );
 
  // Put the results in a div
  posting.done(function( data ) {
    let content = ""
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