
            <form action="<?php echo site_url('configController/saveBusiness')?>" method="post" enctype="multipart/form-data" accept-charset="utf-8" >
                <h3>
                    <a href="#"><?php echo $title?></a> / 
                    <a href="<?php echo site_url('configController/viewBusiness')?>">View Bussiness</a>
                </h3>
               <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Business Name <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="res_name" class="form-control" required="" id="res_name" value="<?php echo isset($edit->res_name)?"$edit->res_name":"";?>">
                                    <input type="text" name="resid" class="form-control hide" id="resid" value="<?php echo isset($edit->res_id)?"$edit->res_id":"";?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Mobile <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="mobile" class="form-control"  required="" id="mobile" value="<?php echo isset($edit->mobile)?"$edit->mobile":"";?>"> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Email</label>
                                <div class="col-md-8">
                                    <input type="text" name="email" class="form-control" id="email" style="
    padding: 2px 10px !important; height: 34px;" value="<?php echo isset($edit->email)?"$edit->email":"";?>"> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Contact Name <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="contact_name" class="form-control" required="" id="contact_name" style="
    padding: 2px 10px !important; height: 34px;" value="<?php echo isset($edit->contactName)?"$edit->contactName":"";?>"> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Location<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="location" class="form-control" required="" id="location" style="
    padding: 2px 10px !important; height: 34px;" value="<?php echo isset($edit->location)?"$edit->location":"";?>">  
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Address <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="address" class="form-control" required="" id="address" style="
    padding: 2px 10px !important; height: 34px;" value="<?php echo isset($edit->address)?"$edit->address":"";?>"> 
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-sm-12" style="padding-top: 15px;">
                    	<table class="table">
						  <thead>
						    <tr>
						      <th scope="col">#</th>
						      <th scope="col">Notification Text</th>
						      <th scope="col">Notification Text Kh</th>
						      <th scope="col">User Role</th>
						    </tr>
						  </thead>
						  <tbody>
						  	<?php 
						  		for ($i=1; $i <= 10; $i++) {
						  	?>
						    <tr>
						      <th scope="row"><?php echo $i;?></th>
						      <td><input type="text" name="txten[]" class="form-control txten"  id="txten" > </td>
						      <td><input type="text" name="txtkh[]" class="form-control txtkh"  id="txtkh" > </td>
						      <td><input type="text" name="role[]" class="form-control role" id="role" > </td>
						    </tr>
						    <?php 
						    	}
						    ?>
						  </tbody>
						</table>
                    </div> -->

                    <div class="col-sm-4">
                        <div class="form-group">
                            <input type="submit" name="btnSubmit" class="btnContact" value="Save" />
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <?php 
                            $text = "";
                            $hide = "hide";
                            $url = current_url();
                            $url_path = parse_url($url, PHP_URL_PATH);
                            $basename = pathinfo($url_path, PATHINFO_BASENAME);
                            if($basename == "success")
                            {
                                $hide = "";
                                $text = "Item has been inserted.";
                            }
                            if($basename == "saveerror")
                            {
                            	$hide = "";
                                $text = "Item insert failed.";
                            }
                            if($basename == "updated")
                            {
                                $hide = "";
                                $text = "Item has been updated.";
                            }
                            if($basename == "uperror")
                            {
                            	$hide = "";
                                $text = "Item update failed.";
                            }
                        ?>	
                        <div class="alert alert-success <?php echo $hide?>" role="alert">
                            <?php echo $text;?>
                        </div>
                    </div>
                </div>
            </form>
</div>
<script type="text/javascript">
    setTimeout(function(){ 
        $('.alert-success').addClass('hide');
    }, 2000);
</script>