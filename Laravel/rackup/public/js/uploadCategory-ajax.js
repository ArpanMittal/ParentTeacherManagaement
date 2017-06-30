/* Create new category*/
$("#create-category").click(function(e){
        e.preventDefault();
        var form_action = $("#newContent").find("form").attr("action");
        var grade = $("#grade").val();
        var  contentName = $("#newContent").find("input[name='contentName']").val();
    
        $.ajax({
        dataType: 'json',
        type:'POST',
        url: form_action,
        data:{grade:grade, contentName:contentName},
        success: function( data ) {
            // alert('success');
            // alert(JSON.stringify(data));
            toastr.success('Created', 'Success Alert', {timeOut: 5000});

            window.location.reload();
            $("#newContent").modal('hide');
            //console.log(JSON.stringify(data));

        },
        error: function(data) {

            toastr.error('Category already exits','Failure Alert', {timeOut: 5000});
            window.location.reload();
            $("#newContent").modal('hide');

            //console.log(JSON.stringify(data));
            // alert('error');
            // alert(JSON.stringify(data));
        }

    });
});





