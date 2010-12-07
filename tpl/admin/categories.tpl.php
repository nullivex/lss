<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['categories'] = <<<HTML
<h1>Categories</h1>
<div class="action_link"><a href="{url_category_create}">Add Category</a></div>

<table cellpadding="10" cellspacing="0" class="tbl">
<tr>
	<th>Category Id</th>
	<th>Name</th>
	<th>URL Name</th>
	<th>Short Name</th>
	<th>Games</th>
</tr>
{categories}
</table>
HTML;

$tpl['category_row'] = <<<HTML
<tr class="{row_class}">
	<td><a href="{url}">{category_id}</a></td>
	<td><a href="{url}">{name}</a></td>
	<td>{urlname}</td>
	<td>{shortname}</td>
	<td>{games}</td>
</tr>
HTML;

$tpl['create'] = <<<HTML
<h1>Category Create</h1>
<form action="{url_category_create}" method="post">
<input type="hidden" name="create" value="true" />

<table class="form_tbl">
<tr>
	<td class="label">Name</td>
	<td><input type="text" class="input_fields" name="name" value="{name}" /></td>
</tr>
<tr>
	<td><input type="submit" value="Create" /></td>
</tr>
</table>
</form>
HTML;

$tpl['edit'] = <<<HTML
<h1>Category Manage</h1>
<form action="{url_category_manage}" method="post">
<input type="hidden" name="edit" value="true" />
<input type="hidden" name="category_id" value="{category_id}" />

<table class="form_tbl">
<tr>
	<td class="label">Name</td>
	<td><input type="text" class="input_fields" name="name" value="{name}" /></td>
</tr>
<tr>
	<td><input type="submit" value="Update" /></td>
</tr>
</table>
</form>

<form action="{url_category_manage}" method="post">
<input type="hidden" name="delete" value="true" />
<input type="hidden" name="category_id" value="{category_id}" />
<table class="form_tbl">
<tr>
	<td class="label">Confirm Delete</td>
	<td><input type="checkbox" name="confirm_delete" value="true" /> (cannot be undone)</td>
</tr>
<tr>
	<td class="label">New Category For Games</td>
	<td>{categories}</td>
</tr>
<tr>
	<td class="label"></td>
	<td><input type="submit" value="Delete Category" /></td>
</tr>
</table>
</form>
HTML;
