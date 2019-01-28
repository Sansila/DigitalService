<div class="List_property">
        <div class="wrapper">
            <div class="clearfix" id="main_content_outer">
                <div id="main_content">
                  <div class="col-xs-12">
                        <div class="col-xs-6">
                        </div>
                          
                  </div>
                  <div class="row">
                        <div class="col-sm-12">
                            <div class="widget-box table-responsive">
                                <div class="widget-title no_wrap" id='top-bar'>
                                    <span class="icon">
                                        <i class="fa fa-th"></i>
                                    </span>
                                        <h5>Item List</h5>
                                    <div style="text-align: right; width:130px; float:right">
                                                        
                                    </div>              
                                </div>
                                <div class="widget-content nopadding" id='tap_print'>

                                    <table id="example" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                            <?php 
                                                foreach($thead as $th=>$val){
                                                    if($th=='Action')
                                                        echo "<th class='remove_tag'>".$th."</th>";
                                                    else
                                                        echo "<th class='sort $val no_wrap' onclick='sort(event);' rel='$val'>".$th."</th>";                                
                                                }
                                            ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach ($this->configModel->getCategories() as $row) 
                                                {
                                            ?>
                                                <tr>
                                                    <td><?php echo $row->CategoryID;?></td>
                                                    <td><?php echo $row->CategoryName;?></td>
                                                    <td><?php echo $row->CategoryNameInKhmer;?></td>
                                                    <td>
                                                        <span>
                                                            <a style='padding:0px 10px;'><img rel="<?php echo $row->CategoryID; ?>" onclick='deletestore(event);' src='<?php echo base_url('images/delete.png')?>' width='30'/></a>
                                                        </span>
                                                        <span>
                                                            <a style='padding:0px 10px;'><img rel="<?php echo $row->CategoryID; ?>" onclick='update(event);' src='<?php echo base_url('images/edit.png')?>' width='30'/></a>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>  

                                </div>
                            </div>
                        </div>          
                    </div> 
                </div>
           </div>
        </div>
    </div>
    
</div>

<script type="text/javascript">
    $(function(){
        //getdata(1);
        $(document).on('click', '.pagenav', function(){
            var page = $(this).attr("id");
            getdata(page);          
        });
        $('#example').DataTable( {
            columnDefs: [ {
                targets: [ 0 ],
                orderData: [ 0, 1 ]
            }, {
                targets: [ 1 ],
                orderData: [ 1, 0 ]
            }, {
                targets: [ 2 ],
                orderData: [ 2, 0 ]
            } ]
        } );
    });
    function update(event){
        var storeid=jQuery(event.target).attr("rel");
        location.href="<?PHP echo site_url('configController/editCategory');?>/"+storeid;
    }
    function deletestore(event){
            var conf=confirm("Are you sure to delete this Category");
            if(conf==true){
                var storeid=jQuery(event.target).attr("rel");
                var url="<?php echo site_url('configController/deleteCategory')?>/"+storeid;
                $.ajax({
                    url:url,
                    type:"POST",
                    datatype:"Json",
                    async:false,
                    data:{},
                    success:function(data) {
                        location.reload();
                    }
                  });
            }
        }
</script>
