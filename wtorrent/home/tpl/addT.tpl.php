	<form enctype="multipart/form-data" action="{$SRC_INDEX}?cls=AddT" method="POST">
	<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
	<div style="width: 100%; height: 180px;">
		<div style="width: 100%; height: 40px; padding-left: 20px; text-align: left;">
			<div style="width: 150px; float: left;font-size: 12px; font-weight: bold; margin-top: 3px;">Torrent:</div>
			<div style="width: 500px; float: left; text-align: left;"><input name="uploadedfile" type="file" size="60" /></div>
		</div>
		<div style="width: 100%; height: 40px; padding-left: 20px; text-align: left;">
			<div style="width: 150px; float: left;font-size: 12px; font-weight: bold; margin-top: 3px;">Torrent url:</div>
			<div style="width: 500px; float: left; text-align: left;"><input name="torrenturl" type="text" size="60" /></div>
		</div>
		<div style="width: 100%; height: 40px; padding-left: 20px; text-align: left;">
			<div style="width: 150px; float: left;font-size: 12px; font-weight: bold; margin-top: 3px;">Download directory:</div>
            {assign var='dir' value=$web->getDir()}
            {if !empty($dir)}{assign var='DIR_DOWNLOAD' value=$dir}{/if}
			<div style="width: 600px; float: left; text-align: left;"><input{if $web->getForceDir() eq 1} readonly="readonly"{/if} name="download_dir" type="text" size="60" value="{$DIR_DOWNLOAD}" /> <span style="margin-left: 5px;">({$str.info_add_upload})</span></div>
		</div>
		<div style="width: 100%; height: 40px; padding-left: 20px; text-align: left;">
			<div style="width: 150px; float: left;font-size: 12px; font-weight: bold; margin-top: 3px;">{$str.start_now}</div>
			<div style="width: 600px; float: left; text-align: left;"><input name="start_now" value="on" type="checkbox" /></div>
		</div>
		<div style="width: 100%; height: 40px; padding-left: 20px; text-align: left;">
			<div style="width: 150px; float: left;font-size: 12px; font-weight: bold; margin-top: 3px;">{$str.private}</div>
			<div style="width: 600px; float: left; text-align: left;"><input name="private" value="on" type="checkbox" /></div>
		</div>
	</div>
	<div style="height: 30px;">
		<input type="submit" value="Upload File" name="upload_torrent" />
	</div>
	</form>