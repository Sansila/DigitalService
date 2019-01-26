<?php
    if(!$this->session->userdata('logged_in')){
        redirect('configController/login', 'refresh');
    }
?>
<link href="<?php echo site_url('assets/css/style.css')?>" rel="stylesheet">
<link href="<?php echo site_url('assets/css/bootstrap.css')?>" rel="stylesheet">
<link href="<?php echo site_url('assets/css/gstyle.css')?>" rel="stylesheet">
<link href="<?php echo site_url('assets/css/jquery-ui.css')?>" rel="stylesheet">
<link href="<?php echo site_url('assets/css/font-awesome.css')?>" rel="stylesheet">

<script src="<?php echo site_url('assets/js/jquery.min.js')?>"></script>
<script src="<?php echo site_url('assets/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo site_url('assets/js/bootstrap.min.js')?>"></script>

<div class="container contact-form">
    <!-- <div class="contact-image">
        <img src="<?php echo site_url('images')?>/rocket_contact.png" alt="rocket_contact"/>
    </div> -->
    <div class="contact-logout">
        <div class="col-sm-12">
            <div class="logout">
                <a href="<?php echo site_url('configController/addcategory/')?>">Add Category</a>
                <a href="<?php echo site_url('configController/additem/')?>">Add Item</a>
                <!-- <a href="<?php //echo site_url('configController/addbusiness')?>">Add Business</a> -->
                <a href="<?php echo site_url('configController/addnotification')?>">Add Notification</a>
                <a href="<?php echo site_url('configController/form_config')?>">Configeration Server</a>
                <!-- <a href="<?php //echo site_url('configController/set_barcode')?>">View QrCode</a> -->
                <a href="<?php echo site_url('configController/logout')?>">Logout</a>
            </div>
        </div>
    </div>