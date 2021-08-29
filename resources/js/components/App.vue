<template>
  <div>
      <div class="container-fluid" style="background: #eef0f2;">
        <Order v-if="order"/>
        <MyOrder :orderData="myOrderData" ref="refMyOrder" v-if="myOrder" />
       <keep-alive>
        <OrderList v-show="orderList" ref="refOrderList"/>
      </keep-alive> 
      </div>
  </div>
</template>
<script>
    import 'vuejs-datatable/dist/themes/bootstrap-4.esm';
    import { VuejsDatatableFactory } from 'vuejs-datatable';
    import Order from './Order.vue';
    import MyOrder from "./MyOrder.vue";
    import VueSimpleAlert from "vue-simple-alert";
    import OrderList from "./OrderList.vue"
    
    export default {
        components:{Order,VueSimpleAlert,VuejsDatatableFactory,OrderList,MyOrder},
        data(){
          return{
             order:false,
             orderList:true,
             myOrder:false,
             myOrderData:{}
          }
        },
        methods:{
         renderNewOrder(){
           this.order=true;
           this.orderList=false;
           this.myOrder = false;
         },
         renderOrderList(){
           this.order=false;
           this.orderList = true;
           this.myOrder = false;
         },
         renderMyOrder(orderData){
           this.order=false;
           this.orderList = false;
           this.myOrder = true;
           this.myOrderData = orderData;
           setTimeout(()=>{this.$refs.refMyOrder.loadOrder(orderData);},1000);
         },
         gotNewOrder(orderData){
           this.renderMyOrder(orderData);
           this.$refs.refOrderList.addRow(orderData);
         }
        }
    }
</script>