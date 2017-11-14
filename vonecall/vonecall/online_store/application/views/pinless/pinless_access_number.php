<?php include APPPATH.'views/_header.php';?>

<style>
	
.dataTables_wrapper table{
	border: 1px solid #d6d6d6;
}
.dataTables_wrapper table td {
    padding: 7px 10px;
}
.odd{
	
}
.even{
	background: none repeat scroll 0 0 #f1f1f1;
}
</style>

<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac">Pinless Access Number</div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    <div class="col_big">
        <div class="box_makepayment ">
            
           <table id="accessnumber_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="float: left;">
			<thead>
			<tr class="bg_table" style="text-align: center;">
			  <th class="bg_table boder_right"><strong><?php echo $this->lang->line('access_number');?> </strong></th>
			  <th class="bg_table boder_right"><strong><?php echo $this->lang->line('city');?></strong></th>
			  <th class="bg_table boder_right" style="padding: 7px 10px;" class="bg_table boder_right"><strong><?php echo $this->lang->line('state');?></strong> </th>
			  <th><strong><?php echo $this->lang->line('language');?></strong></th>
			</tr>
			</thead>
			<tbody>
			<?php if(count($results)>0) {?>
			<?php $i=1;foreach($results as $item) {?>
			<tr>
			  <td class="boder_right"><?php echo format_phone_number($item->AccessNumber);?></td>    
			  <td class="boder_right"><?php echo $item->City;?></td>
			  <td class="boder_right"><?php echo $item->State;?></td>
			  <td class="boder_right"><?php echo $item->access_lang;?></td>      
			</tr>
			<?php  $i++;}?>
			<?php } else {?>
			<tr>
			  <td colspan="5"><?php echo $this->lang->line('empty_results');?></td>
			</tr>
			<?php }?>
			</tbody>
		  </table>
		  <!-- END -->
		  
        <?php echo isset($paging)?$paging:'';?> 
          
        </div>
      </div>
    </div>
    <div class="cb"></div>
    <div class="bottom_pages_afterlogin2"></div>
    <div class="cb"></div>
  </div>
  
<script>
jQuery(document).ready(function($){
		$('#accessnumber_table').dataTable({
			"sPaginationType" : "full_numbers",
			"aaSorting" : [[0, "asc"]],			
		});
	} );

function paging(num) {
	$('input[name=page]').val(num);
	$('#rate_form').submit();
}
</script>
</body>
</html>
<?php include APPPATH.'views/_footer.php';?>