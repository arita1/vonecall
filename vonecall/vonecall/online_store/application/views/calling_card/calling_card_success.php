<?php include APPPATH.'views/_header.php';?>
<style>
.operatorBox img {
    width: 100%;
    height: auto;
}
.operatorBox {
    margin: 10px;
    width: 35%;
}
.operatorLogos {    
    text-align: center;    
}
.operatorBox span {
    background-color: #fff;
}

.box_makepayment table tr td {
    border-bottom: 1px solid #ccc;
    border-right: 1px solid #ccc;
    padding: 5px 10px;
}
.box_makepayment table tr:last-child td {
    border-bottom: medium none;
}
.print-button {
    padding: 10px 5px;
    text-align: center;
}
.print-button > a {
    background: none repeat scroll 0 0 #f8c26a;
    border: 1px solid #bb9f47;
    color: #3a4754;
    padding: 5px 15px;
}
</style>
<div id="main" class="p5t p10b">
  <div class="bg_tt_page"><div class="ac"><?php echo $this->lang->line('buy_calling_card');?></div></div>
  <div class="center_page_afterlogin">
    <?php if (isset($message)) {?><dl id="system-message"><dd class="message"><ul><li><?php echo $message;?></li></ul></dd></dl><?php }?>
    <?php if (isset($warning)) {?><dl id="system-message"><dd class="message error"><ul><li><?php echo $warning;?></li></ul></dd></dl><?php }?>
    
    <!-- Loading Image -->
    <div class="loading"> <img src="<?php echo $this->config->item('base_url')?>public/images/loading_new.gif" /> </div>
   
    <div class="col_big">
        <div class="box_makepayment ">
            
            <div class="operatorLogos">
	    		<div class="operatorBox" >
				   <a href="javascript:void(0)">
			         <img src="<?php echo $this->config->item('base-url')?>public/images/vonecall_calling_card.png" alt="<?php echo $results[0]->callingCardBatchName?>"/>			    	 
			       </a>	
			    </div>			    
	    	</div>          
            <table width="100%" cellpadding="0" cellspacing="0" style="border: 1px solid #ccc">            	
				<tr>
					<td width="25%" style="border-right: 1px solid #ccc;"> Store Name: <?php echo $info->firstName.' '.$info->lastName ?> (<?php echo $results[0]->callingCardPurchaseStoreName;?>)</td> 
					<td style="border-right: 1px solid #ccc;"> Transaction Date: <?php echo date('m/d/Y') ?> </td>						
				</tr>
				<tr>
					<td style="border-right: 1px solid #ccc;"> <h3>Pin Number</h3> </td> 
					<td style="border-right: 1px solid #ccc;"> 
						<?php $i=1;
						foreach($results as $item){ ?> 
							<?php if($i==1){ echo $item->callingCardPin;} else {echo ', '.$item->callingCardPin;}?>	
						<?php $i++; } ?>
					</td>
				</tr>
				<tr>
					<td> Denomination </td>	
					<td> <?php echo format_price($results[0]->batchAmount);?></td>
				</tr>
				<tr>
					<td colspan="2" align="center"> <h4> <b>Access Numbers</b> </h4> </td>
				</tr>
				<tr>
					<td> Access Number</td> 
					<td><?php echo format_phone_number($results[0]->callingCardLocalAccess);?></td>
				</tr>
				<tr>
					<td> Toll Free </td> 
					<td><?php echo format_phone_number($tollFree)?></td>
				</tr>
				<tr>
					<td> Customer Service </td> 
					<td><?php echo format_phone_number($customerService)?></td>
				</tr>
				<tr>
					<td>Instruction:</td>
					<td><?php echo $instruction?></td>
				</tr>
				<tr>
					<td>Disclaimer:</td>
					<td><?php echo $disclaimer?></td>
				</tr>
			</table>
			<div class="print-button"> <a href="javascript:void(0)" onclick="print_page();"> Print </a> </div>
        </div>
      </div>
    <div class="cb"></div>
    <div class="bottom_pages_afterlogin2"></div>
    <div class="cb"></div>
          
  </div>
</div>
<script type="text/javascript">
    function print_page(){
       window.print();
        setTimeout("closePrintView()", 2000);
    };
    function closePrintView() {
        document.location.href = '<?php echo site_url('home');?>'; 
    }
</script>
<?php include APPPATH.'views/_footer.php';?>