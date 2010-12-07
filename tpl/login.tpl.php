<?php
/*
 * Tacker - Simple task tracker system
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['page'] = <<<HTML
<html>
	<head>
		<title>Login to {site_name}</title>
		<link rel="stylesheet" type="text/css" href="{css}/main.css" />
		<link rel="stylesheet" type="text/css" href="{css}/login.css" />
	</head>
	<body>
		<div id="login_frame">
			<center>
				<div id="site_name">Login to {site_name}</div>
				{alert}
				<form action="{url_login}" method="post">
					<div id="table">
						<input type="hidden" name="login" value="true" />
						<div class="label">Username</div>
						<div><input type="text" name="username" value="" /></div>
						<div class="label">Password</div>
						<div><input type="password" name="password" value="" /></div>
						<div><input type="submit" value="Login" id="submit" /></div>
					</div>
				</form>
			</center>
		</div>
		<div id="copy_rights" >&copy; {cur_year} {site_name}; All Rights Reserved.</div>
	</body>
</html>
HTML;
