
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
        var pr = document.getElementById("product_form");
        if(pr != undefined){
            productDatatable();
        }
});

//  Dropdown function   
$('#category_id').on('change', function() { 
    var category_id = $('#category_id').val();
    getSubcategory(category_id);
});
function getSubcategory(category_id,subcat_id = null ){
    $("#subcat_id").html('');
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
        },
    });
    $.ajax({
        url:siteUrl+'/get_subcategory_by_category',
        type: "POST",
        data: {
            category_id: category_id
        },
        dataType : 'json',
        success: function(result){ 
            $('#subcat_id').html('<option value="">Select subcategory</option>'); 
            $.each(result.all_subcat,function(key,value){
                var select = subcat_id==value.id?"selected":"";
                $("#subcat_id").append('<option '+select+' value="'+value.id+'">'+value.title+'</option>');
            }); 
        }
    });
}//end of function

// save form
$("#product_form").on("submit", function (e) {
    e.preventDefault();
    var allData = new FormData(this);
    var desc = window.editor.getData();
    allData.append("description", desc);
    $.ajax({
        url: siteUrl + "/save_product",
        type: "post",
        data: allData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status == 1) {
                formclear();
                toastr["success"](response.msg);
                productDatatable();
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

//Form Clear
function formclear() {
    $("#product_form").trigger("reset");
    $("#category_id").val("");
    $("#subcat_id").val("");
    $("#commodity").val("");
    window.editor.setData("");
    $('#addImages').empty();
    $('#addcode').html('<img src="'+siteUrl+'/images/placeholdernew.png" style="width:100%"/>');
}// end of function

function showSelectedImage(input='' , i=''){
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var data = '<img src="'+e.target.result+'" style="width:100%" >';
                $("#addcode").html(data);
            };
            reader.readAsDataURL(input.files[0]);
        }
}//end of function

//show data
function productDatatable() {
    $("#product_datatable").DataTable().destroy();
    $("#product_datatable").DataTable({
        processing: true,
        serverSide: true,
        order:[0,'desc'],
        ajax: {
            url: siteUrl + "/show_product",
            data: function (d) {
                    (d.search = $('input[type="search"]').val());
            },
        },
        columns: [
            { data: "id"},
            { data: "products"},
            { data: "images"},
            { data: "status"},
            { data: "action"},
        ],
    });
}//end of fucntion 

function edit_product(id = "") {
    $.ajax({
        url: siteUrl + "/edit_product",
        data: { id: id },
        type: "get",
        dataType: "json",
        success: function (res) {
            $("#id").val(res.id);
            $("#title").val(res.title);
            $("#category_id").val(res.cat_id);
            getSubcategory(res.cat_id,res.subcat_id)
            $("#commodity").val(res.commodity_type);
            $("#c_weight").val(res.c_weight);
            $("#o_weight").val(res.o_weight);
            $("#make_charge").val(res.make_charge);
            $("#o_charge").val(res.o_charge);
            $("#description").val(res.description);
            window.editor.setData(res.description);
            var images = res.images.split(",");
            var images_id = res.images_id.split(",");
            var html = '';
            $.each(images,function(key , value){
               html += '<div class="col-sm-2 cus_container"><img src=' + value + ' height="100px" width="100px" class="remove" /><div class="overlay"></div><div data-id='+images_id[key]+' class="c_btn" onclick="delete_gallery('+images_id[key]+',true)">X</div></div>';
            })
            $('#addImages').html(html);
            $('#addcode').html('<img src="'+res.featured_image+'" style="width:100%">');
            $("#save_button").text("Update");
        },
    });
}//end of function

//Status update function
function statusProduct(id = "", status = "") {
    $.ajax({
        url: siteUrl + "/status_product",
        data: { id: id, status: status },
        type: "get",
        dataType: "json",
        success: function (response) {
            if (response["status"] == 1) {
                toastr["success"](response.msg);
                productDatatable();
            } else if (response["status"] == 2) {
                toastr["error"](response.msg);
                productDatatable();
            }
        },
    });
}//end of function


//Multiple Product images 
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
});//end of function 



//Image Remove funcction 
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
});//End of function 

//delete gallery
function delete_gallery(id,flag=false) {
    if (confirm("Are you sure!")) {
        $.ajax({
            url: siteUrl + "/delete_gallery",
            type: "post",
            data: { 'id': id , 'flag' : flag},
            success: function (response) {
                if (response["status"] == 1) {
                    toastr["success"](response.msg);
                } else if (response["status"] == 2) {
                    toastr["error"](response.msg);
                }
            },
        });
    }
}//end of function

//Delete function
function deleteProduct(id = ''){
    if (confirm("Are you sure!")) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
            },
        });
        $.ajax({
            url:siteUrl+'/delete_product',
            type:'post',
            data:{id:id},
            success:function(response)
            {
               if(response['status']==1){
                formclear();
                    toastr["success"](response.msg);
                    productDatatable();
                }else if(response['status']==2){
                    toastr["error"](response.msg);
                    productDatatable();
                }
            }
        });
     }

} // End Of function

// show modal images
function showIamge(id){
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
        },
    });
    $.ajax({
        url: siteUrl + "/show_images",
        data: { id: id},
        type: "post",
        dataType: "json",
        success: function (response) {
            if (response["status"] == 1 && response["data"].lengh != 0) {
                    var html="";
                    var html2="";
                    $.each(response["data"],function(index,value){
                        if(value.is_featured == "yes"){
                            html2 += '<div class="col-lg-2 mr-2" style="border:1px solid #e3e6ef;"><a href="'+value.file_path +'" target="_blank"><img src="'+value.file_path+'" id="setimg" style="width:100%"></a></div>';
                        }else{
                            html += '<div class="col-lg-2 mr-2" style="border:1px solid #e3e6ef;"><a href="'+value.file_path +'" target="_blank"><img src="'+value.file_path+'" id="setimg" style="width:100%"></a></div>';
                        }
                    });

                $('#show_img').html(html);
                $('#showfeatured_img').html(html2);
                $('#img_modal').modal('show');
                
            }
             else if (response["status"] == 2) {
                toastr["error"]('No Image Found');
            }
        },
    });
}//End of function 