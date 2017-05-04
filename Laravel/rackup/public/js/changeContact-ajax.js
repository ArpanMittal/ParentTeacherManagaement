/* Change contact number*/
$("#change-contact").click(function(e){
    e.preventDefault();
    var form_action = $("#contactNumber").attr("action");
    var contact = $("#contactNo").find('input[name="contact"]').val();
    $.ajax({
        dataType: 'json',
        type:'POST',
        url: url,
        data:{contact:contact},
        success: function( data ) {
            //alert('success');
            //alert(JSON.stringify(data));
            toastr.success('Whatsapp video call will be redirected to '+contact, 'Success Alert', {timeOut: 5000});
            window.location.reload();
        },
        error: function(data) {
            //alert('error');
            //alert(JSON.stringify(data));
            toastr.error('Cannot update contact number','Failure Alert', {timeOut: 5000});
            window.location.reload();
            //console.log(JSON.stringify(data));
        }

    });
});