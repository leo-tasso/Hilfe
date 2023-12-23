<?php
function insertmap($id,$lon,$lat,$label)
{ ?>
    <div id="map<?php echo $id; ?>" class="openmap"></div>
    </div>
    <script>
        const map<?php echo $id; ?> = L.map('map<?php echo $id; ?>', {
            center: [<?php echo $lon ?>, <?php echo $lat ?>],
            zoom: 50
        });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map<?php echo $id; ?>);
        var heart = L.icon({
            iconUrl: '../res/MapPointer.svg',
            iconSize: [38, 38], // size of the icon
            iconAnchor: [19, 38], // point of the icon which will correspond to marker's location
            popupAnchor: [0, -38] // point from which the popup should open relative to the iconAnchor
        });
        const marker<?php echo $id; ?> = L.marker([<?php echo $lon ?>, <?php echo $lat ?>], {
            icon: heart
        }).addTo(map<?php echo $id; ?>).bindPopup("<?php echo $label ?>");;
    </script>
<?php
}
?>