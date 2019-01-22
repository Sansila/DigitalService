<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class configController extends CI_Controller {
    
    protected $thead;
	protected $idfield;
	protected $searchrow;

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
        $this->load->model(array('configModel'));
        $this->configModel->app_db = $this->load->database($config_app,TRUE);

        $this->thead=array("No"=>'no',
							"Date"=>"Date",
							"Item Name"=>'Item Name',
							"Item Name Kh"=>'Item Name Kh',
							"Price" => 'Price',
							"Category Name"=>'Category Name',
							"Inventery Type"=> "Inventery Type",
							"Action"=>'Action'							 	
							);
		$this->idfield="categoryid";
    }
    public function index()
    {
        
    }
    function login()
    {
        $this->load->view('configer/login');
    }
    function loginerror($error)
    {
        $data['msg'] = $error;
        $this->load->view('configer/login',$data);
    }
    function logout()
    {
        $this->session->sess_destroy();
        redirect('configController/login', 'refresh');
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
            redirect('configController/form_config', 'refresh'); 
        }else{
            $msg = "error";
            redirect('configController/loginerror/'.$msg, 'refresh');
        }
        
    }
    function form_config()
    {
        $config = $this->db->query("SELECT * FROM tblconfig")->row();
        if($config !="")
            $data['config'] = $config;
        $this->load->view('header');
        $this->load->view('configer/configerform',$data);
    }
    function saveConfiger()
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
    }
    function aftersaveform($msg)
    {
        $config = $this->db->query("SELECT * FROM tblconfig")->row();
        if($config !="")
            $data['config'] = $config;
        if($msg == "upd")
            $data['msg'] = "update";
        if($msg == "insert")
            $data['msg'] = "insert";
        $this->load->view('header');
        $this->load->view('configer/configerform',$data);
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
        //echo '<img style="height: 500px;" src="'.base_url().'tes.png" />';
        $qr['imgqrcode'] = '<img style="height: 400px;" src="'.base_url().'tes.png" />';
        $this->load->view('header');
        $this->load->view('configer/qrcode',$qr);
    }
    function additem()
    {
        $data['title'] = 'add item';
        $this->load->view('header');
        $this->load->view('item/add',$data);
    }
    function saveItemfromConfig()
    {
    	$input = $this->input->post();
        $count = $this->configModel->getIdItem();
        $id = ($count + 1);
        $id = str_pad($id,3,'0',STR_PAD_LEFT);
        $itemid = $input['ItemID'];
        $insert = "";
        $data = array(
            'Description' => $input['name'],
            'DescriptionInKhmer' => $input['namekh'],
            'UnitPrice' => $input['price'],
            'AvgCost' => 0,
            'CategoryID' => $input['category'],
            'VendorID' => '001',
            'UnitOnOrder' => 0,
            'InventoryType' =>  $input['inventery'],
            'TaxIncluded' => false,
            'ModifyingDate' => Date('Y-m-d H:i:s'),
            'Menu' => true,
            'UnitofMeasurement' => '001',
            'UnitofMeasurementLevel2' => '001',
            'QtyInLevel1' => 1,
            'InventoryMix' => false,
            'HourlyCharge' => false,
            'Active' => true,
        );
        $data1 = array('ItemID' => $id,);
        if($itemid !="")
        {
        	$edit = $this->configModel->editItemfromConfig($data,$itemid);
        	if($edit){
        		$msg = "update";
            	$this->do_upload($itemid,$msg);
        	}else{
        		$msg = "false";
            	redirect('configController/additem/failed', 'refresh'); 
        	}
        }else{
        	$insert = $this->configModel->saveItemfromConfig($data,$data1);
        	if($insert){
        		$msg = "insert";
            	$this->do_upload($id,$msg);
        	}else{
        		$msg = "false";
            	redirect('configController/additem/failed', 'refresh'); 
        	}
        }
        
    }
    function do_upload($id,$msg)
    {
        
        if(!file_exists('./img/upload/')){
            if(mkdir('./img/upload/',0755,true)){
                return true;
            }
        }
        $config['upload_path'] ='./img/upload/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['file_name']  = "$id.jpg";
        $config['overwrite']=true;
        $config['file_type']='image/jpg';
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {
            $error = array('error' => $this->upload->display_errors());
            if($msg == "insert")
            	redirect('configController/additem/success', 'refresh');
            else
            	redirect('configController/edit/'.$id.'/updated', 'refresh');
        }
        else
        {               
            $image = "$id.jpg";
            $insert = $this->configModel->saveImage($id,$image);
            if($insert) {
            	if($msg == "update")
                	redirect('configController/edit/'.$id.'/updated', 'refresh');
                else
                	redirect('configController/additem/success', 'refresh');
            }else{
                redirect('configController/additem/failed', 'refresh');
            }
        }
    }
    function viewitem()
    {
    	$data['idfield']=$this->idfield;		
		$data['thead']=	$this->thead;
        $this->load->view('header');
        $this->load->view('item/view',$data);
    }
    function getdata()
    {
    	$perpage=$this->input->post('perpage');
    	$page=$this->input->post('page');
    	$limit = "";
    	// $query = $this->configModel->getListItem();
    	$query = $this->configModel->getLimitPage($limit);
		$table='';
		$pagina='';
		$paging=$this->green->ajax_pagination(count($query),site_url("configController/getdata"),$perpage);
		$i=1;
		$limit = "";
	    if($page == 1)
	    {
	        $limit.= "WHERE i.ItemID BETWEEN ".$paging['start']." AND ".$paging['limit'];
	    }else{
	        $limit.= "WHERE i.ItemID BETWEEN ".($paging['start'] + 1)." AND ".$paging['limit'] * $page;
	    }
		$sql = $this->configModel->getLimitPage($limit);

		foreach($sql as $row){
			
			$table.= "<tr>
				 <td class='no'>".$i."</td>
				 <td class='no'>".date('Y-m-d h:i:s a', strtotime($row->ModifyingDate))."</td>
				 <td class='no'>".$row->Description."</td>
				 <td class='no'>".$row->DescriptionInKhmer."</td>
				 <td class='no'>".$row->UnitPrice."</td>
				 <td class='no'>".$row->CategoryName."</td>
				 <td class='no'>".$row->InventoryType."</td>
				 <td class='remove_tag no_wrap'>";

					$table.= "<a style='padding:0px 10px;'><img rel=".$row->ItemID." onclick='deletestore(event);' src='".base_url('images/delete.png')."' width='30'/></a>";
					$table.= "<a style='padding:0px 10px;'><img rel=".$row->ItemID." onclick='update(event);' src='".base_url('images/edit.png')."' width='30'/></a>";
				 
			$table.= " </td>
				 </tr>
				 ";										 
			$i++;	 
		}
		$arr['data']=$table;
		$arr['pagina']=$paging;
		$arr['limit'] = $limit;
		header("Content-type:text/x-json");
		echo json_encode($arr);
  	}
  	function edit($itemid)
  	{
  		$data['title'] = 'edit item';
  		$query = $this->configModel->getItenbyID($itemid);
  		$data['edit'] = $query;
        $this->load->view('header');
        $this->load->view('item/add',$data);
  	}
  	function delete($itemid)
  	{
  		$this->configModel->deleteItem($itemid);
  	}
  	function addnotification()
  	{
  		$data['title'] = 'add Notification';
        $this->load->view('header');
        $this->load->view('notification/add',$data);
  	}
}
?>