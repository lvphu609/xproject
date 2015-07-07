$(function () {
  
 /* ===========================*/
 
 $(document).on('click', '.addNewTaskType', function () {
     var ajax = $.ajax({
      url: $('#hidUrl').val() + 'home/checkUserLoginPermission/',
      data: {resource_id:'category/activate/'},
      method: 'POST',
      dataType: 'json',
      statusCode: {
        404: function () {
          loading(false);
          //console.log("page not found");
        },
        500: function (obj) {
          loading(false);
          //console.log(obj);
        }
       }
      });
      ajax.done(function (obj) {
        //console.log(obj);
        if (obj.status == 'success')
        { 
          task_type_resetForm();
          $('#modalTaskTypeInfo').find('.titleAddNew').show();
          $('#modalTaskTypeInfo').find('.titleModify').hide();
           $('#modalTaskTypeInfo').find('#buttonSaveAndNewTaskTypeInfo').show();
          showTaskTypeInfo('show');
        }else{
          permission_message(true);
        }
      });
  });
 function task_type_resetForm()
  {
    curr_id = 0;
    $('#modalTaskTypeInfo').find('input.od').val("0");
    $('#modalTaskTypeInfo').find('input#task_type_id').val("0");
    $('#error_message').text('');
    $('#modal-title').text('Thêm mới loại hình công việc');
    $('#code').val('');
    $('#description').val('');
    $('#is_delete').removeClass('checkbox_unchecked').addClass('checkbox_checked');
  }
 function showTaskTypeInfo(showStatus) {
    var dialog = $('#modalTaskTypeInfo');
    dialog.modal(showStatus);
  } 
  $(document).on('click','#buttonSaveTaskTypeInfo',function () {
      var ajax = $.ajax({
      url: $('#hidUrl').val() + 'home/checkUserLoginPermission/',
      data: {resource_id:'category/save/'},
      method: 'POST',
      dataType: 'json',
      statusCode: {
        404: function () {
          loading(false);
          //console.log("page not found");
        },
        500: function (obj) {
          loading(false);
          //console.log(obj);
        }
       }
      });
      ajax.done(function (obj) {
        //console.log(obj);
        if (obj.status == 'success')
        { 
          //--
          id = curr_id = $('input.task_type_id').val();
          func_saveTaskTypeInfo(false, id);
          //--
        }else{
          permission_message(true);
        }
      });
  });

  $(document).on('click', '#buttonSaveAndNewTaskTypeInfo', function () {
    var ajax = $.ajax({
      url: $('#hidUrl').val() + 'home/checkUserLoginPermission/',
      data: {resource_id:'category/save/'},
      method: 'POST',
      dataType: 'json',
      statusCode: {
        404: function () {
          loading(false);
          //console.log("page not found");
        },
        500: function (obj) {
          loading(false);
          //console.log(obj);
        }
       }
      });
      ajax.done(function (obj) {
        //console.log(obj);
        if (obj.status == 'success')
        { 
          //--
          id = curr_id = $('input.task_type_id').val();
          func_saveTaskTypeInfo(true, id);
          //--
        }else{
          permission_message(true);
        }
      });
  });
  
  function func_saveTaskTypeInfo(isCreateNew, id) {
  var od = $('#modalTaskTypeInfo').find('input').val();
  var code = $('#code').val();
  var description = $('#description').val();
  var is_delete = 0;
  if ($('#is_delete').hasClass('checkbox_unchecked')) {
    is_delete = 1;
  };

  loading(true);
  var url = $('#hidUrl').val() + 'category/save';

  var data = {
    od: od,
    id: id,
    code: code,
    description: description,
    is_delete: is_delete
  };
  var ajax = $.ajax({
    url: url,
    data: data,
    method: 'POST',
    dataType: 'json',
    statusCode: {
      404: function () {
        loading(false);
        //console.log("page not found");
      },
      500: function (data) {
        loading(false);
        //console.log(data);
      }
    }
  });

  ajax.done(function (obj) {
    $row = $('#tt' + id);

    if (obj.status == 'failure') {
      if ((obj.message == 'redirect') && (obj.results != null))
      {
        window.location = obj.results;
        return false;
      }

      var error_content = '<div class="col-lg-12 marginTop5 messageAlert"><div class="alert alert-warning fade in">';
                  error_content+= obj.message;
                  error_content+='<button type="button" class="close" data-dismiss="alert">×</button>';       
                  error_content+='</div></div>';
              $('#modalTaskTypeInfo').find('#error_message').html(error_content);
    }
    else if (obj.results != null) {
      if ($row.length) {
        $row.replaceWith(obj.results);
        $row = $('#tt' + id);

        var resUrl = $.trim($('#hidMUrl').val());
        $row.find('td').css({'background-image': 'url('+resUrl+'employee/assets/img/bg-highlight.png)', 'background-repeat': 'repeat'});
        setTimeout(function(){
          $row.find('td').animate({

          }, 1000).css('background-image', 'none');
        }, 1000);
      }
      else {
        $('#tableTaskType tr:last').after(obj.results);
        var rowNumber = $('#tableTaskType tr').length - 1;
        $row = $('#tableTaskType tr:last');
        $row.find('.od').text(rowNumber);
        $('#list_task_type_view_total').text(rowNumber);

        var resUrl = $.trim($('#hidMUrl').val());
        $row.find('td').css({'background-image': 'url('+resUrl+'employee/assets/img/bg-highlight.png)', 'background-repeat': 'repeat'});
        setTimeout(function(){
          $row.find('td').animate({

          }, 1000).css('background-image', 'none');
        }, 1000);
      }
      if (isCreateNew==false)
      {
        showTaskTypeInfo('hide');
      }
      else {
        task_type_resetForm();
      }
    }
    loading(false);
  });
}
  
  
 /* ===========================*/  
  
$(document).on('click','.addNewUser',function(){
   var ajax = $.ajax({
      url: $('#hidUrl').val() + 'home/checkUserLoginPermission/',
      data: {resource_id:'user/add_new_user/'},
      method: 'POST',
      dataType: 'json',
      statusCode: {
        404: function () {
          loading(false);
          //console.log("page not found");
        },
        500: function (obj) {
          loading(false);
          //console.log(obj);
        }
       }
      });
      ajax.done(function (obj) {
            if (obj.status == 'success')
            { 
               func_add_new_user_popup();
             }else{
              permission_message(true);
            }
      });
 }); 
 
 function func_add_new_user_popup(){
   loading(true);
      var url = $('#hidUrl').val() + 'user/add_user_popup';
      var ajax = $.ajax({
          url: url,
          method: 'POST',
          dataType: 'html',
          statusCode: {
              404: function () {
                  loading(false);
                  //console.log("page not found");
              },
              500: function () {
                  loading(false);
                  //console.log("Server error");
              }
          }
      });        
      ajax.done(function (obj){
        if ((obj.status== 'failure') && (obj.message == 'redirect') && (obj.results != null))
        {
          window.location = obj.results;
          return false;
        }

        loading(false);
        var popup = $('#showPopupAddNewUser');          
        popup.html(obj);
        popup.modal('show');
      });
 }

$(document).on('click','#buttonSaveUserInfo',function(){
  var $this = $(this);
  var ajax = $.ajax({
      url: $('#hidUrl').val() + 'home/checkUserLoginPermission/',
      data: {resource_id:'user/add_new_user/'},
      method: 'POST',
      dataType: 'json',
      statusCode: {
        404: function () {
          loading(false);
          //console.log("page not found");
        },
        500: function (obj) {
          loading(false);
          //console.log(obj);
        }
       }
      });
      ajax.done(function (obj) {
            if (obj.status == 'success')
            { 
              func_save_user_info($this);
             }else{
              permission_message(true);
            }
      });
});

function func_save_user_info($this){
  var popup = $('#showPopupAddNewUser');
  
  var userid      = popup.find('#UserID').val();
  var username    = popup.find('#UserName').val();
  var password    = popup.find('#Password').val()
  var repassword    = popup.find('#RePassword').val();
  var email    = popup.find('#Email').val();
  //var siteid  = popup.find('#SiteID').val();
  var is_active  = popup.find('#Is_Active').prop('checked')?1:0;
  var data_action = parseInt($this.attr('data-action'));
  
  //check validate--------------------
  var message='';
  
  //if(siteid == null || siteid == "" ||siteid == "#" || siteid.length ==0){message = "Chọn công trình";}
  if(password != repassword){message ="Mật khẩu và xác nhận mật khẩu không chính xác";} 
  if(repassword ==""){message ="Nhập lại mật khẩu";}
  if(password.length < 6){message ="Mật khẩu từ 6 ký tự trở lên";}
  if(password ==""){message ="Nhập mật khẩu";}
  if(check_email(email) == false){message ="Địa chỉ email định dạng không đúng";}
  if(email ==""){message ="Nhập email";}
  if(username ==""){message ="Nhập tên đăng nhập";}
  if(message != ""){
     var $error_content = '<div class="col-lg-12 marginTop5 messageAlert"><div class="alert alert-warning fade in">';
                  $error_content+= message;
                  $error_content+='<button type="button" class="close" data-dismiss="alert">×</button>';       
                  $error_content+='</div></div>';
              popup.find('#error_message').html($error_content);
  }
  
  if(message == ""){
    loading(true);
    $('#error_message').html('');
    var data={
      username: username,
      password: $.md5(password),
      email: email,
      //site_id: siteid,
      is_active:is_active
    };
    var url = $('#hidUrl').val() + 'user/add_new_user';
    var ajax = $.ajax({
        url: url,
        data: data,
        method: 'POST',
        dataType: 'json',
        statusCode: {
            404: function () {
                loading(false);
                //console.log("page not found");
            },
            500: function (data) {
                loading(false);
                //console.log(data);
            }
        }
    });
    ajax.done(function (obj) {
      if ((obj.status== 'failure') && (obj.message == 'redirect') && (obj.results != null))
      {
        window.location = obj.results;
        return false;
      }


      //console.log(obj);
         if (obj.status == 'failure')
         {
            popup.find('#error_message').html(obj.message);
         }else if (obj.status == 'success'){
           if(data_action == 1){
             popup.find('#error_message').html(obj.message);
           }else{
             popup.modal('hide');
           } 
         }
         loading(false);
    });
  }
}
  
 function check_email(val){
  if(!val.match(/\S+@\S+\.\S+/)){ // Jaymon's / Squirtle's solution
    // do something
    return false;
  }
  if( val.indexOf(' ')!=-1 || val.indexOf('..')!=-1){
    // do something
    return false;
  }
  return true;
 }  
  
  
  
  
  
  
/* ==================================================*/
 $('#btnAddSite').click(function (){
      var ajax = $.ajax({
      url: $('#hidUrl').val() + 'home/checkUserLoginPermission/',
      data: {resource_id:'sites/add_site/'},
      method: 'POST',
      dataType: 'json',
      statusCode: {
        404: function () {
          loading(false);
          //console.log("page not found");
        },
        500: function (obj) {
          loading(false);
          //console.log(obj);
        }
       }
      });
      ajax.done(function (obj) {
            if (obj.status == 'success')
            { 
               $('#modalAddSite').modal();
             }else{
              permission_message(true);
            }
      });
    });

    $('#btnSaveAddSite').click(function (){
      var ajax = $.ajax({
      url: $('#hidUrl').val() + 'home/checkUserLoginPermission/',
      data: {resource_id:'sites/add_site/'},
      method: 'POST',
      dataType: 'json',
      statusCode: {
        404: function () {
          loading(false);
          //console.log("page not found");
        },
        500: function (obj) {
          loading(false);
          //console.log(obj);
        }
       }
      });
      ajax.done(function (obj) {
            if (obj.status == 'success')
            { 
               addNewSite();
             }else{
              permission_message(true);
            }
      });
    });
 function addNewSite()
{
    var txtSiteName = $('#txtAddSiteName'),
        txtDescription = $('#txtAddSiteDescription'),
        siteName = $.trim(txtSiteName.val()),
        des = $.trim(txtDescription.val());
    var ctr;

    if (siteName == '')
    {
        ctr = txtSiteName.closest('.form-group');
        ctr.addClass('has-error');
        ctr.find('.help-block').text('Nhập mã công trình!');
        txtSiteName.focus();
        return false;
    }
    if (siteName.length > 100)
    {
        ctr = txtSiteName.closest('.form-group');
        ctr.addClass('has-error');
        ctr.find('.help-block').text('Mã công trình bé hơn 100 ký tự!');
        txtSiteName.focus();
        return false;
    }

    if (des == '')
    {
        ctr = txtDescription.closest('.form-group');
        ctr.addClass('has-error');
        ctr.find('.help-block').text('Nhập tên công trình!');
        txtDescription.focus();
        return false;
    }
    if (des.length > 255)
    {
        ctr = txtDescription.closest('.form-group');
        ctr.addClass('has-error');
        ctr.find('.help-block').text('Tên công trình bé hơn 255 ký tự!');
        txtSiteName.focus();
        return false;
    }

    ctr = txtDescription.closest('.form-group');
    ctr.removeClass('has-error');
    ctr.find('.help-block').text('');

    ctr = txtSiteName.closest('.form-group');
    ctr.removeClass('has-error');
    ctr.find('.help-block').text('');


    var url = $.trim($('#hidUrl').val()) + 'sites/add_site';
    var param = {
        name: siteName,
        des: des
    };

    loading(true);
    var ajax = $.ajax({
        url: url,
        data: param,
        method: 'POST',
        dataType: 'JSON',
        statusCode: {
            404: function () {
                loading(false);
                //console.log("page not found");
            },
            500: function () {
                loading(false);
                //console.log("Server error");
            }
        }
    });

    ajax.done(function (obj) {
      if ((obj.status== 'failure') && (obj.message == 'redirect') && (obj.results != null))
      {
        window.location = obj.results;
        return false;
      }

        if (obj.status == 'error')
        {
            if (obj.ctr == '#lblAddSiteError')
            {
                $('#lblAddSiteError').text(obj.msg).slideDown();
            }
            else
            {
                var input = $(obj.ctr);
                ctr = input.closest('.form-group');
                ctr.addClass('has-error');
                ctr.find('.help-block').text(obj.msg);
                input.focus();
            }
        }
        else
        {
            //window.location.reload();
            $('#modalAddSite').modal('hide');
        }
        loading(false);
    });
}
 
 
 
 
/* ====================================================*/
var emp_curr_id = '0';
$(document).on('click', '.addNewEmployee', function () {
    var ajax = $.ajax({
      url: $('#hidUrl').val() + 'home/checkUserLoginPermission/',
      data: {resource_id:'employee/save/'},
      method: 'POST',
      dataType: 'json',
      statusCode: {
        404: function () {
          loading(false);
          //console.log("page not found");
        },
        500: function (obj) {
          loading(false);
          //console.log(obj);
        }
       }
      });
      ajax.done(function (obj) {
            if (obj.status == 'success')
            { 
              employee_resetForm();
              showEmployeeInfo('show');
             }else{
              permission_message(true);
            }
      });
    
  });
 
 function employee_resetForm()
  {
    emp_curr_id = 0;
    $('#modalEmployeeInfo').find('input.od').val("0");
    $('#modalEmployeeInfo').find('input.employee_id').val("0");
    $('#modalEmployeeInfo').find('#error_message').text('');
    $('#modalEmployeeInfo').find('#modal-title').text('Thêm mới nhân viên');
    $('#modalEmployeeInfo').find('#code').val('');
    $('#modalEmployeeInfo').find('#name').val('');
    $('#modalEmployeeInfo').find('#id_card').val('');
    $('#modalEmployeeInfo').find('#is_delete').removeClass('checkbox_unchecked').addClass('checkbox_checked');
    $('#modalEmployeeInfo').find(".input-group-addon input:checked").each(function( index ) {
      $(this).prop('checked', false);
    });
  }
 function showEmployeeInfo(showStatus) {
  var dialog = $('#modalEmployeeInfo');
  dialog.modal(showStatus);
}
 
 $(document).on('click','#buttonSaveEmployeeInfo',function () {
    id = emp_curr_id = $('input.employee_id').val();
    var ajax = $.ajax({
      url: $('#hidUrl').val() + 'home/checkUserLoginPermission/',
      data: {resource_id:'employee/save/'},
      method: 'POST',
      dataType: 'json',
      statusCode: {
        404: function () {
          loading(false);
          //console.log("page not found");
        },
        500: function (obj) {
          loading(false);
          //console.log(obj);
        }
       }
      });
      ajax.done(function (obj) {
            if (obj.status == 'success')
            { 
                func_saveEmployeeInfo(false, id);
             }else{
              permission_message(true);
            }
      });
    
  });

  $(document).on('click','#buttonSaveAndNewEmployeeInfo',function () {
    id = emp_curr_id = $('input.employee_id').val();
    var ajax = $.ajax({
      url: $('#hidUrl').val() + 'home/checkUserLoginPermission/',
      data: {resource_id:'employee/save/'},
      method: 'POST',
      dataType: 'json',
      statusCode: {
        404: function () {
          loading(false);
          //console.log("page not found");
        },
        500: function (obj) {
          loading(false);
          //console.log(obj);
        }
       }
      });
      ajax.done(function (obj) {
            if (obj.status == 'success')
            { 
                func_saveEmployeeInfo(true, id);
             }else{
              permission_message(true);
            }
      });
    
  });
 function func_saveEmployeeInfo(isCreateNew, id)
{
  var od = $('#modalEmployeeInfo').find('input').val();
  var code = $('#modalEmployeeInfo').find('#code').val();
  var name = $('#modalEmployeeInfo').find('#name').val();
  var id_card = $('#modalEmployeeInfo').find('#id_card').val();
  var is_delete = 0;
  if ($('#is_delete').hasClass('checkbox_unchecked')) {
    is_delete = 1;
  };

    var default_site = $('#cboDefaultSite').val();
  var default_task_type_id = $('#default_task_type_id').val();
  var default_task_type_display = $("#default_task_type_id option[value='" + default_task_type_id + "']").text();

  var role_list = [];
  $(".input-group-addon input:checked").each(function( index ) {
    role_list[index] = $(this).val();
  });

  loading(true);
  var url = $('#hidUrl').val() + 'employee/save';

  var data = {
    od: od,
    id: id,
    code: code,
    name: name,
    id_card: id_card,
    is_delete: is_delete,
      default_site_id: default_site,
    default_task_type_id: default_task_type_id,
    default_task_type_display: default_task_type_display,
    role_list:role_list
  };
  console.log(data);
  var message_validate = "";
  if(default_task_type_id == "" || default_task_type_id == "#" || typeof default_task_type_id == "undefined"){message_validate = "Chọn công việc mặc định";}
  if(default_site == "" || default_site == "#" || typeof default_site == "undefined"){message_validate = "Chọn công trình mặc định";}
  if(id_card == "" || typeof id_card == "undefined"){message_validate = "Nhập số chứng minh nhân dân";}
  if(name == "" || typeof name == "undefined"){message_validate = "Nhập tên nhân viên";}
  if(code == "" || typeof code == "undefined"){message_validate = "Nhập mã nhân viên";}
  if(message_validate != ""){
    var mes_div  = '<div class="alert alert-warning fade in">';
               mes_div += message_validate;
               mes_div += '<button type="button" class="close" data-dismiss="alert">×</button>';
               mes_div += '</div>';
               $('#modalEmployeeInfo').find('#error_message').html(mes_div);
    loading(false);
  }else{
  
      var ajax = $.ajax({
        url: url,
        data: data,
        method: 'POST',
        dataType: 'json',
        statusCode: {
          404: function () {
            loading(false);
            //console.log("page not found");
          },
          500: function (data) {
            loading(false);
            //console.log(data);
          }
        }
      });

      ajax.done(function (obj) {
        if ((obj.status== 'failure') && (obj.message == 'redirect') && (obj.results != null))
        {
          window.location = obj.results;
          return false;
        }


        $emp_row = $('#emp' + id);

        if (obj.status == 'failure') {

          var mes_div  = '<div class="alert alert-warning fade in">';
                   mes_div += obj.message;
                   mes_div += '<button type="button" class="close" data-dismiss="alert">×</button>';
                   mes_div += '</div>';
                   $('#modalEmployeeInfo').find('#error_message').html(mes_div);
        }
        else if (obj.results != null) {
          if ($emp_row.length) {
            $emp_row.replaceWith(obj.results);
            $emp_row = $('#emp' + id);

            var resUrl = $.trim($('#hidMUrl').val());
            $emp_row.find('td').css({'background-image': 'url('+resUrl+'employee/assets/img/bg-highlight.png)', 'background-repeat': 'repeat'});
            setTimeout(function(){
              $emp_row.find('td').animate({

              }, 1000).css('background-image', 'none');
            }, 1000);
          }
          else {
            $('#tableEmployee tr:last').after(obj.results);
            var rowNumber = $('#tableEmployee tr').length - 1;
            $emp_row = $('#tableEmployee tr:last')
            var resUrl = $.trim($('#hidMUrl').val());
            $emp_row.find('td').css({'background-image': 'url('+resUrl+'employee/assets/img/bg-highlight.png)', 'background-repeat': 'repeat'});
            setTimeout(function(){
              $emp_row.find('td').animate({

              }, 1000).css('background-image', 'none');
            }, 1000);
          }
          if (isCreateNew==false)
          {
            showEmployeeInfo('hide');
          }
          else {
            employee_resetForm();
             var mes_div  = '<div class="alert alert-success fade in">';
                   mes_div += 'Thêm nhân viên thành công.';
                   mes_div += '<button type="button" class="close" data-dismiss="alert">×</button>';
                   mes_div += '</div>';
                   $('#modalEmployeeInfo').find('#error_message').html(mes_div);
          }
        }
        loading(false);
      });
   }
  
  
}
 
/*=================================================*/ 
  $(document).on('click', '.btnImportEmployeePopup', function () {
    var ajax = $.ajax({
      url: $('#hidUrl').val() + 'home/checkUserLoginPermission/',
      data: {resource_id:'employee/import_employee_popup/'},
      method: 'POST',
      dataType: 'json',
      statusCode: {
        404: function () {
          loading(false);
          //console.log("page not found");
        },
        500: function (obj) {
          loading(false);
          //console.log(obj);
        }
       }
      });
      ajax.done(function (obj) {
            if (obj.status == 'success')
            { 
                func_import_employee_popup();
             }else{
              permission_message(true);
            }
      });
  });
  
function func_import_employee_popup(){
    loading(true);
    var url = $('#hidUrl').val() + 'employee/import_employee_popup';
    var ajax = $.ajax({
        url: url,
        method: 'POST',
        dataType: 'html',
        statusCode: {
            404: function () {
                loading(false);
                //console.log("page not found");
            },
            500: function () {
                loading(false);
                //console.log("Server error");
            }
        }
    });        
    ajax.done(function (obj) {
      if ((obj.status== 'failure') && (obj.message == 'redirect') && (obj.results != null))
      {
        window.location = obj.results;
        return false;
      }

      loading(false);
      var popup = $('#modalShowImportEmployeePopup');
      popup.html(obj);
      popup.modal('show');
    })
}  
 
 


/*==========================================================*/
 var tsms_curr_id = '';
 $(document).on('click','.addNewTsmsData',function(){
   var ajax = $.ajax({
      url: $('#hidUrl').val() + 'home/checkUserLoginPermission/',
      data: {resource_id:'tsms_data/add_tsms_data_popup/'},
      method: 'POST',
      dataType: 'json',
      statusCode: {
        404: function () {
          loading(false);
          //console.log("page not found");
        },
        500: function (obj) {
          loading(false);
          //console.log(obj);
        }
       }
      });
      ajax.done(function (obj) {
            if (obj.status == 'success')
            { 
               func_add_new_tsms_data_popup();
             }else{
              permission_message(true);
            }
      });       
 });
 
function func_add_new_tsms_data_popup(){
  loading(true);
        var url = $('#hidUrl').val() + 'tsms_data/add_tsms_data_popup';
        var ajax = $.ajax({
            url: url,
            method: 'POST',
            dataType: 'html',
            statusCode: {
                404: function () {
                    loading(false);
                    //console.log("page not found");
                },
                500: function () {
                    loading(false);
                    //console.log("Server error");
                }
            }
        });        
        ajax.done(function (obj) {
          if ((obj.status== 'failure') && (obj.message == 'redirect') && (obj.results != null))
          {
            window.location = obj.results;
            return false;
          }

          loading(false);
          var popup = $('#modalShowAddNewTsmsData');          
          popup.html(obj);
          popup.modal('show');
          
           $(document).on('click','.btnSaveInfoTsmsData',function(){
             var emp_id = $(this).attr('data-id');
             var emp_name = $(this).attr('data-name');
             var emp_card = $(this).attr('data-card');
             var emp_code = $(this).attr('data-code');
             var user_id = $(this).attr('data-user-id');
             var data_action = parseInt($(this).attr('data-action'));
             
             var ajax = $.ajax({
              url: $('#hidUrl').val() + 'home/checkUserLoginPermission/',
              data: {resource_id:'tsms_data/save_tsms_data/'},
              method: 'POST',
              dataType: 'json',
              statusCode: {
                404: function () {
                  loading(false);
                  //console.log("page not found");
                },
                500: function (obj) {
                  loading(false);
                  //console.log(obj);
                }
               }
              });
              ajax.done(function (obj) {
                    if (obj.status == 'success')
                    { 
                       func_save_info_tsms_data(emp_id,emp_name,emp_card,emp_code,user_id,data_action,popup);
                    }else{
                      permission_message(true);
                    }
              });
             
             
           });
        });
}

function func_save_info_tsms_data(emp_id,emp_name,emp_card,emp_code,user_id,data_action,popup){
  loading(true);
             
            
             var emp_check_in_time = popup.find('#param_checkInTime').val();
             var emp_check_in_time_format = popup.find('.param_checkInTimeInput').val();
             var emp_avatar = popup.find('#param_inputAvatar').val();
             var emp_task_id = popup.find('#param_cboDefaultTaskType').val();
             var emp_site_id = popup.find('#param_cboDefaultSite').val();
             var emp_task_name = popup.find('#param_cboDefaultTaskType').find(':selected').attr('data-name');
             var emp_site_name = popup.find('#param_cboDefaultSite').find(':selected').attr('data-name');
              
             
             //check validate ------------------
             var message ="";
             if(emp_name == "" || typeof emp_name == 'undefined'){
               message = "Chưa chọn nhân viên được chấm công";
             }else if(emp_card == "" || typeof emp_card == 'undefined'){
               message = "Chưa chọn nhân viên được chấm công";
             }else if(emp_code == "" || typeof emp_code == 'undefined'){
               message = "Chưa chọn nhân viên được chấm công";
             }else if(emp_check_in_time == ""){
               message = "Chưa chọn thời gian điểm danh";
             }else if(emp_task_id == "" || emp_task_id == "#"){
               message = "Chưa chọn công việc";
             }else if(emp_site_id == "" || emp_site_id == "#"){
               message = "Chưa chọn công trình";
             }
             
             
             if(message == ""){
                 popup.find('.messageAlert').html('');
                  //post data tsms
                  var tsms_info = {
                      user_id:user_id,
                      employee_id: emp_id,    
                      task_type_id:emp_task_id,
                      check_in_time: emp_check_in_time,
                      image_base64:emp_avatar,
                      site_id:emp_site_id,
                      
                      code: emp_code,
                      name:emp_name,
                      check_in_time_format:emp_check_in_time_format,
                      emp_task_name:emp_task_name,
                      emp_site_name:emp_site_name
                   }
                   var url_save = $('#hidUrl').val() + 'tsms_data/save_tsms_data';
                    var ajax_save = $.ajax({
                      url: url_save,
                      data: tsms_info,
                      method: 'POST',
                      dataType: 'json',
                      statusCode: {
                        404: function () {
                          loading(false);
                          //console.log("page not found");
                        },
                        500: function (data) {
                          loading(false);
                          //console.log(data);
                        }
                      }
                    });
                    ajax_save.done(function (obj) {     
                        if (obj.status == 'failure') {
                          //pupupAddImageSize.find('.messageAlert').html(obj.message);
                        }
                        else if (obj.status == 'success') {
                          
                          $('#viewTSMSDataInDay').prepend(obj.row_data);
                          //sort row table color
                          
                          if(parseInt(data_action)==0){
                             popup.modal('hide');
                          }
                          else{
                            popup.find('.messageAlert').html(obj.message);
                            //reset form
                            
                          }
                        }
                        loading(false);
                    });
             }else{
               loading(false);
               var mes_div  = '<div class="alert alert-warning fade in">';
               mes_div += message;
               mes_div += '<button type="button" class="close" data-dismiss="alert">×</button>';
               mes_div += '</div>';
               popup.find('.messageAlert').html(mes_div);

               
             }
}

$(document).on('click','.popupSelectEmployee',function(){
    var data_option = $(this).attr('data-option-select-emp');
    
    var ajax = $.ajax({
      url: $('#hidUrl').val() + 'home/checkUserLoginPermission/',
      data: {resource_id:'employee/popupSelectEmployeeToAddTsmsData/'},
      method: 'POST',
      dataType: 'json',
      statusCode: {
        404: function () {
          loading(false);
          //console.log("page not found");
        },
        500: function (obj) {
          loading(false);
          //console.log(obj);
        }
       }
      });
      ajax.done(function (obj) {
            if (obj.status == 'success')
            { 
              func_selec_employee_popup(data_option);
            }else{
              permission_message(true);
            }
      });
 });

function func_selec_employee_popup(data_option){
  loading(true);
        var url = $('#hidUrl').val() + 'employee/popupSelectEmployeeToAddTsmsData';        
        var ajax = $.ajax({
            url: url,
            method: 'POST',
            dataType: 'html',
            data: {data_option:data_option},
            statusCode: {
                404: function () {
                    loading(false);
                    //console.log("page not found");
                },
                500: function () {
                    loading(false);
                    //console.log("Server error");
                }
            }
        });        
        ajax.done(function (obj) {
          if ((obj.status== 'failure') && (obj.message == 'redirect') && (obj.results != null))
          {
            window.location = obj.results;
            return false;
          }

          loading(false);
          $('#modalShowAddNewTsmsData').find('.messageAlert').html('');
          var popup = $('#modalShowSelectEmployee');          
          popup.html(obj);
          popup.modal('show');
          var url_paing =  $('#hidUrl').val();
          $('#selectEmployeeToAddTsmsData').ajaxPaging({
              url: url_paing + 'employee/selectEmployeePaging',
              param: {
                id: tsms_curr_id
              },
              position: 'bottom',
              search: {
                use: true,
                url: url_paing + 'employee/searchEmployee'
              },
              callBack: function () {
                  

              }
           }); 
           
           //close popup select employee
           $(document).on('click','.btnCancelSelectEmp',function(){
                popup.modal('hide');
           });
           
           //click row table
           $(document).on('click','#modalShowSelectEmployee table tr',function(){
               $(this).find('input[type=radio]').prop('checked', true);
           });
           
           //selected employee to edit - add
           $(document).on('click','.btnSelectEmp',function(){
               loading(true);
               var emp = popup.find('input[name = "rdo_group_select_emp"]:checked');
               var $emp_id   = emp.attr('data-id');
               var $emp_name = emp.attr('data-name');
               var $emp_card = emp.attr('data-card');
               var $emp_code = emp.attr('data-code');
               var $task_id = emp.attr('data-task-id');
               var $site_id = emp.attr('data-site-id');
               var $task_name = emp.attr('data-task-name');
               var $site_name = emp.attr('data-site-name');
               var pupupParent = $('#modalShowAddNewTsmsData');
               
               pupupParent.find('.emp_name').val($emp_name);
               pupupParent.find('.emp_card').val($emp_card);
               pupupParent.find('.emp_code').val($emp_code);
               
               var check_in_time = getCurrentDate();
                   check_in_time = check_in_time.split("#");
               pupupParent.find('.param_checkInTimeInput').val(check_in_time[1]);
               pupupParent.find('#param_checkInTime').val(check_in_time[0]);
               $('#param_cboDefaultSite').find("option[value='"+$site_id+"']").prop("selected","selected").change();// option[value="'+$site_id+'"]');
               $('#param_cboDefaultTaskType').find("option[value='"+$task_id+"']").prop("selected","selected").change();
               
               var param_info = $('#modalShowAddNewTsmsData').find('.btnSaveInfoTsmsData');
                  param_info.attr('data-name',$emp_name);
                  param_info.attr('data-id',$emp_id);
                  param_info.attr('data-card',$emp_card);
                  param_info.attr('data-code',$emp_code);
                  
               var param_info_edit = $('#modalShowAddNewTsmsData').find('.btnUpdateInfoTsmsData');
                  param_info_edit.attr('data-name',$emp_name);
                  param_info_edit.attr('data-id',$emp_id);
                  param_info_edit.attr('data-card',$emp_card);
                  param_info_edit.attr('data-code',$emp_code);           
                  loading(false);
                  popup.modal('hide');                          
               
           });
           
           $(document).on('click','.btnSelectEmpToFilter',function(){
               loading(true);
               var emp = popup.find('input[name = "rdo_group_select_emp"]:checked');
               var $emp_code = emp.attr('data-code');
               //set code filter
               $('.empCodeToFilter').val($emp_code);
               loading(false);
               popup.modal('hide');                          
               
           });
        });
   }
 function getCurrentDate(){
    var scriptUrl = $('#hidUrl').val() + 'tsms_data/get_current_date';
    var result = "";
     $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            result = data;
        } 
     });
    return result;
 }
 
 $(document).on('click','.btnShowConfigLimitPage',function(){
      loading(true);
      var url = $('#hidUrl').val() + 'dashboard/config_limit_page';
      var ajax = $.ajax({
          url: url,
          method: 'POST',
          dataType: 'html',
          statusCode: {
              404: function () {
                  loading(false);
                  console.log("page not found");
              },
              500: function () {
                  loading(false);
                  console.log("Server error");
              }
          }
      });        
      ajax.done(function (obj){
//        if ((obj.status== 'failure') && (obj.message == 'redirect') && (obj.results != null))
//        {
//          window.location = obj.results;
//          return false;
//        }
        loading(false);
        var popup = $('#modalShowConfigLimitPage');          
        popup.html(obj);
        popup.modal('show');
      });
 });
 
 
 
 
 
 
});

