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
	load: function(url, showLoad, showResponse, options){
		if(typeof(url) != 'object')
		{
			url = url + "&dummy=" + new Date().getTime();
		} else {
			url.dummy = new Date().getTime();
		}
		var myAjax = new Ajax.Request( this.index, {
			method: 'get', 
			parameters: url, 
			onLoading: showLoad, 
			onComplete: showResponse
		} );
	},
	/* Assign loading functions */
	/* Loading of the main frame */
	showLoadMain: function () {
		replaceHtml('content', this.loadingMain);
	},
	/* Loading of messages */
	showLoadMessages: function() {
		replaceHtml('messages', this.loadingMessages);
		$('messages_box').show();
	},
	/* Loading of torrent info */
	showLoadTorrent: function(id) {
		this.display.closeTorrent(id);
    var overlayCell = $('loadingCell').cloneNode(true);
    var positions = $(id).cumulativeOffset();
    overlayCell.writeAttribute('id', 'l' + id);
		overlayCell.setStyle({
		  left: positions.left + 'px',
		  top: positions.top + 'px'
		});
    document.body.appendChild(overlayCell);
    overlayCell.show();
    return;
	},
	/* Assign response functions */
	/* Print response in main frame */
	showResponseMain: function(originalRequest) {
		var newData = originalRequest.responseText;
		replaceHtml('content', newData);
		this.lastMain = originalRequest.request.options.parameters;
		postAjax();
	},
	/* Print response in messages */
	showResponseMessages: function(originalRequest) {
		var newData = originalRequest.responseText;
		replaceHtml('messages', newData);
	},
	/* Print tab info */
	showResponseTorrent: function(id, afterFinish, originalRequest) {
		var newData = originalRequest.responseText;
		replaceHtml('tab' + id, newData);
		$('l' + id).remove();
		this.display.openTorrent(id);
		afterFinish();
	},
	/* Reload main frame */
	reloadMain: function(sort, order) {
		cleanTips();
		var url = this.lastMain;
		if((sort != undefined) && (order != undefined))
		{
			url.sort = sort; 
			url.order = order;
		}
		var showLoad = this.showLoadMain.bind(this);
		var showResponse = this.showResponseMain.bind(this);
		this.load(url, showLoad, showResponse);
	}
});
	