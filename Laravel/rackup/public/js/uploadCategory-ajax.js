/* Create new category*/
$("#create-category").click(function(e){
    //alert('on click');
    e.preventDefault();
    var form_action = $("#newContent").find("form").attr("action");
    var grade = $("#grade:checked").val();
    var  contentName = $("#newContent").find("input[name='contentName']").val();
    //alert(form_action);
    //alert(grade);
    //alert(contentName);
    $.ajax({
        dataType: 'json',
        type:'POST',
        url: 'createCategory',
        data:{grade:grade, contentName:contentName},
        success: function( data ) {
            //alert('success');
            //alert(JSON.stringify(data));
            $(".modal").modal('hide');
            toastr.success('Created', 'Success Alert', {timeOut: 5000});
            //console.log(JSON.stringify(data));
            window.location.reload();
        },
        error: function(data) {
            $(".modal").modal('hide');
            toastr.error('Category already exits','Failure Alert', {timeOut: 5000});
            //window.location.reload();
            //console.log(JSON.stringify(data));
            //alert('error');
            //alert(JSON.stringify(data));
        }

    });
});
