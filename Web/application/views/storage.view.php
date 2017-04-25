<?php
	$cont = '';
	foreach($data['body'] as $olly)
	{
		$download = ($olly[2] >= 2 && $olly[2] <= 4 || $olly[2] == 0 && mb_substr(str_replace('Folder (', '', $olly[1]), 0, -1) == 0 ? ' disabled' : '');
		$cont .= '<tr><td>'.$olly[0].'</td><td>'.$olly[1].'</td><td>'.$olly[3].'</td><td>
			<button act="open" path="'.$olly[0].'" type="'.$olly[2].'" class="btn btn-default btn-xs"'.($olly[2] == 1 ? ' disabled' : '').'>O</button>
			<button act="download" path="'.$olly[0].'" type="'.$olly[2].'" class="btn btn-default btn-xs"'.$download.'>D</button>
			<button act="remove" path="'.$olly[0].'" type="'.$olly[2].'" class="btn btn-default btn-xs">R</button>
		</td></tr>';
	}
	if(empty($cont))
		$cont = '<tr><td colspan="3">This folder is empty</td></td>';
	$pgslist = '';
	$pgid = '';
	if($data['pcount'] > 1)
	{
		$pgid = "<h3 style=\"float: right;\" name=\"location\">Page №".$data['pageid']."</h3>";
		$from = $data['pageid'] - 5;
		$from = $from < 0 ? 0 : $from + 1;
		$to = $from + 10;
		for($i=$from;$i<$to;$i++)
		{
			if($i >= $data['pcount']) break;
			if($data['pageid'] == $i) $opacity = '0.8';
			else $opacity = '1';
			$pgslist .= '<button style="margin-top: 15px;opacity: '.$opacity.'" page="'.$i.'" class="btn btn-default btn-xs">'.$i.'</button> ';
		}
	}
?>
<script>
var Base64 =
{
	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
	encode : function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;
		input = Base64._utf8_encode(input);
		while (i < input.length) {
			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);
			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;
			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}
			output = output +
			this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
			this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
		}
		return output;
	},
	decode : function (input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;
		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
		while (i < input.length) {
 
			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));
			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;
			output = output + String.fromCharCode(chr1);
			if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			}
		}
		output = Base64._utf8_decode(output);
		return output;
	},
	_utf8_encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";
 
		for (var n = 0; n < string.length; n++) {
 
			var c = string.charCodeAt(n);
 
			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}
		}
		return utftext;
	},
	_utf8_decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
		while ( i < utftext.length )
		{
			c = utftext.charCodeAt(i);
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else
			{
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
		}
		return string;
	}
}
</script>
<script>
	$(document).ready(function()
	{
		var regExp = /<script\b[^>]*>([\s\S]*?)<\/script>/gm;
		var location = '';
		var pageid = '0';
		$(document).on('click', '[name="back"]', function()
		{
			if(location.length == 0) return false;
			var idx = location.lastIndexOf('\\');
			if(idx + 1 == location.length)
				location = location.substr(0, location.length-1);
			idx = location.lastIndexOf('\\');
			location = location.substr(0, location.length - (location.length - idx) + 1);
			$.post('?p1=storage',
			{
				path: location
			}, function(data)
			{
				data = data.replace(regExp,"");
				data = $(data);
				$("#general").html(data.find("#general").html());
			});
		});
		$(document).on('click', '[name="home"]', function()
		{
			location = '';
			pageid = 0;
			$('[name="refresh"]').click();
		});
		$(document).on('click', '[name="refresh"]', function()
		{
			if(pageid > <?=$data['pcount'];?>) pageid = 0;
			$.post('?p1=storage',
			{
				path: location,
				page: pageid
			}, function(data)
			{
				data = data.replace(regExp,"");
				data = $(data);
				$("#general").html(data.find("#general").html());
			});
		});
		$(document).on('click', '[page]', function()
		{
			var page = $(this).attr('page');
			$.post('?p1=storage',
			{
				path: location,
				page: page
			}, function(data)
			{
				pageid = page;
				data = data.replace(regExp,"");
				data = $(data);
				$("#general").html(data.find("#general").html());
			});
		});
		$(document).on('click', '[act][type][path]', function()
		{
			var path = $(this).attr('path');
			var type = $(this).attr('type');
			var act = $(this).attr('act');
			if(act == 'open')
			{
				if(type == 0)
				{
					location += path + '\\';
					$.post('?p1=storage',
					{
						path: location
					}, function(data)
					{
						data = data.replace(regExp,"");
						data = $(data);
						$("#general").html(data.find("#general").html());
					});
				}
				else if(type == 4)
				{
					$('[name="boxTitle"]').text('Picture');
					$('[name="boxContent"]').html("<center><a href='?p1=storage&p2=get&p3=4&p4=" + window.btoa(encodeURIComponent(location + path)) + "' target='_blank'><img class='imgd' src='/storage/get/4/" + window.btoa(encodeURIComponent(location + path)) + "'></a></center>");
					$('[name="infoBox"]').modal('show');
				}
				else if(type == 1)
				{
					window.location = '?p1=storage&p2=get&p3=1&p4='+window.btoa(encodeURIComponent(location + path));
				}
				else if(type == 2)
				{
					$.get('?p1=storage&p2=get&p3=2&p4=' + window.btoa(encodeURIComponent(location + path)), function(data)
					{
						$('[name="boxTitle"]').text('Passwords');
						$('[name="boxContent"]').html(data);
						$('[name="infoBox"]').modal('show');
					})
				}
				else if(type == 3)
				{
					$.get('?p1=storage&p2=get&p3=3&p4=' + window.btoa(encodeURIComponent(location + path)), function(data)
					{
						$('[name="boxTitle"]').text('Data');
						$('[name="boxContent"]').html(data);
						$('[name="infoBox"]').modal('show');
					})
				}
			}
			else if(act == "download")
			{
				if(type == 0)
				{
					window.location = '?p1=storage&p2=get&p3=0&p4='+window.btoa(escape(location + path));
				}
				else if(type == 1)
				{
					window.location = '?p1=storage&p2=get&p3=1&p4='+window.btoa(escape(location + path));
				}
			}
			else if(act == "remove")
			{
				$.post('?p1=storage&p2=rem',
				{
					path: location + path,
					type: type
				}, function(data)
				{
					$('[name="refresh"]').click();
				});
			}
		});
	});
</script>
<div id="general">
	<button style="margin-top: 15px;" name="home" class="btn btn-default btn-xs">Home</button>
	<button style="margin-top: 15px;" name="back" class="btn btn-default btn-xs">Back</button>
	<button style="margin-top: 15px;" name="refresh" class="btn btn-default btn-xs">Refresh</button>
	<div style="float: right">
		<?=$pgslist;?>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<?=$pgid;?>
			<h3 name='pageid'>Storage\<?=$data['path'];?></h3>
			<table class="table table-striped table-hover cntr">
				<tr>
					<th>Name</th>
					<th>Type</th>
					<th>Modified At</th>
					<th></th>
				</tr>
				<?=$cont;?>
			</table>
		</div>
	</div>
</div>
<div class="modal fade" name="infoBox" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" name="boxTitle"></h4>
			</div>
			<div class="modal-body" name="boxContent" style="overflow-y: scroll;">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>