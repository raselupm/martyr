<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Martyr</title>
        <style>
            * {
                margin: 0;
                padding: 0;
            }

            #map {
                width: 100%;
                height: 100vh;
            }
            .leaflet-control-attribution a:first-child, .leaflet-control-attribution a:first-child+span {
                display: none;
            }
        </style>
    </head>
    <body>
        <!-- Include Leaflet CSS and JS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

        <!-- Leaflet MarkerCluster CSS and JS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
        <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

        <!-- Create a div element to hold the map -->
        <div id="map"></div>

        <!-- Create a script to initialize the map -->

        <script>
            // Create a map object
            var map = L.map('map').setView([23.7807622, 90.2638035], 8);

            // Add a tile layer to the map
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '<a target="_blank" href="https://medical-info.dghs.gov.bd/">ডেটা সোর্স: স্বাস্থ্যমন্ত্রণালয়</a>'
            }).addTo(map);

            // add map cluster from $data
            let data = {!! json_encode($data) !!};
            let markers = L.markerClusterGroup();

            for (let i = 0; i < data.length; i++) {
                let popupContent = `<b>নাম: ${data[i].patient_name_bn} (${data[i].patient_name_en})</b><br/>পিতার নাম: ${data[i].father_name}<br/>হসপিটাল: ${data[i].facility_name}`;
                if(data[i].present_address) {
                    popupContent += `<br/>ঠিকানা: ${data[i].present_address}`;
                }

                popupContent += `<br/>স্থায়ী ঠিকানা: ${data[i].permanent_address}<br/>মোবাইল নম্বর: ${data[i].contact_no}<br/>ধরন: ${data[i].type_of_service}`;

                let lat = data[i].lat;
                let lng = data[i].lng;
                let marker = L.marker([lat, lng]).bindPopup(popupContent);
                markers.addLayer(marker);
            }

            map.addLayer(markers);

        </script>

    </body>
</html>
