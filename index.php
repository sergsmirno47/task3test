<?php

require_once 'core/cfg.php';

$users = new CController();

$arResult = $users -> ShowUsers();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Users table</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="favicon.png">
  <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js.js"></script>
</head>
<body>
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
  <link href="togo.css" rel="stylesheet">
  <div class="container">
  
    <div id="text" class="alert" role="alert"></div>
  
    <div class="row flex-lg-nowrap">
      <div class="col">
        <div class="row flex-lg-nowrap">
          <div class="col mb-3">
            <div class="e-panel card">
              <div class="card-body">              
              
                <div class="card col-md-4 mt-100" style="margin: 20px 0; padding: 20px;">
                    <div class="btn-group" role="group">
                        <button type="button" class="user-group-act-add btn btn-info" style="margin-bottom: 10px;" data-toggle="modal" data-target="#user-form-modal">Add user</button>
                        
                        <select name="user-group-act-select" class="custom-select mr-sm-2 user-group-act-select" style="margin-bottom: 10px; margin-right: 0 !important;">
                            <option selected > -Please Select- </option>
                            <option value="1" >Set active</option>
                            <option value="2" >Set not active</option>
                            <option value="3" >Delete</option>
                        </select>
                            
                        <button type="button" class="btn btn-success user-group-act-ok" style="margin-bottom: 10px;">OK</button>
                    </div>
                </div>              
              
                <div class="card-title">
                  <h6 class="mr-2"><span>Users</span></h6>
                </div>
                <div class="e-table">
                  <div class="table-responsive table-lg mt-3">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th class="align-top">
                            <div
                              class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0">
                              <input type="checkbox" class="control-input" id="all-items">
                            </div>
                          </th>
                          <th class="max-width">Name</th>
                          <th class="sortable">Role</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                      
                      <?php 
                        foreach($arResult as $res)
                        {
                      ?>
                        <tr id="user_row_<?= $res['id'] ?>">
                          <td class="align-middle">
                            <div class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top">
                              <input type="checkbox" class="control-input" id="item-<?= $res['id'] ?>" id-data="<?= $res['id'] ?>" />
                            </div>
                          </td>
                          <td class="text-nowrap align-middle"><?= $res['first_name'] . ' ' . $res['last_name'] ?></td>
                          <td class="text-nowrap align-middle"><span><?= $res['role'] == 1? 'Admin' : 'User' ?></span></td>
                          <td class="text-center align-middle"><i class="fa fa-circle <?= $res['status'] == 1? '' : 'not-' ?>active-circle"></i></td>
                          <td class="text-center align-middle">
                            <div class="btn-group align-top">
                              <button onclick="getUserData('<?= $res['id'] ?>')" class="btn btn-sm btn-outline-secondary badge" type="button" data-toggle="modal" data-target="#user-form-modal">Edit</button>
                              <button onclick="setData('<?= $res['id'] ?>','del')" class="btn btn-sm btn-outline-secondary badge" type="button"><i class="fa fa-trash"></i></button>
                            </div>
                          </td>
                        </tr>                     
                        
                        <?php                                
                            }
                          ?>                       
                        
                        
                      </tbody>
                    </table>
                  </div>
                </div>                
                                
                <div class="card col-md-4 mt-100" style="margin: 20px 0; padding: 20px;">
                    <div class="btn-group" role="group">
                        <button type="button" class="user-group-act-add btn btn-info" data-toggle="modal" data-target="#user-form-modal">Add user</button>
                        
                        <select name="user-group-act-select" class="user-group-act-select custom-select mr-sm-2" style="margin-right: 0 !important;">
                            <option selected > -Please Select- </option>
                            <option value="1" >Set active</option>
                            <option value="2" >Set not active</option>
                            <option value="3" >Delete</option>
                        </select>
                            
                        <button type="button" class="btn btn-success user-group-act-ok">OK</button>
                    </div>
                </div>                
                
              </div>
            </div>
          </div>
        </div>
        <!-- User Form Modal -->       
        
        <div class="modal fade" id="user-form-modal" tabindex="-1" aria-labelledby="user-form-modal" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="UserModalLabel">Add user</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">            
            
              <form id="user_info">
                <div class="form-group">
                  <label for="first-name" class="col-form-label">First Name:</label>
                  <input name="first-name" type="text" class="form-control" id="first-name">
                </div>
                <div class="form-group">
                  <label for="last-name" class="col-form-label">Last Name:</label>
                  <input name="last-name" type="text" class="form-control" id="last-name">
                </div>                
                
                <div class="input-row">
                    <div class="toggle">
                        <input name="user-status" id="toggle_checkbox" type="checkbox">
                        <span class="slider"></span>
                        <span class="label">not</span>
                    </div>
                </div>
                
                <label class="mr-sm-2" for="inlineFormCustomSelect">Role</label>
                <select name="user-role" class="custom-select mr-sm-2" id="inlineFormCustomSelect">
                    <option value="2" selected >User</option>
                    <option value="1">Admin</option>                                       
                </select>
                
                <input id="user_id" type="hidden" name="user-id-hidd" value="" />
                <input id="user_act" type="hidden" name="user-act-hidd" value="" />                
                
              </form>
              
              <div id="text-form-error" class="alert" style="margin-top: 15px;"></div>
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button onclick="sentUserData()" type="button" class="btn btn-primary">Save</button>
            </div>
          </div>
        </div>
      </div>
      
    <div id="confirm" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p id="confirm_text"></p>
          </div>
          <div class="modal-footer">
            <button id="confirm_yes" onclick="myConfirm()" type="button" class="btn btn-primary">Confirm</button>
            <button id="confirm_no" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    
    <div id="confirm_group" class="modal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <p id="confirm_group_text" class="alert alert-danger"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary">Ok</button>
          </div>
        </div>
      </div>
    </div>
    
    </div>
  </div>
</body>
</html>