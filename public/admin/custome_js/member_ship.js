/* Project Name: Jewellery
     Website: https://akestech.com 
     updated by: Anoop Vishwakarma */
$(document).ready(function() {
    Datatable();

});

// save form
$("#frm_plan").on("submit", function(e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
        },
    });
    $.ajax({
        url: siteUrl + "/save_plan",
        type: "post",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status == 1) {
                $("#frm_plan").trigger('reset');
                $("#id").val("");
                toastr["success"](response.msg);
                Datatable();
                $("#button").text('Save')
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
    $("#plan_datatable").DataTable().destroy();
    $("#plan_datatable").DataTable({
        processing: true,
        serverSide: true,
        ordering: 'true',
        order: [0, 'desc'],
        ajax: {
            url: siteUrl + "/show_plans",
            data: function(d) {
                (d.search = $('input[type="search"]').val());
            },
        },
        columns: [
            { data: "id" },
            { data: "name" },
            { data: "plan_duration" },
            { data: "description" },
            { data: "status" },
            { data: "action" },
        ],
    });
}

//Commodity status function
function status_plan(id = "", status = "") {
    $.ajax({
        url: siteUrl + "/status_plan",
        data: { id: id, status: status },
        type: "get",
        dataType: "json",
        success: function(response) {
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
function edit_plan(id = "") {
    $.ajax({
        url: siteUrl + "/edit_plan",
        data: { id: id },
        type: "get",
        dataType: "json",
        success: function(res) {
            console.log(res);
            $("#id").val(res.id);
            $("#plan_name").val(res.plan_name);
            $("#plan").val(res.plan_duration);
            $("#price").val(res.price);
            $("#description").val(res.description);
            $("#button").text('Update');
        },
    });
} //end functtion

//Delete Function
function delete_plan(id = '') {
    if (confirm("Are you sure!")) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
            },
        });
        $.ajax({
            url: siteUrl + '/delete_plan',
            type: 'post',
            data: { id: id },
            success: function(response) {
                if (response['status'] == 1) {

                    toastr["success"](response.msg);
                    Datatable();
                } else if (response['status'] == 2) {
                    toastr["error"](response.msg);
                }
            }
        });
    }
} //End of function