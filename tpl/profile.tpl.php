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

$tpl['profile'] = <<<HTML
<h1>Member Profile</h1>
<form action="{url_profile}" method="post">
<input type="hidden" name="edit" value="true" />
<input type="hidden" name="member_id" value="{member_id}" />
<div>Email: <input name="email" value="{email}" readonly="readonly" /></div>
<div>Username: <input type="text" name="username" value="{username}" readonly="readonly" /></div>
<div>Password: <input type="password" name="password" value="" /><small>(blank to preserve)</small></div>
<div>Confirm Password: <input type="password" name="confirm_password" value="" /><small>(blank to preserve)</small></div>
<div><input type="submit" value="Update" /></div>
</form>
HTML;
