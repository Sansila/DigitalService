<link href="<?php echo site_url('assets/css/bootstrap.css')?>" rel="stylesheet" id="bootstrap-css">
<script src="<?php echo site_url('assets/js/bootstrap.js')?>"></script>
<script src="<?php echo site_url('assets/js/jquery.min.js')?>"></script>
<link href="<?php echo site_url('assets/css/style.css')?>" rel="stylesheet" id="bootstrap-css">
<!-- <link href="<?php //echo site_url('assets/css/gstyle.css')?>" rel="stylesheet" id="bootstrap-css"> -->

<div class="container contact-form">
    <!-- <div class="contact-image">
        <img src="<?php echo site_url('images')?>/rocket_contact.png" alt="rocket_contact"/>
    </div> -->
    <div class="contact-logout">
        <div class="col-sm-12">
            <div class="logout">
                <?php $msg = "msg"?>
                <a href="<?php echo site_url('dynamicController/additem/'.$msg)?>">Add Item</a>
                <a href="<?php echo site_url('dynamicController/form_config')?>">Add Notification</a>
                <a href="<?php echo site_url('dynamicController/form_config')?>">Configeration Server</a>
                <a href="<?php echo site_url('dynamicController/set_barcode')?>">View QrCode</a>
                <a href="<?php echo site_url('dynamicController/logout')?>">Logout</a>
            </div>
        </div>
    </div>