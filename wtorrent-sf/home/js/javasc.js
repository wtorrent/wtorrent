function servOC(i,hash, nColor) {
  var trObj = (document.getElementById) ? document.getElementById('ihtr' + i) : eval("document.all['ihtr" + i + "']");
  var ifCont = (document.getElementById) ? document.getElementById('contingut') : document.all['contingut'];
  var ifPri = (document.getElementById) ? document.getElementById('principal') : document.all['principal'];
  var ifCtab = $('tab' + hash);
  var height = ifCont.offsetHeight;
  if (trObj != null) {
    if (trObj.style.display=="none") {
		/*ifCont.style.height = height + 100 + "px";*/
		trObj.style.display="";
      	var display = ifPri.style.display;
		ifPri.style.display = 'none';
		ifPri.style.display = display;
		if(ifCtab.innerHTML == "")	
			getTabLData('itab' + hash);
    }
    else {
      trObj.style.display="none";
      ifCont.style.height = "auto";
    }
  }
}