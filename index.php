<!doctype html>
<html lang="en">
  <head>
 <!-- Required meta tags -->
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

 <!-- Bootstrap CSS -->
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

 <!-- Mobiles -->
 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

 <!-- Font Awesome -->
 <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

 <!-- Our CSS -->
 <link rel="stylesheet" href="./assets/css/app.css">
    <title>Ride.fyi</title>
  </head>
  <body>
    <div class="container" style="margin-top: 1em;">
        <div class="row">
          <div class="col-xs-12 col-md-5">
            <div class="form-group">
                <div for="start" class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Start from:</span>
                  </div>
                  <input type="text" class="form-control form-control-lg" id="start" placeholder="Enter Location">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-fw fa-map-marker-alt" style="cursor:pointer;" onClick=getLocation()></i></span>
                  </div>
                </div>
              </div>
          </div>
          <div class="col-xs-12 col-md-5">
            <div for="start" class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Go to:</span>
              </div>
              <input type="text" class="form-control form-control-lg" id="end" placeholder="Enter Location" <?php if(isset($_GET['go'])) { echo 'value="' . htmlspecialchars($_GET['go']) . '"'; } ?>>
            </div>
          </div>
          <div class="col-xs-12 col-md-2">
            <button type="submit" class="btn btn-lg btn-primary btn-block" id ="searchButton">Go</button>
          </div>
        </div>
        <br />
          <div id="result" class="row">

          </div>
    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script>
        function activatePlacesSearch(){
            let start = document.getElementById('start');
            let autocomplete1 = new google.maps.places.Autocomplete(start);
            let end = document.getElementById('end');
            let autocomplete2 = new google.maps.places.Autocomplete(end);

        }
    </script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBC7sKPF4rxctLqVNG6rPa0Cj2O-1tWjag&libraries=places&callback=activatePlacesSearch"></script>
    <script type="text/javascript" async="" src="index.js"></script>
</body>
</html>
