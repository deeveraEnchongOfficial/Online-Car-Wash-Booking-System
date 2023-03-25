<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline rounded-0 card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Bookings</h3>
		<?php if($_settings->userdata('type') == 3): ?>
		<div class="card-tools">
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		</div>
		<?php endif; ?>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="20%">
					<col width="20%">
					<col width="15%">
					<col width="10%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Date Updated</th>
						<th>Code</th>
						<th>Schedule</th>
						<th>Vehicle Type</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<?php if($_settings->userdata('type') == 3): ?>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT b.*, v.name as vehicle 
						FROM `booking_list` b 
						INNER JOIN `vehicle_list` v ON b.vehicle_id = v.id 
						WHERE b.user_id = 3
						ORDER BY UNIX_TIMESTAMP(b.date_updated) DESC");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><p class="m-0 truncate-1"><?= date("M d, Y H:i", strtotime($row['date_updated'])) ?></p></td>
							<td><p class="m-0 truncate-1"><?= $row['code'] ?></p></td>
							<td><p class="m-0 truncate-1"><?= date("M d, Y", strtotime($row['schedule'])) ?></p></td>
							<td><p class="m-0 truncate-1"><?= $row['vehicle'] ?></p></td>
							<td class="text-center">
								<?php 
								switch($row['status']){
									case 0:
										echo '<span class="badge badge-default border px-3 rounded-pill">Pending</span>';
										break;
									case 1:
										echo '<span class="badge badge-primary px-3 rounded-pill">Confirmed</span>';
										break;
									case 2:
										echo '<span class="badge badge-warning px-3 rounded-pill">Arrived</span>';
										break;
									case 3:
										echo '<span class="badge badge-info px-3 rounded-pill">On-process</span>';
										break;
									case 4:
										echo '<span class="badge badge-success px-3 rounded-pill">Done</span>';
										break;
									case 5:
										echo '<span class="badge badge-danger px-3 rounded-pill">Cancelled</span>';
										break;
								}
								?>
                            </td>
							<td align="center">
								<a class="btn btn-default bg-gradient-light btn-flat btn-sm" href="?page=bookings/view_details&id=<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
				<?php endif; ?>
				<?php if($_settings->userdata('type') != 3): ?>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT b.*, v.name as vehicle FROM `booking_list` b inner join `vehicle_list` v on b.vehicle_id = v.id order by unix_timestamp(b.date_updated) desc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><p class="m-0 truncate-1"><?= date("M d, Y H:i", strtotime($row['date_updated'])) ?></p></td>
							<td><p class="m-0 truncate-1"><?= $row['code'] ?></p></td>
							<td><p class="m-0 truncate-1"><?= date("M d, Y", strtotime($row['schedule'])) ?></p></td>
							<td><p class="m-0 truncate-1"><?= $row['vehicle'] ?></p></td>
							<td class="text-center">
								<?php 
								switch($row['status']){
									case 0:
										echo '<span class="badge badge-default border px-3 rounded-pill">Pending</span>';
										break;
									case 1:
										echo '<span class="badge badge-primary px-3 rounded-pill">Confirmed</span>';
										break;
									case 2:
										echo '<span class="badge badge-warning px-3 rounded-pill">Arrived</span>';
										break;
									case 3:
										echo '<span class="badge badge-info px-3 rounded-pill">On-process</span>';
										break;
									case 4:
										echo '<span class="badge badge-success px-3 rounded-pill">Done</span>';
										break;
									case 5:
										echo '<span class="badge badge-danger px-3 rounded-pill">Cancelled</span>';
										break;
								}
								?>
                            </td>
							<td align="center">
								<a class="btn btn-default bg-gradient-light btn-flat btn-sm" href="?page=bookings/view_details&id=<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
			<?php endif; ?>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Service permanently?","delete_service",[$(this).attr('data-id')])
		})
		$('#create_new').click(function(){
			uni_modal("<i class='fa fa-plus'></i> Add New Booking","bookings/manage_booking.php")
		})
		$('.view_data').click(function(){
			uni_modal("<i class='fa fa-tags'></i> Service Details","services/view_service.php?id="+$(this).attr('data-id'))
		})
		$('.manage_price').click(function(){
			uni_modal("<i class='fa fa-eye'></i> Manage Service Price List - "+$(this).attr('data-name'),"services/manage_price.php?id="+$(this).attr('data-id'))
		})
		$('.edit_data').click(function(){
			uni_modal("<i class='fa fa-edit'></i> Update Service Details","services/manage_service.php?id="+$(this).attr('data-id'))
		})
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [5] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
	function delete_service($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_service",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>