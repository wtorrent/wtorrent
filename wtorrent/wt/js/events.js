var EventHandler = Class.create({
	/* Initial binding */
	initialize: function() {
	},
	/* Torrent listener */
	bindTorrent: function(torrentHandler, torrentMouseOverHandler, torrentMouseOutHandler) {
		var torrents = $$('.torrent');
		this.torrentClickHandler =  torrentHandler;
		this.torrentMouseOverHandler =  torrentMouseOverHandler;
		this.torrentMouseOutHandler =  torrentMouseOutHandler;
		torrents.each(
			function(e) {
				e.observe('click', this.torrentClickHandler);
				e.observe('mouseover', this.torrentMouseOverHandler);
				e.observe('mouseout', this.torrentMouseOutHandler);
			}.bind(this)
		);
	},
	unbindTorrent: function() {
		var torrents = $$('.torrent');
		torrents.each(
			function(e) {
				e.stopObserving('click', this.torrentClickHandler);
				e.stopObserving('mouseover', this.torrentMouseOverHandler);
				e.stopObserving('mouseout', this.torrentMouseOutHandler);
			}.bind(this)
		);
	},
	reloadTorrents: function(torrentHandler, torrentMouseOverHandler, torrentMouseOutHandler) {
		this.unbindTorrent();
		this.bindTorrent(torrentHandler, torrentMouseOverHandler, torrentMouseOutHandler);
	},
	/* Torrent list buttons Listener */
	bindListButtons: function(torrentListButtons) {
		if($('torentListButtons'))
		{
			this.torrentListButtonsClickHandler =  torrentListButtons;
			$('torentListButtons').observe('click', this.torrentListButtonsClickHandler);
		}
	},
	unbindListButtons: function() {
		if($('torentListButtons'))
		{
			$('torentListButtons').stopObserving('click', this.torrentListButtonsClickHandler);
		}
	},
	reloadListButtons: function(torrentListButtons) {
		this.unbindListButtons();
		this.bindListButtons(torrentListButtons);
	},
	/* Top Tabs listener */
	bindViewTabs: function(viewTabsHandler) {
		this.viewTabsClickHandler =  viewTabsHandler;
		$('tabs').observe('click', this.viewTabsClickHandler);
	},
	unbindViewTabs: function() {
		$('tabs').stopObserving('click', this.viewTabsClickHandler);
	},
	reloadViewTabs: function(viewTabsHandler) {
		this.unbindViewTabs();
		this.bindViewTabs(viewTabsHandler);
	},
	/* Torrent tabs listener */
	bindTorrentTabs: function(torrentTabsHandler) {
		var leftTabs = $$('.tabsLeft');
		this.torrentTabsClickHandler =  torrentTabsHandler;
		leftTabs.each(
			function(e) {
				e.observe('click', this.torrentTabsClickHandler);
			}.bind(this)
		);
	},
	unbindTorrentTabs: function() {
		var leftTabs = $$('.tabsLeft');
		leftTabs.each(
			function(e) {
				e.stopObserving('click', this.torrentTabsClickHandler);
			}.bind(this)
		);
	},
	reloadTorrentTabs: function(torrentTabsHandler) {
		this.unbindTorrentTabs();
		this.bindTorrentTabs(torrentTabsHandler);
	},
	/* Torrent Tab bind */
	bindTorrentTab: function(torrentPriorityHandler, torrentFilesHandler, torrentTrackersHandler)
	{
		this.reloadPriorityTorrent(torrentPriorityHandler);
		this.reloadPriorityFiles(torrentFilesHandler);
		this.reloadEnableTrackers(torrentTrackersHandler);
	},
	/* Torrent Info tab bind */
	bindPriorityTorrent: function(torrentPriorityHandler) {
		var priorityButtons = $$('.priority');
		this.torrentPriorityClickHandler =  torrentPriorityHandler;
		priorityButtons.each(
			function(e) {
				e.observe('click', this.torrentPriorityClickHandler);
			}.bind(this)
		);
	},
	unbindPriorityTorrent: function() {
		var priorityButtons = $$('.priority');
		priorityButtons.each(
			function(e) {
				e.stopObserving('click', this.torrentPriorityClickHandler);
			}.bind(this)
		);
	},
	reloadPriorityTorrent: function(torrentPriorityHandler) {
		this.unbindPriorityTorrent();
		this.bindPriorityTorrent(torrentPriorityHandler);
	},
	/* Torrent Files tab bind */
	bindPriorityFiles: function(torrentFilesHandler) {
		var filesButtons = $$('.filesButtons');
		this.torrentFilesClickHandler =  torrentFilesHandler;
		filesButtons.each(
			function(e) {
				e.observe('click', this.torrentFilesClickHandler);
			}.bind(this)
		);
	},
	unbindPriorityFiles: function() {
		var filesButtons = $$('.filesButtons');
		filesButtons.each(
			function(e) {
				e.stopObserving('click', this.torrentFilesClickHandler);
			}.bind(this)
		);
	},
	reloadPriorityFiles: function(torrentFilesHandler) {
		this.unbindPriorityFiles();
		this.bindPriorityFiles(torrentFilesHandler);
	},
	/* Torrent Trackers tab bind */
	bindEnableTrackers: function(torrentTrackersHandler) {
		var trackersButtons = $$('.trackersButtons');
		this.torrentTrackersClickHandler =  torrentTrackersHandler;
		trackersButtons.each(
			function(e) {
				e.observe('click', this.torrentTrackersClickHandler);
			}.bind(this)
		);
	},
	unbindEnableTrackers: function() {
		var trackersButtons = $$('.trackersButtons');
		trackersButtons.each(
			function(e) {
				e.stopObserving('click', this.torrentTrackersClickHandler);
			}.bind(this)
		);
	},
	reloadEnableTrackers: function(torrentTrackersHandler) {
		this.unbindEnableTrackers();
		this.bindEnableTrackers(torrentTrackersHandler);
	},
	/* Sort buttons bind */
	bindSortButtons: function(sortHandler) {
		if($('listTorrentsHead')) 
		{
			this.sortHandler = sortHandler;
			$('listTorrentsHead').observe('click', this.sortHandler);
		}
	},
	unbindSortButtons: function() {
		if($('listTorrentsHead')) 
		{
			$('listTorrentsHead').stopObserving('click', this.sortHandler);
		}
	},
	reloadSortButtons: function(sortHandler) {
		this.unbindSortButtons();
		this.bindSortButtons(sortHandler);
	},
	/* Reload page bind */
	bindReloadMain: function(reloadMainHandler) {
		this.reloadMainHandler = reloadMainHandler;
		$('refresh').observe('click', this.reloadMainHandler);
	},
	unbindReloadMain: function() {
		$('refresh').stopObserving('click', this.reloadMainHandler);
	},
	reloadReloadMain: function(reloadMainHandler) {
		this.unbindReloadMain();
		this.bindReloadMain(reloadMainHandler);
	}
});