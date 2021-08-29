<template>
    <div class="row" style="margin:0;">
        <div class="col-2">
            <div class="form-group">
                <img v-bind:src="image_source" style="height:125px; width: 125px;"/>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="id_label_multiple">
                    Article
                </label>
                <select class="form-control select2-hidden-accessible" :id="this._uid" style="width:100%;">
                    <option value=""></option>
                </select>
            </div>
            <div class="form-group mt-3">
                <span>
                    Unit Price: US$ {{(+unit_price).toFixed(2)}}
                </span>
            </div>  
        </div>
         <div class="col-lg-2">
            <div class="form-group">
                <label>
                    Quantity
                </label>
                <br/>
                <input name="quantity" v-model="quantity" min="1" :max="for_sell_limit" type="number" step='1' @change="qtdSelected" style="outline: none;border-radius: 3px;border: 1px solid lightgray;width: 70px;">
            </div>
            <div class="form-group mt-3">
                <span>
                    Total: US$ {{(+unit_price*quantity).toFixed(2)}}
                </span>
            </div> 
         </div>
        <div class="col-lg-2 d-flex flex-column-reverse">
            <div>
                <button  v-on:click="removeFormElement" type="button" class="btn btn-outline-secondary m-2" style="float:right;" >
                    <i class="fa fa-trash"></i>
                </button>
            </div>
            <div style="flex-grow: 1;">
                <span>Article code</span>
                <br>
                <span class="bg-white rounded span-price">{{article_code}}</span>
            </div> 
        </div>
    </div>
</template>
<script>
export default{
    name:"OrderItem",
    
    mounted() {
        var selection = $("#"+this._uid);
        var opts = this.products.map(p=>{
            return {
                id:p.id,
                text:p.article_name
            }
        })
        selection.select2({
            language: "en-US",
            placeholder: "Choose product",
            allowClear: true,
            data: opts
        }).on("change", ev=>{
            var opt = selection.select2('data').shift();
            var item = this.products.find(x=>{return x.id == opt.id});
            item && Object.assign(this,(({ id, unit_price,image_source,article_code,for_sell_limit}) => ({ id, unit_price, image_source:"/image/"+image_source, quantity:1,article_code,for_sell_limit}))(item));
            this.$parent.extract();
        });
        selection.on("select2:unselecting", e=>{
            Object.assign(this,
                {
                    unit_price:0,
                    for_sell_limit:0,
                    quantity:0,
                    article_code:"Code",
                    id:"",
                    image_source:"image/no-item.png"
                }
            );
            this.$parent.extract();
        });
    },
    props:{
        products:Array
    },
    methods:{
        qtdSelected:function(ev){
           this.quantity = ev.target.value;
           this.$parent.extract();
        },
        removeFormElement() {
            const id = this.$vnode.key;
            this.$parent.removeFormElement(id);
        }
    },
    data(){    
        return {
            unit_price:0,
            for_sell_limit:0,
            quantity:0,
            article_code:"Code",
            id:"",
            image_source:"image/no-item.png"
        }
    },
}
</script>
<style scoped>
.span-price{
    padding: 2px 6px;
    width: 140px;
    display: flex;
    justify-content: space-around;
    border: 1px solid #d3d3d37a;
}
</style>