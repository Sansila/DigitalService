
            <form method="post" enctype="multipart/form-data" accept-charset="utf-8" action="<?php echo site_url('dynamicController/saveItemfromConfig')?>">
                <h3><a href="<?php echo site_url('dynamicController/additem/'.$msg="msg")?>">Add Item</a> / <a href="">View Item</a></h3>
               <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Item Name <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="name" class="form-control" required="" id="name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Item Name Kh <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="namekh" class="form-control"  required="" id="namekh"> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Unite Price <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="number" name="price" class="form-control" required="" id="price"> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Category <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control" name="category" id="category" required="">
                                        <option value="0">-Select-</option>
                                        <?php 
                                            foreach ($this->dynamicModel->getCategories() as $cat) {
                                        ?>
                                            <option value="<?php echo $cat->CategoryID?>"><?php echo $cat->CategoryName?></option>
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
                                    <select class="form-control" name="inventery" id="inventery" required="">
                                        <option value="">-Select-</option>
                                        <option value="Inventory">Inventory</option>
                                        <option value="Non-Inventory">Non-Inventory</option>
                                    </select> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-2 control-label">Image </label>
                                <div class="col-md-10">
                                    <input type="file" name="userfile" class="form-control" id="userfile"> 
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
                            if(isset($msg))
                            {
                                if($msg == "false")
                                    $hide = "hide";
                                if($msg == "update"){
                                    $hide = "";
                                    $text = "Your Item has been updated.";
                                }
                                if($msg == "insert"){
                                    $hide = "";
                                    $text = "Your Item has been inserted.";
                                }
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