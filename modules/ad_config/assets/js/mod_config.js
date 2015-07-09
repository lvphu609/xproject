$(document).ready(function(){
    //first load
    postTypesPageList.run();
});

var postTypesPageList = {
    popupModelDelete:  $('#modalDeleteItem'),
    handel: function(){
        $(document).on('click','.btn-save-post-type',function(){
            $('#frm_post_type').submit();
        });
    },
    delete: function(){
        var that = this;

        $(document).on('click','.button-delete-post-type',function(){
            var id = $(this).attr('data-id');

            var $postTypeName = $('.post-type-' + id).children().html();

            that.popupModelDelete.find('.text-name-replace').html($postTypeName);

            that.popupModelDelete.find('.message-alert').html('');

            that.popupModelDelete.find('.btn-delete-confirm').attr('data-id',id);

            that.popupModelDelete.modal('show');
        });

        $(document).on('click','.btn-delete-confirm',function(){
            adminScript.loading.show();
            var id = $(this).attr('data-id');
            var url = $('#hidUrl').val() + 'post_types/delete';

            var data = {
                id: id
            };
            var ajax = $.ajax({
                url: url,
                data: data,
                method: 'POST',
                dataType: 'json',
                statusCode: {
                    404: function () {
                        adminScript.loading.hide();
                        console.log("page not found");
                    },
                    500: function (data) {
                        adminScript.loading.hide();
                        console.log(data);
                    }
                }
            });

            ajax.done(function (data) {
                if(data.status == "success"){
                    $('.post-type-' + id).remove();
                    that.popupModelDelete.modal('hide');
                }
                else{
                    that.popupModelDelete.find('.message-alert').html(data.message);
                }
                adminScript.loading.hide();
            });
        });

    },
    imageCrop: function(){
        $(document).on('click','.avatar-post-type', function () {
            var $imageUrl = $('#img_url').val();
            $('.image-editor').cropit({
                imageState: {
                    src: $imageUrl
                },
                imageBackground: true,
                imageBackgroundBorderWidth: 15
            });
        });

        $('.export').click(function() {
            var imageData = $('.image-editor').cropit('export');
            $('#img_base64').val(imageData);
            $('.avatar-post-type').attr('src',imageData);
            $('.avatar-post-type').parent().removeClass('has-error');
            $('#myModal').modal('hide');
        });
    },
    setup: function(){
        this.delete();
        this.imageCrop();
        this.handel();
    },
    run: function(){
        this.setup();
    }
}