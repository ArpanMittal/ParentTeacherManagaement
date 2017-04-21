$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(".crud-submit").click(function(e){
    //alert("confirm");
    e.preventDefault();
    var appointmentId = $(this).attr('id');
    //alert(appointmentId);
    $.ajax({
        dataType: 'json',
        type:'POST',
        url: "teacherAppointments",
        data:{appointmentId:appointmentId}
        ,success: function( data ) {
            //alert('success');
            $(".modal").modal('hide');
            toastr.success('Appointments', 'Success Alert', {timeOut: 5000});
            console.log(JSON.stringify(data));
            window.location.reload();
        },
        error: function(data) {
            console.log(JSON.stringify(data));
            //alert('error');
        }
    });
});

