/* Project Name: Cozone
     Website: https://akestech.com 
     updated by: Shubham chaudhary */
$(document).ready(function() {
    Datatable();
    $('.js-example-basic-multiple').select2();

});

// save form
$("#frm_add_space").on("submit", function(e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
        },
    });
    $.ajax({
        url: siteUrl + "/save_add_space",
        type: "post",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status == 1) {
                $("#frm_add_space").trigger('reset');
                $("#id").val("");
                $("#addcode").html("");
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
    $("#add_spacetype_datatable").DataTable().destroy();
    $("#add_spacetype_datatable").DataTable({
        processing: true,
        serverSide: true,
        ordering: 'true',
        order: [0, 'desc'],
        ajax: {
            url: siteUrl + "/show_add_space",
            data: function(d) {
                (d.search = $('input[type="search"]').val());
            },
        },
        columns: [
            { data: "id" },
            { data: "name" },
            { data: "space_name" },
            { data: "address" },
            { data: "city_name" },
            { data: "seat_capacity" },
            { data: "area" },
            { data: "price" },
            { data: "status" },
            { data: "action" },
        ],
    });
}

//Commodity status function
function status_add_space(id = "", status = "") {
    $.ajax({
        url: siteUrl + "/status_add_space",
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
function edit_add_space(id = "") {
    $.ajax({
        url: siteUrl + "/edit_add_space",
        data: { id: id },
        type: "get",
        dataType: "json",
        success: function(res) {
            $("#id").val(res.id);
            $("#space_name").val(res.space_name);
            $("#space_type").val(res.space_type);
            $("#address").val(res.address);
            $("#city").val(res.city_id);
            $("#seat_capacity").val(res.seat_capacity);
            $("#area_type").val(res.area);
            $("#email").val(res.email);
            $("#mobile").val(res.mobile);
            $("#button").text('Update');
        },
    });
} //end functtion

//Delete Function
function delete_add_space(id = '') {
    if (confirm("Are you sure!")) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
            },
        });
        $.ajax({
            url: siteUrl + '/delete_add_space',
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



function showSelectedImage(input = "", i = "") {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var data =
                '<img src="' +
                e.target.result +
                '" width="100%" height="250px" >';
            $("#addcode" + i).html(data);
        };
        reader.readAsDataURL(input.files[0]);
    }
} //end of function

$(function() {
    $(":file").change(function() {
        if (this.files && this.files[0]) {
            for (var i = 0; i < this.files.length; i++) {
                var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[i]);
            }
        }
    });
}); //end of function

function imageIsLoaded(e) {
    $("#myImg").append(
        "<img src=" +
        e.target.result +
        ' class="mr-2" style="border:1px solid black;padding:2px;"  height="100px" width="100px">'
    );
} //end oif function