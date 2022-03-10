<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style type="text/css">
		body{ background-color: #fff !important; }
		table {width: auto; border-collapse: collapse;border-spacing: 0;}
		table td {border: 1px solid #c0c0c0; white-space: nowrap; text-align: left; font: normal 12px Arial, sans-serif; color: #000; padding: 5px 15px;}
		table thead td {font-weight: bold;}
		table td.text_left {text-align: left;}
		table td.text_right {text-align: right;}
		table td.text_center {text-align: center;}
		table td.text_bold {font-weight: bold;}
		h1 {font: bold 17px Arial, sans-serif;color: #4e4e4e; text-align: left;}
		h2 {color: #a33c4a; font: bold 14px Arial, sans-serif;padding: 5px 0; text-align: left; text-transform: uppercase;}
		.container {width:990px;color: #4e4e4e;}
		.clear {clear: both;}
		.design_attach {font: bold 16px Arial, sans-serif;font-style: italic;color: #a33c4a;text-align:left;}
		p { margin:0px; color:#000;,font-size: 12px; font-family: Arial}
	</style>
</head>
<body>
<div class="container">
	<div class="ml-10">
		<h1>CUSTOM DESIGN FORM #D{{$data['design']->id}} - {{ $data['design']->client->name }} - {{$data['design']->client->company}}</h1>
		<p class="fs12">Thank you for submitting your TEAMCO Custom Design Form. We will review it and get back to you within 1-2 business days.</p>
		<p class="fs12">Please retain this email as confirmation.</p>
	</div>
	<div class="clear"></div>
	<div class="design_attach"></div>
	<div class="clear"></div>
	@include('email.design.fields')
</div>
</body>
</html>
