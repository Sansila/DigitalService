<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dynamicController extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Phnom_Penh');
        $this->load->library('CryptoLib');
        $this->load->helper('number');
        $this->load->driver('cache');
        $this->load->library('pagination');
        $this->load->library('session');

        $this->load->helper(array('form', 'url','db_dinamic_helper'));
        $config_app = switch_db_dinamico();
        $this->load->model(array('dynamicModel'));
        $this->dynamicModel->app_db = $this->load->database($config_app,TRUE);
    }
    public function index()
    {
        
    }
    function loginconfig()
    {
        $this->load->view('configer/login');
    }
    function loginconfigerror($error)
    {
        $data['msg'] = $error;
        $this->load->view('configer/login',$data);
    }
    function logout()
    {
        $this->session->sess_destroy();
        redirect('dynamicController/loginconfig', 'refresh');
    }
    function loginServer()
    {
        $input = $this->input->post();
        $username = $input['username'];
        $password = md5($input['password']);

        $login = $this->db->query("SELECT * FROM tbluser WHERE username = '$username' AND password = '$password'")->row();

        if(count($login) > 0)
        {
            $newdata = array(
                'userid' => $login->userid,
                'username' => $login->username,
                'password' => $login->password,
                'create_date' => $login->create_date,
                'role' => $login->role,
                'logged_in' => TRUE
            );
            $this->session->set_userdata($newdata);
            redirect('dynamicController/form_config', 'refresh'); 
        }else{
            $msg = "error";
            redirect('dynamicController/loginconfigerror/'.$msg, 'refresh');
        }
        
    }
    function form_config()
    {
        if($this->session->userdata('logged_in'))
        {
            $config = $this->db->query("SELECT * FROM tblconfig")->row();
            if($config !="")
                $data['config'] = $config;
            $this->load->view('configer/configerform',$data);
        }else{
            redirect('dynamicController/loginconfig', 'refresh'); 
        }
    }
    function saveConfiger()
    {
        if($this->session->userdata('logged_in'))
        {
            $input = $this->input->post();
            $data = array(
                'app_name' => $input['appname'],
                'server_name' => $input['server'],
                'user_name' => $input['username'],
                'password' => $input['password'],
                'database_name' => $input['database'],
                'type' => $input['displaytype']            
            );
            $id = $input['id'];
            if($id !="")
            {
                $this->db->where('id',$id);
                $this->db->update('tblconfig',$data);
                $msg = "upd";
                $this->aftersaveform($msg);
            }else{
                $msg = "insert";
                $this->db->insert('tblconfig',$data);
                $this->aftersaveform($msg);
            }
        }else{
            redirect('dynamicController/loginconfig', 'refresh'); 
        }
        
    }
    function aftersaveform($msg)
    {
        if($this->session->userdata('logged_in'))
        {
            $config = $this->db->query("SELECT * FROM tblconfig")->row();
            if($config !="")
                $data['config'] = $config;
            if($msg == "upd")
                $data['msg'] = "update";
            if($msg == "insert")
                $data['msg'] = "insert";
            $this->load->view('configer/configerform',$data);
        }else{
            redirect('dynamicController/loginconfig', 'refresh'); 
        }
    }
    function login()
    {
        $data = $this->input->post();
        $username = $data['user'];
        $password = $data['pass'];

        $login = $this->dynamicModel->loginUser($username,$password);
        header('Content-Type: application/json');
        echo json_encode($login);
    }
    function changepassword()
    {
        $input = $this->input->post();
        $user = $input['username'];
        $pwd  = $input['password'];

        $check = $this->dynamicModel->checkPassword($user);

        if($check == $pwd)
        {
            echo $check;
        }else{
            echo "false";
        }
    }
    function saveNewpassword()
    {
        $input = $this->input->post();
        $user = $input['username'];
        $cpwd = $input['password'];
        //$encryptPwd = CryptoLib::encryptData($cpwd, 960168);
        $updatepwd = $this->dynamicModel->updateNewpassword($user,$cpwd);

        if($updatepwd == TRUE)
        {
            echo "Your password have been update.";
        }else{
            echo "Your password update false.";
        }

    }
    function getTable()
    {
        $getTable = $this->dynamicModel->getTable();
        header('Content-Type: application/json');
        echo json_encode($getTable);
    }
    function left_menu()
    {
        $getMenu = $this->dynamicModel->getMenu();
        header('Content-Type: application/json');
        echo json_encode($getMenu);
    }
    function ListOrders()
    {
        $data = $this->input->post();
        $id   = $data['id'];
        $getListOrder = $this->dynamicModel->getListOrder($id);
        header('Content-Type: application/json');
        echo json_encode($getListOrder);
    }
    function ListOrdersAll($id)
    {
        $getListOrder = $this->dynamicModel->getListOrders($id);
        header('Content-Type: application/json');
        echo json_encode($getListOrder);
    }
    function checkStatusTable()
    {
        $data = $this->input->post();
        $tableid = $data['tableid'];
        $OrderNo = $data['orderNo'];

        $status = $this->dynamicModel->checkStatusTable($tableid,$OrderNo);
        echo $status;
    }
    function makeNewOrder()
    {
        $data = $this->input->post();
        $tableid = $data['tableid'];

        $newOrder = $this->dynamicModel->makeNewOrder($tableid);
        echo $newOrder;
    }
    function makeNewOrder_by_tbl($tableid)
    {
        $newOrder = $this->dynamicModel->makeNewOrder($tableid);
        echo $newOrder;
    }
    function getListItems()
    {
        $data = $this->input->post();
        $categoryid = $data['cate'];
        $itemList = $this->dynamicModel->getListItems($categoryid);
        header('Content-Type: application/json');
        echo json_encode($itemList);
    }
    function getListItembyID($categoryid)
    {
        $data = $this->dynamicModel->getListItembyID($categoryid);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    function getDatadetail()
    {
        $data = $this->input->post();
        $itemID = $data['ItemID'];
        $detail = $this->dynamicModel->getDatadetail($itemID);
        header('Content-Type: application/json');
        echo json_encode($detail);
    }
    function SaveOrderItem()
    {
        $input = $this->input->post();
        $date = Date('Y-m-d H:i:s');
        $data = array(
            'OrderNo' => $input['OrderNo'],
            'ItemID' => $input['itemID'],
            'Qty' => $input['Qty'],
            'Price' => $input['Price'],
            'Discount' => 0,
            'Status' => 'Open',
            'ItemName' => $input['itemName'],
            'Time' => $date,
            'UserName' => $input['UserName'],
            'Remark' => $input['Option'],
            'InventoryMix' => $input['InvMix'],
            'OrderRemark' => $input['Command']
        );

        $save = $this->dynamicModel->SaveOrderItem($data);
        echo $save;
    }
    function saveitemDetail()
    {
        $input = $this->input->post();
        $date = Date('Y-m-d H:i:s');
        $opt = substr($input['allopt'],1, -1);
        $data = array(
              'OrderNo' => $input['orderno'],
              'ItemID' => $input['itemid'],
              'Qty' => $input['counter'],
              'Price' => $input['price'],
              'Discount' => 0,
              'Status' => 'Open',
              'ItemName' => $input['name'],
              'ItemNameKhmer' => $input['namekh'],
              'Time' => $date,
              'Remark' => $opt,
              'OrderRemark' => $input['name']
        );
        $save = $this->dynamicModel->SaveDetail($data);
        echo $save;
    }
    function saveFromCategory()
    {
        $input = $this->input->post();
        $date = Date('Y-m-d H:i:s');
        $data = array(
            'OrderNo' => $input['ord'],
            'ItemID' => $input['itemid'],
            'Qty' => $input['qty'],
            'Price' => $input['price'],
            'Discount' => 0,
            'Status' => 'Open',
            'ItemName' => $input['name'],
            'ItemNameKhmer' => $input['namekh'],
            'Time' => $date,
        );
        $save = $this->dynamicModel->SaveOrderItem($data);
        if($save == 1)
        {
            echo "success";
        }else{
            echo "false";
        }
    }
    function getItemforExtrafood()
    {
        $data = $this->input->post();
        $TableNo = $data['TableNo'];
        $query = $this->dynamicModel->getItemExtrafood($TableNo);
        header('Content-Type: application/json');
        echo json_encode($query);
        //print_r($query);
    }
    function SaveExtrafood()
    {
        $input = $this->input->post();
        $date = Date('Y-m-d H:i:s');
        $data = array(
            'AssociatedItem' => $input['OrderDetailID'],
            'OrderNo'        => $input['OrderNo'],
            'ItemID'         => $input['ItemID'],
            'Qty'            => 1,
            'Price'          => $input['Price'],
            'Discount'       => 0,
            'Status'         => 'Open',
            'ItemName'       => $input['ItemName'],
            'ItemNameKhmer'  => $input['itemNameKH'],
            'Time'           => $date,
            'Remark'         => '',
            'InventoryMix'   => $input['InvMix']
        );

        $save = $this->dynamicModel->SaveExtra($data);
        echo $save; 
    }
    function getSubItem()
    {
        $data = $this->input->post();
        $TableNo = $data['TableNo'];
        $subitem = $this->dynamicModel->getSubs($TableNo);
        header('Content-Type: application/json');
        echo json_encode($subitem);
    }
    function getCurrentOrder()
    {
        $data = $this->input->post();
        $orderNo = $data['orderNo'];
        $tableNo = $data['tid'];
        $currentOrder = $this->dynamicModel->getCurrent($orderNo,$tableNo);
        header('Content-Type: application/json');
        echo json_encode($currentOrder);
    }
    function getCurrentOrderbyID($orderNo,$tableNo)
    {
        // $data = $this->input->post();
        // $orderNo = $data['orderNo'];
        // $tableNo = $data['tid'];
        $currentOrder = $this->dynamicModel->getCurrentOrderbyID($orderNo,$tableNo);
        header('Content-Type: application/json');
        echo json_encode($currentOrder);
    }
    function getCurrentOrderbyIDCaptain($orderNo,$tableNo)
    {
        // $data = $this->input->post();
        // $orderNo = $data['orderNo'];
        // $tableNo = $data['tid'];
        $currentOrder = $this->dynamicModel->getCurrentOrderbyIDCaptain($orderNo,$tableNo);
        header('Content-Type: application/json');
        echo json_encode($currentOrder);
    }
    function deleteCurrentOrderItem()
    {
        $data = $this->input->post();
        $currentID = $data['currentID'];
        $delete = $this->dynamicModel->deleteCurrentOrderbyItem($currentID);
        echo $delete;
    }
    function saveAllCurrentOrderItem()
    {
        $data = $this->input->post();
        $orderNo = $data['orderNo'];
        $qty     = $data['qty'];
        $Remark  = $data['Option'];
        $RemarkCMD = $data['Cmd'];
        $OrderDetailID = $data['OrderDetailID'];
        $update = $this->dynamicModel->saveAllCurrentOrderItemByOrderNo($orderNo, $qty, $Remark, $OrderDetailID, $RemarkCMD);
        echo $update;
        //echo $orderNo, $qty, $Option, $Cmd;
    }
    function deleteAllCurrentOrderItem()
    {
        $data = $this->input->post();
        $orderNo = $data['orderNo'];
        $delete  = $this->dynamicModel->deleteAllCurrentOrderItemByOrderNo($orderNo);
        echo $delete;
    }
    function getOrderSummary($orderNo)
    {
        // $data = $this->input->post();
        // $orderNo = $data['orderNo'];
        $ordersummary = $this->dynamicModel->getAllOrderSummary($orderNo);
        
        header('Content-Type: application/json');
        echo json_encode($ordersummary);
    }
    function getAllCustomerOrder()
    {
        $data = $this->input->post();
        $TableNo = $data['TableNo'];
        $OrderNo = $data['OrderNo'];
        $Status  = $data['Status'];
        $getallOrder = $this->dynamicModel->getAllCustomerOrderByTableNo($TableNo,$OrderNo,$Status);
        header('Content-Type: application/json');
        echo json_encode($getallOrder);
    }
    function DeleteItemformCaptainCheckOrder()
    {
        $data = $this->input->post();
        $OrderDetailID = $data['OrderDetailID'];
        $deleteitem = $this->dynamicModel->DeleteItemformCaptainCheckOrderByID($OrderDetailID);
        echo $deleteitem;
    }
    function confirmOrderCustomer()
    {
        //(link,Command,FoodOption,OrderDetailID,Price,Qty,User,OrderNo);
        $input = $this->input->post();
        $date = Date('Y-m-d H:i:s');
        $Remark = $input['FoodOption'];
        $Price = $input['Price'];
        $Qty = $input['Qty'];
        $UserName = $input['User'];
        $Status = 'Confirm';
        $Time = $date;
        $OrderNo = $input['OrderNo'];
        $OrderDetailID = $input['OrderDetailID'];
        $OrderRemark = $input['Command'];
        $CourseFood  = $input['CourseFood'];
        $update = $this->dynamicModel->confirmOrderCustomerCaptain($Remark, $Price, $Qty, $UserName, $Status, $Time, $OrderNo, 
            $OrderDetailID, $OrderRemark, $CourseFood);
        echo $update;
    }
    function getRemark()
    {
        $data = $this->input->post();
        $OrderDetailID = $data['OrderDetailID'];
        $getRemark = $this->dynamicModel->gerRemarkByID($OrderDetailID);
        echo $getRemark;
    }
    function deleteAllItemCaptain()
    {
        $data = $this->input->post();
        $OrderDetailID = $data['OrderDetailID'];
        $OrderNo = $data['OrderNo'];

        $delete = $this->dynamicModel->deleteAllItemCaptainByID($OrderNo, $OrderDetailID);
        echo $delete;
    }
    function getSubItemforExtrafood()
    {
        $data = $this->input->post();
        $OrderDetailID = $data['OrderDetailID'];
        $getSub = $this->dynamicModel->getSubItemDetail($OrderDetailID);
        echo $getSub;
    }
    function getTimer()
    {
        $data = $this->input->post();
        $TableNo = $data['TableNo'];
        $gettime = $this->dynamicModel->getTimerbyTableNo($TableNo);
        header('Content-Type: application/json');
        echo json_encode($gettime);
    }
    function getLastOrderID($tbl)
    {
        $last = $this->dynamicModel->getLastOrderID($tbl);

        echo $last;
    }
    function saveQtyItem($OrderDetailID,$Qty,$Course)
    {
        // $input = $this->input->post();
        // $OrderDetailID = $input['detailID'];
        // $Qty = $input['qty'];
        // $Course = $input['course'];

        $saveqty = $this->dynamicModel->saveqtybyDetailID($OrderDetailID, $Qty, $Course);
        echo $saveqty;
    }
    function saveItemOption()
    {
        $input = $this->input->post();
        $OrderDetailID = $input['id'];
        $Opt = substr($input['opt'],1,-1);
        $saveopt = $this->dynamicModel->saveoptbyDetailID($OrderDetailID, $Opt);
        echo $saveopt;
    }
    function confirmOrder($OrderDetailID,$Status)
    {
        // $input = $this->input->post();
        // $OrderDetailID = $input['detailID'];
        // $Status = $input['status'];
        $update = $this->dynamicModel->confirmOrderByID($OrderDetailID,$Status);
        echo $update;
    }
    function deleteOrderByStatusOpen($OrderDetailID,$Status)
    {
        // $input = $this->input->post();
        // $OrderDetailID = $input['detailID'];
        // $Status = $input['status'];
        $delete = $this->dynamicModel->deleteOrderByStatusOpen($OrderDetailID,$Status);
        echo $delete;
    }
    function getAllListItem()
    {
        $data = $this->dynamicModel->getAllListItem();

        header('Content-Type: application/json');
        echo json_encode($data);
    }
    function getCountCurrentOrderbyID($orderNo,$tableNo)
    {
        $data = $this->dynamicModel->getCountCurrentOrderbyID($orderNo,$tableNo);
        echo $data;
    }
    function getlistConfig()
    {
        $query = $this->db->query("SELECT * FROM tblconfig")->result();
        header('Content-Type: application/json');
        echo json_encode($query);
    }
    function deleteItemByID($OrderDetailID)
    {
        $data = $this->dynamicModel->deleteItemByID($OrderDetailID);
        echo $data;
    }
    function getListforExtra($tableNo,$orderNo)
    {
        $query = $this->dynamicModel->getListforExtraFood($tableNo,$orderNo);
        header('Content-Type: application/json');
        echo json_encode($query);
    }
    function saveExtra()
    {
        // $input = $this->input->post();
        // $date = Date('Y-m-d H:i:s');
        // $data = array(
        //     'AssociatedItem' => $input['assid'],
        //     'ItemName' => $input['name'],
        //     'ItemNameKhmer' => $input['namekh'],
        //     'Qty' => $input['qty'],
        //     'Discount' => 0,
        //     'Status' => 'Open',
        //     'Price' => $input['price'],
        //     'Time' => $date,
        //     'OrderNo' => $input['ord'],
        //     'ItemID' => $input['itemid'],
        // );
        $input = $this->input->post();
        $date = Date('Y-m-d H:i:s');
        $data = array(
            'AssociatedItem' => $input['assid'],
            'OrderNo'        => $input['ord'],
            'ItemID'         => $input['itemid'],
            'Qty'            => 1,
            'Price'          => $input['price'],
            'Discount'       => 0,
            'Status'         => 'Open',
            'ItemName'       => $input['name'],
            'ItemNameKhmer'  => $input['namekh'],
            'Time'           => $date,
            'Remark'         => ""
        );

        $save = $this->dynamicModel->saveExtraFood($data);
        echo $save;
    }
    function getsubItemextra($detailID,$OrderNo)
    {   
        $query = $this->dynamicModel->getsubItemextra($detailID,$OrderNo);
        header('Content-Type: application/json');
        echo json_encode($query);
    }   
    function set_barcode()
    {
        $this->load->library('ciqrcode');

        $data = $this->db->query("SELECT * FROM tblconfig")->row();

        $params['data'] = json_encode($data);
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = FCPATH.'tes.png';
        $this->ciqrcode->generate($params);

        echo '<img style="height: 500px;" src="'.base_url().'tes.png" />';

    }
    function getTextNotification()
    {
        $query = $this->dynamicModel->getTextNotification();
        header('Content-Type: application/json');
        echo json_encode($query);
    }
    function savePlayerID($player,$roleid,$userid)
    {
        $save = $this->dynamicModel->updatePlayerID($player,$roleid,$userid);
        header('Content-Type: application/json');
        echo json_encode($check);
    }
    function saveTextNotification()
    {
        $input = $this->input->post();
        $text = $input['text'];
        $ord = $input['ord'];
        $tbl = $input['tbl'];
        $roleid = $input['roleid'];
        
        $savetext = $this->dynamicModel->saveTextNotification($text,$ord,$tbl,$roleid);
        header('Content-Type: application/json');
        echo json_encode($savetext);
    }
    function getcountNotification($roleid)
    {
        $count = $this->dynamicModel->countNotification($roleid);
        header('Content-Type: application/json');
        echo json_encode($count);
    }
    function getPlayerIDFromUser($roleid)
    {
        $count = $this->dynamicModel->getPlayerIDFromUser($roleid);
        header('Content-Type: application/json');
        echo json_encode($count);
    }
    function getlistNotification()
    {
        $count = $this->dynamicModel->getlistNotification();
        header('Content-Type: application/json');
        echo json_encode($count);
    }
    function disableNotification($notid)
    {
        $disable = $this->dynamicModel->disableNotification($notid);
        header('Content-Type: application/json');
        echo json_encode($disable);
    }
    function getHistoryNotification()
    {
        $count = $this->dynamicModel->getHistoryNotification();
        header('Content-Type: application/json');
        echo json_encode($count);
    }
    function getFoodRemark()
    {
        $remark = $this->dynamicModel->getFoodRemark();
        header('Content-Type: application/json');
        echo json_encode($remark);
    }
    function getRemarkAfterSave($id)
    {
        $remark = $this->dynamicModel->getRemarkAfterSave($id);
        header('Content-Type: application/json');
        echo json_encode($remark);
    }
    function Allitems($offset)
    {
        $count = $this->dynamicModel->CountItem();
        $page = 10; 
        
        $all = $this->dynamicModel->Allitems((int)$offset,$page);
        if($offset !="")
        {
            $data = array('page'=>(int)$offset,'total_results'=>$count,'total_pages'=>10,'results'=>$all);
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        
    }
    function Category()
    {
        $getMenu = $this->dynamicModel->Category();
        header('Content-Type: application/json');
        echo json_encode($getMenu);
    }
}
