<?php include '_header.php';?>
<div class="p5t p10b" id="main">
	<div class="bg_tt_page"><div class="ac">Instructions</div></div>
	<div class="center_page_afterlogin">
		<div class="col_big">
			<div class="box_phonenumber p10l p10r p10t">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tbody>
						<tr class="bg_table">
							<td class="boder_right"><strong>Function</strong></td>
							<td><strong>Link</strong></td>
						</tr>
						<?php if(count($results)>0) {?>
						<?php $i=1;foreach($results as $item) {?>
						<tr class="<?php echo($i%2==1)?'':'bg_light_blu';?>">
							<td class="boder_right"><?php echo $item->function;?></td>
							<td><a target="_blank" href="<?php echo $item->link;?>"><?php echo $item->link;?></a></td>
						</tr>
						<?php $i++;}?>
						<?php } else {?>
						<tr>
							<td colspan="2"><?php echo $this->lang->line('empty_results');?></td>
						</tr>
						<?php }?>
					</tbody>
				</table>
				<?php echo isset($paging)?$paging:'';?>
				<div class="cb"></div>
			</div>
		</div>
		<div class="cb"></div>
	</div>
	<div class="cb"></div>
	<div class="bottom_pages_afterlogin2"></div>
	<div class="cb"></div>
</div>
<?php include '_footer.php';?>