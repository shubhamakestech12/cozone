$(document).ready(function() {
    // addPlans();
    Datatable()
}); //end of function



// save form
$("#frm_property_memebership").on("submit", function(e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
        },
    });
    $.ajax({
        url: siteUrl + "/save_property_membership",
        type: "post",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status == 1) {
                $("#id").val('');
                $("#frm_property_memebership").trigger('reset');
                toastr["success"](response.msg);
                Datatable();
                $("#button").text("Save");
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
}); //end of function


//show data
function Datatable() {
    $("#property_membership_datatable").DataTable().destroy();
    $("#property_membership_datatable").DataTable({
        processing: true,
        serverSide: true,
        order: [0, 'desc'],
        ajax: {
            url: siteUrl + "/show_property_membership",
            data: function(d) {
                (d.search = $('input[type="search"]').val());
            },
        },
        columns: [
            { data: "id" },
            { data: "title" },
            { data: "plan_name" },
            { data: "price" },
            { data: "amenties" },
            { data: "status" },
            { data: "action" },
        ],
    });
} //end of function

function status_property_memebership(id = "", status = "") {
    $.ajax({
        url: siteUrl + "/status_property_membership",
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
} //end of function

function delete_property_memebership(id = '') {
    if (confirm("Are you sure!")) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
            },
        });
        $.ajax({
            url: siteUrl + '/delete_property_membership',
            type: 'post',
            data: { id: id },
            success: function(response) {
                if (response['status'] == 1) {
                    toastr["success"](response.msg);
                    Datatable();
                } else if (response['status'] == 2) {
                    toastr["error"](response.msg);
                    Datatable();
                }
            }
        });
    }

} // End Of function


// Edit Function

function edit_property_memebership(id = "") {
    $.ajax({
        url: siteUrl + "/edit_property_membership",
        data: { id: id },
        type: "get",
        dataType: "json",
        success: function(res) {
            $("#id").val(res.id);
            $("#property").val(res.property_id);
            $("#plan").val(res.plan_id);
            $("#plan").val(res.plan_id);
            $("#price").val(res.price);
            $("#button").text("Update");
        },
    });
}