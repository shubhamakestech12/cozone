/* Project Name: Jewellery
     Website: https://akestech.com 
     updated by: Anoop Vishwakarma */
$(document).ready(function() {
    Datatable();
});



// save form
$("#frm_country").on("submit", function(e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
        },
    });
    $.ajax({
        url: siteUrl + "/save_country",
        type: "post",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status == 1) {
                $("#id").val('');
                $("#frm_country").trigger('reset');
                ClearForm();
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

// Form Clear
function ClearForm() {
    $("#id").trigger("reset");
    $('#country_name').trigger('reset');
    $('#button').text('Save');
    $('#addcode').html('<img src="' + siteUrl + '/images/placeholdernew.png" style="width:100%" height="50px"/>');
} // end of function

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
    $("#country_datatable").DataTable().destroy();
    $("#country_datatable").DataTable({
        processing: true,
        serverSide: true,
        order: [0, 'desc'],
        ajax: {
            url: siteUrl + "/show_country",
            data: function(d) {
                (d.search = $('input[type="search"]').val());
            },
        },
        columns: [
            { data: "id" },
            { data: "country_name" },
            { data: "status" },
            { data: "action" },
        ],
    });
}

// Edit Function

function edit_seller(id = "") {
    $.ajax({
        url: siteUrl + "/edit_country",
        data: { id: id },
        type: "get",
        dataType: "json",
        success: function(res) {
            $("#id").val(res.id);
            $("#country_name").val(res.name);
            $("#mob").val(res.mob_no);
            $("#business").val(res.business_name);
            $("#reg").val(res.reg_no);
            $("#address").val(res.address);
            $("#p_number").val(res.pin);
            $("#country-dropdown").val(res.country);
            $("#state-dropdown").val(res.state);
            $("#city_dropdown").val(res.city);

            $('#addcode').html('<img src="' + res.flag + '" style="width:100%">');
            $("#button").text("Update");
        },
    });
}

// function getStateUpdate() {
//     var country_id = $('#country-dropdown').val();
//     var state_id = $('#state-dropdown').val();
//     $.ajaxSetup({
//         headers: {
//             "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
//         },
//     });
//     $.ajax({
//         url: siteUrl + '/get_states_by_countrys',
//         type: 'post',
//         data: { country_id: country_id },
//         success: function(response) {
//             var html = '<option>Select State</option>';
//             $.each(response.states, function(_key, value) {
//                 html += "<option ";
//                 html += (state_id == value.id) ? 'selected' : '';
//                 html += " value='";
//                 html += value.id + "' >" + value.name + " </option>";
//             });
//             $("#state-dropdown-value").html(html);
//         },
//     });
// }

// function getCityUpdate() {
//     var state_id = $('#state-dropdown').val();
//     var city_id = $('#city_dropdown').val();
//     $.ajax({
//         url: siteUrl + '/get_cities_by_states',
//         type: 'post',
//         data: { state_id: state_id },
//         success: function(response) {
//             var html = '<option>Select City</option>';
//             $.each(response.cities, function(key, value) {
//                 html += "<option ";
//                 html += (city_id == value.id) ? 'selected' : '';
//                 html += " value='";
//                 html += value.id + "' >" + value.name + " </option>";
//             });
//             $("#city-dropdown").html(html);
//         },
//     });
// }


// Delete function

function delete_seller(id = '') {
    if (confirm("Are you sure!")) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
            },
        });
        $.ajax({
            url: siteUrl + '/delete_country',
            type: 'post',
            data: { id: id },
            success: function(response) {
                if (response['status'] == 1) {
                    ClearForm();
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
        url: siteUrl + "/status_country",
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