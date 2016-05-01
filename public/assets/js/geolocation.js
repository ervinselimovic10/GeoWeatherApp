  $(function(){
      // Get location on click event
      $('#getLoc').on('click', getCurPos);

      // Get current position
      function getCurPos() {
        $('#wait').html('Pridobivam lokacijo...Prosimo, počakajte trenutek.');
        navigator.geolocation.getCurrentPosition(getGeolocation, checkForErrors);
      }

      $(window).load(function(){
        $('#imgLoc').html('Wait').fadeOut(2000);
      });

      // Check for errors
      function checkForErrors(err) {
        switch(err.code)
        {
          case err.PERMISSION_DENIED: 
            alert("Uporabnik ni delil lokacije!");
          break;
   
          case err.POSITION_UNAVAILABLE: 
            alert("Program ni zaznal vaše lokacije!");
          break;
   
          case err.TIMEOUT: 
            alert("Čas pridobivanja lokacije se je iztekel.");
          break;
   
          default: 
            alert("Se opravičujemo, prišlo je do neznane napake.");
          break;
        }
      }
 
      // Get coords and map picture
      function getGeolocation(position) {
        // Store lat and lon
        $lat = position.coords.latitude;
        $lon = position.coords.longitude;

        // Assign form values with lat and lon
        $('#lat').val($lat);
        $('#lon').val($lon);

        // Get map img
        $img_url = "http://maps.google.com/maps/api/staticmap?sensor=false&center=" + $lat + "," +
                      $lon + "&zoom=14&size=300x400&markers=color:blue|label:S|" +
                      $lat + ',' + $lon;

        // Create img element and append it to div
        $('#imgloc').append(
          $(document.createElement('img'))
            .attr('src', $img_url)
            .attr('class','img-responsive img-rounded center-block')
        );

        // Close wait msg - get loc btn and display form button
        $('#wait').addClass('nodisplay');
        $('#getLoc').addClass('nodisplay');
        $('#submit').removeClass('nodisplay');
      }
  });