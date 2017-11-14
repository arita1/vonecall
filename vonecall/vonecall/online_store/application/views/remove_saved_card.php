<?php include '_header.php';?>
<?php if ($this->session->userdata('language')=='spanish') {?>
<style>
.box_makepayment .label1 {width: 200px;}
.btn_checkbox {margin-left:205px; width: 330px;}
.box_makepayment .label_short1 {width: 80px;}
.add_phone_text3 {width: 39px;}
</style>
<?php } else {?>
<style>
.box_makepayment .label1 {width: 160px;}
</style>
<?php } ?>
<script>
function delete_card(div_id,id,card){
  var result = confirm("Are you sure to delete?");
  if(result){
  site_url="<?php echo site_url();?>";
  $.ajax({
             url: site_url + 'store/delete_card',
             data: 'id='+id+'&card='+card,
             type: 'POST',
             success: function (responseData){
                if(responseData == 1){
                    alert('Removed successfully');
                    $('#'+div_id).hide();
                }else{
                    alert('Error in deletion');
                }

             }
       });//ajax close
  }

}
</script>

<div id="main" class="p5t p10b">
 <div class="bg_tt_page"><div class="ac">Saved Card List</div></div>
  <div class="center_page_afterlogin">
    
    <div class="col_big">
      <!-- Display Results -->
      <?php if (isset($data)) {?>
      <div class="box_phonenumber p10l p10r p10t">
        <table width="50%" align="center" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            <tr class="bg_table">
            <!--  <td width="2%" align="center" > <strong></strong></td> -->
             
               <td width="3%" align="left" class="boder_right"> <strong><?php echo $this->lang->line('card_name');?></strong></td>
              <td width="2%" align="left" class="boder_right"> <strong><?php echo $this->lang->line('delete_card');?></strong></td>
                          
            </tr>
            <?php if(count($data)>0) { ?>
            <?php $i=1; foreach($data as $item) {
            ?>
            <tr id="<?php echo $i; ?>" class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
           <!--  <td align="left" ><?php echo $i;?></td> -->
             
              <td align="left" class="boder_right"><?php echo $item['cimCardNumber'];?></td>
              <td align="center" class="boder_right"><span class="bt_submit4 float_left m10l remove"  data-card="<?php echo $item['cimCardNumber'];?>" onclick='delete_card("<?php echo $i; ?>","<?php echo $item['profileID']; ?>","<?php echo $item['cimCardNumber']; ?>")'>Delete</span></td>              
            </tr>
            <?php $i++; }?>
            <?php } else {?>
            <tr>
              <td colspan="6"><?php echo $this->lang->line('empty_cards');?></td>
            </tr>
            <?php }?>
          </tbody>
        </table>
        <div class="cb"></div>
      </div>
      <?php }?>
    </div><!--col_big-->
  </div>
  <div class="cb"></div>
  <div class="bottom_pages_afterlogin2"></div>
  <div class="cb"></div>
</div>
 
<?php include '_footer.php';?>