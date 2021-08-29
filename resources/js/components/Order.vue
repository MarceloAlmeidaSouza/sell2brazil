<template>
    <div class="row" style="height:100vh;">
        <div class="col-md-9" style="height:inherit;">
            <div class="d-flex flex-column col-md-12 bg-light shadow-sm p-2 rounded" style="height:inherit;">
                <div class="shadow-sm bg-white p-2 mb-2 rounded">
                    <div class="title">
                        <h3>Ordering</h3>
                    </div>
                </div>
                <div class="shadow-sm bg-white p-2 mb-2 rounded flex-grow-1" style="overflow: hidden; position: relative;">
                    <div class="d-flex flex-column col-md-12" style="overflow-y:auto;height:100%;" ref="scroll">
                        <component class="bg-light shadow-sm mb-2 mt-2 mr-3 rounded" v-for="item in orderItems" v-bind:is="item.type" :key="item.id" :products="item.products"></component>
                    </div>
                </div>
                <div class="shadow-sm bg-white p-3 rounded">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-primary w-25" @click="addOrderItem"><i class="fas fa-plus"></i>&nbsp;&nbsp;New item</button>
                        </div>
                        <div class="col-6">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-outline-warning me-md-2 w-25" @click="this.$parent.renderOrderList">Cancel</button>
                                <button type="button" class="btn btn-outline-primary me-md-2 w-25" @click="clear">Clear order</button>
                                <button type="button" class="btn btn-outline-success w-25" @click="createOrder">Done</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="height:inherit;">
            <div class="d-flex flex-column bg-light shadow-sm p-2 rounded" style="height:inherit;">
                <div class="shadow-sm bg-white p-2 rounded  mb-2">
                    <h3>Order Extract</h3>
                </div>
                <div class="shadow-sm bg-white p-2 rounded flex-grow-1">
                    <ul class="list-group">
                        <li class="list-group-item">Order status: <span style="float:right"><b>Elaboration</b></span></li>
                        <li class="list-group-item">Number of article:  <span style="float:right">{{numberProducts}}</span></li>
                        <li class="list-group-item">Total selected article:  <span style="float:right">{{totalItems}}</span></li>
                        <li class="list-group-item">Min value article:  <span style="float:right">US$ {{(minValueItem).toFixed(2)}}</span></li>
                        <li class="list-group-item">Max value article:  <span style="float:right">US$ {{(maxValueItem).toFixed(2)}}</span></li>
                        <li class="list-group-item">Amount:  <span style="float:right">US$ {{(amount).toFixed(2)}}</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <loading  :opacity=.7 :active="isLoading"  :is-full-page="true" :loader="loader" :color="color"/>
    </div>
</template>
<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import OrderItem from "./OrderItem.vue";

export default{
    components:{OrderItem, Loading},
    mounted() {
        this.debouncedFirstArticle = _.debounce(()=>{
            this.$nextTick(this.addOrderItem)
        }, 500);
    },
    beforeCreate(){
        axios.post('/articles', {})
        .then((response) =>{
            this.products.push(...response.data);
            this.debouncedFirstArticle();
        })
        .catch(function (error) {
            console.log(error);
        });
    },
    data(){
        return {
            orderItems: [],
            products:[],
            count: 0,
            numberProducts:0,
            totalItems:0,
            minValueItem:0,
            maxValueItem:0,
            amount:0,
            isLoading:false,
            loader:"dots",
            color:"blue"
        }
    },
    created(){
        this.debouncedScrollTo = _.debounce(()=>{
            this.$refs.scroll.scrollTo(0,this.$refs.scroll.scrollHeight);
            $(this.$refs.scroll.lastElementChild).find('select').select2('open');
        }, 500);
    },
    methods:{
        clear:function(){
            this.orderItems = [];
            setTimeout(this.extract,500);
        },
        removeFormElement(id) {
            const index = this.orderItems.findIndex(f => f.id === id);
            this.orderItems.splice(index, 1);
            setTimeout(this.extract,500);
        },
        addOrderItem: function() {
            this.orderItems.push({
                type:"OrderItem",
                id: this.count++,
                products:this.products
            });
            this.debouncedScrollTo();
        },
        extract(){
            this.minValueItem = this.maxValueItem = this.numberProducts = this.amount = this.totalItems = 0;
            var s = new Set();
            var values = [];
            this.$children.forEach(el=>{
                if(el.$vnode.tag.includes('OrderItem') && el.id){
                    this.totalItems += +el.quantity;
                    s.add(el.id);
                    this.numberProducts = s.size;
                    this.amount += (el.quantity*el.unit_price);
                    values.push(+el.unit_price);
                    this.minValueItem =  Math.min.apply(null, values);
                    this.maxValueItem =  Math.max.apply(null, values);
                }
            });
        },
        createOrder(){
            var order = {};
            this.$children.forEach(el=>{
                if(el.$vnode.tag.includes('OrderItem') && el.id){
                    if(!order[el.id]){
                        order[el.id] = {id:el.id,quantity:0};
                    }
                    order[el.id].quantity += +el.quantity
                }
            });

            if(Object.keys(order).length > 0){
                this.isLoading = true;
                axios.post('/create-order', {create_order:order})
                .then((response) =>{
                    this.isLoading = false;
                    if(response.data.order){
                        if(response.data.success){
                            this.$alert("Your order code is: "+response.data.order.order_code,"Order done successfully",'success');
                            this.$parent.gotNewOrder(response.data.order);
                        }else{
                            this.$alert(response.data.message,"Check your order",'warning');
                        }
                    }else{
                        this.$alert("Sorry, our system got an error.","System error",'error');
                    }
                })
                .catch((error)=>{
                     this.isLoading = false;
                     this.$alert("Sorry, our system got an error.","System error",'error');
                });
            }else{
                this.$alert("No article added, please to create an order choose at least one article.","No article added.",'warning');
            }
        }
    }
};
</script>
