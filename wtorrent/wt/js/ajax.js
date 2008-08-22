var AjaxHandler = Class.create({
	
	initialize: function(index, display, loadId) {
		this.index = index;
		this.display = display;
		this.loadingMain = $(loadId.loadingMain).innerHTML;
		this.loadingMessages = $(loadId.loadingMessages).innerHTML;
		/* Init vars with default values */
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
		//var newData = originalRequest.responseText;
		//$('content').insert = newData;
		this.lastMain = originalRequest.request.options.parameters;
		postAjax();
	},
	/* Print response in messages */
	showResponseMessages: function(originalRequest) {
		//var newData = originalRequest.responseText;
		//$('messages').insert(newData);
	},
	/* Print tab info */
	showResponseTorrent: function(id, afterFinish, originalRequest) {
		//var newData = originalRequest.responseText;
		//$('tab' + id).insert = newData;
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
	