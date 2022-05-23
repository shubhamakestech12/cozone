$(document).ready(function() {
    // addPlans();
    Datatable();
}); //end of function
var id = "1"

function addPlans() {
    var html = "";
    $.ajax({
        url: siteUrl + '/get_plans',
        method: 'get',
        success: function(resp) {
            var options = '';
            $.each(resp.data, function(k, v) {
                options += '<option  value = "' + v.id + '" > ' + v.plan_name + ' </option>';
            })
            console.log(options);
            html = '<div class="row mt-2 plans_added_' + id + '"> <div class="col-sm-6 col-md-6"> <select   class="form-control" required name="plans[]" id="plans"> <option value="">Select Membership</option> ' + options + ' </select> </div> <div class="col-sm-4 col-md-4"> <input class="form-control input-sm" placeholder="Enter Price" type="number" required name="price[]" id="price"> </div> <div class="col-sm-2 col-md-2"> <button onclick="deletePlans(this)" type="button" class="btn btn-danger btn-sm">-</button> </div> </div>';
            $('#addPlans').after(html);
        }
    });

    id++
} //end of function

function deletePlans(obj) {
    $(obj).parent().parent().remove();
} //end of function


// save form
$("#frm_property_details").on("submit", function(e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
        },
    });
    $.ajax({
        url: siteUrl + "/save_property_details",
        type: "post",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status == 1) {
                $("#id").val('');
                $("#frm_property_details").trigger('reset');
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
    $("#property_details_datatable").DataTable().destroy();
    $("#property_details_datatable").DataTable({
        processing: true,
        serverSide: true,
        order: [0, 'desc'],
        ajax: {
            url: siteUrl + "/show_property_details",
            data: function(d) {
                (d.search = $('input[type="search"]').val());
            },
        },
        columns: [
            { data: "id" },
            { data: "title" },
            { data: "address" },
            { data: "area" },
            { data: "open_time" },
            { data: "close_time" },
            { data: "status" },
            { data: "action" },
        ],
    });
} //end of function

function status_property_details(id = "", status = "") {
    $.ajax({
        url: siteUrl + "/status_property_details",
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

function delete_property_details(id = '') {
    if (confirm("Are you sure!")) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
            },
        });
        $.ajax({
            url: siteUrl + '/delete_property_details',
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

function edit_property_details(id = "") {

    $.ajaxSetup({
            headers: {
                "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
            },
        });
    $.ajax({
        url: siteUrl + "/edit_property_details",
        data: { id: id },
        type: "post",
        dataType: "json",
        success: function(res) {
            console.log(res);
            $("#id").val(res.id);
            $("#property_name").val(res.property_id);
            $("#area").val(res.area);
            $("#address").val(res.address);
            $("#close_time").val(res.close_time);
            $("#open_time").val(res.open_time);
            $("#about").val(res.about);
            $("#button").text("Update");
        },
    });
}