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
    											i.ImagePath 
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
}
?>