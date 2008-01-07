{*<div style="width: 100%; font-family: arial; font-size: 11px; padding: 0px 2px 8px 2px;">
                        <b style="color: #4C8CC4;">Files:</b>
        </div>*}
<form method="POST" action="{$SRC_INDEX}?cls={$web->getCls()}&tpl=details&hash={$web->getHash()}">
	<table style="border-collapse: collapse; width: 100%; margin-left: auto; margin-right: auto; border-bottom: 1px solid #d4d4d4;">
	<tr style="background-color: #F1F3F5; border-bottom: 1px solid #d4d4d4;">
		<td style="padding: 5px; font-family: arial; font-size: 11px; font-weight: bold;">File Name</td>
		<td style="width: 35px; padding: 5px; font-family: arial; font-size: 11px; font-weight: bold; text-align: center;">Done</td>
		<td style="width: 35px; padding: 5px; font-family: arial; font-size: 11px; font-weight: bold; text-align: center;">Size</td>
		<td style="width: 50px; padding: 5px; font-family: arial; font-size: 11px; font-weight: bold; text-align: center;">% Done</td>
		<td style="width: 35px; padding: 5px; font-family: arial; font-size: 11px; font-weight: bold; text-align: center;">Priority</td>
	</tr>	
	{foreach key=clau item=file from=$web->getFiles()}
	<tr>
                <td style="font-family: arial; font-size: 11px; padding: 2px 5px 2px 10px; text-align: left;">
                        <input type="checkbox" id="{$clau}" class="files{$web->getHash()}" />&nbsp;&nbsp;{if $details.file_percent.$clau eq 100}<a href="{$ftp}{$ftp_data_dir|utf8_encode}{$file|utf8_encode}">{/if}{$file.name|decode}{if $details.file_percent.$clau eq 100}</a>{/if}
                </td>
		<td style="padding: 2px 5px 2px 5px; font-family: arial; font-size: 11px; text-align: center;">
			{$web->getDone($clau)}
		</td>
		<td style="padding: 2px 5px 2px 5px; font-family: arial; font-size: 11px; text-align: center;">
			{$web->getSize($clau)}
		</td>
        <td style="padding: 2px 5px 2px 5px; font-family: arial; font-size: 11px; text-align: center;">
			{$file.percent}%
		</td>
		<td style="padding: 2px 5px 2px 5px; font-family: arial; font-size: 11px; text-align: center;">
			{$web->getPriorityStr($clau)}
		</td>
        </tr>
	{/foreach}
	</table>
	<div style="height: 20px; text-align: left; margin-left: 10px; margin-top: 3px; height: 40px;"><img src="{$DIR_IMG}arrow_ltr.png" alt="arrow" />
	{foreach from=$web->getPriorities() item="priority" key="prKey" name="priorities"}
		{if $smarty.foreach.priorities.first}
			<select id="sf{$web->getHash()}">
		{/if}
		<option value="{$prKey}">{$priority}</option>
		{if $smarty.foreach.priorities.last}
			</select>
		{/if}
	{/foreach}
	<div class="but_files" id="{$web->getHash()}"> {$str.change_pr} </div> 
	<div class="but_fcheck" id="{$web->getHash()}">{$str.check_all}</div>
	<div class="but_funcheck" id="{$web->getHash()}">{$str.uncheck_all}</div>
	</div>
	</form>
	