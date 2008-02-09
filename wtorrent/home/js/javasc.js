function resizeInnerTab(hash) {
	/* Get elements */
	/*var trObj = $('ihtr' + i);
	var ifPri = $('principal');
	var ifCtab = $('tab' + hash);*/
	
	//var height = ifCont.offsetHeight;
	/*if (trObj != null) // If it's hidden, exapand
	{
		if (trObj.style.display=="none") 
		{
			if(ifCtab.innerHTML == "") // If it's the first time load content
			// Load general info in the new-opened div 
			load('tab' + hash, 'info');
			trObj.style.display="";
			var display = ifPri.style.display;
			/* Force redraw of the main widget */
			/*ifPri.style.display = 'none';
			ifPri.style.display = display;
		}
		else // If it's open close it
		{
			trObj.style.display="none";
		}
	}*/
    if($('tab' + hash).innerHTML == "")
    {
        load('tab' + hash, 'info');
    } else {
        Effect.toggle('ttab' + hash, 'blind', {duration:0.5});
    }
    //Effect.BlindUp('id_of_element');
    resize();
}
/* Resize to make shadedborders render the div again */
function resize() {
	Main.render('principal');
}
/* Check all elements of the specified class */
function checkAllByClass(styleClass) {
	var elements = document.getElementsByClassName(styleClass);
		for (var i = 0; i < elements.length; i++) {
			elements[i].checked = true;
		}
}
/* Uncheck all elements of the specified class */
function uncheckAllByClass(styleClass) {
	var elements = document.getElementsByClassName(styleClass);
		for (var i = 0; i < elements.length; i++) {
			elements[i].checked = false;
		}
}
/* Function that does the AJAX loading process */
function load(frame, content) {
	if(frame == 'content') // Actions for frame 'content' (MAIN FRAME)
	{
		var get = getContent(content);
	}
	else // Actions for torrent frames
	{
		var get = getFrame(content, frame);
	}
	ajaxCall(frame, get);
}
/* Generate showLoad and showresponse */
function getShowFunctions(frame)
{
	var functions = new Array();

	functions[0] = (function (frame) { return function () { loadingContent(frame); } })(frame);
	functions[1] = (function (frame) { return function (request) { responseContent(request, frame); } })(frame);
    //functions[2] = (function (frame) { return function () { resizeFrame(frame); } })(frame);
	
	return functions;
}	
/* Get URL to change the content of the Top tabs */
function getContent(id)
{
	if(id == 'public') {
		var pars = 'cls=ListT&tpl=ajax&view=public';
		view = 'public';
		tab = 1;
	}
	if(id == 'private') {
		var pars = 'cls=ListT&tpl=ajax&view=private';
		view = 'private';
		tab = 1;
	}
	return pars;
}
/* Get URL to change the content of the torrent tabs */
function getFrame(content, frame)
{
	var cls;
	switch(content)
	{
		case 'info':
			cls = 'General';
			break;
		case 'files':
			cls = 'Files';
			break;
		case 'trackers':	
			cls = 'Tracker';
			break;
		default:
			break;
	}
	var pars = 'cls=' + cls + '&tpl=details&hash=' + frame.substr(3);
	return pars;
}
/* Load LOADING content in the given frame */
function loadingContent(frame) {
    switch(frame)
    {
        case 'messages':
		    $(frame).style.display = "block";
		    var loadingDiv = loadingCommand;
            $(frame).innerHTML = loadingDiv;
            break;
        case 'content':
		    var loadingDiv = loading;
            $(frame).innerHTML = loadingDiv;
            break;
        default:
            loadingTab(frame);
            break;
    }
    //contractFrame(frame);
}
function loadingTab(frame)
{
    contractFrame('t' + frame);
    var node1 = document.getElementById('node1');
    var overlayCell = $('loadingCell').cloneNode(true);
    var positions = findPos($('i' + frame));
    overlayCell.setAttribute('id', 'l' + frame);
    overlayCell.style.left = positions[0] + 'px';
    overlayCell.style.top = positions[1] + 'px';
    document.body.appendChild(overlayCell);
    overlayCell.show();
    //expandFrame(overlayCell);
    new Effect.Opacity(overlayCell, {duration:0.2, from:0, to:0.6});
    return;
}
/* Load AJAX RESPONSE in given content */
function responseContent(originalRequest, frame) {
	var newData = originalRequest.responseText;
    $(frame).innerHTML = newData;
    if(frame != 'content' || frame != 'messages') {
        var onFinish = (function (frame) { return function (obj) { $(frame).remove(); } })('l'+ frame);
        new Effect.Opacity('l' + frame, {duration:0.2, from:0.6, to:0, afterFinish: onFinish});
        window.setTimeout("expandFrame('t" + frame + "')", 100);
    }
	postAjax();
	resize();
}
function expandFrame(frame) {
    new Effect.BlindDown(frame, {duration:0.5});
}
function contractFrame(frame, onFinish) {
    new Effect.BlindUp(frame, {duration:0.5, afterFinish: onFinish});
}
/* Process commands given through the ajax interface */
function command(command, param)
{
	/* Default frame for wTorrent messages and loading/showing functions */
	var frame = 'messages';
	var call = new Array();
	switch(command)
	{
		case 'files':
			var call = files(param);
			break;
		case 'info':
			var call = info(param);
			break;
		case 'trackers':
			var call = trackers(param);
			break;
		case 'batch':
			var call = batch();
			break;
		default:
			var call = defaultCall(command, param);
			break;
	}
	// Execute the command through ajax
	ajaxCall(frame, call[0]);
	// Update the page (after 500ms to give rtorrent time to process the command (also set the frame to loading)
	loadingContent(call[1]);
	window.setTimeout("load('" + call[1] + "', '" + call[2] + "')", 500);
}

