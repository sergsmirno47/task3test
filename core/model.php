<?php

class CModel {
    
    public $link;
    
    public function __construct(){
        
        if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
        {
            $connectData = array(
                'bdserver' => 'localhost',
                'dbuser' => 'root',
                'dbname' => 'task3',
                'dbpass' => '',
            
            );
        }
        else
        {
            $connectData = array(
                'bdserver' => 'fdb27.biz.nf',
                'dbuser' => '4301586_task3',
                'dbname' => '4301586_task3',
                'dbpass' => 'x1LOqYSd4TD2x',
            
            );
        }
        
        $this->link = mysqli_connect($connectData['bdserver'], $connectData['dbuser'], $connectData['dbpass'], $connectData['dbname']);
        
        if(mysqli_connect_errno()){
            echo "can't connect: ".mysqli_connect_error();
        }        
    }    
    
    public function ShowUsers()
    {
        $sql = "SELECT * FROM users ORDER BY id ASC";
        $res = mysqli_query($this->link, $sql);
        
        while($arRes = mysqli_fetch_assoc($res)){
            $arUsers[] = $arRes;
        }
        
        return $arUsers;
    }
    
    public function DelUsers($arrId)
    {   
        
        if(is_array($arrId))
        {
            $arrId = implode(',', $arrId);
        }
        else
        {
            $arrId = intval($arrId);
        }
        $sql = "DELETE FROM users WHERE id IN ($arrId)";
        //echo $sql; exit;
        $res = mysqli_query($this->link, $sql);
        
        return $res;
    }
    
    public function GetUserData($id)
    {
        $sql = "SELECT * FROM users WHERE id = $id LIMIT 1";
        $res = mysqli_query($this->link, $sql);        
        $arRes = mysqli_fetch_assoc($res);
        
        return $arRes;
    }
    
    public function UpdateUserData($params)
    {        
        $first_name = mysqli_real_escape_string($this->link, trim($params['first-name']));
        $last_name = mysqli_real_escape_string($this->link,trim($params['last-name']));
        
        $sql = "UPDATE users SET first_name='$first_name',
                                last_name='$last_name',
                                status=".$params['user-status'].",
                                role=".$params['user-role']." 
                                WHERE id = ".$params['user-id-hidd']." LIMIT 1";

        $res = mysqli_query($this->link, $sql);
        
        return $res;
    }
    
    public function UpdateUsersStatus($arrId, $status)
    {
        if(is_array($arrId))
        {
            $arrId = implode(',', $arrId);
            $sql = "UPDATE users SET status = $status WHERE id IN ($arrId)";
            $res = mysqli_query($this->link, $sql);
        }
        
        return $res;        
    }
    
    public function AddUser($params)
    {
        $first_name = mysqli_real_escape_string($this->link, trim($params['first-name']));
        $last_name = mysqli_real_escape_string($this->link,trim($params['last-name']));
        
        $sql = "INSERT INTO users (first_name, last_name, status, role) 
                                VALUES 
                                ('$first_name', '$last_name', ".$params['user-status'].", ".$params['user-role'].")";

        $res = mysqli_query($this->link, $sql);
        
        if($res)
        {
            $sql = "SELECT * FROM users WHERE id = LAST_INSERT_ID()";
            $res = mysqli_query($this->link, $sql);
            $inf_last_user = mysqli_fetch_array($res, MYSQLI_ASSOC);
        }
        
        return $inf_last_user;
    }
  
}

?>