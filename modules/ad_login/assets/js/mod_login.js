$(document).ready(function(){
    //first load
    modLogin.run(false);
});

var modLogin = {
    handel: function(){
        $(document).on('click','.btn-login',function(){
            $('#password_md5').val($.md5($('#password').val()));
        });
    },
    setup: function(){
        this.handel();
    },
    run: function(){
        this.setup();
    }
}