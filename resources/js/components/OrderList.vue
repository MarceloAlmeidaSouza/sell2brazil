<template>
<div class="row" style="height:100vh;">
        <div class="col-12" style="height:inherit;">
            <div class="d-flex flex-column col-md-12 bg-light shadow-sm p-2 rounded" style="height:inherit;">
                <div class="shadow-sm bg-white p-2 mb-2 rounded">
                    <div class="title">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" @click="this.$parent.renderNewOrder" class="btn btn-outline-primary w-15" ><i class="fas fa-plus"></i>&nbsp;&nbsp;New order</button>
                            </div>
                    </div>
                </div>
                <div class="shadow-sm bg-white p-2 mb-2 rounded flex-grow-1" style="overflow: hidden; position: relative;">
                    <div class="d-flex flex-column col-md-12" style="overflow-y:auto;height:100%;">
                        <table id="order_table" class="table table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>View order</th>
                            <th>Order code</th>
                            <th>Kind of articles</th>
                            <th>All articles</th>
                            <th>Order date</th>
                            <th>Total amount without discount</th>
                            <th>Total amount with discount</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                    </tbody>
                </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>
 
<script>

    export default {
       mounted(){
          
        axios.post('/order-list', {})
        .then((response) =>{
            
            var table = $("#order_table").DataTable({
                
                select: {
                    style: 'os',
                    selector: 'td:first-child'
                },
                order: [[1, 'asc']],
                pageLength : 5,
                lengthMenu: [5, 10, 20],
                data: response.data,
                columns: [
                    { render: ()=>`
                    <button type="button" class="btn btn-outline-primary w-15" style='width:100%'>
                        </i><i class='fa fa-search'></i> Go to order
                    </button>
                    ` },
                    { data: "order_code" },
                    { data:'total_kind_article' },
                    { data:'total_units_article' },
                    {data:'order_date'},
                    { data: "total_amount_without_discount" },
                    { data: "total_amount_with_discount" }
                ]
            });
            $('#ggplanTable').on('shown.bs.collapse', function () {
                $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            $(table.table().body()).on('click', 'td',  (ev) =>{
                if(ev.currentTarget.firstElementChild instanceof HTMLButtonElement || ev.target instanceof HTMLButtonElement){
                    var tr = ev.currentTarget.closest("tr");
                    var row_data = table.row(tr).data();
                    this.$parent.renderMyOrder.call(this.$parent,row_data);
                }
            });
        })
        .catch(function (error) {
            console.log(error);
        });
       },
       methods:{
           addRow(rowData){
                $("#order_table").DataTable().row.add(rowData).draw();
           }
       }
    }

</script>