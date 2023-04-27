$(document).ready(function()
{    
    $('.toggle input[type="checkbox"]').click(function(){
        $(this).parent().toggleClass('on');

        if ($(this).parent().hasClass('on')) {
            $(this).parent().children('.label').text('active')
        } else {
            $(this).parent().children('.label').text('not')
        }
    });
    
    $('#confirm_no').click(function() {
        $("#confirm").css("display", "none");
    });
    
    $('button[class="close"]').click(function () {
        $("#confirm").css("display", "none");
    });
    
    //натиснув на загальний checkbox і всі стали обрані, і навпаки
    $('#all-items').click(function(){
        if($("#all-items").prop('checked'))
        {
            $('input[type=checkbox].control-input').each(function() { 
                this.checked = true; 
            });
        }
        else
        {
            $('input[type=checkbox].control-input').each(function() { 
                this.checked = false;
            });
        }
    });
    
    //зняття галочки із загального checkbox, якщо хоч один checkbox не позначений
    $('input[type=checkbox].control-input').change(function()
    {
        if($(this).prop('checked'))
        {     
            
            let checkedAll = true;
            $('tbody input[type=checkbox].control-input').each(function()
            {
                if(!$(this).prop('checked'))
                {
                    checkedAll = false;
                }
            });            
            
            if(checkedAll)
            {
                $('#all-items').prop('checked', true);
            }
        }
        else
        {
            $('#all-items').prop('checked', false);
        }
    });    
    
    $('.user-group-act-add').click(function(){
        //очистка форми перед заповненням
        $('#user_info')[0].reset();
        
        $('#UserModalLabel').empty().append('Add user');
        $('#user_id').val('');
        $('#user_act').val('add');
    });
    
    $('#confirm_group button').click(function() {
        $("#confirm_group").css("display", "none");
    });
    $(document).keyup(function(e)
    {
        if (e.key === "Escape")
        {
            if($("#confirm").css("display") != "none")
            {
                $("#confirm").css("display", "none");
            }
            if($("#confirm_group").css("display") != "none")
            {
                $("#confirm_group").css("display", "none");
            }
        }
    });
    
    $('.user-group-act-ok').click(function() {
        //отримання значення селект 1. Set active, 2. Set not active, 3. Delete
        let userStatus = $(this).parent('div').children('select').val();
        //якщо значення не співпадає із встановленим,  або воно  просто не обране - помилка
        if(userStatus == 1 || userStatus == 2 || userStatus == 3)
        {   
            let checkboxes = [];
            $('tbody input:checkbox:checked.control-input').each(function() {
                //в масив checkboxes заношу ID обраних користувачів
                checkboxes.push($(this).attr('id-data'));
            });
            //якщо не обраний ні один користувач - помилка
            if(checkboxes.length !== 0)
            {                
                if(userStatus == 3)
                {
                    dellUser(checkboxes);
                }
                else if(userStatus == 2 || userStatus == 1)
                {
                    const data = {
                        arrId: checkboxes,
                        status: userStatus
                    };
                        
                    $.ajax({
                        url: 'updUsersStts.php',
                        method: 'post',
                        dataType: 'text',
                        data: data,
                        success: function(data)
                        {
                            $('#text').removeClass().empty();
                            const user_data = JSON.parse(data);
                            
                            if(user_data.error == null || user_data.error == undefined)
                            {                                
                                $('#text').addClass('alert alert-success').append('Users updated');
                                $('input[type=checkbox].control-input').each(function() { 
                                    this.checked = false; 
                                });
                                //змінюю статус активний/не активний в таблиці
                                user_data.user.id.forEach(function(elem)
                                {
                                    $('#user_row_'+elem).children('td').eq(3).empty().append('<i class="fa fa-circle '+(userStatus == 1?'':'not-')+'active-circle"></i>');
                                });
                            }
                            else
                            {
                                if(user_data.error.code == 1)
                                {
                                    $('#text').addClass('alert alert-danger').append(user_data.error.message);                    
                                }
                                else if(user_data.error.code == 2)
                                {
                                    $('#text').addClass('alert alert-warning').append(user_data.error.message);
                                }
                                else
                                {
                                    $('#text').addClass('alert alert-info').append(user_data.error.message);
                                }
                                return false;
                            }//*/
                        }
                    });
                }
                else
                {
                    myError('Something wrong((');
                }
            }
            else
            {
                myError('Select user, please !!!');
            }            
        }
        else
        {
            myError('Select action, please !!!');
        } 
    })
    
});


