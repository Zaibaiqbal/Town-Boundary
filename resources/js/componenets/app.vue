<template>
  <div id="app">
    <PolygonMap />
  </div>
</template>

<script>
import PolygonMap from './PolygonMap.vue';

export default {
  components: {
    PolygonMap
  }
};
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
