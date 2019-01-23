
            <form method="post" action="<?php echo site_url('configController/saveConfiger')?>">
                <h3>
                    <a href="#">Configer Server / 
                    <?php 
                        if(isset($config->id))
                        {
                    ?>
                        <a href="<?php echo site_url('configController/set_barcode/'.$config->id)?>">View QrCode</a>
                    <?php
                        }else{
                    ?>
                        <a href="#">View QrCode</a>
                    <?php
                        }
                    ?>
                    
                </h3>
               <div class="row">
                <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Type Server<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control" name="servertype" id="servertype" required="" style="
    padding: 2px 10px !important; height: 34px;">
                                        <!-- <option value="">-Select-</option> -->
                                        <?php
                                            foreach ($this->configModel->getServerType() as $server) {
                                                $sel = "";
                                                if($config->serverid == $server->serverid)
                                                    $sel = "selected";
                                                if($id == $server->serverid)
                                                    $sel = "selected";
                                        ?>
                                            <option <?php echo $sel;?> value="<?php echo $server->serverid;?>"><?php echo $server->servername;?></option>
                                        <?php
                                            }
                                        ?>
                                    </select> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Application Server <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="appname" class="form-control" required="" id="appname" value="<?php echo isset($config->app_name)?"$config->app_name":""; ?>"> 
                                    <input type="text" name="id" class="form-control hide" value="<?php echo isset($config->id)?"$config->id":""; ?>" id="id"> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Database Name <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="database" class="form-control" value="<?php echo isset($config->database_name)?"$config->database_name":""; ?>" required="" id="database"> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Password <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="password" name="password" value="<?php echo isset($config->password)?"$config->password":""; ?>" class="form-control" required="" id="password"> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Server Name or IP <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="server" value="<?php echo isset($config->server_name)?"$config->server_name":""; ?>" class="form-control" required="" id="server"> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">User Name <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="username" value="<?php echo isset($config->user_name)?"$config->user_name":""; ?>" class="form-control" required="" id="username"> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-4 control-label">Display Type <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control" name="displaytype" id="displaytype" required="" style="
    padding: 2px 10px !important; height: 34px;">
                                        <?php
                                            $sel = "";
                                            $sel2 = "";
                                            if(isset($config->type)){
                                                if($config->type == 1)
                                                    $sel = "selected";
                                                if($config->type == 2)
                                                    $sel2 = "selected";
                                            }
                                        ?>
                                        <option value="0">-Select-</option>
                                        <option <?php echo $sel2;?> value="2">Can Order</option>
                                        <option <?php echo $sel;?> value="1">Just Display</option>
                                    </select> 
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
                                $hide = "";
                                if($msg == "update")
                                    $text = "Your server has been updated.";
                                if($msg == "insert")
                                    $text = "Your server has been inserted.";
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

    $('#servertype').change(function(){
        var id = $(this).val();
        loadconfigdata(id);
    });

    function loadconfigdata(id){
        location.href="<?PHP echo site_url('configController/loadFormconfigByServerID');?>/"+id;
    }
</script>