function ajaxCall(frame, pars)
{
	// Get show functions with correct frames
	var functions = getShowFunctions(frame);
	var showLoad = functions[0];
	var showResponse = functions[1];
    //var resizeDiv = functions[2];
	// Dummy variable to avoid iexplorer cache
	pars = pars + "&dummy=" + new Date().getTime();
	var myAjax = new Ajax.Request( url, {
			method: 'get', 
			parameters: pars, 
			onLoading: showLoad, 
			onComplete: showResponse
	} );
}
function getChecked(identifier)
{
	var params = new Array();
	var objects = document.getElementsByClassName(identifier);
	for (var i = 0; i < objects.length; i++) {
		if(objects[i].checked === true)
			params.push(objects[i].id);
	}
	return params;
}
function files(param)
{
	var call = new Array();
	var params = getChecked('files' + param);
	var param2 = params.join('~');     
	param2 = encodeURIComponent(param2);
	var param1 = $('sf' + param).options[$('sf' + param).selectedIndex].value;
 	call[0] = 'cls=commands&tpl=commands&command=files&param=' + param + '&param1=' + param1 + '&param2=' + param2;
	call[1] = 'tab' + param;
	call[2] = 'files';
	
	return call;
}
function info(param)
{
	var call = new Array();
	param1 = $('sp' + param).options[$('sp' + param).selectedIndex].value;
	call[0] = 'cls=commands&tpl=commands&command=info&param=' + param + '&param1=' + param1;
	call[1] = 'tab' + param;
	call[2] = 'info';
	
	return call;
}
function trackers(param)
{
	var call = new Array();
	var params = getChecked('trackers' + param);
	var param2 = params.join('~');     
	param2 = encodeURIComponent(param2);
	var param1 = $('st' + param).options[$('st' + param).selectedIndex].value;
 	call[0] = 'cls=commands&tpl=commands&command=trackers&param=' + param + '&param1=' + param1 + '&param2=' + param2;
	call[1] = 'tab' + param;
	call[2] = 'trackers';
	
	return call;
}
function batch()
{
	var call = new Array();
	var command;
	var params = getChecked('torrent');
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
			
	var param = params.join('~');     
	param = encodeURIComponent(param);
	if(command == 'erase')
	{
		if(!confirm(confirmMsg))
		return;
	}
	call[0] = 'cls=commands&tpl=commands&command=' + command + '&param=' + param;
	call[1] = 'content';
	call[2] = view;
	
	return call;
}
function defaultCall(command, param)
{
	var call = new Array();
	if(command == 'erase')
	{
		if(!confirm(confirmMsg))
			return;
	}
	call[0] = 'cls=commands&tpl=commands&command=' + command + '&param=' + param;
	call[1] = 'content';
	call[2] = view;
	
	return call;
}
/* Find position of an element */
function findPos(obj) {
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		do {
			curleft += obj.offsetLeft;
			curtop += obj.offsetTop;
		} while (obj = obj.offsetParent);
	}
	return [curleft,curtop];
}