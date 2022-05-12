/* Project Name: Jewellery 
     Website: https://akestech.com 
     updated by: Anoop Vishwakarma */
     $(document).ready(function () {
        Datatable();
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
            },
        });
    });

    //show data
    function Datatable() {
        $("#customList_datatable").DataTable().destroy();
        $("#customList_datatable").DataTable({
            processing: true,
            serverSide: true,
            "order": [0, 'desc'],
            ajax: {
                url: siteUrl + "/show_data",
                data: function (d) {
                        (d.search = $('input[type="search"]').val());
                },
            },
            columns: [
                { data: "id" },
                { data: "customer_name"},
                { data: "name"},
                { data: "title"},
                { data: "weight"},
                { data: "price"},
                { data: "date"},
                { data: "order_status"},
                { data: "payment_status"},
            ],
        });
    }
 // Edit Function



//  on order status change
// function change_order_status() {
//     if (confirm('Are You Sure To Confirm')) {
//        $.ajax({

//        })
//     }
// }
//Manage User status function
function change_order_status(obj) {
    confirm('Are You Sure To Confirm');
    var status_id = $(obj).val();
    var update_id = $(obj).attr('data-id');
    var payload = {
        update_id:update_id,status_id:status_id
    }
    $.ajax({
        url: siteUrl + "/order_status",
        data: payload,
        type: "post",
        dataType: "json",
        success: function (response) {
            console.log(response);
            if (response.status == 1) {
                toastr["success"](response.msg);
                Datatable();
            }else if (response.status == 2) {
                toastr["error"](response.msg);
            }

        },
    });
}