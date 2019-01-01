/* eslint-disable strict */
/* eslint-disable no-console */
var L;
var wax;
var stateMapZoom;

function drawMap() {
  var stateAbr = drupalSettings.current_state.toLowerCase();
  var markers = {};
  var upper_layer;
  var lower_layer;

  console.log('drawMap for State: ' + stateAbr);
  // Determine the options of the map as well as its HTML container
  var map = new L.Map('mapbox_ca', {
    attributionControl: false,
    dragging: true,
    touchZoom: true,
    scrollWheelZoom: true,
    doubleClickZoom: false,
    boxZoom: true,
    keyboard: false,
    zoomControl: true
  });

  upper_layer = L.tileLayer('https://api.mapbox.com/styles/v1/digitaldemocracy/cj95ywg5dozjp2snwsdas3zf5/tiles/256/{z}/{x}/{y}?access_token={access_token}', {
                    access_token: 'pk.eyJ1IjoiZGlnaXRhbGRlbW9jcmFjeSIsImEiOiJjajUxbWZuYnowNXJyMzNwOXFkYjJxaXcwIn0.NvzOOy_QYRy1fdFfZMLacA',
                    opacity: 1
                    });
  lower_layer = L.tileLayer('https://api.mapbox.com/styles/v1/digitaldemocracy/cj9g8ji428rkz2so696jvnt9r/tiles/256/{z}/{x}/{y}?access_token={access_token}', {
                    access_token: 'pk.eyJ1IjoiZGlnaXRhbGRlbW9jcmFjeSIsImEiOiJjajUxbWZuYnowNXJyMzNwOXFkYjJxaXcwIn0.NvzOOy_QYRy1fdFfZMLacA',
                    opacity: 0
                    });
  L.Icon.Default.mergeOptions({
    iconUrl: 'themes/custom/dd/libraries/districtmap/images/marker-icon.png',
    shadowUrl: 'themes/custom/dd/libraries/districtmap/images/marker-shadow.png'
  });

  upper_layer.addTo(map);
  lower_layer.addTo(map);

  var terms = drupalSettings.dd_terms;

  for (var pid in terms) {
    if (terms[pid] !== 'None') {
      try {
        var districtInfo = terms[pid];

        var regex = /({|,)(?:\s*)(?:')?([A-Za-z_$\.][A-Za-z0-9_ \-\.$]*)(?:')?(?:\s*):/g;
        var newString = districtInfo.region.replace(regex, '$1"$2":');
        var region = JSON.parse(newString);

        console.log(districtInfo.name + '\n' + districtInfo.house +
          ' District ' + districtInfo.district);
        // Push markers to map corresponding to each speaker's district
        // as well as adding a 'pan to' feature when a marker is selected
        // DRUPAL-355: Added the house name to the marker popup.
        markers[pid] = new L.Marker([region.center_lat, region.center_lon])
          .bindPopup(districtInfo.name + '\n' + districtInfo.house
            + ' District ' + districtInfo.district)
          .on('click', function (e) {
            map.setView(this.getLatLng(), 10);
          }).addTo(map);
      }
      catch(e) {
        console.log(e);
      }
    }
  }

  // Function which opens up a marker's popup which is the marker
  // associated with the current speaker in the hearing
  window.findMarker = function (pid) {
    var marker;

    if (markers.hasOwnProperty(pid)) {
      marker = markers[pid];
      marker.openPopup();
      map.panTo(marker.getLatLng(), {duration: 2, easeLinearity: .15});
      map.setView(marker.getLatLng(), 10);

        if (marker._popup._content.search('Senate') > 0) {
          console.log('Switching to Senate map');
          upper_layer.setOpacity(1);
          lower_layer.setOpacity(0);
        }
        else {
          console.log('Switching to Assembly map');
          lower_layer.setOpacity(1);
          upper_layer.setOpacity(0);
        }
    }
    else {
      // Deselect all markers
      for (var m in markers) {
        markers[m].closePopup();
      }
      map.setZoom(7, {duration: 1, easeLinearity: .15});
    }
  };
}
