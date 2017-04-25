<?php
	$ostable = '';
	$ctable = '';
	$atable = '';
	$pcinfo = '';
	if(count($data['os']) > 0) 		foreach($data['os'] as $key) 		$ostable .= '<tr><th>'.$key[0].'</th><td>'.$key[1].'</td></tr>';
	if(count($data['country']) > 0) foreach($data['country'] as $key) 	$ctable .= '<tr><th>'.$key[0].'</th><td>'.$key[1].'</td></tr>';
	if(count($data['arc']) > 0) 	foreach($data['arc'] as $key) 		$atable .= '<tr><th>'.$key[0].'</th><td>'.$key[1].'</td></tr>';
	if(!empty($ostable)) $ostable = '<div class="col-lg-6"><h3>Os</h3><table class="table table-striped table-hover">'.$ostable.'</table></div>';
	if(!empty($ctable)) $ctable = '<div class="col-lg-6"><h3>Country</h3><table class="table table-striped table-hover">'.$ctable.'</table></div>';
	if(!empty($atable)) $atable = '<div class="col-lg-6"><h3>Architecture</h3><table class="table table-striped table-hover">'.$atable.'</table></div>';
	$pcinfo = "<div id=\"row\">$ostable$atable</div>";
?>
<div id="general">
	<div class="row">
		<div class="col-lg-6">
			<h3>Reports</h3>
			<table class="table table-striped table-hover">
				<tr>
					<th>Passwords</th>
					<td><?=$data['reports']['passwords'];?></td>
				</tr>
				<tr>
					<th>Cookies</th>
					<td><?=$data['reports']['cookies'];?></td>
				</tr>
				<tr>
					<th>Documents</th>
					<td><?=$data['reports']['docs'];?></td>
				</tr>
				<tr>
					<th>IM Clients</th>
					<td><?=$data['reports']['imcl'];?></td>
				</tr>
				<tr>
					<th>Ftp Clients</th>
					<td><?=$data['reports']['ftpcl'];?></td>
				</tr>
				<tr>
					<th>Wallets</th>
					<td><?=$data['reports']['wallets'];?></td>
				</tr>
				<tr>
					<th>Rdp</th>
					<td><?=$data['reports']['rdp'];?></td>
				</tr>
				<tr>
					<th>Mail Clients</th>
					<td><?=$data['reports']['mail'];?></td>
				</tr>
			</table>
		</div>
		<?=$ctable;?>
	</div>
	<?=$pcinfo;?>
</div>