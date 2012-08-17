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

$tpl['register'] = <<<HTML
<form action="{url_register}" method="post" id="commentform">
<input type="hidden" name="register" value="true" />
<div>Username: <input name="username" type="text" /></div>
<div>Email: <input name="email" type="text" /></div>
<div>Password: <input autocomplete="off" name="password" type="password" /></div>
<div>Confirm Password: <input autocomplete="off" name="confirm_password" type="password" /></div>
<input name="submit" value="Submit" type="submit" />
</form>
HTML;
