<link href="<?php echo site_url('assets/css/bootstrap.css')?>" rel="stylesheet" id="bootstrap-css">
<script src="<?php echo site_url('assets/js/bootstrap.js')?>"></script>
<script src="<?php echo site_url('assets/js/jquery.min.js')?>"></script>
<style type="text/css">
    body{
        background: -webkit-linear-gradient(left, #0072ff, #00c6ff);
    }
    .contact-form{
        background: #fff;
        margin-top: 10%;
        margin-bottom: 5%;
        width: 100%;
    }
    .form-group {
        margin-bottom: 3rem;
    }
    .contact-form .form-control{
        border-radius:2rem;
    }
    .contact-image{
        text-align: center;
    }
    .contact-image img{
        border-radius: 6rem;
        width: 11%;
        margin-top: -3%;
        transform: rotate(29deg);
    }
    .contact-form form{
        padding: 10% 5% 14% 5%;
    }
    .contact-form form .row{
        margin-bottom: -7%;
    }
    .contact-form h3{
        margin-bottom: 8%;
        margin-top: -10%;
        text-align: center;
        color: #0062cc;
    }
    .contact-form .btnContact {
        width: 50%;
        border: none;
        border-radius: 1rem;
        padding: 1.5%;
        background: #dc3545;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
    }
    .btnContactSubmit
    {
        width: 50%;
        border-radius: 1rem;
        padding: 1.5%;
        color: #fff;
        background-color: #0062cc;
        border: none;
        cursor: pointer;
    }
    .hide{
        display: none;
    }
    .logout{
        position: absolute;
        right: 0;
        top: -78px;
        font-size: 14px;
        text-transform: uppercase;
        font-weight: bold;
        color: #0062cc;
    }
</style>

<div class="container contact-form">
            <div class="contact-image">
                <img src="<?php echo site_url('images')?>/rocket_contact.png" alt="rocket_contact"/>
            </div>
            <div class="contact-logout">
                <div class="col-sm-12">
                    <div class="logout"><a href="<?php echo site_url('dynamicController/logout')?>">Logout</a></div>
                </div>
            </div>
            <form method="post" action="<?php echo site_url('dynamicController/saveConfiger')?>">
                <h3>Configer Server</h3>
               <div class="row">
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
                                    <select class="form-control" name="displaytype" id="displaytype" required="">
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
</script>