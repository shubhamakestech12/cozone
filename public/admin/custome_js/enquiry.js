$(document).ready(function() {
    Datatable();
})


function Datatable() {
    $("#enquiry_datatable").DataTable().destroy();
    $("#enquiry_datatable").DataTable({
        processing: true,
        serverSide: true,
        ordering: 'true',
        order: [0, 'desc'],
        ajax: {
            url: siteUrl + "/show_enquiry",
            data: function(d) {
                (d.search = $('input[type="search"]').val());
            },
        },
        columns: [
            { data: "id" },
            { data: "name" },
            { data: "email" },
            { data: "mobile_no" },
            { data: "space_type" },
            { data: "persons" },
        ],
    });
}