function myError(text)
{
    $("#confirm_group").css("display", "block");
    $('#confirm_group_text').empty();
    $('#confirm_group_text').append(text);
}

function sentUserData()
{   //отримую дані з  форми
    const userDataForSend = $('#user_info').serialize();
    //console.log(userDataForSend);
    //
    const data = {
        all_user_data: userDataForSend
    };
        
    $.ajax({
        url: 'update.php',
        method: 'post',
        dataType: 'text',
        data: data,
        success: function(data){
            $('#text-form-error').addClass('alert alert-info').append(data);
            //console.log(data);

            const user_data = JSON.parse(data);
            console.log(user_data);
            
            $('#text').removeClass().empty();
            $('#text-form-error').removeClass().empty();
            
            if(user_data.error == null || user_data.error == undefined)
            {
                $('#user-form-modal').modal('hide');
                                
                if($('#user_act').val() === 'upd')
                {
                    $('#text').addClass('alert alert-success').append('User updated');
                    //змінюю дані в таблиці після оновлення користувача
                    $('#user_row_'+user_data.user.id).children('td').eq(1).empty().append(user_data.user.first_name+' '+user_data.user.last_name);
                    $('#user_row_'+user_data.user.id).children('td').eq(2).empty().append('<span>'+(user_data.user.role == 1?'Admin':'User')+'</span>');
                    $('#user_row_'+user_data.user.id).children('td').eq(3).empty().append('<i class="fa fa-circle '+(user_data.user.status == 1?'':'not-')+'active-circle"></i>');                 
                }
                else if($('#user_act').val() === 'add')
                {
                    $('#text').addClass('alert alert-success').append('User added');
                    //додаю новий запис із користувачем
                    $('table > tbody').prepend('<tr id="user_row_'+user_data.user.id+'">'+
                                                  '<td class="align-middle">'+
                                                    '<div class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top">'+
                                                      '<input type="checkbox" class="control-input" id="item-'+user_data.user.id+'" id-data="'+user_data.user.id+'" />'+
                                                    '</div>'+
                                                  '</td>'+
                                                  '<td class="text-nowrap align-middle">'+user_data.user.first_name+' '+user_data.user.last_name+'</td>'+
                                                  '<td class="text-nowrap align-middle"><span>'+(user_data.user.role == 1? 'Admin' : 'User')+'</span></td>'+
                                                  '<td class="text-center align-middle"><i class="fa fa-circle '+(user_data.user.status == 1? '' : 'not-')+'active-circle"></i></td>'+
                                                  '<td class="text-center align-middle">'+
                                                    '<div class="btn-group align-top">'+
                                                      '<button onclick="getUserData(\''+user_data.user.id+'\')" class="btn btn-sm btn-outline-secondary badge" type="button" data-toggle="modal" data-target="#user-form-modal">Edit</button>'+
                                                      '<button onclick="setData(\''+user_data.user.id+'\',\'del\')" class="btn btn-sm btn-outline-secondary badge" type="button"><i class="fa fa-trash"></i></button>'+
                                                    '</div>'+
                                                  '</td>'+
                                                '</tr>');
                }
                else
                {
                    myError('Something wrong((');
                }
                
                $('#user_id').val('');
                $('#user_act').val('');
                
            }
            else
            {
                if(user_data.error.code == 1)
                {
                    $('#text-form-error').addClass('alert alert-danger').append(user_data.error.message);                    
                }
                else if(user_data.error.code == 2)
                {
                    $('#text-form-error').addClass('alert alert-warning').append(user_data.error.message);
                }
                else
                {
                    $('#text-form-error').addClass('alert alert-info').append(user_data.error.message);
                }
            }//*/
            
        }
    });
}

