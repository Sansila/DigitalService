<link href="<?php echo site_url('assets/css/bootstrap.css')?>" rel="stylesheet" id="bootstrap-css">
<!-- <script src="<?php //echo site_url('assets/js/bootstrap.js')?>"></script> -->
<script src="<?php echo site_url('assets/js/jquery.min.js')?>"></script>
<link href="<?php echo site_url('assets/css/style.css')?>" rel="stylesheet" id="bootstrap-css">
<style type="text/css">
    .form-group {
        margin-bottom: 0.5rem;
    }
    .contact-image{
        text-align: center;
    }
    .contact-image img{
        border-radius: 6rem;
        width: 23%;
        margin-top: -3%;
        transform: rotate(29deg);
    }
</style>

<div class="container contact-form contact-forms">
    <div class="contact-image">
        <img src="<?php echo site_url('images')?>/rocket_contact.png" alt="rocket_contact"/>
    </div>
    <form method="post" action="<?php echo site_url('configController/loginServer/')?>">
        <h3>Config Server Login</h3>
       <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <label class="col-md-4 control-label">User Name <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" name="username" class="form-control" required="" id="username" style="
    padding: 4px 10px !important;"> 
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-md-4 control-label">Password <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="password" name="password" class="form-control" required="" id="password" style="
    padding: 4px 10px !important;"> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="form-group">
                    <input type="submit" name="btnSubmit" class="btnContact" value="Login" />
                </div>
            </div>
            <div class="col-sm-12">
            	<?php 
            		$hide = "hide";
            		$text = "";
            		if(isset($msg))
            		{
            			if($msg == "error")
            				$text = "Login failed your name or your password is incorrect.";
            				$hide = "";
            		}

            	?>
                <div class="alert alert-danger <?php echo $hide;?>" role="alert" style="font-size: 12px;">
                    <?php echo $text;?>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    setTimeout(function(){ 
        $('.alert-danger').addClass('hide');
    }, 2000);
</script>