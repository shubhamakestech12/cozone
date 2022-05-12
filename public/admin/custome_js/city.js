/* Project Name: Jewellery
     Website: https://akestech.com 
     updated by: Anoop Vishwakarma */
$(document).ready(function() {
    Datatable();
});




// save form
$("#frm_city").on("submit", function(e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
        },
    });
    $.ajax({
        url: siteUrl + "/save-city",
        type: "post",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status == 1) {
                $("#frm_city").trigger('reset');
                $("#id").val('');
                $("#addcode").html("");
                $("#button").text("Save");
                toastr["success"](response.msg);
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
}); //end of function

// Form Clear


function showSelectedImage(input = '', i = '') {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        // debugger;
        if (file.type == "application/pdf") {
            var fileReader = new FileReader();
            fileReader.onload = function() {};

            fileReader.readAsArrayBuffer(file);
            var data = '<img src="' + siteUrl + '/images/placeholdern.png" style="width:100%" >';
            $("#addcode").html(data);
        } else {
            var reader = new FileReader();
            reader.onload = function(e) {
                console.log(e);
                var file = input.files[0];

                var data = '<embed src="' + e.target.result + '" style="width:100%" >';
                $("#addcode").html(data);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
} //End of function

//show data
function Datatable() {
    $("#city_datatable").DataTable().destroy();
    $("#city_datatable").DataTable({
        processing: true,
        serverSide: true,
        order: [0, 'desc'],
        aoColumnDefs: [{ bSortable: false, aTargets: [2, 3] }],
        ajax: {
            url: siteUrl + "/show_city_list",
            data: function(d) {
                (d.search = $('input[type="search"]').val());
            },
        },
        columns: [
            { data: "id" },
            { data: "name" },
            { data: "status" },
            { data: "action" },
        ],
    });
}

// Edit Function

function edit_seller(id = "") {
    $.ajax({
        url: siteUrl + "/edit-city",
        data: { id: id },
        type: "get",
        dataType: "json",
        success: function(res) {
            $("#id").val(res.id);
            $("#city_name").val(res.location);
            $("#country").val(res.country_id);

            $('#addcode').html('<img src="' + res.image + '" style="width:100%">');
            $("#button").text("Update");
        },
    });
}


// Delete function

function delete_seller(id = '') {
    if (confirm("Are you sure!")) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
            },
        });
        $.ajax({
            url: siteUrl + '/delete-city',
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

//Status update function
function status_seller(id = "", status = "") {
    $.ajax({
        url: siteUrl + "/status-city",
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