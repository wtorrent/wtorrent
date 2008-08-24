var AjaxHandler = Class.create({
	
	initialize: function(index, display, main, events, loadId) {
		this.index = index;
		this.display = display;
		this.main = main;
		this.events = events;
		this.loadingMain = $(loadId.loadingMain).innerHTML;
		this.loadingMessages = $(loadId.loadingMessages).innerHTML;
		this.lastMain = new Object();
		this.lastMain.cls = 'ListT';
		this.lastMain.tpl = 'ajax';
		this.lastMain.view = 'public';
		//this.loadingTorrent = $(loadId.loadingTorrent).innerHTML;
	},
	/* Main loader */
	load: function(id, url, showLoad, showResponse, options){
		if(typeof(url) != 'object')
		{
			url = url + "&dummy=" + new Date().getTime();
		} else {
			url.dummy = new Date().getTime();
		}
		var myAjax = new Ajax.Updater( id, this.index, {
			method: 'get', 
			parameters: url, 
			evalJS: false,
			onLoading: showLoad, 
			onComplete: showResponse
		} );
	},
	/* Assign loading functions */
	/* Loading of the main frame */
	showLoadMain: function () {
		$('content').update(this.loadingMain);
	},
	/* Loading of messages */
	showLoadMessages: function() {
		$('messages').update(this.loadingMessages);
		$('messages_box').show();

		// hide after 7 seconds
		setTimeout(
			function() {
				var elt = $('messages_box');
				if ('fade' in elt)
				{
					elt.fade();
				}
				else
				{
					elt.hide()
				}
			},
			7000
		);

	},
	/* Loading of torrent info */
	showLoadTorrent: function(id) {
		this.display.closeTorrent(id);
		$(id).addClassName('loading');
	},
	/* Assign response functions */
	/* Print response in main frame */
	showResponseMain: function(originalRequest) {
		cleanTips();
		this.lastMain = originalRequest.request.options.parameters;
		this.events.reloadTorrent(this.main.torrentHandler.bindAsEventListener(this.main), this.main.torrentMouseOverHandler.bindAsEventListener(this.main), this.main.torrentMouseOutHandler.bindAsEventListener(this.main));
		this.events.reloadTorrentTabs(this.main.torrentTabsHandler.bindAsEventListener(this.main));
		this.events.reloadListButtons(this.main.torrentListButtons.bindAsEventListener(this.main));
		this.events.reloadSortButtons(this.main.torrentSort.bindAsEventListener(this.main));
		postAjax();
	},
	/* Print response in messages */
	showResponseMessages: function(originalRequest) {
	},
	/* Print tab info */
	showResponseTorrent: function(id, afterFinish, originalRequest) {
		$(id).removeClassName('loading');
		this.display.openTorrent(id);
		afterFinish();
	},
	/* Reload main frame */
	reloadMain: function(sort, order) {
		var url = this.lastMain;
		if((sort != undefined) && (order != undefined))
		{
			url.sort = sort; 
			url.order = order;
		}
		var showLoad = this.showLoadMain.bind(this);
		var showResponse = this.showResponseMain.bind(this);
		this.load('content', url, showLoad, showResponse);
	}
});
	
