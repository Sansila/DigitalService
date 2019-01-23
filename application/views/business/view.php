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
                                        <h5>Business List</h5>
                                    <div style="text-align: right; width:130px; float:right">
                                                        
                                    </div>              
                                </div>
                                <div class="widget-content nopadding" id='tap_print'>

                                    <table class="table table-bordered table-striped table-hover">
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
                                            <tr class='remove_tag'>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th width='150'>
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class='list'>

                                        </tbody>
                                    </table>  

                                </div>
                            </div>
                            <div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
                                    <div class='col-sm-3'>
                                        <label>Show 
                                            
                                            <select id='perpage' onchange='getdata(1);' name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" tabindex="-1" class="form-control select2-offscreen">
                                                <?PHP
                                                for ($i=10; $i < 500; $i+=10) { 
                                                    echo "<option value='$i'>$i</option>";
                                                }
                                                 ?>
                                            </select> 
                                        </label>
                                    </div>
                                    <div class='dataTables_paginate'></div>
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
        getdata(1);
        $(document).on('click', '.pagenav', function(){
            var page = $(this).attr("id");
            getdata(page);          
        });
    });
    function getdata(page){
        var url="<?php echo site_url('configController/getdataBusiness')?>";
        var perpage=$('#perpage').val();
        $.ajax({
            url:url,
            type:"POST",
            datatype:"Json",
            async:false,
            data:{
                'page':page,
                'perpage':perpage
            },
            success:function(data) {
              $(".list").html(data.data); console.log(data);
              $('.dataTables_paginate').html(data.pagina.pagination);
            }
        });
    }
    function update(event){
        var storeid=jQuery(event.target).attr("rel");
        location.href="<?PHP echo site_url('configController/editbusiness');?>/"+storeid;
    }
    function deletestore(event){
            var conf=confirm("Are you sure to delete this Business");
            if(conf==true){
                var storeid=jQuery(event.target).attr("rel");
                var url="<?php echo site_url('configController/deletebusiness')?>/"+storeid;
                $.ajax({
                    url:url,
                    type:"POST",
                    datatype:"Json",
                    async:false,
                    data:{},
                    success:function(data) {
                        getdata(1);
                    }
                  })
            }
        }
</script>
