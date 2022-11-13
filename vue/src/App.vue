<template>
    <div id="map"/>
</template>

<script>
import mapboxgl from "mapbox-gl";
import "mapbox-gl/dist/mapbox-gl.css";
import {onMounted} from "vue";

export default {
    setup() {
        onMounted(() => {
            mapboxgl.accessToken = "pk.eyJ1IjoiYWxleGFybmF1dG92IiwiYSI6ImNsYWR4dzFjMjA1emYzcnBxcmtudHl1bDQifQ.0huUzqEOQ1x5BUNSSeKtVw";
            const map = new mapboxgl.Map({
                container: "map",
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [4.897070, 52.377956], // starting position [lng, lat]
                zoom: 12, // starting zoom
                projection: 'globe' // display the map as a 3D globe
            });
            map.addControl(new mapboxgl.FullscreenControl({container: document.querySelector('body')}));
            map.addControl(new mapboxgl.GeolocateControl({
                positionOptions: {
                    enableHighAccuracy: true
                },
                trackUserLocation: true,
                showUserHeading: true
            }));

            map.on("load", () => {
                map.addSource('points', {
                    'type': 'geojson',
                    "data": "http://localhost/api/sensors"
                });

                map.addLayer({
                    'id': 'points',
                    'type': 'circle',
                    'source': 'points',
                    'paint': {
                        'circle-color': '#4264fb',
                        'circle-radius': 10,
                        'circle-stroke-width': 3,
                        'circle-stroke-color': '#ffffff'
                    },
                });

                // Create a popup, but don't add it to the map yet.
                const popup = new mapboxgl.Popup({
                    closeButton: false,
                    closeOnClick: false
                });

                map.on('mouseenter', 'points', (e) => {
                    map.getCanvas().style.cursor = 'pointer';
                    const coordinates = e.features[0].geometry.coordinates.slice();
                    const description = e.features[0].properties.description;

                    while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                        coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                    }
                    popup.setLngLat(coordinates).setHTML(description).addTo(map);
                });

                map.on('mouseleave', 'points', () => {
                    map.getCanvas().style.cursor = '';
                    popup.remove();
                });

            });

        });
    },
};


</script>

<style>
#map {
    height: 100vh;
}
</style>