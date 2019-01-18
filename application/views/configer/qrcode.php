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
    .contact-form .form{
        padding: 10% 5% 1% 5%;
    }
    .contact-form form .row{
        margin-bottom: -7%;
    }
    .contact-form h3{
        margin-bottom: 2%;
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
            <div class="logout">
                <a href="<?php echo site_url('dynamicController/form_config')?>">View Config Server</a> / 
                <a href="<?php echo site_url('dynamicController/logout')?>">Logout</a>
            </div>
        </div>
    </div>
    <div class="form">
    	<h3>View Qrcode</h3>
    	<div style="text-align: center;">
    		<?php echo $imgqrcode;?>
    	</div>
    </div>
    
            
</div>
