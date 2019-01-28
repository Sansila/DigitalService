
            <form method="post" enctype="multipart/form-data" accept-charset="utf-8" action="<?php echo site_url('configController/saveCategoryfromConfig')?>">
                <h3>
                    <a href="#"><?php echo $title?></a> / 
                    <a href="<?php echo site_url('configController/viewcategory')?>">View Category</a>
                </h3>
               <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Category Name <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="name" class="form-control" required="" id="name" value="<?php echo isset($edit->CategoryName)?"$edit->CategoryName":"";?>">
                                    <input type="text" name="cateid" class="form-control hide" id="cateid" value="<?php echo isset($edit->CategoryID)?"$edit->CategoryID":"";?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Category Name Kh <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="namekh" class="form-control"  required="" id="namekh" value="<?php echo isset($edit->CategoryNameInKhmer)?"$edit->CategoryNameInKhmer":"";?>"> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Description </label>
                                <div class="col-md-8">
                                    <input type="text" name="description" class="form-control" id="description" value="<?php echo isset($edit->Description)?"$edit->Description":"";?>"> 
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Menu Category<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <div class="custom-control custom-checkbox">
                                        <?php
                                            $checked = "checked";
                                            if(isset($edit->MenuCategory))
                                            {
                                                if($edit->MenuCategory == true)
                                                    $checked = "checked";
                                                else
                                                    $checked = "";
                                            }

                                        ?>
                                        <input <?php echo $checked;?> type="checkbox" class="custom-control-input" name="menu" id="defaultUnchecked">
                                        <label class="custom-control-label" for="defaultUnchecked"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Default Category</label>
                                <div class="col-md-8">
                                    <div class="custom-control custom-checkbox">
                                        <?php
                                            $checked = "";
                                            if(isset($edit->IsDefault))
                                            {
                                                if($edit->IsDefault == true)
                                                    $checked = "checked";
                                                else
                                                    $checked = "";
                                            }

                                        ?>
                                        <input <?php echo $checked;?> type="checkbox" class="custom-control-input" name="is_default" id="defaultUncheckeds">
                                        <label class="custom-control-label" for="defaultUncheckeds"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6"></div>
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
                                $text = "Category has been inserted.";
                            }
                            if($basename == "error")
                            {
                                $hide = "";
                                $text = "Category insert failed.";
                            }
                            if($basename == "updated")
                            {
                                $hide = "";
                                $text = "Category has been updated.";
                            }
                            if($basename == "uperror")
                            {
                            	$hide = "";
                                $text = "Category update failed.";
                            }
                            if($basename == "exist")
                            {
                                $hide = "";
                                $text = "Category is already exist...!";
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