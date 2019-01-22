<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	class Green{
		
		public $roleid;
		public $roleinfor;
		public $moduleids;
		public $moduleinfos;
		public $pageids;
		public $pageinfos;		
		
		public $active_role;
		public $active_module;
		public $active_page;
		public $active_user;
		public function runSQL($sql){
			$ci=& get_instance();               
	        $query = $ci->db->query($sql);
			return $query ;
		}
		public function getTable($sql){
			$arrDatas=array();		         
	       	$query = $this->runSQL($sql);
			foreach ($query->result_array() as $row)		
			{
				$arrDatas[]=$row;			   
			}		
			return $arrDatas;
		}
		public function getOneRow($sql){
			$row = $this->runSQL($sql)->row_array();		
			return $row;
		}
		public function getValue($sql){		
			$row = $this->runSQL($sql)->row_array();
			$num_arr = array_values($row);			
			return isset($num_arr[0])?$num_arr[0]:"";				
		}		
		public function getTotalRow($sql){		
	        $row = $this->runSQL($sql)->num_rows();
			return $row;
		}
		public function getFieldName($sql){		
			$query = $this->runSQL($sql)->list_fields();
			return $query;
		}	
		public function create_temp($sql){
			return $this->runSQL($sql);
		}
		public function drop_temp($tem_name){
			$ci=& get_instance();               
	        $query = $this->runSQL("DROP TEMPORARY TABLE IF EXISTS $tem_name");		
		}
			
		public function gictEnc($str){
			$ci=& get_instance(); 
			$key=$ci->config->item('encryption_key');		
			return $ci->encrypt->encode($str,$key);
		}
		public function gictDec($str){
			$ci=& get_instance(); 
			$key=$ci->config->item('encryption_key');
			return $ci->encrypt->decode($str,$key);
		}
		public function goToPage($page){
			  redirect($page, 'refresh');
		}
		public function clearSession(){
			$ci=& get_instance();
			foreach (array_keys($ci->session->userdata) as $key)
			{
			  $ci->session->unset_userdata($key);
			}
		}		

		public function exportToXls($data,$fileName="Sheet"){			
			header('ETag: etagforie7download'); //IE7 requires this header
			header('Content-type: application/octet_stream');
			header('Content-disposition: attachment;filename="'.$fileName.' '.date('Y-m-d').'.xls');
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';	
			echo $data;
			die();	
		}
		
		
		public function myUpload($file,$new_name,$is_thumb=0){
			$ci=& get_instance();
			//================================ upload image ============================
			$path =$ci->config->item('image_upload_path')."/Promotions";	
			$sqlcheck = "SELECT * FROM folder where foldername='Promotions' and folderpath='".$path."'";
			$folder_check=$ci->db->query($sqlcheck);
			if($folder_check->num_rows()<=0){
				$sqlinsert = "INSERT INTO folder SET  foldername='Promotions',folderpath='".$path."'";
				$ci->db->query($sqlinsert);	
				mkdir($path,0777); 						
			}
			//============= Checking File To Upload ==============================
			if (isset($_FILES[$file]) AND $_FILES[$file]['name'] != '') {
				$AllowType=array("image/gif","image/jpeg","image/pjpeg","image/png","image/bmp");
				$result = $_FILES[$file]['error'];
				$UploadTheFile = 'Yes'; 
				$filename = $path . '/' .$new_name ;
			   if($_FILES[$file]['size'] > $ci->config->item('max_size_upload') * 1024) {       
					$UploadTheFile = 'No';
					echo 'size';
				}elseif(!(in_array($_FILES[$file]['type'],$AllowType))){
					$UploadTheFile = 'No';
					echo 'Type';
				}
				elseif (file_exists($filename)) {       
					$result = unlink($filename);
					if (!$result) {           
						$UploadTheFile = 'No';
					}
				}
				if ($UploadTheFile == 'Yes') {
					$result = move_uploaded_file($_FILES[$file]['tmp_name'], $filename);     
				}
			}
			if($is_thumb==1){
				createThumbImg($new_name);
			}
				
		}
		public function createThumbImg($source_image,$height=70,$width=60){
			$ci=& get_instance();
			#============= Create Thumb Image ============
			$path =$ci->config->item('image_upload_path')."/Promotions";
			$config['image_library'] = 'gd2';
			$config['source_image']	= $path.'/'.$source_image;
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['width']	 = $width;
			$config['height']	= $height;
			
			#$config['new_image'] = $path.'/'.$filename;
			
			$ci->load->library('image_lib', $config);
			$ci->image_lib->initialize($config); 
			$ci->image_lib->resize();
			
			if ( ! $ci->image_lib->resize())
			{
				echo $ci->image_lib->display_errors();
			}
		}
		public function getCombo($source="",$key="",$display="",$sql=""){
			#==== $source=tablename,$key=idfield,display=field for show 
			$result=array();
			if($sql==""){
				$data=$this->getTable("SELECT `{$key}`,`{$display}` FROM `{$source}` ORDER BY `{$display}`");
			}else{
				$data=$this->getTable($sql);
			}
			if(count($data)>0){
				foreach($data as $d){
					$result[$d[0]]=$d[1];
				}
			}
			return $result;
		}		
		

		public function getModule($roleid){
			
			$this->roleinfor=$this->getOneRow("SELECT
													z_role.roleid,
													z_role.role,
													z_role.is_admin
												FROM
													z_role
												WHERE
													is_active = 1
												AND roleid = '".$roleid."'
											");
			
			$where=" WHERE roleid = '".$roleid."'";
			if($roleid==1){
				$where="";
			}
			$arrModules=$this->getTable("SELECT
											DISTINCT
											rolmod.moduleid
										FROM
											z_role_module_detail as rolmod
										{$where}
										ORDER BY rolmod.`order`
										");
			$this->moduleids=$arrModules;
			$this->roleid=$this->roleinfor['roleid'];						
					
		}
		public function getModuleInfo($moduleid){			
			
			$where=" AND moduleid = '".$moduleid."'";			
				
			$arrModule=$this->getOneRow("SELECT
											z_module.moduleid,
											z_module.module_name,
											z_module.sort_mod,
											z_module.mod_position
											FROM
											z_module											
											WHERE
												is_active = 1
											{$where}
											ORDER BY `order`;
										");
			return $arrModule;
		}
				
		public function getRolePage($moduleid){
			$where="";				
			if($this->roleid==1){
				$where.=" AND moduleid = '".$moduleid."'";					
				
				$arrPages=$this->getTable("SELECT
											page.pageid,
											page.page_name,
											page.link,
											page.moduleid,
											page.`order`,
											page.is_insert,
											page.is_update,
											page.is_delete,
											page.is_show as is_read,
											page.is_print,
											page.is_export,
											page.created_by,
											page.created_date
										FROM
											z_page AS page
										WHERE
											is_active = 1											
										{$where}										
										ORDER BY moduleid,`order`																				
									");				
			}else{
				$where.=" AND roleid = '".$this->roleid."'";							
				$where.=" AND moduleid = '".$moduleid."'";	
					
				$arrPages=$this->getTable("SELECT
											rolepage.pageid,
											rolepage.moduleid,
											rolepage.roleid,
											rolepage.is_read,
											rolepage.is_insert,
											rolepage.is_delete,
											rolepage.is_update,
											rolepage.is_print,
											rolepage.is_export,
											rolepage.is_import
										FROM
											z_role_page as rolepage							
										WHERE
											1 = 1
										{$where}										
									");	
			}			
								
			$this->pageids=$arrPages;
			
		}
		public function getPageInfo($pageid){
			$where=" AND pageid = '".$pageid."'";					
			$arrPages=$this->getOneRow("SELECT
											page.pageid,
											page.page_name,
											page.link,
											page.moduleid,
											page.`order`,
											page.is_insert,
											page.is_update,
											page.is_delete,
											page.is_show,
											page.is_print,
											page.is_export,
											page.created_by,
											page.created_date,
											page.icon
										FROM
											z_page AS page
										WHERE
											is_active = 1											
										{$where}
										ORDER BY moduleid,`order`										
									");
			$this->pageinfos=$arrPages;
		}

		function setActiveRole($role){
			$this->active_role=$role;
		}
		function getActiveRole(){
			return $this->active_role;
		}
		function setActiveModule($module){
			$this->active_module=$module;
		}
		function getActiveModule(){
			return $this->active_module;
		}
		function setActivePage($page){
			$this->active_page=$page;
		}
		function getActivePage(){
			return $this->active_page;
		}
		function setActiveUser($user){
			$this->active_user=$user;
		}
		function getActiveUser(){
			return $this->active_user;
		}

		public function gAction($action){			
			$arrAct=array('C'=>'is_insert',
							'D'=>'is_delete',
							'U'=>'is_update',
							'R'=>'is_read',
							'E'=>'is_export',	
							'P'=>'is_print',
							'I'=>'is_import'
						);

			$sqlAction="SELECT ".$arrAct[$action]."					
							FROM z_role_page 
							WHERE roleid='".$this->getActiveRole()."' 
							AND moduleid='".$this->getActiveModule()."' 
							AND pageid='".$this->getActivePage()."'";
					
			$res=$this->getValue($sqlAction)-0;
			if($res==1 || $this->getActiveRole()==1){
				return true;
			}else{
				return false;
			}
		}

		public function displayDate($date){
			$date=date_create($date);
			return date_format($date,"d-m-Y");
		}

		
		function getStatusOp($isblank,$selected,$isYesNo){
			$opStat="";
			if($isblank==1){
				//$opStat.="<option value=''></option>";
			}

			if($isYesNo==1){
				$active="Yes";
				$inactive="No";
			}else{
				$active="Active";
				$inactive="Inactive";
			}

			$opStat.="<option value='1' ".($selected==1?'selected="selected"':'').">".$active."</option>";
			$opStat.="<option value='0' ".($selected==0?'selected=selected':'').">".$inactive."</option>";
			echo $opStat;
		}

		function gOption($arr,$selected){
			if(count($arr)>0){
				$op="";
				foreach ($arr as $key => $value) {
					$op.="<option value='".$key."' ".($key==$selected?"selected":"").">$value</option>";
				}
			}
			return $op;
		}

		function formatSQLDate($date){
			if($date!=""){
				if(strpos($date,"-")!== false){
					$datepart=explode("-", $date);
				}else if(strpos($date,".")!== false){
					$datepart=explode(".", $date);
				}
				else{
					$datepart=explode("/", $date);
				}
				return $datepart[2].'-'.$datepart[1].'-'.$datepart[0];
			}else{
				return "";
			}
		}

		function convertSQLDate($date){
			if($date!=""){
				$datepart=explode("-", $date);
				return $datepart[2].'-'.$datepart[1].'-'.$datepart[0];
			}
			
		}

		function nextTran($typeid,$type){
			$last_seq=$this->getValue("SELECT sequence FROM z_systype WHERE typeid='".$typeid."'");
			if($last_seq==""){
				$this->runSQL("INSERT INTO z_systype SET sequence=1,type='".$type."',typeid='".$typeid."'");
				$last_seq=1;				
			}
			$this->runSQL("UPDATE z_systype SET sequence=sequence+1 WHERE typeid='".$typeid."'");		
		
			return $last_seq;
		}
		
	function ajax_pagination($total_row, $url, $limit=5){
		//$limit=10; //********** Number for select from DB **********		
		$start=0; //********** Number for start select from DB **********
		$adjacents = 2;
		
		if (isset($_POST["page"])) {
			$page = $_POST["page"];
		}
		else{
			$page=1;
		}
		
		if($page!=0) 
			$start = ($page - 1) * $limit;
		else
			$start = 0;			
		
		$total_pages = $total_row;
		
		if ($page == 0) $page = 1;
		$prev = $page - 1;
		$next = $page + 1;
		$lastpage = ceil($total_pages/$limit);
		$lpm1 = $lastpage - 1;
		
		//=========================================================================			
		$pagination ="";
		$pagination .= "<div class='dataTables_paginate'>";
			if($lastpage > 1)
			{	
				if ($page > 1) 
					$pagination.= "<a class=\"pagenav fg-button ui-button ui-state-default\" id='1' href='javascript:void(0)' id=1><span class='fa fa-fast-backward'></span></a>
									<a class=\"pagenav fg-button ui-button ui-state-default\" href='javascript:void(0)' id='$prev'><span class='fa fa-backward'></span></a>";
				else
					$pagination.= "<span class='fa fa-fast-backward fg-button ui-button ui-state-default'></span>
									<span class='fa fa-backward fg-button ui-button ui-state-default'></span>";
				if ($lastpage < 2 + ($adjacents * 2))
				{	
					for ($counter = 1; $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<a class=\"fg-button ui-button ui-state-default ui-state-disabled \">$counter</a>";
						else
							$pagination.= "<a class=\"pagenav fg-button ui-button ui-state-default\" href='javascript:void(0)' id='$counter'>$counter</a>";					
					}
				}
				elseif($lastpage >= ($adjacents * 2))	//enough pages to hide some
				{
					//close to beginning; only hide later pages
					
					if($page <= 2 + ($adjacents) && $page >= $lastpage-$adjacents-1)		
					{
					
						for ($counter = 1; $counter < $page - 1 + ($adjacents * 2); $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"pagenav fg-button ui-button ui-state-default ui-state-disabled\">$counter</span>";
							else
								$pagination.= "<a class=\"pagenav fg-button ui-button ui-state-default\" href='javascript:void(0)' id='$counter'>$counter</a>";					
						}
						$pagination.= "<a class=\"pagenav fg-button ui-button ui-state-default\" href='javascript:void(0)' id='$lastpage'>$lastpage</a>";		
					}
					//in middle; hide some front and some back
					elseif($page <= 2 + ($adjacents) && $page < $lastpage-$adjacents-1)
					{
						if ($page<4){$page_=3;}
						else{$page_=$page;}
						for ($counter = 1; $counter <=$page_ + ($adjacents); $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"pagenav fg-button ui-button ui-state-default ui-state-disabled\">$counter</span>";
							else
								$pagination.= "<a class=\"pagenav fg-button ui-button ui-state-default\" href='javascript:void(0)' id='$counter'>$counter</a>";					
						}
						$pagination.= "<a href='javascript:void(0)'>...</a>";
						$pagination.= "<a class=\"pagenav fg-button ui-button ui-state-default\" href='javascript:void(0)' id='$lastpage'>$lastpage</a>";		
					}
					elseif($page > 2 + ($adjacents) && $page >= $lastpage-$adjacents-1)		
					{
						$pagination.="<a class=\"pagenav fg-button ui-button ui-state-default\" href='javascript:void(0)' id=1>1</a>";
						$pagination.= "<a href='javascript:void(0)'>...</a>";
						if($page>$lastpage-3){$page_=$lastpage-4;}
						else{$page_=$page-2;}
						for ($counter = $page_; $counter <= $lastpage; $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"pagenav fg-button ui-button ui-state-default ui-state-disabled\">$counter</span>";
							else
								$pagination.= "<a class=\"pagenav fg-button ui-button ui-state-default\" href='javascript:void(0)' id='$counter'>$counter</a>";					
						}		
					}
					elseif($page > 2 + ($adjacents) && $page < $lastpage-$adjacents-1)		
					{
						$pagination.="<a class=\"pagenav fg-button ui-button ui-state-default\" href='javascript:void(0)' id=1>1</a>";
						$pagination.= "<a href='javascript:void(0)'>...</a>";
						for ($counter = $page-2; $counter < $page - 1 + ($adjacents * 2); $counter++)
						{
							if ($counter == $page)
								$pagination.= "<span class=\"pagenav fg-button ui-button ui-state-default ui-state-disabled\">$counter</span>";
							else
								$pagination.= "<a class=\"pagenav fg-button ui-button ui-state-default\" href='javascript:void(0)' id='$counter'>$counter</a>";					
						}
						$pagination.= "<a href='javascript:void(0)'>...</a>";
						$pagination.= "<a class=\"pagenav fg-button ui-button ui-state-default\" href='javascript:void(0)' id='$lastpage'>$lastpage</a>";		
					}
					//close to end; only hide early pages
				}
				if ($page < $counter - 1) 
					$pagination.= "<a class=\"pagenav fg-button ui-button ui-state-default\" href='javascript:void(0)' id='$next'><span class='fa fa-forward'></span></a>
									<a class=\"pagenav fg-button ui-button ui-state-default\" href='javascript:void(0)' id='$lastpage'><span class='fa fa-fast-forward'></span></a>";
				else
					$pagination.="<span class='fa fa-forward fg-button ui-button ui-state-default'></span>
									<span class='fa fa-fast-forward fg-button ui-button ui-state-default'></span>";
		
			}
			else
			$pagination.="&nbsp;";
			$pagination.="</div>";			
			$arr_pag= array();
			$arr_pag['start']=$start;
			$arr_pag['limit']=$limit;
			$arr_pag['pagination']=$pagination;				
			return $arr_pag;
		}

		function p_ajax_pagination($total_row, $url, $limit=5){
		//$limit=10; //********** Number for select from DB **********		
		$start=0; //********** Number for start select from DB **********
		$adjacents = 2;
		
		if (isset($_POST["page"])) {
			$page = $_POST["page"];
		}
		else{
			$page=1;
		}
		
		if($page!=0) 
			$start = ($page - 1) * $limit;
		else
			$start = 0;			
		
		$total_pages = $total_row;
		
		if ($page == 0) $page = 1;
		$prev = $page - 1;
		$next = $page + 1;
		$lastpage = ceil($total_pages/$limit);
		$lpm1 = $lastpage - 1;
		
		//=========================================================================			
		$pagination_pre ="";
		$pagination_next ="";
		// $pagination .= "<div class='dataTables_paginate'>";
			if($lastpage > 1)
			{	
				if ($page > 1) 
					$pagination_pre.= "<a class=\"pagenavpre \" href='javascript:void(0)' id='$prev'><span class='btnprev'><img src='".site_url('/assets/images/prev.png')."'/></span></a>";
				// else
				// $pagination.= "<span class='hide fa fa-chevron-right fg-button ui-button ui-state-default'></span>";
				
				if ($page < $lastpage) 
					$pagination_next.= "<a class=\"pagenavnext\" href='javascript:void(0)' id='$next'><span class='btnprev'><img src='".site_url('/assets/images/next.png')."'/></span></a>";
				// else
				// 	$pagination.="<span class='fg-button ui-button ui-state-default fa fa-chevron-right'></span>";
		
			}
			else
			// $pagination.="&nbsp;";
			// $pagination.="</div>";			
			$arr_pag= array();
			$arr_pag['start']=$start;
			$arr_pag['limit']=$limit;
			$arr_pag['pagination_prev']=$pagination_pre;
			$arr_pag['pagination_next']=$pagination_next;				
			return $arr_pag;
		}
				
				#=============== sub category stucture ==========
		function lineage($field_name,$field_value,$parent_field,$parent_value,$table){
			$lineage="";
			if($field_value==$parent_value){
				return $field_value;
			}
			$lineage=$field_value;
			$row=$this->getOneRow("SELECT {$field_name},
												$parent_field 
											FROM {$table} 
											WHERE $field_name ='".$parent_value."'");			
			if(isset($row[$field_name]) AND $row[$field_name]!=""){							
				$lineage=$this->lineage($field_name,$row[$field_name],$parent_field,$row[$parent_field],$table)."-".$field_value;
			}
			return $lineage;
		}	
		function lineage_level($field_name,$field_value,$parent_field,$parent_value,$table){
			$lineage_level = array("lineage" => $field_value,"level" => 0);
			$lineage_level['lineage'] = $this->lineage($field_name,$field_value,$parent_field,$parent_value,$table);
			$arr_level = explode('-',$lineage_level['lineage']);
			$lineage_level['level'] = count($arr_level) - 1 ;
			return $lineage_level;
		}
		function updateCateLineAge($categoryid=""){
			$w=$categoryid==""?"":" AND menu_id='".$categoryid."'";
			$data=$this->getTable("SELECT menu_id,parentid FROM tblmenus WHERE 1=1 {$w}");
			if(count($data)>0){
				foreach ($data as $rows) {
					$arr_lineage=$this->lineage_level('menu_id',$rows['menu_id'],'parentid',$rows['parentid'],'tblmenus');
					$this->runSQL("UPDATE tblmenus 
											SET lineage='".$arr_lineage['lineage']."',
												level='".$arr_lineage['level']."'
											WHERE menu_id='".$rows['menu_id']."'
										");
				}
			}
		}
		function getuserip() {
		    $headers = array ('HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'HTTP_VIA', 'HTTP_X_COMING_FROM', 'HTTP_COMING_FROM', 'HTTP_CLIENT_IP' );
		 
		    foreach ( $headers as $header ) {
		        if (isset ( $_SERVER [$header]  )) {
		        
		            if (($pos = strpos ( $_SERVER [$header], ',' )) != false) {
		                $ip = substr ( $_SERVER [$header], 0, $pos );
		            } else {
		                $ip = $_SERVER [$header];
		            }
		            $ipnum = ip2long ( $ip );
		            if ($ipnum !== - 1 && $ipnum !== false && (long2ip ( $ipnum ) === $ip)) {
		                if (($ipnum - 184549375) && // Not in 10.0.0.0/8
		                ($ipnum  - 1407188993) && // Not in 172.16.0.0/12
		                ($ipnum  - 1062666241)) // Not in 192.168.0.0/16
		                if (($pos = strpos ( $_SERVER [$header], ',' )) != false) {
		                    $ip = substr ( $_SERVER [$header], 0, $pos );
		                } else {
		                    $ip = $_SERVER [$header];
		                }
		                return $ip;
		            }
		        }
		        
		    }
		    return $_SERVER ['REMOTE_ADDR'];
		}
		function savelastlogin($userid){
			$date=date('Y-m-d H:i:s');
			$ip=$this->getuserip();
			$this->runSQL("UPDATE admin_user SET last_visit='".$date."', last_visit_ip='".$ip."'");
		}
		#===================== Create sub location ==========================
		function lineages($field_name,$field_value,$parent_field,$parent_value,$table){
			$lineages="";
			if($field_value==$parent_value){
				return $field_value;
			}
			$lineages=$field_value;
			$row=$this->getOneRow("SELECT {$field_name},
												$parent_field 
											FROM {$table} 
											WHERE $field_name ='".$parent_value."'");			
			if(isset($row[$field_name]) AND $row[$field_name]!=""){							
				$lineages=$this->lineages($field_name,$row[$field_name],$parent_field,$row[$parent_field],$table)."-".$field_value;
			}
			return $lineages;
		}	
		function lineage_levels($field_name,$field_value,$parent_field,$parent_value,$table){
			$lineage_levels = array("lineage" => $field_value,"level" => 0);
			$lineage_levels['lineage'] = $this->lineages($field_name,$field_value,$parent_field,$parent_value,$table);
			$arr_level = explode('-',$lineage_levels['lineage']);
			$lineage_levels['level'] = count($arr_level) - 1 ;
			return $lineage_levels;
		}
		function updateCateLineAges($categoryid=""){
			$w=$categoryid==""?"":" AND propertylocationid='".$categoryid."'";
			$data=$this->getTable("SELECT propertylocationid,parent_id FROM tblpropertylocation WHERE 1=1 {$w}");
			if(count($data)>0){
				foreach ($data as $rows) {
					$arr_lineage=$this->lineage_levels('propertylocationid',$rows['propertylocationid'],'parent_id',$rows['parent_id'],'tblpropertylocation');
					$this->runSQL("UPDATE tblpropertylocation 
											SET lineage='".$arr_lineage['lineage']."',
												level='".$arr_lineage['level']."'
											WHERE propertylocationid='".$rows['propertylocationid']."' ");
				}
			}
		}
		function getuserips() {
		    $headers = array ('HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'HTTP_VIA', 'HTTP_X_COMING_FROM', 'HTTP_COMING_FROM', 'HTTP_CLIENT_IP' );
		 
		    foreach ( $headers as $header ) {
		        if (isset ( $_SERVER [$header]  )) {
		        
		            if (($pos = strpos ( $_SERVER [$header], ',' )) != false) {
		                $ip = substr ( $_SERVER [$header], 0, $pos );
		            } else {
		                $ip = $_SERVER [$header];
		            }
		            $ipnum = ip2long ( $ip );
		            if ($ipnum !== - 1 && $ipnum !== false && (long2ip ( $ipnum ) === $ip)) {
		                if (($ipnum - 184549375) && // Not in 10.0.0.0/8
		                ($ipnum  - 1407188993) && // Not in 172.16.0.0/12
		                ($ipnum  - 1062666241)) // Not in 192.168.0.0/16
		                if (($pos = strpos ( $_SERVER [$header], ',' )) != false) {
		                    $ip = substr ( $_SERVER [$header], 0, $pos );
		                } else {
		                    $ip = $_SERVER [$header];
		                }
		                return $ip;
		            }
		        }
		        
		    }
		    return $_SERVER ['REMOTE_ADDR'];
		}
		function savelastlogins($userid){
			$date=date('Y-m-d H:i:s');
			$ip=$this->getuserips();
			$this->runSQL("UPDATE admin_user SET last_visit='".$date."', last_visit_ip='".$ip."'");
		}
		#===================== End of sub catory stucture ===================	
		function getsubcategory($cateid){
			$id=$cateid;
			$arrsub=array();
			$arrsub[]=$cateid;
  	  		while ($id!='') { 
  	  			$arrid=explode(',', $id);//convert all id from query to array
  	  			$id='';
  	  			for ($i=0; $i < count($arrid) ; $i++) { 
  	  				$sql=$this->runSQL("SELECT * FROM stock_category WHERE parentid='".$arrid[$i]."'");
	      	  		foreach ($sql->result_array() as $row) {
	      	  			$arrsub[]=$row['categoryid'];
	      	  			$id.=$row['categoryid'].',';
	      	  		}
	      	  	}
	      	  	$id=trim($id,',');
  	  		}
  	  		return $arrsub;
		}
		function getsubcate($menu_id,$lang){
			echo "<ul>";
			$data = $this->runSQL("SELECT * FROM tblmenus WHERE parentid='".$menu_id."' AND is_active='1'")->result();
			foreach ($data as $menu) {
				$men_name = $menu->menu_name;
  			if($lang == 'khmer') {
  				$men_name = $menu->menu_name_kh;
  			}
				$count = $this->runSQL("SELECT count(*) as count FROM tblmenus WHERE parentid='".$menu->menu_id."' AND is_active='1'")->row()->count;
				if($count > 0) {
					echo "<li><a href='#'>$men_name <span class='fa fa-angle-down'></span></a>";
					$this->getsubcate($menu->menu_id,$lang);
				}else{
					$href = site_url('site/content/'.$menu->menu_id.'/'.$menu->article_id);
					if($menu->menu_type == 4) {
						$href = site_url('site/videoall/'.$menu->menu_id.'/'.$menu->article_id.'/'.$menu->menu_type);
					} else if($menu->menu_type == 3) {
						$href = site_url('site/events/'.$menu->menu_id.'/'.$menu->article_id.'/'.$menu->menu_type);
					}

					echo "<li><a href='".$href."'>$men_name </a>";
				}				
				echo "</li>";
			}
			echo "</ul>";
		}
	}
	
	
?>