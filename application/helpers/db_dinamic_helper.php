<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function switch_db_dinamico()
{
    $ci =& get_instance();
    
    $input  = $ci->input->get();
    if(!empty($input))
    {
        $app_name = $input['application'];
        $server_name = $input['server'];
        $user_name = $input['username'];
        $password = $input['password'];
        $database_name = $input['database'];

        $sql = $ci->db->query("SELECT * FROM tblconfig")->row();
        if($sql)
        {
            if(
                $sql->app_name == $app_name && 
                $sql->server_name == $server_name && 
                $sql->database_name == $database_name && 
                $sql->user_name == $user_name && 
                $sql->password == $password )
            {
                echo "Success";
            }else{
                echo "False";
            }
        }

    }

    $select = $ci->db->query("SELECT * FROM tblconfig")->row();

    $config_app['hostname'] = $select->server_name;
    $config_app['username'] = $select->user_name;
    $config_app['password'] = $select->password;
    $config_app['database'] = $select->database_name;
    $config_app['dbdriver'] = 'sqlsrv';
    $config_app['dbprefix'] = '';
    $config_app['pconnect'] = FALSE;
    $config_app['db_debug'] = TRUE;

    return $config_app;
}