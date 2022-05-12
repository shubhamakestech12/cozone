/* Project Name: Cospace
     Website: https://akestech.com 
     updated by: Shubham chuadhary */
$(document).ready(function() {
    Datatable();

});

// save form
$("#frm_enter_prise").on("submit", function(e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
        },
    });
    $.ajax({
        url: siteUrl + "/save-enterprise-plans",
        type: "post",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status == 1) {
                $("#frm_enter_prise").trigger('reset');
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
    $("#enter_prise_datatable").DataTable().destroy();
    $("#enter_prise_datatable").DataTable({
        processing: true,
        serverSide: true,
        ordering: 'true',
        order: [0, 'desc'],
        ajax: {
            url: siteUrl + "/show-enterprise-plans",
            data: function(d) {
                (d.search = $('input[type="search"]').val());
            },
        },
        columns: [
            { data: "id" },
            { data: "name" },

            { data: "description" },
            { data: "status" },
            { data: "action" },
        ],
    });
}

//Commodity status function
function status_plan(id = "", status = "") {
    $.ajax({
        url: siteUrl + "/status-enterprise-plans",
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
        url: siteUrl + "/edit-enterprise-plans",
        data: { id: id },
        type: "get",
        dataType: "json",
        success: function(res) {
            $("#id").val(res.id);
            $("#plan_name").val(res.plan_name);
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
            url: siteUrl + '/delete-enterprise-plans',
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