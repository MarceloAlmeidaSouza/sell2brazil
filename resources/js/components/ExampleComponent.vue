<template>
  <div>
      <div class="container-fluid">
        <div class="row">
        <AlertDefault/>  
        </div>
        <div class="row bg-white">
            <div class="col-8">
              <div class="container">Container Left</div>
            </div>
            <div class="col-4">col-4</div>
        </div>
        
        <div class="row align-items-end">
          <div class="col-8">col-8</div>
          <div class="col-4">col-4</div>
        </div>
      </div>
  </div>
</template>
<script>
    Vue.component('AlertDefault', require('./AlertDefault.vue').default);
    export default {
        components:{AlertDefault},
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
