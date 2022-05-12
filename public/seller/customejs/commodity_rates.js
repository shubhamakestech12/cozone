/* Project Name: Jewellery
     Website: https://akestech.com 
     updated by: Anoop Vishwakarma */
     $(document).ready(function () {
        Datatable();
    });
    
// save form
    $("#commodityrates_form").on("submit", function (e) {

        e.preventDefault();
        $.ajax({
            url: siteUrl + "/save_commodity_rates",
            type: "post",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == 1) {
                    ClearForm();
                    toastr["success"](response.msg);
                    location.reload();
                    Datatable();
                } else if (response.status == 9) {
                    var dd = response.error;

                    for (var i = 0; i < dd.length; i++) {
                        toastr["error"](dd[i]);
                    }
                } else if (response.status == 2) {
                    toastr["error"](response.msg);
                }
            },
        });
    });
    
    //show data
    function Datatable() {
        $("#commodity_datatable").DataTable().destroy();
        $("#commodity_datatable").DataTable({
            processing: true,
            serverSide: true,
            ordering:'true',
            order: [0, 'desc'],
            ajax: {
                url: siteUrl + "/show_commodity",
                data: function (d) {
                        (d.search = $('input[type="search"]').val());
                },
            },
            columns: [
                { data: "id"},
                { data: "title"},
                { data: "price"},
                { data: "date"},
                { data: "status"},
                { data: "action"},
            ],
        });
    }

    //Commodity status function
function status_commodity(id = "", status = "") {
    $.ajax({
        url: siteUrl + "/status_commodity",
        data: { id: id, status: status },
        type: "get",
        dataType: "json",
        success: function (response) {
            if (response["status"] == 1) {
                toastr["success"](response.msg);
                Datatable();
            } else if (response["status"] == 2) {
                toastr["error"](response.msg);
                Datatable();
            }
        },
    });
}

   // Edit Function    
function edit_commodity(id = "") {
    $.ajax({
        url: siteUrl + "/edit_commodity",
        data: { id: id },
        type: "get",
        dataType: "json",
        success: function (res) {
            $("#id").val(res.id); 
            $("#commodity").val(res.commodity_type);
            $("#commodity").attr("readonly", true); 
            $("#commodity").attr('disabled',true);
            $("#price").val(res.price);
            $("#date").prop('readonly', true);
            $("#date").prop('disabled', true);
            $("#date").val(res.date);
            $("#button").text("Update");
        },
    });
}//end functtion

//Delete Function
function delete_commodity(id = ''){
    if (confirm("Are you sure!")) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
            },
        });
        $.ajax({
            url:siteUrl+'/delete_commodity',
            type:'post',
            data:{id:id},
            success:function(response)
            {
               if(response['status']==1){
                ClearForm();
                    toastr["success"](response.msg);
                    Datatable();
                }else if(response['status']==2){
                    toastr["error"](response.msg);
                }
            }
        });
     }
} //End of function

// Form Clear
function ClearForm() {
    $("#commodity_form").trigger("reset");
    $('#button').text('submit');
}// end of function

//date picker
$('#date').daterangepicker({
    singleDatePicker: true,
    minDate:new Date(),
    showDropdowns: true,
    locale: {
    format: 'DD/MM/Y'
    }
}); 