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
	{assign var=clau value=$web->getHash()|cat:'_'|cat:$clau}
	<tr>
		<td class="file_list_first">
			<input type="checkbox" id="{$clau}" class="files{$web->getHash()}" />&nbsp;&nbsp;
			{if $details.file_percent.$clau eq 100}
				<a href="{$ftp}{$ftp_data_dir|utf8_encode}{$file|utf8_encode}">
			{/if}
			<label for="{$clau}">{$file.name}</label>
			{if $details.file_percent.$clau eq 100}</a>{/if}
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
	<div class="filesButtons" style="height: 20px; text-align: left; margin-left: 10px; margin-top: 3px; height: 40px;"><img src="{$DIR_IMG}arrow_ltr.png" alt="arrow" />
	{foreach from=$web->getPriorities() item="priority" key="prKey" name="priorities"}
		{if $smarty.foreach.priorities.first}
			<select id="sf{$web->getHash()}">
		{/if}
		<option value="{$prKey}">{$priority}</option>
		{if $smarty.foreach.priorities.last}
			</select>
		{/if}
	{/foreach}
	<div class="but_bottom filesPriority"> {$str.change_pr} </div> 
	<div class="but_bottom filesCheckAll"> {$str.check_all} </div>
	<div class="but_bottom filesUncheckAll"> {$str.uncheck_all} </div>
	<div class="but_bottom filesInvertAll"> {$str.invert_all} </div>
	</div>
	</form>
	
