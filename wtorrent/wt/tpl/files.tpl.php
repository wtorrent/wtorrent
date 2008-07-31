{*<div style="width: 100%; font-family: arial; font-size: 11px; padding: 0px 2px 8px 2px;">
                        <b style="color: #4C8CC4;">{$str.files}:</b>
        </div>*}
<form method="post" action="{$SRC_INDEX}?cls={$web->getCls()}&tpl=details&hash={$web->getHash()}">
	<table id="file_list" >
	<tr>
		<th>{$str.file_name}</th>
		<th>{$str.tb_done}</th>
		<th>{$str.tb_size}</th>
		<th>{$str.percent_done}</th>
		<th>{$str.priority}</th>
	</tr>	
	{foreach key=clau item=file from=$web->getFiles()}
	<tr>
                <td class="file_list_first">
                        <input type="checkbox" id="{$clau}" class="files{$web->getHash()}" />&nbsp;&nbsp;{if $details.file_percent.$clau eq 100}<a href="{$ftp}{$ftp_data_dir|utf8_encode}{$file|utf8_encode}">{/if}{$file.name}{if $details.file_percent.$clau eq 100}</a>{/if}
                </td>
		<td>
			{$web->getDone($clau)}
		</td>
		<td>
			{$web->getSize($clau)}
		</td>
		<td>
			{$file.percent}%
		</td>
		<td>
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
	<div class="but_bottom" onclick="command('files', '{$web->getHash()}');"> {$str.change_pr} </div> 
	<div class="but_bottom" onclick="checkAllByClass('files{$web->getHash()}');">{$str.check_all}</div>
	<div class="but_bottom" onclick="uncheckAllByClass('files{$web->getHash()}');">{$str.uncheck_all}</div>
	<div class="but_bottom" onclick="invertAllByClass('files{$web->getHash()}');">{$str.invert_all}</div>
	</div>
	</form>
	
