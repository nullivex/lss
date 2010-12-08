<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['games'] = <<<HTML
<h1>Games</h1>
<div class="action_link"><a href="{url_game_create}">Add Game</a></div>
<div class="action_link">
	<a href="{url_active}">Active</a> |
	<a href="{url_featured}">Featured</a> |
	<a href="{url_tradable}">Tradable</a> |
	<a href="{url_intradable}">Intradable</a> |
	<a href="{url_inactive}">Inactive</a> |
	<a href="{url_deleted}">Deleted</a>
</div>

<div class="search">
	<form action="{url_games}" method="get">
	<input type="hidden" name="act" value="games" />
	<input type="hidden" name="do" value="search" />
	<input type="hidden" name="status" value="{status}" />
	<input type="text" name="keywords" value="{keywords}" />
	<input type="submit" value="Search" />
	</form>
</div>

<table cellpadding="10" cellspacing="0" class="tbl">
<tr>
	<th>Game Id</th>
	<th>Category</th>
	<th>Name</th>
	<th>Tags</th>
	<th>Plays (Today)</th>
	<th>Rating</th>
	<th>Featured</th>
	<th>Tradable</th>
	<th>Active</th>
</tr>
{games}
</table>
HTML;

$tpl['game_row'] = <<<HTML
<tr class="{row_class}">
	<td><a href="{url}">{game_id}</a></td>
	<td>{category}</td>
	<td><a href="{url}">{name}</a></td>
	<td>{tags}</td>
	<td>{plays} <small>({plays_today})</small></td>
	<td>{rating}</td>
	<td>{featured}</td>
	<td>{tradable}</td>
	<td>{active}</td>
</tr>
HTML;

$tpl['create'] = <<<HTML
<h1>Game Create</h1>
<form action="{url_game_create}" method="post">
<input type="hidden" name="create" value="true" />

<table class="form_tbl">
<tr>
	<td class="label">Category</td>
	<td>{categories}</td>
</tr>
<tr>
	<td class="label">Name</td>
	<td><input type="text" class="input_fields" name="name" value="{name}" /></td>
</tr>
<tr>
	<td class="label">Description</td>
	<td><input type="text" class="input_fields" name="desc" value="{desc}" /></td>
</tr>
<tr>
	<td class="label">Instructions</td>
	<td><input type="text" class="input_fields" name="inst" value="{inst}" /></td>
</tr>
<tr>
	<td class="label">Author</td>
	<td><input type="text" class="input_fields" name="author" value="{author}" /></td>
</tr>
<tr>
	<td class="label">Author Url</td>
	<td><input type="text" class="input_fields" name="author_url" value="{author_url}" /></td>
</tr>
<tr>
	<td class="label">Width</td>
	<td><input type="text" class="input_fields" name="width" value="{width}" /></td>
</tr>
<tr>
	<td class="label">Height</td>
	<td><input type="text" class="input_fields" name="height" value="{height}" /></td>
</tr>
<tr>
	<td class="label">Size</td>
	<td><input type="text" class="input_fields" name="size" value="{size}" /></td>
</tr>
<tr>
	<td class="label">Icon</td>
	<td><input type="text" class="input_fields" name="icon" value="{icon}" /></td>
</tr>
<tr>
	<td class="label">Thumb</td>
	<td><input type="text" class="input_fields" name="thumb" value="{thumb}" /></td>
</tr>
<tr>
	<td class="label">Large Image</td>
	<td><input type="text" class="input_fields" name="large" value="{large}" /></td>
</tr>
<tr>
	<td class="label">Media File</td>
	<td><input type="text" class="input_fields" name="media" value="{media}" /></td>
</tr>
<tr>
	<td class="label">Media Type</td>
	<td><input type="text" class="input_fields" name="media_type" value="{media_type}" /></td>
</tr>
<tr>
	<td class="label">Featured?</td>
	<td><input type="checkbox" name="is_featured" {is_featured} /></td>
</tr>
<tr>
	<td class="label">Tradable?</td>
	<td><input type="checkbox" name="is_tradable" {is_tradable} /></td>
</tr>
<tr>
	<td class="label">Active?</td>
	<td><input type="checkbox" name="is_active" {is_active} /></td>
</tr>
<tr>
	<td><input type="submit" value="Create" /></td>
</tr>
</table>
</form>
HTML;

$tpl['edit'] = <<<HTML
<h1>Game Manage</h1>
<form action="{url_game_manage}" method="post">
<input type="hidden" name="edit" value="true" />
<input type="hidden" name="game_id" value="{game_id}" />

<table class="form_tbl">
<tr>
	<td class="label">Category</td>
	<td>{categories}</td>
</tr>
<tr>
	<td class="label">Name</td>
	<td><input type="text" class="input_fields" name="name" value="{name}" /></td>
</tr>
<tr>
	<td class="label">Description</td>
	<td><input type="text" class="input_fields" name="desc" value="{desc}" /></td>
</tr>
<tr>
	<td class="label">Instructions</td>
	<td><input type="text" class="input_fields" name="inst" value="{inst}" /></td>
</tr>
<tr>
	<td class="label">Author</td>
	<td><input type="text" class="input_fields" name="author" value="{author}" /></td>
</tr>
<tr>
	<td class="label">Author Url</td>
	<td><input type="text" class="input_fields" name="author_url" value="{author_url}" /></td>
</tr>
<tr>
	<td class="label">Width</td>
	<td><input type="text" class="input_fields" name="width" value="{width}" /></td>
</tr>
<tr>
	<td class="label">Height</td>
	<td><input type="text" class="input_fields" name="height" value="{height}" /></td>
</tr>
<tr>
	<td class="label">Size</td>
	<td><input type="text" class="input_fields" name="size" value="{size}" /></td>
</tr>
<tr>
	<td class="label">Icon</td>
	<td><input type="text" class="input_fields" name="icon" value="{icon}" /></td>
</tr>
<tr>
	<td class="label">Thumb</td>
	<td><input type="text" class="input_fields" name="thumb" value="{thumb}" /></td>
</tr>
<tr>
	<td class="label">Large Image</td>
	<td><input type="text" class="input_fields" name="large" value="{large}" /></td>
</tr>
<tr>
	<td class="label">Media File</td>
	<td><input type="text" class="input_fields" name="media" value="{media}" /></td>
</tr>
<tr>
	<td class="label">Media Type</td>
	<td><input type="text" class="input_fields" name="media_type" value="{media_type}" /></td>
</tr>
<tr>
	<td class="label">Featured?</td>
	<td><input type="checkbox" name="is_featured" {is_featured} /></td>
</tr>
<tr>
	<td class="label">Tradable?</td>
	<td><input type="checkbox" name="is_tradable" {is_tradable} /></td>
</tr>
<tr>
	<td class="label">Active?</td>
	<td><input type="checkbox" name="is_active" {is_active} /></td>
</tr>
<tr>
	<td colspan="2"><input type="submit" value="Update" /> <input type="submit" name="copy" value="Copy" /></td>
</tr>
</table>
</form>

<form action="{url_game_manage}" method="post">
<input type="hidden" name="delete" value="true" />
<input type="hidden" name="game_id" value="{game_id}" />
<table class="form_tbl">
<tr>
	<td class="label">Confirm Delete</td>
	<td><input type="checkbox" name="confirm_delete" value="true" /></td>
</tr>
<tr>
	<td class="label"></td>
	<td><input type="submit" value="Delete Game" /> (if deleted will undelete)</td>
</tr>
</table>
</form>
HTML;
