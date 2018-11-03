rideFyiSearch = function(location){
   var query = location.selectionText;

   const Http = new XMLHttpRequest();
   const url='http://10.67.173.53/vandyhacks5/api/validate.php?query=' + query;
   Http.open("GET", url);
   Http.send();
   Http.onreadystatechange=(e)=>{
     response = JSON.parse(Http.responseText);

     if(response.status == "error") {
       alert(response.message);
     } else {
       chrome.tabs.create({url: "http://10.67.173.53/vandyhacks5?go=" + response.location_name + "&coords=" + response.coords});
     }

   }
};

chrome.contextMenus.create({
  title: "Search on Ride.fyi",
  contexts:["selection"],  // ContextType
  onclick: rideFyiSearch // A callback functio
});
