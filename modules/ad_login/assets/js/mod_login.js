

$(function () {

  $('input[name=username]').on('mouseup', function() { $(this).select(); });
  $('input[name=password]').on('mouseup', function() { $(this).select(); });

  $('input[name=password]').change(function() {
    $('input[name=haspas]').val('');
  });

    $('#tsms-login-form').submit(function() {
      var hp = $('input[name=haspas]').val();
      if(hp!=null && hp.length>0)
      {
        $('input[name=hidden-value]').val(hp);
      }
      else
      {
        $('input[name=hidden-value]').val($.md5($('input[name=password]').val()))
      }
      return true;
    });

   $(document).on('click', '#rememberMe', function() {
     var value = $(this).val();
     if(parseInt(value)==0){
       $(this).val('1');
     }
     else{
       $(this).val('0');
       
     }
     
   });

  $("input:text:visible:first").focus();
 });