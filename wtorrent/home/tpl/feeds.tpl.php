{if $web->isView() eq false}
		<div style="border-bottom: 2px solid #85a5c0; color: #85a5c0; font-size: 14px; font-weight: bold; text-align: left; margin-left: 10px; padding-left: 20px; margin-right: 10px; padding-bottom: 5px;margin-bottom: 10px;">{$str.tl_feeds}</div>
		{foreach from=$web->getFeeds() item=feed name=feeds}
		{if $smarty.foreach.feeds.first}
		{*<table style="border: 1px solid #d4d4d4; border-collapse: collapse; width: 850px; margin: 20px auto; margin-top: 10px;">
			<tr style="background-color: #d7e4ef; font-size: 11px; font-weight: bold; border-bottom: 1px solid #d4d4d4;">
				<td style="padding: 5px;">{$str.title_feeds}</td>
				<td style="padding: 5px;">{$str.desc_feeds}</td>
				<td style="padding: 5px;">&nbsp;</td>
			</tr>*}
			<div style="width: 850px; height: 24px; margin: 0px auto; font-weight: bold; margin-bottom: 0px; background-color: #d7e4ef; border: 1px solid #d4d4d4; font-size: 11px;">
        		<div style="text-align: center; padding: 5px; padding-left: 30px; font-weight: bold; width: 310px; float: left;">{$str.title_feeds}</div>
        		<div style="text-align: center; padding: 5px; font-weight: bold; width: 290px; float: left;">{$str.desc_feeds}</div>
        		<div style="padding: 5px; font-weight: bold; width: 190px; float: left;"> </div>
        	</div>
			{/if}
			{*<tr>
				<td style="padding: 5px;">{$feed.title|truncate:50}</td>
				<td style="padding: 5px;">{$feed.description|truncate:50}</td>
				<td style="padding: 5px; padding-top: 0px;"><a href="{$SRC_INDEX}?cls={$web->getCls()}&view={$feed.id}" title="{$str.view_feed}"><img src="{$DIR_IMG}feed_go.png" alt="{$str.view_feed}" /></a>&nbsp;&nbsp;<a href="{$SRC_INDEX}?cls={$web->getCls()}&amp;edit={$feed.id}" title="{$str.edit_feed}"><img src="{$DIR_IMG}feed_edit.png" alt="{$str.edit_feed}" /></a>&nbsp;&nbsp;<a href="{$SRC_INDEX}?cls={$web->getCls()}&amp;erase={$feed.id}" title="{$str.erase_feed}"><img src="{$DIR_IMG}feed_delete.png" alt="{$str.erase_feed}" /></a></td>
			</tr>*}
			<div style="width: 850px; height: 24px; margin: 0px auto; margin-bottom: 0px; border: 1px solid #d4d4d4; border-width: 0px 1px 0px 1px; font-size: 11px;">
        		<div style="text-align: center; padding: 5px; padding-left: 30px; width: 310px; float: left;">{$feed.title|truncate:50}</div>
        		<div style="text-align: center; padding: 5px; width: 290px; float: left;">{$feed.description|truncate:50}</div>
        		<div style=" text-align: center; padding: 5px; width: 190px; float: left;"><a href="{$SRC_INDEX}?cls={$web->getCls()}&amp;view={$feed.id}" title="{$str.view_feed}"><img src="{$DIR_IMG}feed_go.png" alt="{$str.view_feed}" /></a>&nbsp;&nbsp;<a href="{$SRC_INDEX}?cls={$web->getCls()}&amp;edit={$feed.id}" title="{$str.edit_feed}"><img src="{$DIR_IMG}feed_edit.png" alt="{$str.edit_feed}" /></a>&nbsp;&nbsp;<a href="{$SRC_INDEX}?cls={$web->getCls()}&amp;erase={$feed.id}" title="{$str.erase_feed}"><img src="{$DIR_IMG}feed_delete.png" alt="{$str.erase_feed}" /></a></div>
        	</div>
			{if $smarty.foreach.feeds.last}
			<div style="width: 850px; height: 5px; margin: 0px auto; margin-bottom: 10px; border: 1px solid #d4d4d4; border-width: 0px 1px 1px 1px;">
        	</div>
			{*</table>*}
		{/if}
		{foreachelse}
        	<div style="height: 30px; width: 100%; text-align: center; font-style: italic; font-size: 12px;">{$str.no_feeds}</div>
        {/foreach}
		<div style="border-bottom: 2px solid #85a5c0; color: #85a5c0; font-size: 14px; font-weight: bold; text-align: left; margin-left: 10px; padding-left: 20px; margin-right: 10px; padding-bottom: 5px;margin-bottom: 10px;">{$str.tl_feed_add}</div>
		<div style="text-align: left; padding-left: 30px; padding-bottom: 15px; padding-top: 5px; font-size: 14px;"><form method="post" action="{$SRC_INDEX}?cls={$web->getCls()}"><div class="">{$str.feed_url}: <input type="text" name="feed_url" size="60" />&nbsp;&nbsp;<input type="submit" name="feed_add" value="{$str.feed_add}" /></div></form></div>
{else}
	{assign value=$web->getFeed() var=feed}
	<div style="border-bottom: 2px solid #85a5c0; color: #85a5c0; font-size: 14px; font-weight: bold; text-align: left; margin-left: 10px; padding-left: 20px; margin-right: 10px; padding-bottom: 5px;margin-bottom: 10px;">{$feed->get_title()}</div>
	{foreach from=$feed->get_items() item=torrent name=torrents}
		{if $smarty.foreach.torrents.first}
		{*<table style="border: 1px solid #d4d4d4; border-collapse: collapse; width: 850px; margin: 20px auto; margin-top: 10px;">
			<tr style="background-color: #d7e4ef; font-size: 11px; font-weight: bold; border-bottom: 1px solid #d4d4d4;">
				<td style="padding: 5px; width: 20px;">&nbsp;</td>
				<td style="padding: 5px;">{$str.feed_torrent}</td>
				<td style="padding: 5px;">{$str.feed_date}</td>
			</tr>*}
			<div style="width: 850px; height: 24px; margin: 0px auto; font-weight: bold; margin-bottom: 0px; background-color: #d7e4ef; border: 1px solid #d4d4d4; font-size: 11px;">
        		<div style="text-align: center; padding: 5px; padding-left: 30px; font-weight: bold; width: 510px; float: left;">{$str.feed_torrent}</div>
        		<div style="text-align: center; padding: 5px; font-weight: bold; width: 290px; float: left;">{$str.feed_date}</div>
        	</div>
			{/if}
			{*<tr>
				<td style="padding: 5px;"><a href="{$SRC_INDEX}?cls={$web->getCls()}&amp;download={$torrent->get_link()}&amp;view={$web->getView()}" title="{$str.feed_down_tor}"><img src="{$DIR_IMG}bullet_go.png" alt="{$str.feed_down_tor}" /></a></td>
				<td style="padding: 5px; text-align: left;"><a href="{$torrent->get_link()}">{$torrent->get_title()}</a></td>
				<td style="padding: 5px;">{$torrent->get_date('j F Y | g:i a')}</td>
			</tr>*}
			<div style="width: 850px; height: 24px; margin: 0px auto; margin-bottom: 0px; border: 1px solid #d4d4d4; border-width: 0px 1px 0px 1px; font-size: 11px;">
        		<div style="tex-talign: center; padding: 3px 0px 0px 0px; width: 30px; float: left;"><a href="{$SRC_INDEX}?cls={$web->getCls()}&amp;download={$torrent->get_link()}&amp;view={$web->getView()}" title="{$str.feed_down_tor}"><img src="{$DIR_IMG}bullet_go.png" alt="{$str.feed_down_tor}" /></a></div>
				<div style="text-align: left; padding: 5px; width: 510px; float: left;"><a href="{$torrent->get_link()}">{$torrent->get_title()}</a></div>
        		<div style="text-align: center; padding: 5px; width: 290px; float: left;">{$torrent->get_date('j F Y | g:i a')}</div>
        	</div>
			{if $smarty.foreach.torrents.last}
			<div style="width: 850px; height: 2px; margin: 0px auto; margin-bottom: 10px; border: 1px solid #d4d4d4; border-width: 0px 1px 1px 1px;">
        	</div>
			{*</table>*}
		{/if}
		{foreachelse}
        	<div style="height: 30px; width: 100%; text-align: center; font-style: italic; font-size: 12px;">{$str.no_torrents}</div>
        {/foreach}
        <div style="border-bottom: 2px solid #85a5c0; color: #85a5c0; font-size: 14px; font-weight: bold; text-align: left; margin-left: 10px; padding-left: 20px; margin-right: 10px; padding-bottom: 5px;margin-bottom: 10px;">{$str.down_dir}</div>
		<div style="text-align: left; padding-left: 30px; padding-bottom: 15px; padding-top: 5px; font-size: 14px;"><form method="post" action="{$SRC_INDEX}?cls={$web->getCls()}&amp;view={$web->getView()}"><div>{$str.down_dir}: <input type="text" name="down_dir" size="60" value="{$web->getDownDir()}" />&nbsp;&nbsp;<input type="submit" name="ch_dir" value="{$str.download_dir}" /> {$str.info_add_upload}</div></form></div>
{/if}
