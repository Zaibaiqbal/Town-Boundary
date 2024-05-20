<template>
  <div class="map-container">
    <l-map :zoom="zoom" :center="center">
      <l-tile-layer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"></l-tile-layer>
      
      <l-geo-json :geojson="geojsonData" :options="geoJsonOptions"></l-geo-json>
    </l-map>
  </div>
</template>

<script>
import { LMap, LTileLayer, LGeoJson } from 'vue3-leaflet';

export default {
  components: {
    LMap,
    LTileLayer,
    LGeoJson,
  },
  data() {
    return {
      zoom: 8,
      center: [38.9, -74.0],
      geojsonData: null,
      geoJsonOptions: {
        style: {
          color: '#6432a8',
          weight: 2,
          opacity: 0.7,
          fillColor: '#9166cc',
          fillOpacity: 0
        },
        onEachFeature: (feature, layer) => {
          layer.bindTooltip(`
            <div>
              <h4> County: ${feature.properties.county}</h4>
              <hr>
              <h4> Name: ${feature.properties.name}</h4>
            </div>
          `);
        }
      }
    };
  },
  mounted() {
    fetch('http://localhost:8080/town_bounadry')
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        if (data.length === 0) {
          console.warn('No GeoJSON data found.');
          return;
        }

        // Parse st_asgeojson strings into GeoJSON objects
        const features = data.map(item => ({
          type: 'Feature',
          properties: {
            id: item.id,
            county: item.county,
            name: item.name
          },
          geometry: JSON.parse(item.st_asgeojson)
        }));

        // Construct a FeatureCollection
        const featureCollection = {
          type: 'FeatureCollection',
          features: features
        };

        // Update geojsonData
        this.geojsonData = featureCollection;
      })
      .catch(error => console.error('Error fetching or processing GeoJSON data:', error));
  }
};
</script>

<style scoped>
.map-container {
  height: 100vh;
  width: 100%;
}
</style>
