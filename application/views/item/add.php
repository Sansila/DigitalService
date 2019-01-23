
            <form method="post" enctype="multipart/form-data" accept-charset="utf-8" action="<?php echo site_url('configController/saveItemfromConfig')?>">
                <h3>
                    <a href="#"><?php echo $title?></a> / 
                    <a href="<?php echo site_url('configController/viewitem')?>">View Item</a>
                </h3>
               <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Item Name <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="name" class="form-control" required="" id="name" value="<?php echo isset($edit->Description)?"$edit->Description":"";?>">
                                    <input type="text" name="ItemID" class="form-control hide" id="ItemID" value="<?php echo isset($edit->ItemID)?"$edit->ItemID":"";?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Item Name Kh <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="namekh" class="form-control"  required="" id="namekh" value="<?php echo isset($edit->DescriptionInKhmer)?"$edit->DescriptionInKhmer":"";?>"> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Unite Price <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="number" name="price" class="form-control" required="" id="price" style="
    padding: 2px 10px !important; height: 34px;" value="<?php echo isset($edit->UnitPrice)?"$edit->UnitPrice":"";?>"> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Category <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control" name="category" id="category" required="" style="
    padding: 2px 10px !important; height: 34px;">
                                        <option value="0">-Select-</option>
                                        <?php 
                                            foreach ($this->configModel->getCategories() as $cat) {
                                                $sel = "";
                                                if($edit->CategoryID == $cat->CategoryID)
                                                    $sel = "selected";
                                        ?>
                                            <option <?php echo $sel?> value="<?php echo $cat->CategoryID?>"><?php echo $cat->CategoryName?></option>
                                        <?php
                                            }
                                        ?>
                                    </select> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Inventory Type<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control" name="inventery" id="inventery" required="" style="
    padding: 2px 10px !important; height: 34px;">
                                        <?php 
                                            $sel1 = ""; $sel2 = "";
                                            if($edit->InventoryType == "Inventery")
                                                $sel1 = "selected";
                                            if($edit->InventoryType == "Non-Inventery")
                                                $sel2 = "selected";
                                        ?>
                                        <option value="">-Select-</option>
                                        <option <?php echo $sel1;?> value="Inventery">Inventery</option>
                                        <option <?php echo $sel2;?> value="Non-Inventery">Non-Inventery</option>
                                    </select> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Addon Menu <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <div class="custom-control custom-checkbox">
                                        <?php
                                            $checked = "checked";
                                            if(isset($edit->AddOnMenu))
                                            {
                                                if($edit->AddOnMenu == true)
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
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label">Image </label>
                                <div class="col-md-10">
                                    <input type="file" name="userfile" class="form-control" id="userfile" style="
    padding: 1px 10px !important; height: 34px;"> 
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
                                $text = "Item has been inserted.";
                            }
                            if($basename == "updated")
                            {
                                $hide = "";
                                $text = "Item has been updated.";
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