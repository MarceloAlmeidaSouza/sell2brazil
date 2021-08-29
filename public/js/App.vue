<template>
  <div>
      <div class="container-fluid">
        <div class="row">
          <Alert/>
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
import Alert from './vue-components/Alert.vue';
export default {
    name:"App",
    components:{
      Alert
    }
};
</script>
