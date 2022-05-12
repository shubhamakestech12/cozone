/* Project Name:Jewelley 
   Website: https://akestech.com 
   updated by: Anoop Vishwakarma */

// for Admin Login
$("#loginForm").on("submit", function (e) {
    //alert('ok');
    e.preventDefault();
    $.ajax({
        url: siteUrl + "/auth",
        type: "post",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status == 1) {
                window.location.href = siteUrl + "/purchase_order";
            } else if (response.status == 2) {
                var dd = response.error;
                for (var i = 0; i < dd.length; i++) {
                    toastr["error"](dd[i]);
                }
            } else if (response.status == 3) {
                toastr["error"]("Wrong Mobile Number or password ");
            }
        },
    });
});

$("#show").on("click", function () {
    var type = $("#password-field").attr("type");
    $(this).toggleClass("fa-eye fa-eye-slash");
    if (type == "password") {
        $("#password-field").attr("type", "text");
    } else {
        $("#password-field").attr("type", "password");
    }
});
