<template>
    <div class="row" style="height:100vh;">
        <div class="col-md-9" style="height:inherit;">
            <div class="d-flex flex-column col-md-12 bg-light shadow-sm p-2 rounded" style="height:inherit;">
                <div class="shadow-sm bg-white p-2 mb-2 rounded">
                    <div class="title">
                        <h3>Order: {{order_code}}</h3>
                    </div>
                </div>
                <div class="shadow-sm bg-white p-2 mb-2 rounded flex-grow-1" style="overflow: hidden; position: relative;">
                    <div class="d-flex flex-column col-md-12" style="overflow-y:auto;height:100%;">
                        <div v-for="item in order_articles" v-bind:key="item.id" class="row bg-light shadow-sm mb-2 mt-2 mr-3 rounded" style="margin: 0px;">
                            <div class="col-2">
                                <div class="form-group">
                                    <img :src="'image/'+item.article.image_source" style="height: 125px; width: 125px;">
                                </div>
                            </div> 
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <span>Article</span>
                                    <br /> 
                                    <span class="bg-white rounded myorder-span-full">{{item.article.article_name}}</span>
                                </div> 
                                <div class="form-group mt-2">
                                    <span>Unit Price</span>
                                    <br>
                                    <span class="bg-white rounded myorder-span-price">US$ {{item.unit_price}}</span>
                                </div>
                            </div> 
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <span>Quantity</span> 
                                    <br> 
                                    <span class="bg-white rounded myorder-span-quantity" v-text="item.quantity"></span>
                                </div> 
                                <div class="form-group mt-2">
                                    <span>Total</span>
                                    <br />
                                    <span class="bg-white rounded myorder-span-price">US$ {{item.total_price}}</span>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <span>Article code</span>
                                <br>
                                <span class="bg-white rounded myorder-span-price">{{item.article.article_code}}</span>
                            </div> 
                        </div>
                    </div> 
                </div>


            <div class="shadow-sm bg-white p-3 rounded">
            <div class="row">
                <div class="col-6">
                    <button type="button" class="btn btn-outline-primary" @click="this.$parent.renderOrderList">My order list</button>
                </div>
                <div class="col-6">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-outline-primary me-md-2 w-25" @click="this.$parent.renderNewOrder">New order</button>
                    </div>
                </div>
            </div>
        </div>

            </div>
        </div>
        
        <div class="col-md-3" style="height:inherit;">
            <div class="d-flex flex-column bg-light shadow-sm p-2 rounded" style="height:inherit;">
                <div class="shadow-sm bg-white p-2 rounded mb-2">
                    <h3>Order Extract</h3>
                </div>
                <div class="shadow-sm bg-white p-2 rounded mb-2">
                    <ul class="list-group">
                        <li class="list-group-item">Order status: <span style="float:right"><b>Done</b></span></li>
                        <li class="list-group-item">Number of articles:  <span style="float:right">{{total_kind_article}}</span></li>
                        <li class="list-group-item">Total selected articles:  <span style="float:right">{{total_units_article}}</span></li>
                        <li class="list-group-item">Min value article:  <span style="float:right">US$ {{(min_value).toFixed(2)}}</span></li>
                        <li class="list-group-item">Max value article:  <span style="float:right">US$ {{(max_value).toFixed(2)}}</span></li>
                        <li class="list-group-item">Amount:  <span style="float:right">US$ {{(+total_amount_without_discount).toFixed(2)}}</span></li>
                    </ul>
                </div>
                <div class="shadow-sm bg-white p-2 rounded flex-grow-1">
                    <ul class="list-group">
                        <li class="list-group-item" style="border-color:green">Order: <span style="float:right">{{order_code}}</span></li>
                        <li class="list-group-item" style="border-color:green">Order date: <span style="float:right">{{order_date}}</span></li>
                        <li class="list-group-item" style="border-color:green">Discount:  <span style="float:right">US$ {{(+discount).toFixed(2)}}</span></li>
                        <li class="list-group-item" style="border-color:green">Total amount to pay:  <span style="float:right;text-decoration:underline;font-weight:bold;">US$ {{(+total_amount_with_discount).toFixed(2)}}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default{
    data(){
        return {
            total_kind_article:0,
            total_units_article:0,
            order_code:"",
            min_value:0,
            max_value:0,
            discount:0,
            total_amount_without_discount:0,
            total_amount_with_discount:0,
            order_articles:[],
            order_date:""
        }
    },
   
  
    methods:{
        loadOrder(orderData){
            Object.assign(this,orderData);
            this.min_value =  Math.min.apply(null, this.order_articles.map(function(item) {return item.unit_price;}));
            this.max_value =  Math.max.apply(null, this.order_articles.map(function(item) {return item.unit_price;}));
        }
    }
};
</script>
<style scoped>
.myorder-span-full{
    display: flex;
    padding: 2px 6px;
    border: 1px solid #d3d3d37a;
}
.myorder-span{
    padding: 2px 6px;
    border: 1px solid #d3d3d37a;
}
.myorder-span-price{
    padding: 2px 6px;
    width: 140px;
    display: flex;
    justify-content: space-around;
    border: 1px solid #d3d3d37a;
}
.myorder-span-quantity{
    padding: 2px 6px;
    width: 100px;
    display: flex;
    justify-content: space-around;
    border: 1px solid #d3d3d37a;
}
</style>