{if $web->isView() eq false}
		<div style="border-bottom: 2px solid #85a5c0; color: #85a5c0; font-size: 14px; font-weight: bold; text-align: left; margin-left: 10px; padding-left: 20px; margin-right: 10px; padding-bottom: 5px;margin-bottom: 10px;">{$str.tl_feeds}</div>
		{foreach from=$web->getFeeds() item=feed name=feeds}
			{if $smarty.foreach.feeds.first}
				<table style="width:86%; margin: 2em; margin-left: auto; margin-right: auto; border-collapse: collapse; text-align: left;">
				<tr style="background: #d7e4ef; border: 1px solid #d4d4d4; margin-bottom: 1ex;">
					<th>{$str.title_feeds}</th>
					<th colspan="2">{$str.desc_feeds}</th>
				</tr>
			{/if}
			<tr style="font-size: medium; height:3em;">
				<td style=""><a href="{$SRC_INDEX}?cls={$web->getCls()}&amp;action=viewFeed&amp;feed_id={$feed.id}" title="{$str.view.feed}">{$feed.title|truncate:50}</a></td>
				<td>{$feed.description|truncate:50}</td>
				<td>
					&nbsp;&nbsp;<a href="{$SRC_INDEX}?cls={$web->getCls()}&amp;action=edit&amp;feed_id={$feed.id}" title="{$str.edit_feed}"><img src="{$DIR_IMG}feed_edit.png" alt="{$str.edit_feed}" /></a>
					&nbsp;&nbsp;<a href="{$SRC_INDEX}?cls={$web->getCls()}&amp;action=erase&amp;feed_id={$feed.id}" title="{$str.erase_feed}"><img src="{$DIR_IMG}feed_delete.png" alt="{$str.erase_feed}" /></a>
				</td>
			</tr>
			{foreach from=$feed.news item=newitem name=news}
				<tr style="font-size: smaller;">
					<td style="padding-left: 3ex;" colspan="2">
					{if time() - $newitem->get_date('U') lte 172800}
						<span style="font-weight: bold;">
					{/if}
						<a href="{$SRC_INDEX}?cls={$web->getCls()}&amp;action=download&amp;uri={$newitem->get_link()}&amp;view={$web->getView()}" title="{$str.feed_down_tor}">{$newitem->get_title()|escape:'html'}</a>
					{if time() - $newitem->get_date('U') lte 172800}
						</span>
						</td><td style="color: green; font-weight: bold">
					{else}
						</td><td>
					{/if}
						{$newitem->get_local_date()}
					</td>
				</tr>
			{/foreach}
			{*<div style="width: 850px; height: 24px; margin: 0px auto; margin-bottom: 0px; border: 1px solid #d4d4d4; border-width: 0px 1px 0px 1px; font-size: 11px;">
				<div style="text-align: center; padding: 5px; padding-left: 30px; width: 310px; float: left;">
					<a href="{$SRC_INDEX}?cls={$web->getCls()}&amp;action=viewFeed&amp;feed_id={$feed.id}" title="{$str.view_feed}">
						{$feed.title|truncate:50}
					</a>
				</div>
        		<div style="text-align: center; padding: 5px; width: 290px; float: left;">{$feed.description|truncate:50}</div>
				<div style=" text-align: center; padding: 5px; width: 190px; float: left;">
					&nbsp;&nbsp;<a href="{$SRC_INDEX}?cls={$web->getCls()}&amp;action=edit&amp;feed_id={$feed.id}" title="{$str.edit_feed}"><img src="{$DIR_IMG}feed_edit.png" alt="{$str.edit_feed}" /></a>
					&nbsp;&nbsp;<a href="{$SRC_INDEX}?cls={$web->getCls()}&amp;action=erase&amp;feed_id={$feed.id}" title="{$str.erase_feed}"><img src="{$DIR_IMG}feed_delete.png" alt="{$str.erase_feed}" /></a>
				</div>
        	</div>*}
			{if $smarty.foreach.feeds.last}
				</table>
				{*<div style="width: 850px; height: 5px; margin: 0px auto; margin-bottom: 10px; border: 1px solid #d4d4d4; border-width: 0px 1px 1px 1px;">*}
				</div>
			{/if}
		{foreachelse}
        	<div style="height: 30px; width: 100%; text-align: center; font-style: italic; font-size: 12px;">{$str.no_feeds}</div>
        {/foreach}
		<div style="border-bottom: 2px solid #85a5c0; color: #85a5c0; font-size: 14px; font-weight: bold; text-align: left; margin-left: 10px; padding-left: 20px; margin-right: 10px; padding-bottom: 5px;margin-bottom: 10px;">{$str.tl_feed_add}</div>
		<div style="text-align: left; padding-left: 30px; padding-bottom: 15px; padding-top: 5px; font-size: 14px;">
			<form method="post" action="{$SRC_INDEX}?cls={$web->getCls()}">
				<div class="">
				<input type="hidden" name="action" value="add"/>
					<label for="feed_url">{$str.feed_url}</label>: <input type="text" id="feed_url" name="feed_url" size="60" />
					&nbsp;&nbsp;<label for="feed_title">{$str.feed_title}</label>: <input type="text" id="feed_title" name="feed_title" />
					&nbsp;&nbsp;<input type="submit" value="{$str.feed_add}" />
				</div>
			</form>

