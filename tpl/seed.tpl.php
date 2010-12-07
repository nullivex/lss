<?php
/*
 * Tacker - Simple task tracker system
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();
$tpl['seed'] = <<<HTML
<h1>Task Seeding</h1>
{add}
<form action="{url_seed}" method="post">
<table cellpadding="10" cellspacing="0" class="tasks_tbl" style="margin-bottom: 0px;">
<tr>
	<th class="blank"> &nbsp;</th>
	<th class="task_id">Id</th>
	<th class="assigned">Assigned</th>
	<th class="description">Task</th>
	<th class="start">Seed</th>
	<th class="duration">Duration</th>
	<th class="blank"></th>
</tr>
{tasks}
</table>
<div class="actions" style="margin-top: 5px;">
	<select name="action"><option value="delete">Delete</option></select>
	<input type="submit" value="Go" />
</div>
</form>
HTML;

$tpl['seed_row'] = <<<HTML
<tr class="{row_class}">
	<td class="blank">
		<input type="hidden" name="seed[{seed_id}][seed_id]" value="{seed_id}" />
		<input type="checkbox" name="seed[{seed_id}][action]" value="{seed_id}" />
	</td>
	<td>{seed_id}</td>
	<td>{assigned}</td>
	<td>
		<input type="text" name="seed[{seed_id}][desc]" value="{desc}" />
	</td>
	<td><input type="text" name="seed[{seed_id}][seed]" value="{seed}" size="15" /></td>
	<td><input type="text" name="seed[{seed_id}][duration]" value="{duration}" size="3" /><small>hrs</small></td>
	<td><input type="submit" name="update" value="Update" /></td>
</tr>
HTML;

$tpl['create'] = <<<HTML
<form action="{url_seed}" method="post">
<input type="hidden" name="create" value="true" />
<div id="add_task" class="side_margins">
	Task: <input type="text" name="desc" value="{desc}" />
	Duration: <input type="text" name="duration" value="{duration}" /><small>hrs</small>
	Seed: <input type="text" name="seed" value="{seed}" />
	Assign: {assign}
	<input type="submit" value="Seed Task" />
</div>
</form>
HTML;
