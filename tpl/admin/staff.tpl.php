<?php
/*
 * Simple CMS
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['staff'] = <<<HTML
<h1>Staff</h1>
<div class="action_link"><a href="{url_staff_create}">Add Staff Member</a></div>

<table cellpadding="10" cellspacing="0" class="tbl">
<tr>
	<th>Staff Id</th>
	<th>Username</th>
	<th>Email</th>
	<th>Manager</th>
</tr>
{staff}
</table>
HTML;

$tpl['staff_row'] = <<<HTML
<tr class="{row_class}">
	<td><a href="{url}">{staff_id}</a></td>
	<td><a href="{url}">{username}</a></td>
	<td>{email}</td>
	<td>{manager}</td>
</tr>
HTML;

$tpl['create'] = <<<HTML
<h1>Staff Create</h1>
<form action="{url_staff_create}" method="post">
<input type="hidden" name="create" value="true" />
<table class="form_tbl">
<tr>
	<td class="label">Email</td>
	<td><input class="input_field" type="text" name="email" value="{email}" /></td>
</tr>
<tr>
	<td class="label">Username</td>
	<td><input class="input_field" type="text" name="username" value="{username}" /></td>
</tr>
<tr>
	<td class="label">Password</td>
	<td><input class="input_field" type="password" name="password" value="" /></td>
</tr>
<tr >
	<td class="label">Confirm Password</td>
	<td><input class="input_field confirm" type="password" name="confirm_password" value="" /></td>
</tr>
<tr>
	<td class="label">Manager</td>
	<td><input type="checkbox" name="is_manager" value="true" {is_manager}/></td>
</tr>
<tr>
	<td align="right" colspan="2"><input type="submit" value="Create" /></td>
</tr>
</table>
</form>
HTML;

$tpl['edit'] = <<<HTML
<h1>Staff Manage</h1>
<form action="{url_staff_manage}" method="post">
<input type="hidden" name="edit" value="true" />
<input type="hidden" name="staff_id" value="{staff_id}" />

<table class="form_tbl">
<tr>
	<td class="label">Email</td>
	<td><input type="text"  class="input_fields" name="email" value="{email}" /></td>
</tr>
<tr>
	<td class="label">Username</td>
	<td><input type="text" class="input_fields" name="username" value="{username}" /></td>
</tr>
<tr valign="top">
	<td class="label">Password<small>(blank to preserve)</small></td>
	<td><input type="password" class="input_fields" name="password" value="" /></td>
</tr>
<tr>
	<td class="label">Confirm Password</td>
	<td><input type="password" class="input_fields" name="confirm_password" value="" /></td>
</tr>
<tr>
	<td class="label">Manager</td>
	<td><input type="checkbox"  name="is_manager" value="true" {is_manager}/></td>
</tr>
<tr>
	<td align="right" colspan="2"><input type="submit" value="Update" /></td>
</tr>
</table>
</form>

<form action="{url_staff_manage}" method="post">
<input type="hidden" name="delete" value="true" />
<input type="hidden" name="staff_id" value="{staff_id}" />
<table class="form_tbl">
<tr>
	<td class="label">Confirm Delete</td>
	<td><input type="checkbox" name="confirm_delete" value="true" /> (cannot be undone)</td>
</tr>
<tr>
	<td align="right" colspan="2"><input type="submit" value="Delete Staff" /></td>
</tr>
</table>
</form>
HTML;

$tpl['profile'] = <<<HTML
<h1>Staff Profile</h1>
<form action="{url_profile}" method="post">
<input type="hidden" name="edit" value="true" />
<input type="hidden" name="staff_id" value="{staff_id}" />

<table class="form_tbl">
<tr>
	<td class="label">Email</td>
	<td><input type="text"  class="input_fields" name="email" value="{email}" /></td>
</tr>
<tr>
	<td class="label">Username</td>
	<td><input type="text" class="input_fields" name="username" value="{username}" /></td>
</tr>
<tr valign="top">
	<td class="label">Password<small>(blank to preserve)</small></td>
	<td><input type="password" class="input_fields" name="password" value="" /></td>
</tr>
<tr>
	<td class="label">Confirm Password</td>
	<td><input type="password" class="input_fields" name="confirm_password" value="" /></td>
</tr>
<tr>
	<td align="right" colspan="2"><input type="submit" value="Update" /></td>
</tr>
</table>
</form>
HTML;