{else}
	{assign value=$web->getFeed() var=feed}
	<div style="border-bottom: 2px solid #85a5c0; color: #85a5c0; font-size: 14px; font-weight: bold; text-align: left; margin-left: 10px; padding-left: 20px; margin-right: 10px; padding-bottom: 5px;margin-bottom: 10px;">{$web->getFeedTitle()} - <small>{$web->getFeedUrl()}</small></div>
	{foreach from=$feed->get_items() item=torrent name=torrents}
		{if $smarty.foreach.torrents.first}
		<div style="width: 850px; height: 24px; margin: 0px auto; font-weight: bold; margin-bottom: 0px; background-color: #d7e4ef; border: 1px solid #d4d4d4; font-size: 11px;">
        		<div style="text-align: center; padding: 5px; padding-left: 30px; font-weight: bold; width: 510px; float: left;">{$str.feed_torrent}</div>
        		<div style="text-align: center; padding: 5px; font-weight: bold; width: 290px; float: left;">{$str.feed_date}</div>
        	</div>
			{/if}
		<div style="width: 850px; height: 24px; margin: 0px auto; margin-bottom: 0px; border: 1px solid #d4d4d4; border-width: 0px 1px 0px 1px; font-size: 11px;">
        		<div style="tex-talign: center; padding: 3px 0px 0px 0px; width: 30px; float: left;"><a href="{$SRC_INDEX}?cls={$web->getCls()}&amp;action=download&amp;uri={$torrent->get_link()}&amp;view={$web->getView()}" title="{$str.feed_down_tor}"><img src="{$DIR_IMG}bullet_go.png" alt="{$str.feed_down_tor}" /></a></div>
				<div style="text-align: left; padding: 5px; width: 510px; float: left;"><a href="{$torrent->get_link()}">{$torrent->get_title()|truncate|escape:'html'}</a></div>
        		<div style="text-align: center; padding: 5px; width: 290px; float: left;">{$torrent->get_date('j F Y | g:i a')}</div>
        	</div>
			{if $smarty.foreach.torrents.last}
			<div style="width: 850px; height: 2px; margin: 0px auto; margin-bottom: 10px; border: 1px solid #d4d4d4; border-width: 0px 1px 1px 1px;">
        	</div>
		{/if}
		{foreachelse}
        	<div style="height: 30px; width: 100%; text-align: center; font-style: italic; font-size: 12px;">{$str.no_torrents}</div>
        {/foreach}
        <div style="border-bottom: 2px solid #85a5c0; color: #85a5c0; font-size: 14px; font-weight: bold; text-align: left; margin-left: 10px; padding-left: 20px; margin-right: 10px; padding-bottom: 5px;margin-bottom: 10px;">{$str.down_dir}</div>
		<div style="text-align: left; padding-left: 30px; padding-bottom: 15px; padding-top: 5px; font-size: 14px;"><form method="post" action="{$SRC_INDEX}?cls={$web->getCls()}&amp;view={$web->getView()}"><div>{$str.down_dir}: <input type="text" name="down_dir" size="60" value="{$web->getDownDir()}" />&nbsp;&nbsp;<input type="submit" name="ch_dir" value="{$str.download_dir}" /> {$str.info_add_upload}</div></form></div>
{/if}
