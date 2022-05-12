
$(document).ready(function () {
    ClassicEditor.create(document.querySelector(".description"), {
        licenseKey: "",
    })
        .then((editor) => {
            window.editor = editor;
        })
        .catch((error) => {
            console.error("Oops, something went wrong!");
        });
    showProfiledata();
    setSellerConfig();
});

   //  Dropdown function   
$('#country-dropdown').on('change', function() { 
    getState();
});
function getState(){
    var country_id = $('#country-dropdown').val();
    $("#state-dropdown-value").html('');
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
        },
    });
    $.ajax({
        url:siteUrl+'/get_states_by_country',
        type: "POST",
        data: {
        country_id: country_id
        },
        dataType : 'json',
        success: function(result){ 
            $('#state-dropdown-value').html('<option value="">Select State</option>'); 
            $.each(result.states,function(key,value){
            $("#state-dropdown-value").append('<option value="'+value.id+'">'+value.name+'</option>');
            });
            $('#city-dropdown').html('<option value="">Select State First</option>'); 
        }
    });
}
$('#state-dropdown-value').on('change', function() {
    getCity();
});
function getCity(){
var state_id = $('#state-dropdown-value').val();
$("#city-dropdown").html('');
$.ajaxSetup({
    headers: {
        "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
    },
});
$.ajax({
    url:siteUrl+'/get_cities_by_state',
    type: "POST",
    data: {
    state_id: state_id
    },
    dataType : 'json',
    success: function(result){
        $('#city-dropdown').html('<option value="">Select City</option>'); 
        $.each(result.cities,function(key,value){
        $("#city-dropdown").append('<option value="'+value.id+'">'+value.name+'</option>');
        });
    }
});
} // end of function

function showProfiledata() {    
    $.ajax({
        url: siteUrl + "/set_seller_data",
        type: "get",
        dataType: "json",
        success: function (res) {
            $("#id").val(res.id);
            $("#name").val(res.name);
            $("#mob").val(res.mob_no);
            $("#mob").prop('disabled', true);
            $("#business").val(res.business_name);
            $("#reg").val(res.reg_no);
            $("#address").val(res.address);
            $("#description").val(res.description);
            window.editor.setData(res.description);
            $("#p_number").val(res.pin);
            $("#country-dropdown").val(res.country);
            $("#state-dropdown").val(res.state);
            $("#city_dropdown").val(res.city); 
            if(res.country != ""){
                getStateUpdate();
            }
            if(res.state != ""){
                getCityUpdate();
            }
            $('#addcode').html('<img src="'+res.certificate+'" style="width:100%">');
            $('#addlogo').html('<img src="'+res.logo+'" style="width:100%">');  
            $("#button").text("Update Profile");
            $("#desc_button").text("Update");
            $("#gallery_button").text("Add Gallery");
        },
    });
}


function setSellerConfig(){
    $.ajax({
        url: siteUrl + "/set_seller_config",
        type: "get",
        dataType: "json",
        success: function (res) {
            $("#id").val(res.id);
            $("#expe_del").val(res.expe_delivery);
            $("#minovalue").val(res.min_ord_value);
            $("#config_button").text('Update');
            
        },
    });
}//end of function

// update seller config form
$("#config_form").on("submit", function (e) {
    e.preventDefault();
    var id = $('#id').val();
    var data = new FormData(this);
    data.append('id',id);
    $.ajax({
        url: siteUrl + "/edit_seller_config",
        type: "post",
        data: data,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status == 1) {
                toastr["success"](response.msg);
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
});// seller config

function getStateUpdate() {
    var country_id = $('#country-dropdown').val();
    var state_id = $('#state-dropdown').val();
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
        },
    });
    $.ajax({
        url: siteUrl + '/get_states_by_country',
        type: 'post',
        data: { country_id: country_id },
        success: function (response) {
            var html = '<option>Select State</option>';
            $.each(response.states, function (_key, value) {
                html += "<option ";
                html += (state_id == value.id) ? 'selected' : '';
                html += " value='";
                html += value.id + "' >" + value.name + " </option>";
            });
            $("#state-dropdown-value").html(html);
        },
    });
}

function getCityUpdate() {
    var state_id = $('#state-dropdown').val();
    var city_id = $('#city_dropdown').val();
    $.ajax({
        url: siteUrl + '/get_cities_by_state',
        type: 'post',
        data: { state_id: state_id },
        success: function (response) {
            var html = '<option>Select City</option>';
            $.each(response.cities, function (key, value) {
                html += "<option ";
                html += (city_id == value.id) ? 'selected' : '';
                html += " value='";
                html += value.id + "' >" + value.name + " </option>";
            });
            $("#city-dropdown").html(html);
        },
    });
}

// save form
$("#seller_form").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
        url: siteUrl + "/edit_seller_profile",
        type: "post",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status == 1) {
                toastr["success"](response.msg);
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

function showSelectedImage(input=''){
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var data = '<img src="'+e.target.result+'" style="width:100%" >';
                $("#addcode").html(data);
            };
            reader.readAsDataURL(input.files[0]);
        }
}

function showSelectedlogo(input='' , i=''){
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var data = '<img src="'+e.target.result+'" style="width:100%" >';
            $("#addlogo").html(data);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Description save form
$("#dese_form").on("submit", function (e) {
    e.preventDefault();
    var desc = window.editor.getData();
    var id = $('#id').val();
    $.ajax({
        url: siteUrl + "/description",
        type: "post",
        data: {'description':desc, 'id':id},
        success: function (response) {
            if (response.status == 1) {
                toastr["success"](response.msg);
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
});//end of function

$(function() {
    $("#files").on('change',function() {
        if (this.files && this.files[0]) {
            for (var i = 0; i < this.files.length; i++) {
            var reader = new FileReader();
            position = 0 ;
            reader.onload = function(e) {
            $('#addImages').append('<div class="col-sm-2 cus_container"><img src=' + e.target.result + ' height="100px" width="100px" class="remove" /><div class="overlay"></div><div data-id='+position+' class="close_btn">X</div></div>');
            position++;
            }
            reader.readAsDataURL(this.files[i]);
            }
        }
    });
});
$(document).on('click','.close_btn',function(){
    const dt = new DataTransfer();
    const input = document.getElementById('files');
    const { files } = input;
    const position = $(this).attr('data-id');
    for (let i = 0; i < files.length; i++) {
    var file = files[i]
    if (position != i)
    dt.items.add(file) // here you exclude the file. thus removing it.
    }
    input.files = dt.files;
    $(this).parent().remove();
});

$('#gallery_form').on('submit',function(e){
    e.preventDefault();
        $.ajax({
        url:siteUrl+'/save_gallery',
        type:'post',
        data:new FormData(this),
        processData: false,
        contentType: false,
        success:function(response){
            if(response.status == 1){
                toastr["success"](response.msg); 
        }else if(response.status == 9){
            var dd = response.error ;
            for(var i=0; i<dd.length;i++){
                toastr["error"](dd[i]);
            }
        }else if(response.status == 2){
            toastr["error"](response.msg);
        }
    }
    });
});

function delete_gallery(id) {
    if (confirm("Are you sure!")) {
        $.ajax({
            url: siteUrl + "/delete_gallery",
            type: "post",
            data: { 'id': id },
            success: function (response) {
                if (response["status"] == 1) {
                    toastr["success"](response.msg);
                } else if (response["status"] == 2) {
                    toastr["error"](response.msg);
                }
            },
        });
    }
}