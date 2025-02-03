<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="dark">
<meta name="supported-color-schemes" content="dark">
<style>
body {
font-family: 'Open Sans', Arial, sans-serif;
background-color: #1f1f1f;
color: #ffffff;
margin: 0;
padding: 0;
}
.center {
    max-width: 1200px;
    margin: 20px auto;
}
.container {
    text-align: left;
max-width: 1200px;
margin: 20px;
background: rgb(20,20,20);
background: linear-gradient(48deg, rgba(0,0,0,1) 0%, rgba(14,14,14,1) 100%);
padding: 20px;
border-radius: 5px;
box-shadow: 6px 6px 0px #424242;
}
.header_a {
    text-decoration: none !important;
    color: #ffffff;
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
