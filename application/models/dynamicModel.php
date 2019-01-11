<?php
class dynamicModel extends CI_Model {
    public $app_db;
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Phnom_Penh');
        $this->load->database('app_dbsqlserver');
    }

    function loginUser($username,$password)
    {
        $query = $this->app_db->query("SELECT * FROM tblUserIpad WHERE IpadUser = '$username' AND IpadPassWord = '$password' ")->row();
        if($query)
        {
          return $query->UserType;
        }else{
          return false;
        }
        
    }
    function checkPassword($user)
    {
      $query = $this->app_db->query("SELECT * FROM tblUserIpad WHERE IpadUser = '$user' ");
      $ret = $query->row();
      return $ret->IpadPassWord;
    }
    function updateNewpassword($user,$cpwd)
    {
      $this->app_db->set('IpadPassWord', $cpwd);
      $this->app_db->where('IpadUser',$user);
      $this->app_db->update('tblUserIpad');
      return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE; 
    }
    function getTable()
    {
        $query = $this->app_db->query("SELECT * FROM View_TableStatus")->result();
        return $query;
    }
    function getMenu()
    {
        $query = $this->app_db->query("SELECT * FROM tblCategory WHERE MenuCategory != 'false' ")->result();
        $data = array();
        foreach ($query as $cat) {
          $name = $cat->CategoryName;
          if($this->session->userdata('lang') == 'en')
          {
            $name = $cat->CategoryName;
          }else if($this->session->userdata('lang') == 'kh'){
            $name = $cat->CategoryNameInKhmer;
          }

          $data[] = array(
            'CategoryID' => $cat->CategoryID,
            'CategoryName' => $cat->CategoryName,
            'CategoryNameKH' => $cat->CategoryNameInKhmer,
          );
        }
        return $data;
    }
    function getListOrder($id)
    {
        $query = $this->app_db->query("SELECT * FROM tblOrder WHERE [Table] = '$id' AND Status = 'Open' ORDER BY OrderNo DESC")->result();

        $name = 'OrderNo'; $list = 'Lists Order Number';
        if($this->session->userdata('lang') == 'en')
        {
          $name = 'OrderNo'; $list = 'Lists Order Number';
        }else if($this->session->userdata('lang') == 'kh'){
          $name = 'លេខកម្មង់'; $list = 'បញ្ជីរាយលេខបញ្ជាកម្មង់';
        }

        $data = array();
        foreach ($query as $val) {
          $data[] = array(
            'OrderNo' => $val->OrderNo,
            'Date' => $val->Date,
            'Name' => $name,
            'List' => $list,
          );
        }
        return $data;
    }
    function getListOrders($id)
    {
        $query = $this->app_db->query("SELECT * FROM tblOrder WHERE [Table] = '$id' AND Status = 'Open' ORDER BY OrderNo DESC")->result();
        $data = array();
        $i = 1;
        foreach ($query as $val) {
          $data[] = array(
            'index' => $i,
            'orderno' => $val->OrderNo,
            'date' => $val->Date,
          );
          $i++;
        }
        return $data;
    }
    function checkStatusTable($tableid,$OrderNo)
    {
        $row = $this->app_db->query("SELECT * FROM tblTable WHERE TableNo = '$tableid' ")->row();
        
        if($row->Status == 'Available')
        {
            $this->app_db->query("UPDATE tblTable SET Status = 'Busy' WHERE TableNo = '$tableid' ");

            $Order = $this->app_db->query("SELECT TOP 1 * FROM tblOrder ORDER BY OrderNo DESC ")->row();

            $newOrderNo = $Order->OrderNo + 1;

            $date = Date('Y-m-d H:i:s');
            $data = array('OrderNo' => $newOrderNo,
                          'Table' => $tableid,
                          'Date'  => $date,
                          'StationID' => '001',
                          'CustomerID' => '001',
                          'POSLocationID' => '001',
                          'ModifyingDate' => $date,
                          'OrderingPersonID' => '001',
                          'ServingPersonID' => '001',
                          'Status' => 'Open',
                          'CheckOutTime' => $date
                        );
            $this->app_db->insert('tblOrder',$data);
            return $newOrderNo;
        }else{
            return $OrderNo;
        }

        
    }
    function makeNewOrder($tableid)
    {
        $table = $this->app_db->query("SELECT * FROM tblTable WHERE TableNo = '$tableid' ")->row();
        if($table->Status = 'Available')
        {
            $this->app_db->query("UPDATE tblTable SET Status = 'Busy' WHERE TableNo = '$tableid' ");
        }

        $Order = $this->app_db->query("SELECT TOP 1 * FROM tblOrder ORDER BY OrderNo DESC ")->row();
        $date = Date('Y-m-d H:i:s');
        $newOrderNo = $Order->OrderNo + 1;
        $data = array('OrderNo' => $newOrderNo,
                          'Table' => $tableid,
                          'Date'  => $date,
                          'StationID' => '001',
                          'CustomerID' => '001',
                          'POSLocationID' => '001',
                          'ModifyingDate' => $date,
                          'OrderingPersonID' => '001',
                          'ServingPersonID' => '001',
                          'Status' => 'Open',
                          'CheckOutTime' => $date
                        );
        $this->app_db->insert('tblOrder',$data);
        return $newOrderNo;
    }
    function getListItems($categoryid)
    {
        $query = $this->app_db->query("SELECT i.*, c.CategoryID, c.ModifyingPersonID FROM tblItem as i INNER JOIN tblCategory as c ON i.CategoryID = c.CategoryID WHERE i.CategoryID = '$categoryid' ")->result();

        $data = array();
        foreach ($query as $val) {
          if($val->Picture)
          {
            $mime = "image/jpeg";
            $b64Src = "data:".$mime.";base64," . base64_encode($val->Picture);
            $img = '<img src="'.$b64Src.'" alt="" />';
          }else{
            $img = '<img src="'.base_url('img/Notfound.png').'" alt="" />';
          }

          $name = $val->Description;
          if($this->session->userdata('lang') == 'en')
          {
            $name = $val->Description;
          }else if($this->session->userdata('lang') == 'kh'){
            $name = $val->DescriptionInKhmer;
          }

          $data[] = array('ItemID'  => $val->ItemID,
                        'Description'   => $name,
                        'DescriptionInKhmer'    => $val->DescriptionInKhmer,
                        'UnitPrice' => $val->UnitPrice,
                        'CategoryID' => $val->CategoryID,
                        'Picture' => $img,
                        'ModifyingPersonID' => $val->ModifyingPersonID,
                        'AvgCost' => $val->AvgCost,
                        'InventoryMix' => $val->InventoryMix  
                      );
        }
        return $data;
    }
    function getListItembyID($categoryid)
    {
        $query = $this->app_db->query("SELECT *, c.CategoryID, c.ModifyingPersonID FROM tblItem as i INNER JOIN tblCategory as c ON i.CategoryID = c.CategoryID WHERE i.CategoryID = '$categoryid' ")->result();

        $data = array();
        foreach ($query as $val) {
          $name = $val->Description;
          $data[] = array('itemid'  => $val->ItemID,
                        'description' => $name,
                        'descriptionkh' => $val->DescriptionInKhmer,
                        'price' => number_format($val->UnitPrice,2),
                        'categoryid' => $val->CategoryID,
                        'picture' => base64_encode($val->Picture),
                        'pictures' => site_url('img/beef.jpg'),
                        'modifyID' => $val->ModifyingPersonID
                      );
        }
        return $data;
    }
    function getDatadetail($itemID)
    {
        $query = $this->app_db->query("SELECT * FROM tblItem WHERE ItemID = '$itemID' ")->result();
        $data = array();
        foreach ($query as $val) {
          if($val->Picture)
          {
            $mime = "image/jpeg";
            $b64Src = "data:".$mime.";base64," . base64_encode($val->Picture);
            $img = '<img src="'.$b64Src.'" alt="" />';
          }else{
            $img = '<img src="'.base_url('img/Notfound.png').'" alt="" />';
          }
          $name = $val->Description;
          if($this->session->userdata('lang') == 'en')
          {
            $name = $val->Description;
          }else if($this->session->userdata('lang') == 'kh'){
            $name = $val->DescriptionInKhmer;
          }
          $data[] = array('ItemID'  => $val->ItemID,
                        'Description'  => $name,
                        'DescriptionInKhmer' => $val->DescriptionInKhmer,
                        'UnitPrice' => $val->UnitPrice,
                        'CategoryID' => $val->CategoryID,
                        'Picture' => $img,
                        'InventoryMix' => $val->InventoryMix,
                        'AvgCost' => $val->AvgCost
                      );
        }
        return $data;
    }
    function SaveOrderItem($data)
    {
       $query = $this->app_db->insert('tblOrderDetails', $data);
       if($query)
       {
          return 1;
       }else{
          return 0;
       }
    }
    function SaveDetail($data)
    {
      $save = $this->app_db->insert('tblOrderDetails', $data);
      if($save)
      {
          return "success";
      }else{
          return "false";
      }
    }
    function getItemExtrafood($TableNo)
    {
        $query = $this->app_db->query("SELECT
                                    o.OrderNo,
                                    o.[Table],
                                    od.OrderDetailID,
                                    od.AssociatedItem,
                                    od.OrderNo,
                                    od.ItemName,
                                    od.ItemNameKhmer,
                                    od.ItemID,
                                    od.Price,
                                    od.Status,
                                    i.ItemID,
                                    i.Picture
                                  FROM
                                    tblOrderDetails AS od
                                  INNER JOIN tblOrder AS o ON od.OrderNo = o.OrderNo
                                  INNER JOIN tblItem as i On i.ItemID = od.ItemID
                                  WHERE
                                    od.Status = 'Open'
                                  AND o.[Table] = '$TableNo' ");
        $cat = array(
            'items' => array(),
            'parents' => array()
        );
        foreach ($query->result() as $cats) {
            $cat['items'][$cats->OrderDetailID] = $cats;
            $cat['parents'][$cats->AssociatedItem][] = $cats->OrderDetailID;
        }
        if ($cat) {
            $result = $this->getSubItemDetail(null, $cat);
            return $result;
        } else {
            return FALSE;
        }
    }
    function getSubItemDetail($parent, $menu)
    {
      $html = "";
      if (isset($menu['parents'][$parent])) {

          $html .= "<ul>";
          foreach ($menu['parents'][$parent] as $itemId) {

              if($menu['items'][$itemId]->Picture)
                {
                  $mime = "image/jpeg";
                  $b64Src = "data:".$mime.";base64," . base64_encode($menu['items'][$itemId]->Picture);
                  $img = '<img src="'.$b64Src.'" alt="" width="88" height="88" />';
                }else{
                  $img = '<img src="'.base_url('img/Notfound.png').'" alt="" width="88" height="88" />';
              }


              if (!isset($menu['parents'][$itemId])) {

                  $html .= '<li>';
                  $html .= '<div class="item-content">';
                  $html .= '<div class="item-media">';
                  $html .= $img;
                  $html .= '</div>';
                  $html .= '<div class="item-inner">';
                  $html .= '<div class="item-title-row">';
                  $html .= '<div class="item-title" style="padding: 0px 10px 0px 0px;">$'.$menu['items'][$itemId]->Price.'</div>';
                  $html .= '</div>';
                  $html .= '<div class="item-text">'.$menu['items'][$itemId]->ItemName.'</div>';
                  $html .= '</div>';
                  $html .= '<div class="btn-choose" data="'.$menu['items'][$itemId]->OrderDetailID.'">Choose</div>';
                  $html .= '</div>';
                  $html.='</li>';

              }
              if (isset($menu['parents'][$itemId])) {

                  $html .= '<li>';
                  $html .= '<div class="item-content">';
                  $html .= '<div class="item-media">';
                  $html .= $img;
                  $html .= '</div>';
                  $html .= '<div class="item-inner">';
                  $html .= '<div class="item-title-row">';
                  $html .= '<div class="item-title" style="padding: 0px 10px 0px 0px;">$'.$menu['items'][$itemId]->Price.'</div>';
                  $html .= '</div>';
                  $html .= '<div class="item-text">'.$menu['items'][$itemId]->ItemName.'</div>';
                  $html .= '</div>';
                  $html .= '<div class="btn-choose" data="'.$menu['items'][$itemId]->OrderDetailID.'">Choose</div>';
                  $html .= '</div>';
                  $html .= $this->getSubItemDetail($itemId, $menu);
                  $html.='</li>';
              }

          }

          $html .= "</ul>";
      }
      return $html;

    }
    function SaveExtra($data)
    {
      $query = $this->app_db->insert('tblOrderDetails', $data);
       if($query)
       {
          return 1;
       }else{
          return 0;
       }
    }
    function getSub($OrderDetailID)
    {
        $query = $this->app_db->query("SELECT
                                        i.ItemID,
                                        i.Picture,
                                        i.Description,
                                        i.DescriptionInKhmer,
                                        i.CategoryID,
                                        od.ItemID,
                                        od.OrderDetailID,
                                        od.Price,
                                        od.OrderNo,
                                        od.AssociatedItem
                                      FROM
                                        tblItem AS i
                                      INNER JOIN tblOrderDetails AS od ON i.ItemID = od.ItemID
                                      WHERE
                                        od.AssociatedItem = '$OrderDetailID' ")->result();
        $data = array();
        foreach ($query as $val) {
          if($val->Picture)
          {
            $mime = "image/jpeg";
            $b64Src = "data:".$mime.";base64," . base64_encode($val->Picture);
            $img = '<img src="'.$b64Src.'" width="105" height="105" alt="" />';
          }else{
            $img = '<img src="'.base_url('img/Notfound.png').'" width="105" height="105" alt="" />';
          }

          $name = $val->Description;
          if($this->session->userdata('lang') == 'en')
          {
            $name = $val->Description;
          }else if($this->session->userdata('lang') == 'kh'){
            $name = $val->DescriptionInKhmer;
          }

          $data[] = array('ItemID'  => $val->ItemID,
                        'Description'   => $name,
                        'DescriptionInKhmer'    => $val->DescriptionInKhmer,
                        'UnitPrice' => $val->Price,
                        'CategoryID' => $val->CategoryID,
                        'Picture' => $img,
                        'OrderDetailID' => $val->OrderDetailID,
                        'AssociatedItem' => $val->AssociatedItem
                      );
        }
        return $data;
    }

    function getSubs($TableNo)
    {
        $query = $this->app_db->query("SELECT * FROM tblOrderDetails as od INNER JOIN tblOrder as o ON od.OrderNo = o.OrderNo
           WHERE od.Status = 'open' AND o.[Table] = '$TableNo' ")->result();

        return $query;
    }
    function getCurrent($orderNo, $tableNo)
    {
       $query = $this->app_db->query("SELECT
                                        i.ItemID,
                                        i.Picture,
                                        i.Description,
                                        i.DescriptionInKhmer,
                                        i.CategoryID,
                                        od.ItemID,
                                        od.OrderDetailID,
                                        od.Price,
                                        od.OrderNo,
                                        od.AssociatedItem,
                                        od.Status,
                                        od.Qty,
                                        od.Remark,
                                        od.OrderRemark
                                      FROM
                                        tblItem AS i
                                      INNER JOIN tblOrderDetails AS od ON i.ItemID = od.ItemID
                                      WHERE
                                        od.OrderNo = '$orderNo' AND od.Status = 'Open' 
                                      ORDER BY od.OrderDetailID DESC ")->result();
        $data = array();
        foreach ($query as $val) {
          if($val->Picture)
          {
            $mime = "image/jpeg";
            $b64Src = "data:".$mime.";base64," . base64_encode($val->Picture);
            $img = '<img src="'.$b64Src.'" width="105" height="105" alt="" />';
          }else{
            $img = '<img src="'.base_url('img/Notfound.png').'" width="105" height="105" alt="" />';
          }

          $name = $val->Description; $delete ='Delete'; $RemarkTXT = 'Remark...';
          if($this->session->userdata('lang') == 'en')
          {
            $name = $val->Description;
            $delete = 'Delete';
            $RemarkTXT = 'Remark...';
          }else if($this->session->userdata('lang') == 'kh'){
            $name = $val->DescriptionInKhmer;
            $delete = 'លុប';
            $RemarkTXT = 'ការកំណត់...';
          }

          $data[] = array('ItemID'  => $val->ItemID,
                        'Description'   => $name,
                        'DescriptionInKhmer'    => $val->DescriptionInKhmer,
                        'UnitPrice' => $val->Price,
                        'CategoryID' => $val->CategoryID,
                        'Picture' => $img,
                        'OrderDetailID' => $val->OrderDetailID,
                        'AssociatedItem' => $val->AssociatedItem,
                        'Qty' => $val->Qty,
                        'Remark' => $val->Remark,
                        'OrderRemark' => $val->OrderRemark,
                        'Delete' => $delete,
                        'RemarkTXT' => $RemarkTXT,
                        'Image' => base64_encode($val->Picture),
                      );
        }
        return $data;
    }
    function getCurrentOrderbyID($orderNo, $tableNo)
    {
      $query = $this->app_db->query("SELECT
                                        i.ItemID,
                                        i.Picture,
                                        i.Description,
                                        i.DescriptionInKhmer,
                                        i.CategoryID,
                                        od.ItemID,
                                        od.OrderDetailID,
                                        od.Price,
                                        od.OrderNo,
                                        od.AssociatedItem,
                                        od.Status,
                                        od.Qty,
                                        od.Remark,
                                        od.OrderRemark,
                                        od.Course,
                                        (select count(*) from tblOrderDetails where AssociatedItem = od.OrderDetailID) as countAss
                                      FROM
                                        tblItem AS i
                                      INNER JOIN tblOrderDetails AS od ON i.ItemID = od.ItemID
                                      WHERE
                                        od.OrderNo = '$orderNo' AND od.Status = 'Open' 
                                      AND od.AssociatedItem IS NULL
                                      ORDER BY od.OrderDetailID DESC ")->result();
        $UnitPrice = 0;
        $float = 0;
        $data = array();
        foreach ($query as $val) {
          $UnitPrice = number_format($val->Price,2);
          $float = floatval($UnitPrice);
          $data[] = array('Index' => $val->OrderDetailID,
                        'ItemID'  => $val->ItemID,
                        'Description'   => $val->Description,
                        'DescriptionInKhmer'    => $val->DescriptionInKhmer,
                        'UnitPrice' => floatval($val->Price),
                        'Price' => floatval($val->Price * $val->Qty),
                        'CategoryID' => $val->CategoryID,
                        'OrderDetailID' => $val->OrderDetailID,
                        'AssociatedItem' => $val->AssociatedItem,
                        'Qty' => $val->Qty,
                        'Remark' => $val->Remark,
                        'OrderRemark' => $val->OrderRemark,
                        'Image' => base64_encode($val->Picture),
                        'Course' => $val->Course, 
                        'countAss' => $val->countAss
                      );
        }
        return $data;
    }
    function getCurrentOrderbyIDCaptain($orderNo, $tableNo)
    {
      $query = $this->app_db->query("SELECT
                                        i.ItemID,
                                        i.Picture,
                                        i.Description,
                                        i.DescriptionInKhmer,
                                        i.CategoryID,
                                        od.ItemID,
                                        od.OrderDetailID,
                                        od.Price,
                                        od.OrderNo,
                                        od.AssociatedItem,
                                        od.Status,
                                        od.Qty,
                                        od.Remark,
                                        od.OrderRemark,
                                        od.Course,
                                        (select count(*) from tblOrderDetails where AssociatedItem = od.OrderDetailID) as countAss
                                      FROM
                                        tblItem AS i
                                      INNER JOIN tblOrderDetails AS od ON i.ItemID = od.ItemID
                                      WHERE
                                        od.OrderNo = '$orderNo' AND od.Status = 'Open'
                                      ORDER BY od.OrderDetailID DESC ")->result();
        $UnitPrice = 0;
        $float = 0;
        $data = array();
        foreach ($query as $val) {
          $UnitPrice = number_format($val->Price,2);
          $float = floatval($UnitPrice);
          $data[] = array('Index' => $val->OrderDetailID,
                        'ItemID'  => $val->ItemID,
                        'Description'   => $val->Description,
                        'DescriptionInKhmer'    => $val->DescriptionInKhmer,
                        'UnitPrice' => floatval($val->Price),
                        'Price' => floatval($val->Price * $val->Qty),
                        'CategoryID' => $val->CategoryID,
                        'OrderDetailID' => $val->OrderDetailID,
                        'AssociatedItem' => $val->AssociatedItem,
                        'Qty' => $val->Qty,
                        'Remark' => $val->Remark,
                        'OrderRemark' => $val->OrderRemark,
                        'Image' => base64_encode($val->Picture),
                        'Course' => $val->Course, 
                        'countAss' => $val->countAss
                      );
        }
        return $data;
    }
    function deleteCurrentOrderbyItem($currentID)
    {
        $query = $this->app_db->query("DELETE FROM tblOrderDetails WHERE OrderDetailID = '$currentID' ");
        if($query)
        {
          return 1;
        }else{
          return 0;
        }
    }
    function saveAllCurrentOrderItemByOrderNo($orderNo, $qty, $Remark, $OrderDetailID, $RemarkCMD)
    {
        $query = $this->app_db->query(" UPDATE tblOrderDetails SET Qty = '$qty', Remark = '$Remark', OrderRemark = '$RemarkCMD' WHERE OrderDetailID = '$OrderDetailID' AND OrderNo = '$orderNo' AND Status = 'Open' ");
        if($query)
        {
          return 1;
        }else{
          return 0;
        }
    }
    function deleteAllCurrentOrderItemByOrderNo($orderNo)
    {
        $query = $this->app_db->query("DELETE FROM tblOrderDetails WHERE OrderNo = '$orderNo' AND Status = 'Open' ");
        if($query)
        {
          return 1;
        }else{
          return 0;
        }
    }
    function getAllOrderSummary($orderNo)
    {
        $query = $this->app_db->query("SELECT
                                        i.ItemID,
                                        i.Picture,
                                        i.Description,
                                        i.DescriptionInKhmer,
                                        i.CategoryID,
                                        od.ItemID,
                                        od.OrderDetailID,
                                        od.Price,
                                        od.OrderNo,
                                        od.AssociatedItem,
                                        od.Status,
                                        od.Qty,
                                        od.OrderRemark,
                                        od.Course
                                      FROM
                                        tblItem AS i
                                      INNER JOIN tblOrderDetails AS od ON i.ItemID = od.ItemID
                                      WHERE
                                        od.OrderNo = '$orderNo' AND od.Status = 'Confirm' ORDER BY od.Course DESC")->result();
        $data = array();
        foreach ($query as $val) {
          $data[] = array('ItemID' => $val->ItemID,
                        'Description' => $val->Description,
                        'DescriptionInKhmer' => $val->DescriptionInKhmer,
                        'UnitPrice' => number_format($val->Price,2),
                        'CategoryID' => $val->CategoryID,
                        'Picture' => base64_encode($val->Picture),
                        'OrderDetailID' => $val->OrderDetailID,
                        'AssociatedItem' => $val->AssociatedItem,
                        'Qty' => $val->Qty,
                        'OrderRemark' => $val->OrderRemark,
                        'Course' => $val->Course,

                      );
        }
        return $data;
    }
    function getAllCustomerOrderByTableNo($TableNo,$OrderNo,$Status)
    {
      $where = '';
      if($OrderNo)
      {
        $where .= " AND od.OrderNo = '$OrderNo' ";
      }else{
        $where .= "";
      }
      $query = $this->app_db->query("SELECT
                                      i.ItemID,
                                      i.Description,
                                      i.DescriptionInKhmer,
                                      i.Picture,
                                      i.UnitPrice,
                                      o.OrderNo,
                                      o.[Table],
                                      od.OrderNo,
                                      od.OrderDetailID,
                                      od.Status,
                                      od.Qty,
                                      od.Remark,
                                      od.OrderRemark,
                                      od.Course
                                    FROM
                                      tblOrder AS o
                                    INNER JOIN tblOrderDetails AS od ON o.OrderNo = od.OrderNo
                                    INNER JOIN tblItem AS i ON od.ItemID = i.ItemID
                                    WHERE
                                      o.[Table] = '$TableNo' {$where}
                                    AND od.Status = '$Status' ORDER BY od.Course DESC ")->result();
      $data = array();
      foreach ($query as $val) {
        $data[] = array('ItemID' => $val->ItemID,
                      'Description' => $val->Description,
                      'DescriptionInKhmer' => $val->DescriptionInKhmer,
                      'UnitPrice' => $val->UnitPrice,
                      'Picture' => $val->Picture,
                      'OrderDetailID' => $val->OrderDetailID,
                      'Qty' => $val->Qty,
                      'Remark' => $val->Remark,
                      'OrderRemark' => $val->OrderRemark,
                      'Course' => $val->Course,
                    );
      }
      return $data;

    }
    function DeleteItemformCaptainCheckOrderByID($OrderDetailID)
    {
      $query = $this->app_db->query("DELETE FROM tblOrderDetails WHERE OrderDetailID = '$OrderDetailID' ");
      if($query)
      {
        return 1;
      }else{
        return 0;
      }
    }
    function confirmOrderCustomerCaptain($Remark, $Price, $Qty, $UserName, $Status, $Time, $OrderNo, $OrderDetailID, $OrderRemark,$CourseFood)
    {
        $where = "";
        if($OrderNo !="")
        {
          $where .= " AND OrderNo = '$OrderNo' ";
        }
        $query = $this->app_db->query("UPDATE tblOrderDetails SET Qty = '$Qty', Price = '$Price', Remark = '$Remark', Status = '$Status', Time = '$Time', UserName = '$UserName', OrderRemark = '$OrderRemark', Course = '$CourseFood' WHERE OrderDetailID = '$OrderDetailID' {$where} ");
        if($query)
          return 1;
        else
          return 0;
    }
    function gerRemarkByID($OrderDetailID)
    {
      $query = $this->app_db->query("SELECT * FROM tblOrderDetails WHERE OrderDetailID = '$OrderDetailID' ")->row();
      return $query->Remark;
    }
    function deleteAllItemCaptainByID($OrderNo, $OrderDetailID)
    {
      $where = "";
      if($OrderNo !="")
      {
        $where .= " AND OrderNo = '$OrderNo' ";
      }
      $query = $this->app_db->query("DELETE FROM tblOrderDetails WHERE OrderDetailID = '$OrderDetailID' {$where} ");
      if($query)
        return 1;
      else
        return 0;
    }
    function getTimerbyTableNo($TableNo)
    {
      $query = $this->app_db->query("SELECT TOP 1
                                  o.OrderNo,
                                  o.[Table],
                                  od.OrderNo,
                                  od.[Time]
                                FROM
                                  tblOrder AS o
                                INNER JOIN tblOrderDetails AS od ON o.OrderNo = od.OrderNo
                                WHERE o.[Table] = 'A01' ORDER BY od.[Time] DESC ")->result();
      return $query;
    }
    function getLastOrderID($tbl)
    {
      $query = $this->app_db->query("SELECT top 1 * FROM tblOrder where [Table] = '$tbl' order by OrderNo DESC")->row();
      return $query->OrderNo;
    }
    function saveqtybyDetailID($OrderDetailID, $Qty, $Course)
    {
      $this->app_db->set('Qty',$Qty);
      if($Course > 0)
      {
        $this->app_db->set('Course',$Course);
      }
      $this->app_db->where('OrderDetailID',$OrderDetailID);
      $this->app_db->update('tblOrderDetails');
      return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function saveoptbyDetailID($OrderDetailID, $Opt)
    {
      $this->app_db->set('Remark',$Opt);
      $this->app_db->where('OrderDetailID',$OrderDetailID);
      $this->app_db->update('tblOrderDetails');
      return ($this->app_db->affected_rows() > 0) ? "Success" : "False";
    }
    function confirmOrderByID($OrderDetailID, $Status)
    {
      $this->app_db->set('Status',$Status);
      $this->app_db->where('OrderDetailID',$OrderDetailID);
      $this->app_db->update('tblOrderDetails');
      return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function deleteOrderByStatusOpen($OrderDetailID,$Status)
    {
      $this->app_db->where('OrderDetailID',$OrderDetailID);
      $this->app_db->where('Status',$Status);
      $this->app_db->delete('tblOrderDetails');

      $this->deleteSub($OrderDetailID);
      return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function getAllListItem()
    {
      $query = $this->app_db->query("SELECT i.*, c.CategoryID, c.ModifyingPersonID FROM tblItem as i INNER JOIN tblCategory as c ON i.CategoryID = c.CategoryID ")->result();

        $data = array();
        foreach ($query as $val) {
          $name = $val->Description;
          $data[] = array('itemid'  => $val->ItemID,
                        'description' => $name,
                        'price' => number_format($val->UnitPrice,2),
                        'categoryid' => $val->CategoryID,
                        'picture' => base64_encode($val->Picture),
                        'pictures' => site_url('img/beef.jpg'),
                        'descriptionkh' => $val->DescriptionInKhmer,
                        'modifyID' => $val->ModifyingPersonID
                      );
        }
        return $data;
    }
    function getCountCurrentOrderbyID($orderNo,$tableNo)
    {
      $query = $this->app_db->query("SELECT * FROM
                                        tblOrderDetails AS od
                                      WHERE
                                        od.OrderNo = '$orderNo' AND od.Status = 'Open' ")->result();
      if($query)
      {
        $i = 1;
        $data;
        foreach ($query as $val) {
          $data = $i++;
        }
        return $data;
      }else{
        return 0;
      }
    }
    function deleteItemByID($OrderDetailID)
    {
      $query  = $this->app_db->query("DELETE FROM tblOrderDetails WHERE OrderDetailID = '$OrderDetailID' ");
      $this->deleteSub($OrderDetailID);
      if($query)
      {
        return "Success";
      }else{
        return "False";
      }
    }
    function deleteSub($OrderDetailID){
      $query  = $this->app_db->query("DELETE FROM tblOrderDetails WHERE AssociatedItem = '$OrderDetailID' ");
    }
    function getListforExtraFood($tableNo,$orderNo)
    {
      $query = $this->app_db->query("SELECT
                                    o.OrderNo,
                                    o.[Table],
                                    od.OrderDetailID,
                                    od.AssociatedItem,
                                    od.OrderNo,
                                    od.ItemName,
                                    od.ItemNameKhmer,
                                    od.ItemID,
                                    od.Price,
                                    od.Status,
                                    i.ItemID,
                                    i.Picture
                                  FROM
                                    tblOrderDetails AS od
                                  INNER JOIN tblOrder AS o ON od.OrderNo = o.OrderNo
                                  INNER JOIN tblItem as i On i.ItemID = od.ItemID
                                  WHERE
                                    od.Status = 'Open'
                                  AND o.[Table] = '$tableNo' 
                                  AND od.OrderNo = '$orderNo' 
                                  AND od.AssociatedItem IS NULL 
                                  ORDER BY od.OrderDetailID DESC
                                   ")->result();

      $data = array();
      foreach ($query as $row) {
        $data[] = array(
          'id' => $row->OrderDetailID,
          'name' => $row->ItemName,
          'namekh' => $row->ItemNameKhmer,
          'image' => base64_encode($row->Picture),
          'price' => $row->Price
        );
      }

      return $data;

    }
    function saveExtraFood($data)
    {
      $query = $this->app_db->insert('tblOrderDetails', $data);
      if($query)
      {
        return "Success";
      }else{
        return "False";
      }
    }
    function getsubItemextra($detailID,$OrderNo)
    {
      $query = $this->app_db->query("SELECT
                                        i.ItemID,
                                        i.Picture,
                                        i.Description,
                                        i.DescriptionInKhmer,
                                        i.CategoryID,
                                        od.ItemID,
                                        od.OrderDetailID,
                                        od.Price,
                                        od.OrderNo,
                                        od.AssociatedItem,
                                        od.Status,
                                        od.Qty,
                                        od.Remark,
                                        od.OrderRemark,
                                        od.Course,
                                        (select count(*) from tblOrderDetails where AssociatedItem = od.OrderDetailID) as countAss
                                      FROM
                                        tblItem AS i
                                      INNER JOIN tblOrderDetails AS od ON i.ItemID = od.ItemID
                                      WHERE
                                        od.OrderNo = '$OrderNo' AND od.Status = 'Open' 
                                        AND od.AssociatedItem = '$detailID'
                                      ORDER BY od.OrderDetailID DESC ")->result();
        $UnitPrice = 0;
        $float = 0;
        $data = array();
        foreach ($query as $val) {
          $UnitPrice = number_format($val->Price,2);
          $float = floatval($UnitPrice);
          $data[] = array('Index' => $val->OrderDetailID,
                        'ItemID'  => $val->ItemID,
                        'Description'   => $val->Description,
                        'DescriptionInKhmer'    => $val->DescriptionInKhmer,
                        'UnitPrice' => floatval($val->Price),
                        'Price' => floatval($val->Price * $val->Qty),
                        'CategoryID' => $val->CategoryID,
                        'OrderDetailID' => $val->OrderDetailID,
                        'AssociatedItem' => $val->AssociatedItem,
                        'Qty' => $val->Qty,
                        'Remark' => $val->Remark,
                        'OrderRemark' => $val->OrderRemark,
                        'Image' => base64_encode($val->Picture),
                        'Course' => $val->Course, 
                        'countAss' => $val->countAss
                      );
        }
        return $data;
    }
    function getTextNotification()
    {
      $sql = $this->app_db->query("SELECT * FROM tblNotificationText")->result();
      return $sql;
    }
    function savePlayerID($player)
    {
      $this->app_db->set('playerID', $player);
      $this->app_db->update('tblNotificationText');
      return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE; 
    }
    function saveTextNotification($text,$ord,$tbl)
    {
      $date = Date('Y-m-d H:i:s');
      $data = array(
        'notificationName' => $text,
        'status' => 1,
        'tableNo' => $tbl,
        'orderNo' => $ord,
        'Time' => $date
      );
      $this->app_db->insert('tblNotification',$data);
      return ($this->app_db->affected_rows() > 0) ? TRUE : FALSE; 
    }
    function countNotification()
    {
      $sql = $this->app_db->query("SELECT count(*) as countNotification FROM tblNotification where status = 1 ")->row();
      return $sql->countNotification;
    }
    function getlistNotification()
    {
      $sql = $this->app_db->query("SELECT * FROM tblNotification where status = 1 ORDER BY notificationID DESC")->result();
      return $sql;
    }
    function disableNotification($id)
    {
      // $date = Date('Y-m-d H:i:s');
      // $this->app_db->set('status', 0);
      // $this->app_db->set('Time',$date);
      // $this->app_db->where('notificationID',$id);
      // $this->app_db->update('tblNotification');
      // return ($this->app_db->affected_rows() > 0) ? 1 : 0; 

      $sql = $this->app_db->query("UPDATE tblNotification SET status = 0 WHERE notificationID = '$id' ");
      if($sql)
        return 1;
      else
        return 0;
    }
    function getHistoryNotification()
    {
      $sql = $this->app_db->query("SELECT TOP 100 * FROM tblNotification where status = 0 ORDER BY notificationID DESC")->result();
      return $sql;
    }
    function getFoodRemark()
    {
      $data = array();
      $sql = $this->app_db->query("SELECT * FROM tblMenuRemark")->result();
      foreach ($sql as $row) {
        $data[] = $row->Description;
      }
      return $data;
    }
    function getRemarkAfterSave($id)
    {
      $sql = $this->app_db->query("SELECT Remark FROM tblOrderDetails WHERE OrderDetailID = '$id' ")->row();

      $remark = trim($sql->Remark, ',');
      $arr = explode(',', $sql->Remark);
      return $arr;
    }
    function CountItem()
    {
      $query = $this->app_db->query("SELECT count(*) as ItemCount FROM tblItem")->row();
      return $query->ItemCount;
    }
    function Allitems($offset,$page)
    {
      $where = "";
      if($offset == 1)
      {
        $where.= "WHERE ItemID BETWEEN ".$offset." AND ".$page;
      }else{
        $where.= "WHERE ItemID BETWEEN ".(($page * ($offset -1)) + 1)." AND ".$page * $offset;
      }

      $query = $this->app_db->query("SELECT i.*, c.CategoryID, c.ModifyingPersonID FROM tblItem as i INNER JOIN tblCategory as c ON i.CategoryID = c.CategoryID {$where} ");

      if ($query->num_rows() > 0) {
          $data = array();
          foreach ($query->result() as $val) {
            $data[] = array(
                            'vote_count' => $val->ItemID,
                            'id' => $val->ItemID,
                            'video' => false,
                            'vote_average' => 6.9,
                            'title' => $val->Description,
                            'titlekh' => $val->DescriptionInKhmer,
                            'popularity' => 497.334,
                            'poster_path' => base64_encode($val->Picture),
                            'original_language' => 'en',
                            'original_title' => 'Aquaman',
                            'backdrop_path' => site_url('img/beef.jpg'),
                            'adult' => false,
                            'overview' => 'Arthur Curry learns that he is the heir to the underwater kingdom of Atlantis, and must step forward to lead his people and be a hero to the world.',
                            'release_date' => '2018-12-07',
                            'modifyid' => $val->ModifyingPersonID,
                            'price' => number_format($val->UnitPrice,2)
                        );
          }
          return $data;
      }
      
      return false;
    }
    function Category()
    {
        $query = $this->app_db->query("SELECT * FROM tblCategory WHERE MenuCategory != 'false' ")->result();
        $data = array();
        foreach ($query as $cat) {
          $data[] = array('id' => $cat->CategoryID,
                          'name' => $cat->CategoryName,
                          'namekh' => $cat->CategoryNameInKhmer,
                          'menu' => $cat->MenuCategory,
                          'modifyid' => $cat->ModifyingPersonID,
                          'room' => $cat->RoomService
                          );
        }
        return $data;
    }
}

