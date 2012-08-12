<?php
/*
 * LSS Core
 * OpenLSS - Light, sturdy, stupid simple
 * 2010 Nullivex LLC, All Rights Reserved.
 * Bryan Tong <contact@nullivex.com>
 *
 *   OpenLSS is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   OpenLSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with OpenLSS.  If not, see <http://www.gnu.org/licenses/>.
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
