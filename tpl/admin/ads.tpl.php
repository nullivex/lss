<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['ads'] = <<<HTML
<h1>Ads</h1>
<div class="action_link"><a href="{url_ad_create}">Add Ad</a></div>

<table cellpadding="10" cellspacing="0" class="tbl">
<tr>
	<th>Ad Id</th>
	<th>Name</th>
	<th>Ref</th>
	<th>URL</th>
	<th>Priority</th>
	<th>Hits</th>
	<th>Unique Hits</th>
</tr>
{ads}
</table>
HTML;

$tpl['ad_row'] = <<<HTML
<tr class="{row_class}">
	<td><a href="{url}">{ad_id}</a></td>
	<td><a href="{url}">{name}</a></td>
	<td><strong>{ref}</strong></td>
	<td>{ad_url}</td>
	<td>{priority}</td>
	<td>{hits}</td>
	<td>{uhits}</td>
</tr>
HTML;

$tpl['create'] = <<<HTML
<h1>Ad Create</h1>
<form action="{url_ad_create}" method="post">
<input type="hidden" name="create" value="true" />
<table class="form_tbl">
<tr>
	<td class="label">Name</td>
	<td><input type="text" class="input_fields" name="name" value="{name}" /></td>
</tr>
<tr>
	<td class="label">Ref</td>
	<td><input type="text" class="input_fields" name="ref" value="{ref}" /></td>
</tr>
<tr>
	<td class="label" colspan="2">
		<div><strong>Code</strong></div>
		<div>Vars - <small>template globals will work</small></div>
		<ul>
			<li>[[url]] - The url that will be linked out to.</li>
		</ul>
	</td>
</tr>
<tr>
	<td colspan="2"><textarea name="code" style="width: 100%; height: 100px;">{code}</textarea></td>
</tr>
<tr>
	<td class="label">Url</td>
	<td><input type="text" class="input_fields" name="url" value="{url}" /></td>
</tr>
<tr>
	<td class="label">Priority</td>
	<td><input type="text" class="input_fields" name="priority" value="{priority}" /></td>
</tr>
<tr>
	<td><input type="submit" value="Create" /></td>
</tr>
</table>
</form>
HTML;

$tpl['edit'] = <<<HTML
<h1>Ad Manage</h1>
<form action="{url_ad_manage}" method="post">
<input type="hidden" name="edit" value="true" />
<input type="hidden" name="ad_id" value="{ad_id}" />

<table class="form_tbl">
<tr>
	<td class="label">Name</td>
	<td><input type="text" class="input_fields" name="name" value="{name}" /></td>
</tr>
<tr>
	<td class="label">Ref</td>
	<td><input type="text" class="input_fields" name="ref" value="{ref}" /></td>
</tr>
<tr>
	<td class="label" colspan="2">
		<div><strong>Code</strong></div>
		<div>Vars - <small>template globals will work</small></div>
		<ul>
			<li>[[url]] - The url that will be linked out to.</li>
		</ul>
	</td>
</tr>
<tr>
	<td colspan="2"><textarea name="code" style="width: 100%; height: 100px;">{code}</textarea></td>
</tr>
<tr>
	<td class="label">Url</td>
	<td><input type="text" class="input_fields" name="url" value="{url}" /></td>
</tr>
<tr>
	<td class="label">Priority</td>
	<td><input type="text" class="input_fields" name="priority" value="{priority}" /></td>
</tr>
<tr>
	<td><input type="submit" value="Update" /> <input type="submit" name="copy" value="Copy" /></td>
</tr>
</table>
</form>

<form action="{url_ad_manage}" method="post">
<input type="hidden" name="delete" value="true" />
<input type="hidden" name="ad_id" value="{ad_id}" />
<table class="form_tbl">
<tr>
	<td class="label">Confirm Delete</td>
	<td><input type="checkbox" name="confirm_delete" value="true" /> (cannot be undone)</td>
</tr>
<tr>
	<td class="label"></td>
	<td><input type="submit" value="Delete Ad" /></td>
</tr>
</table>
</form>
HTML;
