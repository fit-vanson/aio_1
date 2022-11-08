$(document).ready(function() {
    $(document).on('click','.check_Status_CHPLAY', function () {
        var _id = $(this).data("package");
        var btn = $(this);
        $.ajax({
            type: "get",
            url: "cronProject/chplay?projectID=" + _id + '&return=true',
            success: function (data) {
                var status = data.status_app;
                var html = '<span data-package="'+_id+'" class="check_Status_CHPLAY badge badge-'
                switch (status){
                    case 1:
                        html +=  'success "> Publish';
                        break;
                    case 2:
                        html +=  'warning "> Suppend';
                        break;
                    case 3:
                        html += 'info"> UnPublish';
                        break;
                    case 4:
                        html +=  'primary"> Remove';
                        break;
                    case 5:
                        html +=  'dark"> Reject';
                        break;
                    case 6:
                        html +=  'danger"> Check ';
                        break;
                    case 7:
                        html +=  'warning"> Pending';
                        break;
                    default:
                        html += 'secondary"> Mặc định';
                        break;
                }
                html += '</span>';
                btn.replaceWith(html);
                $('#app_link_'+_id).attr("href",data.app_link);
                $.notify(data.package, "success");
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    })

    $(document).on('click','.check_Status_SAMSUNG', function () {
        var _id = $(this).data("package");
        var btn = $(this);
        $.ajax({
            type: "get",
            url: "cronProject/samsung?projectID=" + _id ,
            success: function (data) {

                if(data.error){
                    $.notify(data.error, "error");
                }
                if(data.success){
                    var status = data.project.status_app;
                    var html = '<span data-package="'+_id+'" class="check_Status_SAMSUNG badge badge-'
                    switch (status){
                        case 1:
                            html +=  'success "> Publish';
                            break;
                        case 2:
                            html +=  'warning "> Suppend';
                            break;
                        case 3:
                            html += 'info"> UnPublish';
                            break;
                        case 4:
                            html +=  'primary"> Remove';
                            break;
                        case 5:
                            html +=  'dark"> Reject';
                            break;
                        case 6:
                            html +=  'danger"> Check ';
                            break;
                        case 7:
                            html +=  'warning"> Pending';
                            break;
                        default:
                            html += 'secondary"> Mặc định';
                            break;
                    }
                    html += '</span>';
                    btn.replaceWith(html);
                    $('#app_link_'+_id).attr("href",data.app_link);
                    $.notify(data.package, "success");
                }


            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    })
});
