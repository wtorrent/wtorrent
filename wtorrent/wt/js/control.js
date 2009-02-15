/* Controller of the app */
var Control = Class.create({
	/* Init other objects */
	initialize: function(effects) {
		this.display = new DisplayActions(effects);
		this.events = new EventHandler();
		this.ajax = new AjaxHandler(
			'index.php',
			this.display,
			this,
			this.events,
			{
				loadingMain: 'loadingMain',
				loadingMessages: 'loadingMessages',
				loadingTorrent: 'loadingTorrent'
			}
		);
		/* Add all events listeners */
		/* Torrent event listening */
		var events = {
			'.torrent': {
				'click': 'torrentHandler',
				'mouseover': 'torrentMouseOverHandler',
				'mouseout': 'torrentMouseOutHandler'
			},
			'#tabs': {
				'click': 'viewTabsHandler'
			},
			'.tabsLeft': {
				'click': 'torrentTabsHandler'
			},
			'#torrentListButtons': {
				'click': 'torrentListButtons',
			},
			'.priority': {
				'click': 'torrentPriorityHandler'
			},
			'#listTorrentsHead': {
				'click': 'torrentSort'
			},
			'#refresh': {
				'click': 'reloadMain'
			},
			'.filesButtons': {
				'click': 'torrentFilesHandler'
			},
			'.trackersButtons': {
				'click': 'torrentTrackersHandler'
			},
			'.torrentFolders': {
				'click': 'torrentFolderSelect'
			}
		};
		
		for (var evt in events) 
		{
			var actions = events[evt];
			for (var action in actions)
			{
				this.events.bindHandler(evt, action, this[actions[action]].bind(this));
			}
		}
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
				var afterFinish = this.events.rebindAllHandlers(this.events);
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
	confirmTorrentAction: function(el, message) {
		
		// make me an array
		if(!Object.isArray(el)) 
		{
			el = [el];
		}
		// Cannot confirm changed for empty list
		if (!el.length) {
			return false;
		}
		// reduce the elements array to a string
		var torrents = '\n"' + el.map(
			function(el ) {
				el = el.up('.torrent').down('.name');
				return (Prototype.Browser.IE ? el.innerText : el.textContent).replace(/^\s+|\s+$/g, '');
			}
		).join('",\n"') + '"';
		// remove the initial linebreak, but only if we process just one torrent
		if (el.length == 1)
		{
			torrents = torrents.slice(1);
		}
		return confirm(message.replace(/%S/g, torrents));
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
			if (!this.confirmTorrentAction(el, confirm_erase))
			{
				return;
			}
			var url = 'cls=commands&tpl=commands&command=erase&param=' + id;
		}
		if(el.hasClassName('chash'))
		{
			if (!this.confirmTorrentAction(el, confirm_chash))
			{
				return;
			}
			var url = 'cls=commands&tpl=commands&command=chash&param=' + id;
		}
		/* Prepare load/response functions */
		var showLoad = this.ajax.showLoadMessages.bind(this.ajax);
		var showResponse = this.ajax.showResponseMessages.bind(this.ajax);
		/* Do the call */
		this.ajax.load('messages', url, showLoad, showResponse);
		this.reloadMain();
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
		var afterFinish = this.events.rebindAllHandlers.bind(this.events);
		var showResponse = this.ajax.showResponseTorrent.bind(this.ajax, id, afterFinish);
		this.ajax.load('tab' + id, url, showLoad, showResponse);
	},
	/* Helper functions */
	torrentBatchCommand: function() {
		/* Get checked torrents */
		var params = getChecked('.torrentCheckbox');
		if(params.length < 1)
		{
			alert(no_torrents_selected);
			return;
		}
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
				if (!this.confirmTorrentAction(params, confirm_erase))
				{
					return;
				}
				break;
			case '3':
				command = 'chash';
				if (!this.confirmTorrentAction(params, confirm_chash))
				{
					return;
				}
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
		/* Process the call */
		var url = 'cls=commands&tpl=commands&command=' + command + '&param=' + hashes;
		var showLoad = this.ajax.showLoadMessages.bind(this.ajax);
		var showResponse = this.ajax.showResponseMessages.bind(this.ajax);
		this.ajax.load('messages', url, showLoad, showResponse);
		this.reloadMain();
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
		var afterFinish = this.events.rebindAllHandlers(this.events);
		var showResponse = this.ajax.showResponseTorrent.bind(this.ajax, id, afterFinish);
		this.ajax.load('tab' + id, url, showLoad, showResponse);
	},
	/* Torrent Files Handler */
	torrentFilesHandler: function(e) {
		var el = e.element();
		var id = el.up('.tbBulk').previous(0).identify();
		if(el.hasClassName('filesPriority'))
		{
			var ids = getChecked('.files' + id).map(function(e) { return e.identify().split('_')[1]; });

			var param1 = $('sf' + id).options[$('sf' + id).selectedIndex].value;
			var param2 = ids.join('~');     

			var url = 'cls=commands&tpl=commands&command=files&param=' + id + '&param1=' + param1 + '&param2=' + param2;
			var showLoad = this.ajax.showLoadMessages.bind(this.ajax);
			var showResponse = this.ajax.showResponseMessages.bind(this.ajax);
			this.ajax.load('messages', url, showLoad, showResponse);
			/* Reload Torrent Files tab */
			var url = 'cls=Files&tpl=details&hash=' + id;
			var showLoad = this.ajax.showLoadTorrent.bind(this.ajax, id);
			var showResponse = this.ajax.showResponseTorrent.bind(this.ajax, id);
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
	/* Torrent folder select */
	torrentFolderSelect: function(e) {
		var el = e.element();
		var ckIds = el.classNames();
		ckIds.remove(0);
		ckIds.each(
			function(e) {
				e.checked = !e.checked;
			}
		);
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
			var showResponse = this.ajax.showResponseTorrent.bind(this.ajax, id);
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
			this.reloadMain(el.up().identify(), 'asc');
			return;
		}
		if(el.hasClassName('des'))
		{
			this.reloadMain(el.up().identify(), 'desc');
			return;
		}
	},
	reloadMain: function(sortType, sortOrder)
	{
		if (sortType && sortOrder)
		{
			this._sortType = sortType;
			this._sortOrder = sortOrder;
		}
		this.ajax.reloadMain(this._sortType, this._sortOrder);
	}
});

var EventHandler = Class.create({
	_bindings: {},
	/* Initial binding */
	initialize: function() {
	},
	bindHandler: function(cls, type, handler) {
		if (!(cls in this._bindings)) {
			this._bindings[cls] = {};
		}
		if (!(type in this._bindings[cls])) {
			this._bindings[cls][type] = [];
		}
		this._bindings[cls][type].push(handler);
		this.rebindHandlers(cls);
	},
	rebindHandlers: function(cls) {
		var elts = $$(cls);
		if (!elts.length) {
			return;
		}
		if (!(cls in this._bindings)) {
			return;
		}
		var bindings = this._bindings[cls];
		for (var type in bindings) {
			bindings[type].each(
				function(h) {
					elts.each(
						function(e) {
							e.stopObserving(type, h);
							e.observe(type, h);
						}
					);
				}
			);
		}
					
	},
	rebindAllHandlers: function() {
		for (var cls in this._bindings) {
			this.rebindHandlers(cls);
		}
	}
});

var DisplayActions = Class.create({
	/* Use scriptaculous or not */
	initialize: function(effects) {
		if(effects == null)
		{
			this.effects = true;
		} else {
			this.effects = effects;
		}
	},
	/* Toggle torrent frame */
	toggleTorrent: function(id) {
		if(this.effects)
		{
			new Effect.toggle('ttab' + id, 'blind', {duration:0.5});
		} else {
			$('ttab' + id).toggle();
		}
	},
	/* Open torrent info */
	openTorrent: function(id) {
		if(this.effects)
		{
			new Effect.BlindDown('ttab' + id, {duration:0.5});
		} else {
			$('ttab' + id).show();
		}
	},
	/* Close torrent info */
	closeTorrent: function(id) {
		if(this.effects)
		{
			new Effect.BlindUp('ttab' + id, {duration:0.5});
		} else {
			$('ttab' + id).hide();
		}
	}
});
