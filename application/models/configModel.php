<?php
class configModel extends CI_Model {
    public $app_db;
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Phnom_Penh');
        $this->load->database('app_dbsqlserver');
    }
    function getListItem()
    {
    	$query = $this->app_db->query("SELECT i.ItemID,i.ModifyingDate,i.CategoryID,c.CategoryID FROM tblItem as i 
			   INNER JOIN tblCategory as c
			   ON i.CategoryID = c.CategoryID
			   ORDER By i.ModifyingDate DESC")->result();
		return $query;
    }
    function getLimitPage($limit)
    {
    	$query = $this->app_db->query("SELECT i.ItemID,
    										  i.CategoryID,
    										  i.Description,
    										  i.DescriptionInKhmer,
    										  i.UnitPrice,
    										  i.InventoryType,
    										  i.ModifyingDate,
    										  c.CategoryID, 
    										  c.CategoryName,
    										  i.ModifyingDate
    										  FROM tblItem as i 
			   INNER JOIN tblCategory as c
			   ON i.CategoryID = c.CategoryID 
			   {$limit}
			   ORDER By i.ModifyingDate DESC ")->result();
		return $query;
    }
    function getCategories()
    {
      $cate = $this->app_db->query("SELECT * FROM tblCategory WHERE MenuCategory != 'false' ")->result();
      return $cate;
    }
    function getItenbyID($itemid)
    {
    	$query = $this->app_db->query("SELECT   i.ItemID,
    											i.DescriptionInKhmer, 
    											i.Description,
    											i.UnitPrice,
    											i.InventoryType,
    											i.CategoryID,
    											i.ImagePath,
    											i.AddOnMenu
    											FROM tblItem as i WHERE i.ItemID = $itemid ")->row();
    	return $query;
    }
    function getIdItem()
    {
        $id = $this->app_db->query("SELECT TOP 1 * FROM tblItem ORDER BY ItemID DESC")->row();
        return $id->ItemID;
    }
    function saveItemfromConfig($data,$data1)
    {
      $this->app_db->insert('tblItem',array_merge($data,$data1));
      return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function saveImage($id,$image)
    {
      $this->app_db->set('ImagePath',$image);
      $this->app_db->where('ItemID',$id);
      $this->app_db->update('tblItem');
      return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function editItemfromConfig($data,$itemid)
    {
      	$this->app_db->where('ItemID',$itemid);
      	$this->app_db->update('tblItem',$data);
      	return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function deleteItem($itemid)
    {
    	$this->app_db->where('ItemID',$itemid);
    	$this->app_db->delete('tblItem');
    }
    function saveBusiness($data,$data_date)
    {
        $this->app_db->insert('tblBusinessInfo',array_merge($data,$data_date));
        return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function saveTextNotification($txten,$txtkh,$role)
    {
    	$data = array(
        'notificationText' => $txten,
        'notificationTextKh' => $txtkh,
        'userRoleID' => $role
        );

    	$this->db->insert('tblNotificationText', $data);
    	return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function getBusinessLimitPage($limit)
    {
    	$query = $this->app_db->query("SELECT * FROM tblBusinessInfo
			   WHERE status = 1 {$limit} ORDER By res_id DESC ")->result();
		return $query;
    }
    function deleteBusiness($bid)
    {
    	$this->app_db->set('status',0);
    	$this->app_db->where('res_id',$bid);
    	$this->app_db->update('tblBusinessInfo');
    	return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function getBusinessByID($bid)
    {
    	$query = $this->app_db->query("SELECT * FROM tblBusinessInfo WHERE res_id = $bid")->row();
    	return $query;
    }
    function editBusiness($bid,$data)
    {
    	$this->app_db->where('res_id',$bid);
    	$this->app_db->update('tblBusinessInfo',$data);
    	return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function getBusinessList()
    {
    	$query = $this->app_db->query("SELECT * FROM tblBusinessInfo WHERE status = 1 ")->result();
    	return $query;
    }
    function saveNotification($data)
    {
    	$this->app_db->insert('tblNotificationText',$data);
    	return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function getNotificationLimitPage($limit)
    {
    	$query = $this->app_db->query("SELECT * FROM tblNotificationText as n 
			   INNER JOIN tblBusinessInfo as b
			   ON b.res_id = n.res_id 
			   WHERE b.status = 1 {$limit}
			   ORDER By n.notificationTextID DESC ")->result();
		return $query;
    }
    function deletenotificationByID($noteid)
    {
    	$this->app_db->where('notificationTextID',$noteid);
    	$this->app_db->delete('tblNotificationText');
    	return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function getNotificationByID($notid)
    {
    	$query = $this->app_db->query("SELECT * FROM tblNotificationText WHERE notificationTextID = $notid ")->row();
    	return $query;
    }
    function editNotification($noteid,$data)
    {
    	$this->app_db->where('notificationTextID',$noteid);
    	$this->app_db->update('tblNotificationText',$data);
    	return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function validateNotificationText($note,$notekh,$id)
    {
    	$where='';
        if($id!='')
            $where.=" AND notificationTextID<>'$id'";
        return $this->app_db->query("SELECT COUNT(*) as count FROM tblNotificationText where notificationText='$note' AND notificationTextKh = '$notekh' {$where}")->row()->count;
    }
    function getServerType()
    {
    	$query = $this->db->query("SELECT * FROM tblserver")->result();
    	return $query;
    }
}
?>