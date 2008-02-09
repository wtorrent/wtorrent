<div id="principal">
	<div id="content">
		{include file=$web->getTpl()}
	</div>
	<div style="width: 100%; height: 10px; clear: both;"></div>
</div>
{literal}
<script language="javascript" type="text/javascript">
    Bora.render('menu');
	Main.render('principal');
	
	var tabs = $('tabs').getElementsByTagName("li");
    for (var i=0; i < tabs.length; ++i) {
      tabBorder.render(tabs[i]);
    }
	/*var TabsL = document.getElementsByClassName('tabsLeft');
    	var numTabsL = TabsL.length;
    	for(var j=0; j< numTabsL; j++) {
    		var tabsL = $('tabsL' + j);
    		var tabs = tabsL.getElementsByTagName("li");
    		for (var i=0; i < tabs.length; ++i) {
      		tabL.render(tabs[i]);
    		}
    	}  */
	var tabsL = document.getElementsByClassName('tabsLeft');
	for (var i=0; i < tabsL.length; i++) {
		var tabs = tabsL[i].getElementsByTagName('li');
		for(var j = 0; j < tabs.length; j++)
			tabL.render(tabs[j]);
	}
</script>
{/literal}