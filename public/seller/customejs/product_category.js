/* Project Name: Jwellery 
     Website: https://akestech.com 
     Author: Anoop Vishwakarma */

    $(document).ready(function() {
        Datatable();
    });

$(document).on('submit','#category_form',function(e){
    e.preventDefault();
    $.ajax({
      url:siteUrl+'/save_category',
      type:'post',
      data:new FormData(this),
      processData:false,
      contentType:false,
      success:function(response){
            if(response.status == 1){
                ClearForm();
                  toastr["success"](response.msg);
                  window.location.reload();

                  Datatable();
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

//show featured image 
function showSelectedImage(input=''){
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var data = '<img src="'+e.target.result+'" style="width:100%" >';
            $("#addcode").html(data);
        };
        reader.readAsDataURL(input.files[0]);
    }
}//end of function 

//form clear 
function ClearForm(){
    $("#category_form").trigger('reset');
}//end of day

//show data
function Datatable() {
    $("#category_datatable").DataTable().destroy();
    $("#category_datatable").DataTable({
        processing: true,
        serverSide: true,
        order:[0,'desc'],
        ajax: {
            url: siteUrl + "/show_category",
            data: function (d) {
                    (d.search = $('input[type="search"]').val());
            },
        },
        columns: [
             { data: "id"},
            { data: "title"},
            {data: "status"},
            { data: "action"},
        ],
    });
}//end of fucntion 

//Edit Category
function editCategory(id = "") {
    $.ajax({
        url: siteUrl + "/edit_category",
        data: { id: id },
        type: "get",
        dataType: "json",
        success: function (res) {
            $("#id").val(res.id);
            $("#title").val(res.title); 
            $("#button").text("Update Category");
            $('#addcode').html('<img src="'+res.file_path+'" style="width:100%">');
        },
    });
}//end of function


//Delete function
function deleteCategory(id = ''){
    if (confirm("Are you sure!")) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
            },
        });
        $.ajax({
            url:siteUrl+'/delete_category',
            type:'post',
            data:{id:id},
            success:function(response)
            {
               if(response['status']==1){
                ClearForm();
                    toastr["success"](response.msg);
                    Datatable();
                }else if(response['status']==2){
                    toastr["error"](response.msg);
                    Datatable();
                }
            }
        });
     }

} // End Of function

//Status update function
function statusCategory(id = "", status = "") {
    $.ajax({
        url: siteUrl + "/status_category",
        data: { id: id, status: status },
        type: "get",
        dataType: "json",
        success: function (response) {
            if (response["status"] == 1) {
                toastr["success"](response.msg);
                Datatable();
            } else if (response["status"] == 2) {
                toastr["error"](response.msg);
                Datatable();
            }
        },
    });
}//end of function
