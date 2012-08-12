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

$tpl['pages'] = <<<HTML
<h1>Pages</h1>
<div class="action_link"><a href="{url_page_create}">Add Page</a></div>

<table cellpadding="10" cellspacing="0" class="tbl">
<tr>
	<th>Page Id</th>
	<th>Name</th>
	<th>URL Name</th>
	<th>Views</th>
</tr>
{pages}
</table>
HTML;

$tpl['page_row'] = <<<HTML
<tr class="{row_class}">
	<td><a href="{url}">{page_id}</a></td>
	<td><a href="{url}">{name}</a></td>
	<td>{urlname}</td>
	<td>{views}</td>
</tr>
HTML;

$tpl['create'] = <<<HTML
<h1>Page Create</h1>
<form action="{url_page_create}" method="post">
<input type="hidden" name="create" value="true" />

<table class="form_tbl">
<tr>
	<td class="label">Name</td>
	<td><input type="text" class="input_fields" name="name" value="{name}" /></td>
</tr>
<tr>
	<td class="label" colspan="2">Content</td>
</tr>
<tr>
	<td colspan="2"><textarea name="content" style="width: 100%; height: 300px;">{content}</textarea></td>
</tr>
<tr>
	<td><input type="submit" value="Create" /></td>
</tr>
</table>
</form>
HTML;

$tpl['edit'] = <<<HTML
<h1>Page Manage</h1>
<form action="{url_page_manage}" method="post">
<input type="hidden" name="edit" value="true" />
<input type="hidden" name="page_id" value="{page_id}" />

<table class="form_tbl">
<tr>
	<td class="label">Name</td>
	<td><input type="text" class="input_fields" name="name" value="{name}" /></td>
</tr>
<tr>
	<td class="label" colspan="2">Content</td>
</tr>
<tr>
	<td colspan="2"><textarea name="content" style="width: 100%; height: 300px;">{content}</textarea></td>
</tr>
<tr>
	<td colspan="2"><input type="submit" value="Update" /> <input type="submit" name="copy" value="Copy" /></td>
</tr>
</table>
</form>

<form action="{url_page_manage}" method="post">
<input type="hidden" name="delete" value="true" />
<input type="hidden" name="page_id" value="{page_id}" />
<table class="form_tbl">
<tr>
	<td class="label">Confirm Delete</td>
	<td><input type="checkbox" name="confirm_delete" value="true" /> (cannot be undone)</td>
</tr>
<tr>
	<td class="label"></td>
	<td><input type="submit" value="Delete Page" /></td>
</tr>
</table>
</form>
HTML;
