/* Project Name: Jewellery
     Website: https://akestech.com 
     updated by: Anoop Vishwakarma */
$(document).ready(function() {
    Datatable();
});

// save form
$("#frm_amenties").on("submit", function(e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
        },
    });


    $.ajax({
        url: siteUrl + "/save_amenties",
        type: "post",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status == 1) {
                $("#frm_amenties").trigger('reset');
                $("#id").val('');
                $("#addcode_amenty").html("");
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
    $("#amenty_datatable").DataTable().destroy();
    $("#amenty_datatable").DataTable({
        processing: true,
        serverSide: true,
        ordering: 'true',
        order: [0, 'desc'],
        ajax: {
            url: siteUrl + "/show_amenties",
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

//Commodity status function
function status_space(id = "", status = "") {
    $.ajax({
        url: siteUrl + "/status_amenties",
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
function edit_amenty(id = "") {
    $.ajax({
        url: siteUrl + "/edit_amenties",
        data: { id: id },
        type: "get",
        dataType: "json",
        success: function(res) {
            $("#id").val(res.id);
            $("#amenties").val(res.name);

            $("#button").text('Update');
        },
    });
} //end functtion

//Delete Function
function delete_amenty(id = '') {
    if (confirm("Are you sure!")) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
            },
        });
        $.ajax({
            url: siteUrl + '/delete_amenties',
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

// Form Clear
// function ClearForm() {
//     $("#commodity_form").trigger("reset");
//     $('#button').text('submit');
// } // end of function

//date picker
$('#date').daterangepicker({
    singleDatePicker: true,
    minDate: new Date(),
    showDropdowns: true,
    locale: {
        format: 'DD/MM/Y'
    }
});

function showSelectedImage(input = '', i = '') {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        // debugger;
        if (file.type == "application/pdf") {
            var fileReader = new FileReader();
            fileReader.onload = function() {};

            fileReader.readAsArrayBuffer(file);
            var data = '<img src="' + siteUrl + '/images/placeholdern.png" style="width:100%" >';
            $("#addcode_amenty").html(data);
        } else {
            var reader = new FileReader();
            reader.onload = function(e) {
                console.log(e);
                var file = input.files[0];

                var data = '<embed src="' + e.target.result + '" style="width:100%" >';
                $("#addcode_amenty").html(data);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
} //End of function