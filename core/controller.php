<?php

class CController {
    
    public function __construct(){
        
    }
    
    public function ShowUsers()
    {
        $data = new CModel();
        $result = $data->ShowUsers();
        
        return $result;
    }
    
    public function DelUsers($arrId)
    {
        $data = new CModel();
        $result = $data->DelUsers($arrId);
        
        return $result;
    }
    
    public function GetUserData($id)
    {
        $data = new CModel();
        $result = $data->GetUserData($id);
        
        return $result;
    }
    
    public function UpdateUserData($params)
    {
        $data = new CModel();
        $result = $data->UpdateUserData($params);
        
        return $result;
    }
    
    public function AddUser($params)
    {
        $data = new CModel();
        $result = $data->AddUser($params);
        
        return $result;
    }
    
    public function UpdateUsersStatus($arrId, $status = 0)
    {
        $data = new CModel();
        $result = $data->UpdateUsersStatus($arrId, $status);
        
        return $result;
    }
    
}

?>