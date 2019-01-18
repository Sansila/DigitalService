<link href="<?php echo site_url('assets/css/bootstrap.css')?>" rel="stylesheet" id="bootstrap-css">
<!-- <script src="<?php //echo site_url('assets/js/bootstrap.js')?>"></script> -->
<script src="<?php echo site_url('assets/js/jquery.min.js')?>"></script>
<style type="text/css">
    body{
        background: -webkit-linear-gradient(left, #0072ff, #00c6ff);
    }
    .contact-form{
        background: #fff;
        margin-top: 10%;
        margin-bottom: 5%;
        width: 420px;
        border-radius: 10px;
    }
    .form-group {
        margin-bottom: 2rem;
    }
    .contact-form .form-control{
        border-radius:2rem;
    }
    .contact-image{
        text-align: center;
    }
    .contact-image img{
        border-radius: 6rem;
        width: 25%;
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
</style>

<div class="container contact-form">
    <div class="contact-image">
        <img src="<?php echo site_url('images')?>/rocket_contact.png" alt="rocket_contact"/>
    </div>
    <form method="post" action="<?php echo site_url('dynamicController/loginServer/')?>">
        <h3>Config Server Login</h3>
       <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <label class="col-md-4 control-label">User Name <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" name="username" class="form-control" required="" id="username"> 
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-md-4 control-label">Password <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="password" name="password" class="form-control" required="" id="password"> 
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