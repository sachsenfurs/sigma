<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="dark">
<meta name="supported-color-schemes" content="dark light">
<style>
@media (prefers-color-scheme: light) {
body {
background-color: #fff !important;
color: #000 !important;
}
.button {
color: #000 !important;
background: #eee !important;
box-shadow: 3px 3px 0 #dfdfdf !important;
}
.container {
background: linear-gradient(48deg, rgb(244, 244, 244) 0%, rgb(255, 255, 255) 100%) !important;
box-shadow: 6px 6px 0 #e1e1e1 !important;
}
}
@yield("style")
</style>
</head>

<body>
<center>
<div class="center">
<div class="container">
{{ $header ?? '' }}

{{ $slot }}

{{ $subcopy ?? '' }}

</div>
{{ $footer ?? '' }}
</div>
</center>
</body>
</html>
