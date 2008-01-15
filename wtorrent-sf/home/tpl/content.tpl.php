<div id="principal">
	<div id="contingut">
		{include file=$web->getTpl()}
	</div>
	<div style="width: 100%; height: 10px; clear: both;"></div>
</div>
{literal}
<script language="javascript" type="text/javascript">
	if (!window.$) {
      window.$ = function(id) { return document.getElementById(id); }
    }
    Bora.render('menu');
	Main.render('principal');
	
	/*for (id in partialBorders) {
      partialBorders[id].render(id);
    }*/
	var tabs = $('tabs').getElementsByTagName("li");
    for (var i=0; i < tabs.length; ++i) {
      tabBorder.render(tabs[i]);
    }
    {/literal}{if $web->getTplName() eq listT}{literal}
    var numTabL = {/literal}{if $web->getView() neq 'private'}{$web->getPublicHashesNum()}{else}{$web->getPrivateHashesNum()}{/if}{literal};
    for(var j=0; j< numTabL; j++) {
    	/*var tabsL = document.getElementsByClassName('tabsL' + j);*/
    	var tabsL = (document.getElementById) ? document.getElementById('tabsL' + j) : eval("document.all['tabsL"  + j + "']");
    	var tabs = tabsL.getElementsByTagName("li");
    	for (var i=0; i < tabs.length; ++i) {
     		tabL.render(tabs[i]);
    	}
    }
    {/literal}{/if}{literal}
    
</script>
{/literal}