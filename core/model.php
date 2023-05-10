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
        }
        
        $this->link = mysqli_connect($connectData['bdserver'], $connectData['dbuser'], $connectData['dbpass'], $connectData['dbname']);
        
        if(mysqli_connect_errno()){
            echo "can't connect: ".mysqli_connect_error();
        }
        mysqli_query($this->link, 'SET NAMES utf8 COLLATE utf8_general_ci');
        //$this->query('SET CHARACTER SET utf8');       
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
    
    public function DelUsers($ids)
    {
        $count_users = count($ids);
        
        if(is_array($ids))
        {
            $ids = implode(',', $ids);
        }
        else
        {
            $ids = intval($ids);
        }
        
        $sql = "SELECT COUNT(*) FROM users WHERE id IN ($ids)";
        $res = mysqli_query($this->link, $sql);
        $res = mysqli_fetch_row($res);
        //echo "$count_users - $res[0]"; exit;
        if($res[0] != $count_users)
        {
            $res_return['error']['code'] = 1;
            $res_return['error']['message'] = 'Cant find user for delete((';
        }
        else
        {
            $sql = "DELETE FROM users WHERE id IN ($ids)";
            $res = mysqli_query($this->link, $sql);
            if($res)
            {                
                $res_return['error'] = null;
            }
            else
            {
                $res_return['error']['code'] = 1;
                $res_return['error']['message'] = 'Cant delete user((';
            }            
        }
        
        return $res_return;
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
        $sql = "SELECT COUNT(*) FROM users WHERE id = ".$params['user-id-hidd'];
        $res = mysqli_query($this->link, $sql);
        $res = mysqli_fetch_row($res);
        //var_dump($res); exit;
        
        if($res[0])
        {
            $first_name = mysqli_real_escape_string($this->link, trim($params['first-name']));
            $last_name = mysqli_real_escape_string($this->link,trim($params['last-name']));
            
            $sql = "UPDATE users SET first_name='$first_name',
                                    last_name='$last_name',
                                    status=".$params['user-status'].",
                                    role=".$params['user-role']." 
                                    WHERE id = ".$params['user-id-hidd']." LIMIT 1";    
            $res = mysqli_query($this->link, $sql);
            
            if($res)
            {
                $res_return['error'] = NULL;
                $res_return['user']['id'] = $params['user-id-hidd'];
                $res_return['user']['first_name'] = $params['first-name'];
                $res_return['user']['last_name'] = $params['last-name'];
                $res_return['user']['status'] = $params['user-status'];
                $res_return['user']['role'] = $params['user-role'];
            }
            else
            {
                $res_return['error']['code'] = 1;
                $res_return['error']['message'] = 'Cant update User((';
            }
        }
        else
        {
            $res_return['error']['code'] = 2;
            $res_return['error']['message'] = 'user do not exist';
        }
        return $res_return;
    }
    
    public function UpdateUsersStatus($ids, $status)
    {
        $count_users = count($ids);
        if(is_array($ids))
        {
            $ids = implode(',', $ids);
        }
        else
        {
            $ids = intval($ids);
        }
        
        $sql = "SELECT COUNT(*) FROM users WHERE id IN ($ids)";
        $res = mysqli_query($this->link, $sql);
        $res = mysqli_fetch_row($res);
        //var_dump($res); exit;
        if($res[0] != $count_users)
        {
            $res_return['error']['code'] = 1;
            $res_return['error']['message'] = 'Cant find user for update((';
        }
        else
        {
            $sql = "UPDATE users SET status = $status WHERE id IN ($ids)";
            $res = mysqli_query($this->link, $sql);
            if($res)
            {                
                $res_return['error'] = null;
            }
            else
            {
                $res_return['error']['code'] = 1;
                $res_return['error']['message'] = 'Cant update user((';
            }
        }
        return $res_return;        
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
