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

        $(document).on('click','.buttonDelete',function(){
            var id = $(this).attr('data-id');

            that.popupModelDelete.find('.messageAlert').html('');

            that.popupModelDelete.find('.btnConfirmDelete').attr('data-id',id);

            that.popupModelDelete.modal('show');
        });

        $(document).on('click','.btnConfirmDelete',function(){
            loadPageProcess.run(true);
            var id = $(this).attr('data-id');
            var url = $('#hidUrl').val() + 'del_article';

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
                        loadPageProcess.run(false);
                        console.log("page not found");
                    },
                    500: function (data) {
                        loadPageProcess.run(false);
                        console.log(data);
                    }
                }
            });

            ajax.done(function (data) {
                if(data.status == "success"){
                    $('.art-' + id).remove();
                    that.popupModelDelete.modal('hide');
                }
                else{
                    that.popupModelDelete.find('.messageAlert').html(data.message);
                }

                loadPageProcess.run(false);
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