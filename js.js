$(document).ready(function()
{    
    $('.toggle input[type="checkbox"]').click(function(){
        $(this).parent().toggleClass('on');

        if ($(this).parent().hasClass('on')) {
            $(this).parent().children('.label').text('active')
        } else {
            $(this).parent().children('.label').text('not active')
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
        checkAll();
    });
    
    //зняття галочки із загального checkbox, якщо хоч один checkbox не позначений
    $('tbody input[type=checkbox].control-input').change(function()
    {
        let el = $(this);
        //console.log(el);
        checkboxOpt(el);
    });    
    
    $('.user-group-act-add').click(function(){
        //очистка форми перед заповненням
        $('#user_info')[0].reset();
        $('#text-form-error').removeClass().empty();
        $('#user_info .input-row .toggle').removeClass().addClass('toggle');
        $('#user_info .input-row .toggle .label').text('not active');
        
        $('#UserModalLabel').text('Add user');
        $('#user_id').val('');
        $('#user_act').val('add');
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
                    setData(checkboxes, 'del');
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
                            //$('#text').addClass('alert alert-info').append(data);
                            
                            $('#text').removeClass();
                            const user_data = JSON.parse(data);
                            
                            if(user_data.error == null || user_data.error == undefined)
                            {                                
                                $('#text').addClass('alert alert-success').text('Users updated');
                                $('input[type=checkbox].control-input').each(function() { 
                                    this.checked = false; 
                                });
                                //змінюю статус активний/не активний в таблиці
                                checkboxes.forEach(function(elem)
                                {
                                    $('#user_row_'+elem).children('td').eq(3).empty().append('<i class="fa fa-circle '+(userStatus == 1?'':'not-')+'active-circle"></i>');
                                });
                            }
                            else
                            {
                                myError(user_data.error.message); 
                            }//*/
                        }
                    });
                }
                else
                {
                    myError('Action is wrong((');
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
    });
    
});

function checkAll()
{
    $('input[type=checkbox].control-input').prop('checked', $('#all-items').prop('checked'));
}

function checkboxOpt(el)
{
    if(el.prop('checked'))
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
}

function myError(text)
{
    $("#confirm_group").modal('show');
    $('#confirm_group_text').text(text);
}

function sentUserData()
{   //отримую дані з  форми
    const userDataForSend = $('#user_info').serialize();
    
    const data = {
        all_user_data: userDataForSend
    };
        
    $.ajax({
        url: 'update.php',
        method: 'post',
        dataType: 'text',
        data: data,
        success: function(data){
            //$('#text').addClass('alert alert-info').append(data);
            //console.log(data);
            
            const user_data = JSON.parse(data);
            //console.log(user_data);
            
            $('#text').removeClass();
            $('#text-form-error').removeClass();
            
            if(user_data.error == null || user_data.error == undefined)
            {
                $('#user-form-modal').modal('hide');
                                
                if($('#user_act').val() === 'upd')
                {
                    $('#text').addClass('alert alert-success').text('User updated');
                    //змінюю дані в таблиці після оновлення користувача
                    $('#user_row_'+user_data.user.id).children('td').eq(1).text(user_data.user.first_name+' '+user_data.user.last_name);
                    $('#user_row_'+user_data.user.id).children('td').eq(2).empty().append('<span>'+(user_data.user.role == 1?'Admin':'User')+'</span>');
                    $('#user_row_'+user_data.user.id).children('td').eq(3).empty().append('<i class="fa fa-circle '+(user_data.user.status == 1?'':'not-')+'active-circle"></i>');                 
                }
                else if($('#user_act').val() === 'add')
                {
                    $('#text').addClass('alert alert-success').text('User added');
                    //додаю новий запис із користувачем
                    $('table > tbody').append('<tr id="user_row_'+user_data.user.id+'">'+
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
                                                      '<button onclick="getUserData(\''+user_data.user.id+'\')" class="btn btn-sm btn-outline-secondary badge" type="button">Edit</button>'+
                                                      '<button onclick="setData(\''+user_data.user.id+'\',\'del\')" class="btn btn-sm btn-outline-secondary badge" type="button"><i class="fa fa-trash"></i></button>'+
                                                    '</div>'+
                                                  '</td>'+
                                                '</tr>');
                    
                    checkAll();
                    $('.container').on('click', '#item-'+user_data.user.id, function(){checkboxOpt($('#item-'+user_data.user.id))});
                    
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
                    $('#user-form-modal').modal('hide');
                    myError(user_data.error.message);                   
                }
                else if(user_data.error.code == 2)
                {
                    $('#text-form-error').addClass('alert alert-warning').text(user_data.error.message);
                }
                else
                {
                    $('#text-form-error').addClass('alert alert-info').text(user_data.error.message);
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
        $('.toggle input[type="checkbox"]').parent().children('.label').text('not active');
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
                $('#text-form-error').removeClass().empty();
                $('#UserModalLabel').text('Edit user');
                $('#user-form-modal').modal('show');
                fillUserData(user_data);                
            }
            else
            {
                //$('#user-form-modal').modal('hide');
                myError(user_data.error.message);
            }//*/
        }
    });
}

function setData(ids, act)
{
    if(ids == '')
    {
        myError('ID is empty ((');
    }
    else if(act !== 'del')
    {
        myError('ACTION is wrong ((');
    }
    else
    {
        $('#user_id').val(ids);
        $('#user_act').val(act);
        
        $("#confirm").modal('show');
        
        if(typeof ids == 'string')
        {
            $('#confirm_text').text('Delete user - '+$('#user_row_'+ids).children('td').eq(1).text()+'??');
        }
        else
        {
            let usersName = [];
            ids.forEach(element => {
                usersName.push($('#user_row_'+element).children('td').eq(1).text());
            });
            $('#confirm_text').text('Delete users - '+usersName+' ??');
        }
    }    
}

function myConfirm()
{   
    $("#confirm").modal('hide');
    
    let myAction = $('#user_act').val();
    let userIds = $('#user_id').val();
    userIds = userIds.split(',');
    
    //console.log(myAction, userIds.length);
    if(userIds.length)
    {
        if(myAction === 'del')
        {
            dellUser(userIds);
        }
    }
    else
    {
        myError('ID is empty ((');
    }    
}

function dellUser(ids)
{    
    const data = {
        arrId: ids
    };
    
    $.ajax({
        url: 'del.php',
        method: 'post',
        dataType: 'text',
        data: data,
        success: function(data){
            //$('#text').addClass('alert alert-info').text(data);            
            
            const all_data = JSON.parse(data);
            
            if(all_data.error == null || all_data.error == undefined)
            {
                ids.forEach(function(elem)
                {
                    $('#user_row_'+elem).remove();
                });
                $('#text').addClass('alert alert-success').text('Users deleted');
            }
            else
            {
                if(all_data.error.code == 1)
                {
                    myError(all_data.error.message);
                    
                }
                else if(all_data.error.code == 2)
                {
                    $('#text').addClass('alert alert-warning').text(all_data.error.message);
                }
                else
                {
                    $('#text').addClass('alert alert-info').text(all_data.error.message);
                }
            }//*/
        }
    });//*/
}