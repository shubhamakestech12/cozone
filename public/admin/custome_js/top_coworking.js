$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
    Datatable();
})

$('#city').on('change', function() {
    var id = $('#city').val();
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
        },
    });
    $.ajax({
        url: siteUrl + '/get_spaces',
        data: { id: id },
        method: 'post',
        success: function(resp) {
            var html = '';
            $.each(resp.data, function(k, v) {
                html += "<option>" + v.space_name + "</option>";
                $('#spaces').html(html);
            })
        }
    })

}); //end of function

$("#frm_top_coworkings").on("submit", function(e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
        },
    });
    $.ajax({
        url: siteUrl + "/save_top_coworking",
        type: "post",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status == 1) {
                $("#id").val('');
                $("#frm_top_coworkings").trigger('reset');
                $("#spaces").text('');

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

function Datatable() {
    $("#plan_top_coworking").DataTable().destroy();
    $("#plan_top_coworking").DataTable({
        processing: true,
        serverSide: true,
        order: [0, 'desc'],
        ajax: {
            url: siteUrl + "/show_top_coworking",
            data: function(d) {
                (d.search = $('input[type="search"]').val());
            },
        },
        columns: [
            { data: "id" },
            { data: "space_names" },
            { data: "space_type_name" },
            { data: "location" },
            { data: "status" },
            { data: "action" },
        ],
    });
} //end of function

function status_top_coworking(id = "", status = "") {
    $.ajax({
        url: siteUrl + "/status_top_coworking",
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

function delete_top_coworking(id = '') {
    if (confirm("Are you sure!")) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
            },
        });
        $.ajax({
            url: siteUrl + '/delete_top_coworking',
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


function edit_topCoworking(id = "") {
    $.ajax({
        url: siteUrl + "/edit_top_coworking",
        data: { id: id },
        type: "get",
        dataType: "json",
        success: function(res) {
            $("#id").val(res.id);
            $("#space_types").val(res.space_type);
            $("#city").val(res.city);
            $(".selection").val(res.space_names);

            $("#button").text("Update");
        },
    });
}