//заповнюю форму
function fillUserData(user_data)
{    
    $('#user_id').val(user_data.user.id);
    $('#user_act').val('upd');
    
    $('#first-name').empty().val(user_data.user.first_name);
    $('#last-name').empty().val(user_data.user.last_name);
    
    if(user_data.user.status == 1)
    {
        $('.toggle input[type="checkbox"]').parent().addClass('on');
        $('.toggle input[type="checkbox"]').parent().children('.label').text('active');
        $('#toggle_checkbox').prop('checked', true);        
    }
    else
    {
        $('.toggle input[type="checkbox"]').parent().removeClass().addClass('toggle');
        $('.toggle input[type="checkbox"]').parent().children('.label').text('not');
        $('#toggle_checkbox').prop('checked', false);
    }
    
    $('#inlineFormCustomSelect option[value='+user_data.user.role+']').prop('selected', true);
}

function getUserData(id)
{    
    const data = {
        id: id
    };
    
    $.ajax({
        url: 'getUserData.php',
        method: 'post',
        dataType: 'text',
        data: data,
        success: function(data){
            //$('#text').addClass('alert alert-info').append(data);
            
            const user_data = JSON.parse(data);
            //console.log(user_data);
            
            if(user_data.error == null || user_data.error == undefined)
            {   
                $('#UserModalLabel').empty().append('Edit user');
                fillUserData(user_data);                
            }
            else
            {
                if(user_data.error.code == 1)
                {
                    $('#text').addClass('alert alert-danger').append(user_data.error.message);                    
                }
                else if(user_data.error.code == 2)
                {
                    $('#text').addClass('alert alert-warning').append(user_data.error.message);
                }
                else
                {
                    $('#text').addClass('alert alert-info').append(user_data.error.message);
                }
                return false;
            }//*/
        }
    });
}

function setData(id, act)
{
    $('#user_id').val(id);
    $('#user_act').val(act);
    
    $('#text').removeClass().empty();
    $('#confirm_text').empty();
    $("#confirm").css("display", "block");
    $('#confirm_text').append('Are you sure want to delete??');
}

function myConfirm()
{   
    $("#confirm").css("display", "none");
    
    let myAction = $('#user_act').val();
    let userId = $('#user_id').val();
    
    if(myAction === 'del')
    {
        dellUser(userId);
    }
}

function dellUser(id)
{    
    const data = {
        arrId: id
    };
    //console.log(userId, myAction, id);
    
    $.ajax({
        url: 'del.php',
        method: 'post',
        dataType: 'text',
        data: data,
        success: function(data){
            //$('#text').addClass('alert alert-info').append(data);            
            
            const all_data = JSON.parse(data);
            //console.log(all_data);
            
            $('#text').empty();
            
            if(all_data.error == null || all_data.error == undefined)
            {
                if(Array.isArray(all_data.user.id))
                {
                    all_data.user.id.forEach(function(elem)
                    {
                        $('#user_row_'+elem).remove();
                    });
                    $('#text').addClass('alert alert-success').append('Users deleted');
                }
                else
                {
                    $('#text').addClass('alert alert-success').append('User deleted');
                    $('#user_row_'+all_data.user.id).remove();
                }
            }
            else
            {
                if(all_data.error.code == 1)
                {
                    $('#text').addClass('alert alert-danger').append(all_data.error.message);
                    
                }
                else if(all_data.error.code == 2)
                {
                    $('#text').addClass('alert alert-warning').append(all_data.error.message);
                }
                else
                {
                    $('#text').addClass('alert alert-info').append(all_data.error.message);
                }
            }//*/
        }
    });
}