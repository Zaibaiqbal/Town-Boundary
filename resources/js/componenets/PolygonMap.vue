<template>
  <div class="leaflet-map">
    <v-app>
      <h2>Town Boundary Map</h2>
      <l-map
        ref="map"
        :zoom="zoom"
        :center="[centerLat, centerLon]"
        :zoomControl="true"
        @ready="onMapReady"
      >
        <l-tile-layer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"></l-tile-layer>
        
        <l-geo-json 
          :geojson="geojsonData" 
          :options="geoJsonOptions"
        ></l-geo-json>
        
        <l-geo-json
          v-if="selectedPolygon"
          :geojson="selectedPolygon.geometry"
          :options="selectedPolygonOptions"
        ></l-geo-json>
      </l-map>

      <div class="row-container">
        <div class="search-town">
          <strong for="town-select">Search Town:</strong>
          <select id="town-select" v-model="selectedTown" @change="flyToTown" class="town-select">
            <option value="">Select a town</option>
            <option v-for="town in townNames" :key="town.id" :value="town.id">{{ town.name }}</option>
          </select>
        </div>

        <v-dialog v-model="dialog" max-width="400" class="town-dialog">
          <v-card>
            <strong>Town Information</strong>
            <v-card-text>
              <p v-if="selectedTownInfo.name">Name: {{ selectedTownInfo.name }}</p>
              <p v-if="selectedTownInfo.county">County: {{ selectedTownInfo.county }}</p>
            </v-card-text>
            <v-card-actions>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </div>
      
    </v-app>
  </div>
</template>

<script>
import { ref, onMounted, reactive } from 'vue';
import {LMap, LTileLayer, LGeoJson} from 'vue3-leaflet';
import 'proj4leaflet';
import * as turf from '@turf/turf';

export default {
  components: {
    LMap,
    LTileLayer,
    LGeoJson,
  },
  setup() {
    const map = ref(null);
    const centerLat = ref(39.99779213);
    const centerLon = ref(-75.2352583);
    const zoom = ref(7);
    const polygons = ref([]);
    const selectedPolygon = ref(null);
    const geojsonData = ref(null);
    const townNames = ref([]);
    const selectedTown = ref('');
    const dialog = ref(false);
    const selectedTownInfo = reactive({ name: '', county: '' });
    

    const geoJsonOptions = {
      style: {
        color: 'red',
        weight: 3,
        fillOpacity: 0.1,
      }
    };

    const selectedPolygonOptions = {
      style: {
        fillOpacity: 0.2,
        color: 'blue',
        weight: 4,
      },
    };

    const fetchData = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/town_boundaries');
        const data = await response.json();
        polygons.value = data.map((item) => ({
          type: 'FeatureCollection',
          geometry: JSON.parse(item.st_asgeojson),
          properties: {
            id: item.id,
            name: item.name,
            county: item.county,
            town_code: item.town_code,
          },
        }));
        
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };

    const fetchTownNames = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/gettownsnamelist');
        const data = await response.json();
        townNames.value = data.town_name_list;
      } catch (error) {
        console.error('Error fetching town names:', error);
      }
    };

    const flyToTown = () => {
      if (!map.value) {
        console.error('Map instance is not available');
        return;
      }

      const selectedTownValue = selectedTown.value;
      const townPolygon = polygons.value.find(
        (polygon) => polygon.properties.id === selectedTownValue
      );

      if (townPolygon && townPolygon.geometry) {
        const coordinates = townPolygon.geometry.coordinates[0][0];
        const poly = turf.polygon([coordinates]);
        const centroid = turf.centroid(poly);

        const [centroidLon, centroidLat] = centroid.geometry.coordinates;
        const zoomLevel = zoom.value + 4;
        map.value.flyTo([centroidLat, centroidLon], zoomLevel, {
          animate: true,
          duration: 1,
        });

        selectedTownInfo.name = townPolygon.properties.name;
        selectedTownInfo.county = townPolygon.properties.county;
        selectedPolygon.value = townPolygon; // Set the selected polygon
        dialog.value = true;
      } else {
        console.error('Town polygon not found for selected town:', selectedTownValue);
      }
    };

    const onMapReady = (mapInstance) => {
      map.value = mapInstance;
    };

    onMounted(() => {
      fetchData();
      fetchTownNames();

      fetch('/data.json')
        .then(response => response.json())
        .then(data => {
          geojsonData.value = data;
        })
        .catch(error => {
          console.error('Error fetching GeoJSON data:', error);
        });
    });

    return {
      selectedTownInfo,
      map,
      centerLat,
      centerLon,
      zoom,
      polygons,
      selectedPolygon,
      geojsonData,
      townNames,
      selectedTown,
      dialog,
      geoJsonOptions,
      selectedPolygonOptions,
      fetchData,
      fetchTownNames,
      flyToTown,
      onMapReady,
    };
  }
};
</script>

<style>
.leaflet-map {
  height: 400px;
  width: 800px;
  margin: 2% auto 0;
  border: 1px solid black;
}

.search-town {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}
.town-dialog {
  border: 1px solid black;
  padding: 2px; 
  border-radius: 4px; 
  margin-top: 2px;
  margin-bottom: 5px;
  align-items: flex-start;
}

.town-select {
  border: 1px solid black;
  padding: 1px; 
  border-radius: 4px;
}

.row-container {
  display: flex;
  justify-content: space-between; 
  align-items: flex-start; 
}

.search-town label {
  margin-right: 10px;
}
</style>
