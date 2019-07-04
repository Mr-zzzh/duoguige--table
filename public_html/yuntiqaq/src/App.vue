<template>
  <div id="app">
    <router-view/>
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style>
@import "../static/css/main.css";
/* #app { */
  /* font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50; */
  /* margin-top: 60px; */
/* } */
.page{
   overflow: hidden;
    background: white;
    padding-bottom: 62px;
    background:rgba(255,255,255,1);
    border:1px solid rgba(230, 230, 230, 1);
    box-shadow:0px 2px 3px 0px rgba(0, 0, 0, 0.05);
    border-radius:6px;
    padding-top: 20px;
    padding-left: 20px;
    padding-right: 20px;
  }
</style>
