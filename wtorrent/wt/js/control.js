/* Controller of the app */
var Control = Class.create({
	/* Init other objects */
	initialize: function(effects) {
		this.display = new DisplayActions(effects);
		this.events = new EventHandler();
		this.ajax = new AjaxHandler('index.php', this.display, {loadingMain: 'loadingMain', loadingMessages: 'loadingMessages', loadingTorrent: 'loadingTorrent'});
		/* Add all events listeners */
		/* Torrent event listening */
		this.events.bindTorrent(this.torrentHandler.bindAsEventListener(this), this.torrentMouseOverHandler.bindAsEventListener(this), this.torrentMouseOutHandler.bindAsEventListener(this));
		/* Top tabs listener */
		this.events.reloadViewTabs(this.viewTabsHandler.bindAsEventListener(this));
		/* Left torrent tabs listener */
		this.events.bindTorrentTabs(this.torrentTabsHandler.bindAsEventListener(this));
		/* Torrent List buttons listener */
		this.events.bindListButtons(this.torrentListButtons.bindAsEventListener(this));
		/* Torrent change priority listener */
		this.events.bindPriorityTorrent(this.torrentPriorityHandler.bindAsEventListener(this));
		/* Sort buttons */
		this.events.bindSortButtons(this.torrentSort.bindAsEventListener(this));
		/* Reload Main */
		this.events.reloadReloadMain(this.ajax.reloadMain.bindAsEventListener(this.ajax));
	},
	/* Event handlers */
	/* Torrent event handler */
	torrentHandler: function(e) {
		var el = e.element();
		if(el.hasClassName('start') || el.hasClassName('stop') || el.hasClassName('close') || el.hasClassName('erase') || el.hasClassName('chash'))
		{
			/* The click has been on a button, pass the call to the button handler */
			this.buttonHandler(e);
		} else if(!el.hasClassName('torrentCheckbox')) {
			/* Get the id (hash) of the torrent */
			var id = el.up('.torrent').identify();
			if($('tab' + id).innerHTML == "") {
				/* The tab is empty, prepare content to load */
				var showLoad = this.ajax.showLoadTorrent.bind(this.ajax, id);
				var afterFinish = this.events.reloadPriorityTorrent.bind(this.events, this.torrentPriorityHandler.bindAsEventListener(this));
				var showResponse = this.ajax.showResponseTorrent.bind(this.ajax, id, afterFinish);
				var url = 'cls=General&tpl=details&hash=' + id;
				/* Do the call */
				this.ajax.load('tab' + id, url, showLoad, showResponse);
			} else {
				/* toggle tab, since there's already loaded content in it */
				this.display.toggleTorrent(id);
			}
		}
	},
	/* Mouse over, change background color */
	torrentMouseOverHandler: function(e) {
		var el = e.element();
		if(!el.hasClassName('torrent'))
		{
			el = el.up('.torrent');
		}
		el.addClassName('active');
	},
	/* Mouse out,change backgorund color */
	torrentMouseOutHandler: function(e) {
		var el = e.element();
		if(!el.hasClassName('torrent'))
		{
			el = el.up('.torrent');
		}
		el.removeClassName('active');
	},
	/* Torrent List buttons */
	torrentListButtons: function(e) {
		var el = e.element();
		if(el.hasClassName('torrentAction'))
		{
			this.torrentBatchCommand();
		}
		if(el.hasClassName('torrentCheckAll'))
		{
			checkAllByClass('.torrentCheckbox');
		}
		if(el.hasClassName('torrentUncheckAll'))
		{
			uncheckAllByClass('.torrentCheckbox');
		}
		if(el.hasClassName('torrentInvertAll'))
		{
			invertAllByClass('.torrentCheckbox');
		}
	},
	/* Buttons handler */
	buttonHandler: function(e) {
		var el = e.element();
		var id = el.up('.torrent').identify();
		/* Decide which action to do based on the class of the button */
		if(el.hasClassName('start'))
		{
			var url = 'cls=commands&tpl=commands&command=start&param=' + id;
		}
		if(el.hasClassName('stop'))
		{
			var url = 'cls=commands&tpl=commands&command=stop&param=' + id;
		}
		if(el.hasClassName('close'))
		{
			var url = 'cls=commands&tpl=commands&command=close&param=' + id;
		}
		if(el.hasClassName('erase'))
		{
			var url = 'cls=commands&tpl=commands&command=erase&param=' + id;
		}
		if(el.hasClassName('chash'))
		{
			var url = 'cls=commands&tpl=commands&command=chash&param=' + id;
		}
		/* Prepare load/response functions */
		var showLoad = this.ajax.showLoadMessages.bind(this.ajax);
		var showResponse = this.ajax.showResponseMessages.bind(this.ajax);
		/* Do the call */
		this.ajax.load('messages', url, showLoad, showResponse);
		this.ajax.reloadMain();
	},
	/* View tabs handler */
	viewTabsHandler: function(e) {
		var el = e.element();
		var id = el.up('.tabs').identify();
		var url = 'cls=ListT&tpl=ajax&view=' + id;
		var showLoad = this.ajax.showLoadMain.bind(this.ajax);
		var showResponse = this.ajax.showResponseMain.bind(this.ajax);
		this.ajax.load('content', url, showLoad, showResponse);
	},
	/* Torrent info handler */
	torrentTabsHandler: function(e) {
		var el = e.element();
		var id = el.up('.tbBulk').previous(0).identify();
		if(el.hasClassName('info')) 
		{
			var cls = 'General';
		}
		if(el.hasClassName('files')) 
		{
			var cls = 'Files';
		}
		if(el.hasClassName('trackers')) 
		{
			var cls = 'Tracker';
		}
		if(el.hasClassName('peers')) 
		{
			var cls = 'Peers';
		}
		var url = 'cls=' + cls + '&tpl=details&hash=' + id;
		var showLoad = this.ajax.showLoadTorrent.bind(this.ajax, id);
		var afterFinish = this.events.bindTorrentTab.bind(this.events, this.torrentPriorityHandler.bindAsEventListener(this), this.torrentFilesHandler.bindAsEventListener(this), this.torrentTrackersHandler.bindAsEventListener(this));
		var showResponse = this.ajax.showResponseTorrent.bind(this.ajax, id, afterFinish);
		this.ajax.load('tab' + id, url, showLoad, showResponse);
	},
	/* Helper functions */
	torrentBatchCommand: function() {
		/* Get checked torrents */
		var params = getChecked('.torrentCheckbox');
		/* Get action to apply */
		var number = $('actions').options[$('actions').selectedIndex].value;

		switch(number)
		{
			case '0':
				command = 'stop';
				break;
			case '1':
				command = 'start';
				break;
			case '2':
				command = 'erase';
				break;
			case '3':
				command = 'chash';
				break;
			case '4':
				command = 'close';
				break;
		}
		/* Convert checked elements to list of hashes */
		var hash = new Array();
		params.each(
			function(e) {
				hash.push(e.up('.torrent').identify());
			}
		);
		var hashes = hash.join('~');
		/* Confirmation msg is erase */     
		if(command == 'erase')
		{
			if(!confirm(confirmMsg))
			return;
		}
		/* Process the call */
		var url = 'cls=commands&tpl=commands&command=' + command + '&param=' + hashes;
		var showLoad = this.ajax.showLoadMessages.bind(this.ajax);
		var showResponse = this.ajax.showResponseMessages.bind(this.ajax);
		this.ajax.load('messages', url, showLoad, showResponse);
		this.ajax.reloadMain();
	},
	/* Torrent Priority Handler */
	torrentPriorityHandler: function(e) {
		var el = e.element();
		var id = el.up('.tbBulk').previous(0).identify();

		var param1 = $('sp' + id).options[$('sp' + id).selectedIndex].value;
		var url = 'cls=commands&tpl=commands&command=info&param=' + id + '&param1=' + param1;
		var showLoad = this.ajax.showLoadMessages.bind(this.ajax);
		var showResponse = this.ajax.showResponseMessages.bind(this.ajax);
		this.ajax.load('messages', url, showLoad, showResponse);
		/* Reload Torrent Info tab */
		var url = 'cls=General&tpl=details&hash=' + id;
		var showLoad = this.ajax.showLoadTorrent.bind(this.ajax, id);
		var afterFinish = this.events.bindTorrentTab.bind(this.events, this.torrentPriorityHandler.bindAsEventListener(this), this.torrentFilesHandler.bindAsEventListener(this), this.torrentTrackersHandler.bindAsEventListener(this));
		var showResponse = this.ajax.showResponseTorrent.bind(this.ajax, id, afterFinish);
		this.ajax.load('tab' + id, url, showLoad, showResponse);
	},
	/* Torrent Files Handler */
	torrentFilesHandler: function(e) {
		var el = e.element();
		var id = el.up('.tbBulk').previous(0).identify();
		if(el.hasClassName('filesPriority'))
		{
			var params = getChecked('.files' + id);
			var ids = new Array();
			params.each(
				function(e) {
					ids.push(e.identify());
				}
			);
			var param2 = ids.join('~');     
			var param1 = $('sf' + id).options[$('sf' + id).selectedIndex].value;

			var url = 'cls=commands&tpl=commands&command=files&param=' + id + '&param1=' + param1 + '&param2=' + param2;
			var showLoad = this.ajax.showLoadMessages.bind(this.ajax);
			var showResponse = this.ajax.showResponseMessages.bind(this.ajax);
			this.ajax.load('messages', url, showLoad, showResponse);
			/* Reload Torrent Files tab */
			var url = 'cls=Files&tpl=details&hash=' + id;
			var showLoad = this.ajax.showLoadTorrent.bind(this.ajax, id);
			var afterFinish = this.events.bindTorrentTab.bind(this.events, this.torrentPriorityHandler.bindAsEventListener(this), this.torrentFilesHandler.bindAsEventListener(this), this.torrentTrackersHandler.bindAsEventListener(this));
			var showResponse = this.ajax.showResponseTorrent.bind(this.ajax, id, afterFinish);
			this.ajax.load('tab' + id, url, showLoad, showResponse);
		}
		if(el.hasClassName('filesCheckAll'))
		{
			checkAllByClass('.files' + id);
		}
		if(el.hasClassName('filesUncheckAll'))
		{
			uncheckAllByClass('.files' + id);
		}
		if(el.hasClassName('filesInvertAll'))
		{
			invertAllByClass('.files' + id);
		}
	},
	/* Torrent Tracker Handler */
	torrentTrackersHandler: function(e) {
		var el = e.element();
		var id = el.up('.tbBulk').previous(0).identify();
		if(el.hasClassName('trackersEnable'))
		{
			var params = getChecked('.trackers' + id);
			var ids = new Array();
			params.each(
				function(e) {
					ids.push(e.identify());
				}
			);
			var param2 = ids.join('~');     
			var param1 = $('st' + id).options[$('st' + id).selectedIndex].value;

			var url = 'cls=commands&tpl=commands&command=trackers&param=' + id + '&param1=' + param1 + '&param2=' + param2;
			var showLoad = this.ajax.showLoadMessages.bind(this.ajax);
			var showResponse = this.ajax.showResponseMessages.bind(this.ajax);
			this.ajax.load('messages', url, showLoad, showResponse);
			/* Reload Torrent Trackers tab */
			var url = 'cls=Tracker&tpl=details&hash=' + id;
			var showLoad = this.ajax.showLoadTorrent.bind(this.ajax, id);
			var afterFinish = this.events.bindTorrentTab.bind(this.events, this.torrentPriorityHandler.bindAsEventListener(this), this.torrentFilesHandler.bindAsEventListener(this), this.torrentTrackersHandler.bindAsEventListener(this));
			var showResponse = this.ajax.showResponseTorrent.bind(this.ajax, id, afterFinish);
			this.ajax.load('tab' + id, url, showLoad, showResponse);
		}
		if(el.hasClassName('trackersCheckAll'))
		{
			checkAllByClass('.trackers' + id);
		}
		if(el.hasClassName('trackersUncheckAll'))
		{
			uncheckAllByClass('.trackers' + id);
		}
		if(el.hasClassName('trackersInvertAll'))
		{
			invertAllByClass('.trackers' + id);
		}
	},
	/* Sort handler */
	torrentSort: function(e) {
		var el = e.element();
		if(el.hasClassName('asc'))
		{
			this.ajax.reloadMain(el.up().identify(), 'asc');
		}
		if(el.hasClassName('des'))
		{
			this.ajax.reloadMain(el.up().identify(), 'desc');
		}
	}
});