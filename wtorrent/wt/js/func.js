/* innerHTML replacement, faster */
function replaceHtml(el, html) {
	var oldEl = typeof el === "string" ? document.getElementById(el) : el;
	/*@cc_on // Pure innerHTML is slightly faster in IE
		oldEl.innerHTML = html;
		return oldEl;
	@*/
	var newEl = oldEl.cloneNode(false);
	newEl.innerHTML = html;
	oldEl.parentNode.replaceChild(newEl, oldEl);
	/* Since we just removed the old element from the DOM, return a reference
	to the new element, which can be used to restore variable references. */
	return newEl;
}
/* Get checked element by class name */
function getChecked(identifier)
{
	var params = new Array();
	var objects = $$(identifier);
	objects.each(
		function(e) {
			if(e.checked)
				params.push(e);
		}
	);
	return params;
}
/* Check all elements of the specified class */
function checkAllByClass(styleClass) {
	var elements = $$(styleClass);
	elements.each(
		function(e) {
			e.checked = true;
		}
	);
}
/* Uncheck all elements of the specified class */
function uncheckAllByClass(styleClass) {
	var elements = $$(styleClass);
	elements.each(
		function(e) {
			e.checked = false;
		}
	);
}
/* Invert selection */
function invertAllByClass(styleClass) {
	var elements = $$(styleClass);
	elements.each(
		function(e) {
			e.checked = !e.checked;
		}
	);
}
function torrentTip(elementId) {
	var content = $('tipContent' + elementId).cloneNode(true);
	if(content.hasClassName('red'))
	{
		content.id = elementId + 'copy';
		content.show();
		new Tip(
			'tip' + elementId,                 // the id of your element
			content,                 // a string or an element
			{  
				closeButton: false,    // or true
				// duration: 0.3,         // duration of the effect, if used
				delay: 0,             // seconds before tooltip appears
				effect: false,         // false, 'appear' or 'blind'
				fixed: false,          // follow the mouse if false
				hideAfter: false,      // hides after seconds of inactivity, not hovering the element or the tooltip
				hideOn: 'mouseout',     // any other event, false or: { element: 'element|target|tip|closeButton|.close', event: 'click|mouseover|mousemove' }
				hook: false,           // { target: 'topLeft|topRight|bottomLeft|bottomRight|topMiddle|bottomMiddle|leftMiddle|rightMiddle',tip: 'topLeft|topRight|bottomLeft|bottomRight|topMiddle|bottomMiddle|leftMiddle|rightMiddle' }
				showOn: 'mousemove',   // or any other event
				viewport: false         // keep within viewport, false when fixed or hooked
			}
		);
	}
}
function cleanTips()
{
	var tips = $$('.torrent');
	for(var i = 0; i < tips.length; i++)
	{
		var temp = $(tips[i].id + 'copy');
		if(temp != null)
			temp.remove();
	}
}

/**
 * Deletes a cookie by submitting the eraseForm
 */
function eraseCookie(id)
{
	$('eraseId').value = id;
	return $('eraseForm').submit();
}
