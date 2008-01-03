{*<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1" />
  <title>{$TITLE}</title>
  <script src="javasc.js" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="{$DIR_CSS_DETALLS}" media="all" />
</head>

<body>
<div id="principal" class="principal">*}
<div id="ctab{$web->getHash()}">
{include file=$web->getTpl()}
</div>
{*</div>
</body>

</html>*}
