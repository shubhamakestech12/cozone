/* Project Name: Jwellery 
     Website: https://akestech.com 
     Author: Anoop Vishwakarma */

     $(document).ready(function() {
        Datatable();
    });

$('#subcategory_form').on('submit',function(e){
    e.preventDefault();
    $.ajax({
      url:siteUrl+'/save_subcategory',
      type:'post',
      data:new FormData(this),
      processData:false,
      contentType:false,
      success:function(response){
            if(response.status == 1){
                ClearForm();
                  toastr["success"](response.msg);
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

//form clear 
function ClearForm(){
    $("#subcategory_form").trigger('reset');
    $('#button').text('Add Category');
}//end of day

//show data
function Datatable() {
    $("#subcat_datatable").DataTable().destroy();
    $("#subcat_datatable").DataTable({
        processing: true,
        serverSide: true,
        order:[0,'desc'],
        ajax: {
            url: siteUrl + "/show_subcategory",
            data: function (d) {
                    (d.search = $('input[type="search"]').val());
            },
        },
        columns: [
             { data: "id"},
            { data: "title"},
            {data: "cat_name"},
            {data: "status"},
            { data: "action"},
        ],
    });
}//end of fucntion 

// edit 
function editSubCategory(id = "") {
    $.ajax({
        url: siteUrl + "/edit_subcategory",
        data: { id: id },
        type: "get",
        dataType: "json",
        success: function (res) {
            $("#id").val(res.id);
            $("#title").val(res.title); 
            $("#category1").val(res.category_id);
            $("#button").text("Update");
        },
    });
}//end of function


//Delete function
function deleteSubCategory(id = ''){
    if (confirm("Are you sure!")) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": jQuery("meta[name=_token]").attr("content"),
            },
        });
        $.ajax({
            url:siteUrl+'/delete_subcategory',
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
function statusSubCategory(id = "", status = "") {
    $.ajax({
        url: siteUrl + "/status_subscategory",
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
