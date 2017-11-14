<?php $this->load->view('online_store/inc_header');?>
<style>
	.access-table{
		max-height: 610px;
		overflow-x: auto;
	}
</style>

	<!-- slider -->
			<div id="main-slider" class="slider">
	    		 <div><img src="images/slider/img4.jpg" title="" /></div>
			</div>	
	<!-- /slider -->

<!-- content -->
<div class="content">
<!--new-->
    	<div class="row_rates_table pinless_div">
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-success access-table">
					<div class="panel-heading">
						<h3 class="panel-title">Access Numbers</h3>
						<!--<div class="pull-right">
							<span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
								<i class="glyphicon glyphicon-filter"></i>
							</span>
						</div>-->
					</div>
					<div class="panel-body ">
						<input type="text" class="form-control search_string " id="task-table-filter" data-action="filter" data-filters="#task-table" placeholder="Search Access Number" />
					</div>
					<table class="table table-hover" id="task-table">
						
						<thead>
						  <tr>
						  	<th>#</th>
							<th>Access Number</th>
							<th>City</th>
							<th>State</th>
							<th>Language</th>
						  </tr>
						</thead>
						<tbody>
							<?php if(!empty($results)){
								$i=1;
								foreach($results as $res){?>
						  <tr>
						  	<td><?php echo $i; ?></td>
							<td><?php echo $res->AccessNumber; ?></td>
							<td><?php echo $res->State; ?></td>
							<td><?php echo $res->City; ?></td>
							<td><?php echo $res->access_lang; ?></td>
						  </tr>
						  <?php 
						  $i++;
								}
								
							}?>
						</tbody>
					
					
					</table>
				</div>
			</div>
			<div class="col-md-6">
			<div>
				<p><a href="#"><img src="<?php echo base_url();?>img/no-voip.png" /></a></p>
				
			</div>
			</div>
		</div>
		</div>
	
<!--new ends-->
</div>
<?php $this->load->view('online_store/inc_footer');?>

<script type="text/javascript">
/**
*   I don't recommend using this plugin on large tables, I just wrote it to make the demo useable. It will work fine for smaller tables 
*   but will likely encounter performance issues on larger tables.
*
*		<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Developers" />
*		$(input-element).filterTable()
*		
*	The important attributes are 'data-action="filter"' and 'data-filters="#table-selector"'
*/
(function(){
    'use strict';
	var $ = jQuery;
	$.fn.extend({
		filterTable: function(){
			return this.each(function(){
				$(this).on('keyup', function(e){
					$('.filterTable_no_results').remove();
					var $this = $(this), 
                        search = $this.val().toLowerCase(), 
                        target = $this.attr('data-filters'), 
                        $target = $(target), 
                        $rows = $target.find('tbody tr');
                        
					if(search == '') {
						$rows.show(); 
					} else {
						$rows.each(function(){
							var $this = $(this);
							$this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();
						})
						if($target.find('tbody tr:visible').size() === 0) {
							var col_count = $target.find('tr').first().find('td').size();
							var no_results = $('<tr class="filterTable_no_results"><td colspan="'+col_count+'">No results found</td></tr>')
							$target.find('tbody').append(no_results);
						}
					}
				});
			});
		}
	});
	$('[data-action="filter"]').filterTable();
})(jQuery);

$(function(){
    // attach table filter plugin to inputs
	$('[data-action="filter"]').filterTable();
	
	$('.container').on('click', '.panel-heading span.filter', function(e){
		var $this = $(this), 
			$panel = $this.parents('.panel');
		
		$panel.find('.panel-body').slideToggle();
		if($this.css('display') != 'none') {
			$panel.find('.panel-body input').focus();
		}
	});
	$('[data-toggle="tooltip"]').tooltip();
})
</script>

