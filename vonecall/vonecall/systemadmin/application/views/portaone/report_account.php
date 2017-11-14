<div class="bg_title_content">Get Account Report</div>        
        <div class="box_phonenumber p12t p13b">
        	
          <?php if (isset($accountList)) {?>
          <table width="50%" border="0" cellspacing="0" cellpadding="0">
            <tr class="white_color">
              <td align="center" class="bg_table boder_right">No</td>
              <td align="center" class="bg_table boder_right">i_account</td>
              <td align="center" class="bg_table boder_right">Account Id</td>
              <td align="center" class="bg_table boder_right">Current Balance</td> 
                           
            </tr>
            
            <?php if(count($accountList)>0) { ?>
            <?php $i=1;?>
            <?php foreach($accountList as $item) {?>
            <tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
              <td class="boder_right" align="center"><?php echo $i;?></td>              
              <td class="boder_right"><?php echo $item->i_account;?></td>
              <td class="boder_right"><?php echo str_replace('ani', '', $item->id);?></td>
              <td>$ <?php echo $item->balance;?></td>              
            </tr>
            <?php $i++;?>
            <?php }?>
            <?php } else {?>
            <tr>
              <td colspan="9"><?php echo $this->lang->line('empty_results');?></td>
            </tr>
            <?php }?>
          </table>          
          <?php }?>
      </div>
      
