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
    function getAllItem()
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
               ORDER By i.ItemID DESC ")->result();
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
    											i.AddOnMenu,
                                                i.Menu
    											FROM tblItem as i WHERE i.ItemID = $itemid ")->row();
    	return $query;
    }
    function getIdItem()
    {
        $id = $this->app_db->query("SELECT TOP 1 * FROM tblItem ORDER BY ItemID DESC")->row();
        return $id->ItemID;
    }
    function saveItemfromConfig($id,$name,$namekh,$price,$category,$inventery,$addon,$is_menu)
    {
      //$this->app_db->insert('tblItem',array_merge($data,$data1));
        $date = Date('Y-m-d H:i:s');
        $this->app_db->query("INSERT INTO tblItem (ItemID,
                                                 Description,
                                                 DescriptionInKhmer,
                                                 UnitPrice,
                                                 AvgCost,
                                                 CategoryID,
                                                 VendorID,
                                                 UnitOnOrder,
                                                 InventoryType,
                                                 TaxIncluded,
                                                 ModifyingDate,
                                                 AddOnMenu,
                                                 UnitofMeasurement,
                                                 UnitofMeasurementLevel2,
                                                 QtyInLevel1,
                                                 InventoryMix,
                                                 HourlyCharge,
                                                 Active,
                                                 Menu)
                                            VALUES( '$id',
                                                    '$name',
                                                    N'$namekh',
                                                    '$price',
                                                    '0',
                                                    '$category',
                                                    '001',
                                                    '0',
                                                    '$inventery',
                                                    'false',
                                                    '$date',
                                                    '$addon',
                                                    '001',
                                                    '001',
                                                    '1',
                                                    'False',
                                                    'False',
                                                    'True',
                                                    '$is_menu')
                                             ");
        return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function saveImage($id,$image)
    {
      $this->app_db->set('ImagePath',$image);
      $this->app_db->where('ItemID',$id);
      $this->app_db->update('tblItem');
      return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function editItemfromConfig($itemid,$name,$namekh,$price,$category,$inventery,$addon,$is_menu)
    {
        $date = Date('Y-m-d H:i:s');
        $this->app_db->query("UPDATE tblItem SET Description = '$name',
                                             DescriptionInKhmer = N'$namekh',
                                             UnitPrice = '$price',
                                             AvgCost = '0',
                                             CategoryID = '$category',
                                             VendorID = '001',
                                             UnitOnOrder = '0',
                                             InventoryType = '$inventery',
                                             TaxIncluded = 'False',
                                             ModifyingDate = '$date',
                                             AddOnMenu = '$addon',
                                             UnitofMeasurement = '001',
                                             UnitofMeasurementLevel2 = '001',
                                             QtyInLevel1 = '1',
                                             InventoryMix = 'false',
                                             HourlyCharge = 'false',
                                             Active = 'true',
                                             Menu = '$is_menu' WHERE ItemID = '$itemid' ");
        
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
    function getNotificationLimitPage($limit)
    {
    	$query = $this->app_db->query("SELECT * FROM tblNotificationText as n 
			   LEFT JOIN tblBusinessInfo as b
			   ON b.res_id = n.res_id 
			   WHERE b.status = 1 {$limit}
			   ORDER By n.notificationTextID DESC ")->result();
		return $query;
    }
    function getAllNotification()
    {
        $query = $this->app_db->query("SELECT * FROM tblNotificationText as n 
               LEFT JOIN tblBusinessInfo as b
               ON b.res_id = n.res_id 
               WHERE b.status = 1
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
    function saveNotification($business,$note,$notekh,$role)
    {
        $date = Date('Y-m-d H:i:s');
        $this->app_db->query("INSERT INTO tblNotificationText (res_id,
                                                               notificationText,
                                                               notificationTextKh,
                                                               modifyingDate,
                                                               userRoleID)
                                                        VALUES ('$business',
                                                                '$note',
                                                                N'$notekh',
                                                                '$date',
                                                                '$role')
                                                               ");
        return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function editNotification($noteid,$business,$note,$notekh,$role)
    {
        $date = Date('Y-m-d H:i:s');
        $this->app_db->query("UPDATE tblNotificationText SET res_id = '$business',
                                                             notificationText = '$note',
                                                             notificationTextKh = N'$notekh',
                                                             modifyingDate = '$date',
                                                             userRoleID = '$role' WHERE notificationTextID = '$noteid' ");
    	return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function validateNotificationText($note,$notekh,$id)
    {
    	$where='';
        if($id!='')
            $where.=" AND notificationTextID<>'$id'";
        return $this->app_db->query("SELECT COUNT(*) as count FROM tblNotificationText where notificationText='$note' {$where}")->row()->count;
    }
    function getServerType()
    {
    	$query = $this->db->query("SELECT * FROM tblserver")->result();
    	return $query;
    }
    function getIdCategory()
    {
        $id = $this->app_db->query("SELECT TOP 1 * FROM tblCategory ORDER BY CategoryID DESC")->row();
        return $id->CategoryID;
    }
    function validateCategory($name,$namekh,$cateid)
    {
        $where='';
        if($cateid!='')
            $where.=" AND CategoryID <> '$cateid'";
        return $this->app_db->query("SELECT COUNT(*) as count FROM tblCategory where CategoryName = '$name' {$where} ")->row()->count;
    }
    function getCategoryLimitPage($limit)
    {
        $query = $this->app_db->query("SELECT * FROM tblCategory 
               WHERE MenuCategory = 'True' {$limit}
               ORDER By CategoryID DESC ")->result();
        return $query;
    }
    function getCategorybyID($id)
    {
        $query = $this->app_db->query("SELECT * FROM tblCategory WHERE CategoryID = $id ")->row();
        return $query;
    }
    function saveCatgory($id,$name,$namekh,$description,$addon,$is_default)
    {
        //$this->app_db->insert('tblCategory',$data);
        $date = Date('Y-m-d H:i:s');
        $this->app_db->query("INSERT INTO tblCategory (CategoryID,
                                                       CategoryName,
                                                       CategoryNameInKhmer,
                                                       Description,
                                                       MenuCategory,
                                                       ModifyingDate,
                                                       ModifyingPersonID,
                                                       RoomService,
                                                       IsDefault)
                                            VALUES ('$id',
                                                    '$name',
                                                    N'$namekh',
                                                    '$description',
                                                    '$addon',
                                                    '$date',
                                                    '001',
                                                    'False',
                                                    '$is_default'
                                                    )
                                                       ");
        return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function editCategory($cateid,$name,$namekh,$description,$addon,$is_default)
    {
        $date = Date('Y-m-d H:i:s');
        $this->app_db->query("UPDATE tblCategory SET IsDefault = 'False' WHERE CategoryID <> $cateid ");
        $this->app_db->query("UPDATE tblCategory SET CategoryName = '$name',
                                                     CategoryNameInKhmer = N'$namekh',
                                                     Description = '$description',
                                                     MenuCategory = '$addon',
                                                     ModifyingDate = '$date',
                                                     ModifyingPersonID = '001',
                                                     RoomService = 'False',
                                                     IsDefault = '$is_default' WHERE CategoryID = '$cateid' ");
        return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function deleteCategory($id)
    {
        $this->app_db->where('CategoryID',$id);
        $this->app_db->delete('tblCategory');
        return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function getCategoryall()
    {
        $query = $this->app_db->query("SELECT * FROM tblCategory WHERE MenuCategory = 'True' ")->result();
        return $query;
    }
    function getTable()
    {
        $query = $this->app_db->query("SELECT * FROM tblTable")->result();
        return $query;
    }
    function editConfiger($id,$data)
    {
        $this->db->where('id',$id);
        $this->db->update('tblconfig',$data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function saveConfiger($data)
    {
        $this->db->insert('tblconfig',$data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
}
?>