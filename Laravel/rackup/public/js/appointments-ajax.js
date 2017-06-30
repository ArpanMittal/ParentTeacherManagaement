var page = 1;
var current_page = 1;
var total_page = 0;
var is_ajax_fire = 0;

manageData();

/* manage data*/
function manageData() {
    //alert("manage data");
    $.ajax({
        dataType: 'json',
        url: url,
        data: {page:page}
    }).done(function(data){

        total_page = data.last_page;
        current_page = data.current_page;

        $('#pagination').twbsPagination({
            totalPages: total_page,
            visiblePages: current_page,
            onPageClick: function (event, pageL) {
                page = pageL;
                if(is_ajax_fire != 0){
                    getPageData();
                }
            }
        });

        manageRow(data.data);
        is_ajax_fire = 1;
    });
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

/* Get Page Data*/
function getPageData() {
    //alert("get page data");
    $.ajax({
        dataType: 'json',
        url: url,
        data: {page:page}
    }).done(function(data){
        manageRow(data.data);
    });
}
/* Add new rows */
function manageRow(data) {
    //alert("manage rows");
    var	rows = '';
    $.each( data, function( key, value ) {
        rows = rows + '<tr>';
        rows = rows + '<td>'+value.id+'</td>';
        rows = rows + '<td>'+value.name+'</td>';
        rows = rows + '<td>'+value.dates+'</td>';
        rows = rows + '<td>'+value.day+'</td>';
        rows = rows + '<td>'+value.duration+'</td>';
        rows = rows + '<td>'+value.booked+'</td>';
        rows = rows + '</tr>';
    });

    $("tbody").html(rows);
}

/* Get appointments */
$(".crud-submit").click(function(e){
    //alert("get-appointments");
    e.preventDefault();
    var form_action = $("#get-appointments").find("form").attr("action");
    var teacherName = $("#teacherName").val();
    //alert(teacherName);
    $.ajax({
        dataType: 'json',
        type:'POST',
        url: form_action,
        data:{teacherName:teacherName}
    ,success: function( data ) {
            //getPageData();
            manageRow(data);
            $(".modal").modal('hide');
            toastr.success('Appointments', 'Success Alert', {timeOut: 5000});
           //alert(JSON.stringify(data));
    },
        error: function(data) {
            console.log(JSON.stringify(data));
            //alert('error');
        }
    });
});


