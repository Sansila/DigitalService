
            <form method="post" action="<?php echo site_url('configController/saveNotification')?>" id="contact-form" enctype="multipart/form-data" accept-charset="utf-8" >
                <h3>
                    <a href="#"><?php echo $title?></a> / 
                    <a href="<?php echo site_url('configController/viewNotification')?>">View Notification</a>
                </h3>
               <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label" for="business">Business Name <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control" name="business" id="business" required="" style="
    padding: 2px 10px !important; height: 34px;">
                                        <option value="">-Select-</option>
                                        <?php 
                                            foreach ($this->configModel->getBusinessList() as $bus) {
                                            	$sel = "";
                                            	if($edit->res_id == $bus->res_id)
                                            		$sel = "selected";
                                        ?>
                                            <option <?php echo $sel?> value="<?php echo $bus->res_id?>"><?php echo $bus->res_name?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                    <input type="text" name="noteid" value="<?php echo isset($edit->notificationTextID)?"$edit->notificationTextID":"";?>" class="form-control hide" id="noteid">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label" for="role">Role <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="role" class="form-control" value="<?php echo isset($edit->userRoleID)?"$edit->userRoleID":"";?>" required="" id="role"> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label" for="note">Notification Text <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="note" class="form-control" value="<?php echo isset($edit->notificationText)?"$edit->notificationText":"";?>" required="" id="note"> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label" for="notekh">Notification TextKh <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="notekh" class="form-control" value="<?php echo isset($edit->notificationTextKh)?"$edit->notificationTextKh":"";?>" required="" id="notekh" > 
                                </div>
                            </div>
                        </div>
                    </div>

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
                                $text = "Notification Text has been inserted.";
                            }
                            if($basename == "saveerror")
                            {
                            	$hide = "";
                                $text = "Notification Text insert failed.";
                            }
                            if($basename == "updated")
                            {
                                $hide = "";
                                $text = "Notification Text has been updated.";
                            }
                            if($basename == "uperror")
                            {
                            	$hide = "";
                                $text = "Notification Text update failed.";
                            }
                            if($basename == "exist")
                            {
                                $hide = "";
                                $text = "Notification is already exist...!";
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