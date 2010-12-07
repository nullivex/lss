<?php
/*
 * Tacker - Simple task tracker system
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['tasks'] = <<<HTML
<h1>Tasks</h1>
{add}
<form action="{url_tasks}" method="post">
<table cellpadding="10" cellspacing="0" class="tasks_tbl" style="margin-bottom: 0px;">
<tr>
	<th class="blank"> &nbsp;</th>
	<th class="task_id">Id</th>
	<th class="assigned">Assigned</th>
	<th class="description">Task</th>
	<th class="start">Start</th>
	<th class="age">Age</th>
	<th class="duration">Duration</th>
	<th class="blank"></th>
</tr>
{tasks}
</table>
<div class="actions" style="margin-top: 5px;">
	<select name="action">
		<option value="start">Start</option>
		<option value="finish">Finish</option>
		<option value="delete">Delete</option>
	</select>
	<input type="submit" value="Go" />
</div>
</form>
HTML;

$tpl['task_row_manage'] = <<<HTML
<tr class="{row_class}">
	<td class="blank"><input type="checkbox" name="task[{task_id}]" value="{task_id}" /></td>
	<td>{task_id}</td>
	<td>{assigned}</td>
	<td>
		<input type="text" name="desc[{task_id}]" value="{desc}" />
	</td>
	<td><input type="text" name="start[{task_id}]" value="{start}" size="15" /></td>
	<td>{age}</td>
	<td><input type="text" name="duration[{task_id}]" value="{duration}" size="3" /><small>hrs</small></td>
	<td><input type="submit" name="update" value="Update" /></td>
</tr>
HTML;

$tpl['task_row'] = <<<HTML
<tr class="{row_class}">
	<td class="blank"><input type="checkbox" name="task[{task_id}]" value="{task_id}" /></td>
	<td><a href="{url_task_todo}">{task_id}</a></td>
	<td>{assigned}</td>
	<td><a href="{url_task_todo}">{desc}</a></td>
	</td>
	<td>{start}</td>
	<td>{age}</td>
	<td>{duration}hrs</td>
	<td><a href="{url_task_todo}">Manage Todos</a></td>
</tr>
HTML;

$tpl['tasks_short'] = <<<HTML
<h4>{name}</h4>
<table cellpadding="10" cellspacing="0" class="tasks_tbl">
<tr>
	<th class="task_id">Id</th>
	<th class="description">Task</th>
	<th class="start">Start</th>
	<th class="end">End</th>
	<th class="age">Age</th>
	<th class="duration">Duration</th>
</tr>
{tasks}
</table>
HTML;

$tpl['task_row_short'] = <<<HTML
<tr class="{row_class}">
	<td>{task_id}</td>
	<td>{desc}</td>
	<td>{start}</td>
	<td>{end}</td>
	<td>{age}</td>
	<td>{duration}hrs</td>
</tr>
HTML;

$tpl['tasks_roadmap'] = <<<HTML
<h1>{name}</h1>
<div>
	<a href="{url_today}">Today</a> |
	<a href="{url_yesterday}">Yesterday</a> |
	<a href="{url_week}">Week</a> |
	<a href="{url_biweek}">Biweek</a> | 
	<a href="{url_month}">Month</a> |
	<a href="{url_year}">Year</a>
</div>
<br />
<div>
	<strong>Tasks:</strong> {task_count}
	<strong>Alloted:</strong> {task_allotted} hours
	<strong>Worked:</strong> {task_age}
</div>
<br />
<table cellpadding="10" cellspacing="0" class="tasks_tbl">
<tr>
	<th class="task_id">Id</th>
	<th class="assigned">Assigned</th>
	<th class="description">Task</th>
	<th class="start">Start</th>
	<th class="end">End</th>
	<th class="age">Age</th>
	<th class="duration">Duration</th>
</tr>
{tasks}
</table>
HTML;

$tpl['task_row_roadmap'] = <<<HTML
<tr class="{row_class}">
	<td>{task_id}</td>
	<td>{username}</td>
	<td>{desc}</td>
	<td>{start}</td>
	<td>{end}</td>
	<td>{age}</td>
	<td>{duration}hrs</td>
</tr>
HTML;

$tpl['task_review'] = <<<HTML
<h1>Task Review</h1>
<form action="{url_task_review}" method="post">
<table cellpadding="10" cellspacing="0" class="tasks_tbl" >
<tr>
	<th class="blank"></th>
	<th class="task_id">Id</th>
	<th class="assigned">Assigned</th>
	<th class="description">Task</th>
	<th class="start">Start</th>
	<th class="end">End</th>
	<th class="age">Age</th>
	<th class="duration">Duration</th>
	<th class="blank"></th>
</tr>
{tasks}
</table>
<div class="actions">
	<select name="action">
		<option value="review">Complete</option>
		<option value="unfinish">Unfinish</option>
		<option value="delete">Delete</option>
	</select>
	<input type="submit" value="Go" />
</div>
</form>
HTML;

$tpl['task_review_row'] = <<<HTML
<tr class="{row_class}">
	<td><input type="checkbox" name="task[{task_id}]" value="{task_id}" /></td>
	<td>{task_id}</td>
	<td>{assigned}</td>
	<td>
		<input type="text" name="desc[{task_id}]" value="{desc}" />
	</td>
	<td><input type="text" name="start[{task_id}]" value="{start}" size="15" /></td>
	<td><input type="text" name="end[{task_id}]" value="{end}" size="15" /></td>
	<td>{age}</td>
	<td><input type="text" name="duration[{task_id}]" value="{duration}" size="3" /><small>hrs</small></td>
	<td><input type="submit" name="update" value="Update" /></td>
</tr>
HTML;

$tpl['create'] = <<<HTML
<form action="{url_task_create}" method="post">
<input type="hidden" name="create" value="true" />
<div id="add_task" class="side_margins">
	Task: <input type="text" name="desc" value="{desc}" />
	Start: <input type="text" name="start" value="{start}" />
	Duration: <input type="text" name="duration" value="{duration}" /><small>hrs</small>
	Assign: {assign}
	<input type="submit" value="Start Task" />
</div>
</form>
HTML;

$tpl['create_short'] = <<<HTML
<form action="{url_task_create}" method="post">
<input type="hidden" name="create" value="true" />
<input type="hidden" name="start" value="{start}" />
<input type="hidden" name="staff_id" value="{staff_id}" />
<div id="add_task" class="side_margins">
	Task: <input type="text" name="desc" value="{desc}" />
	Duration: <input type="text" name="duration" value="{duration}" /><small>hrs</small>
	<input type="submit" value="Start Task" />
</div>
</form>
HTML;

$tpl['task_reports_common'] = <<<HTML
<table cellpadding="5" cellspacing="0" class="tasks_tbl">
	<thead>
		<tr>
			<th class="left">Time Range</th>
			<th class="right">Total</th>
			<th class="right">Year</th>
			<th class="right">Quarter</th>
			<th class="right">Month</th>
			<th class="right">2-Week</th>
			<th class="right">Week</th>
			<th class="right">Day</th>
		</tr>
	</thead>
	<tbody>
		<tr class="odd">
			<td>Tasks Complete</td>
			<td class="right">{tasks_complete}</td>
			<td class="right">{tasks_complete_year}</td>
			<td class="right">{tasks_complete_quarter}</td>
			<td class="right">{tasks_complete_month}</td>
			<td class="right">{tasks_complete_biweek}</td>
			<td class="right">{tasks_complete_week}</td>
			<td class="right">{tasks_complete_day}</td>
		</tr>
		<tr class="even">
			<td>Tasks Finished <small>(Awaiting Review)</small></td>
			<td class="right">{tasks_finished}</td>
			<td class="right">{tasks_finished_year}</td>
			<td class="right">{tasks_finished_quarter}</td>
			<td class="right">{tasks_finished_month}</td>
			<td class="right">{tasks_finished_biweek}</td>
			<td class="right">{tasks_finished_week}</td>
			<td class="right">{tasks_finished_day}</td>
		</tr>
		<tr class="odd">
			<td>Tasks Open</td>
			<td class="right">{tasks_open}</td>
			<td class="right">{tasks_open_year}</td>
			<td class="right">{tasks_open_quarter}</td>
			<td class="right">{tasks_open_month}</td>
			<td class="right">{tasks_open_biweek}</td>
			<td class="right">{tasks_open_week}</td>
			<td class="right">{tasks_open_day}</td>
		</tr>
		<tr class="even">
			<td>Tasks Deleted</td>
			<td class="right">{tasks_deleted}</td>
			<td class="right">{tasks_deleted_year}</td>
			<td class="right">{tasks_deleted_quarter}</td>
			<td class="right">{tasks_deleted_month}</td>
			<td class="right">{tasks_deleted_biweek}</td>
			<td class="right">{tasks_deleted_week}</td>
			<td class="right">{tasks_deleted_day}</td>
		</tr>
		<tr class="odd">
			<td>Average Task Duration</td>
			<td class="right">{tasks_avg_duration}</td>
			<td class="right">{tasks_avg_duration_year}</td>
			<td class="right">{tasks_avg_duration_quarter}</td>
			<td class="right">{tasks_avg_duration_month}</td>
			<td class="right">{tasks_avg_duration_biweek}</td>
			<td class="right">{tasks_avg_duration_week}</td>
			<td class="right">{tasks_avg_duration_day}</td>
		</tr>
		<tr class="even">
			<td>Total Task Duration</td>
			<td class="right">{tasks_total_duration}</td>
			<td class="right">{tasks_total_duration_year}</td>
			<td class="right">{tasks_total_duration_quarter}</td>
			<td class="right">{tasks_total_duration_month}</td>
			<td class="right">{tasks_total_duration_biweek}</td>
			<td class="right">{tasks_total_duration_week}</td>
			<td class="right">{tasks_total_duration_day}</td>
		</tr>
		<tr class="odd">
			<td>Allotted Task Duration</td>
			<td class="right">{tasks_allotted_duration}</td>
			<td class="right">{tasks_allotted_duration_year}</td>
			<td class="right">{tasks_allotted_duration_quarter}</td>
			<td class="right">{tasks_allotted_duration_month}</td>
			<td class="right">{tasks_allotted_duration_biweek}</td>
			<td class="right">{tasks_allotted_duration_week}</td>
			<td class="right">{tasks_allotted_duration_day}</td>
		</tr>
		<tr class="even">
			<td>Task Loss</td>
			<td class="right" style="color: {tasks_loss_color};">{tasks_loss}</td>
			<td class="right" style="color: {tasks_loss_color_year};">{tasks_loss_year}</td>
			<td class="right" style="color: {tasks_loss_color_quarter};">{tasks_loss_quarter}</td>
			<td class="right" style="color: {tasks_loss_color_month};">{tasks_loss_month}</td>
			<td class="right" style="color: {tasks_loss_color_biweek};">{tasks_loss_biweek}</td>
			<td class="right" style="color: {tasks_loss_color_week};">{tasks_loss_week}</td>
			<td class="right" style="color: {tasks_loss_color_day};">{tasks_loss_day}</td>
		</tr>
	</tbody>
</table>
HTML;

$tpl['task_reports_hdr_general'] = <<<HTML
<h1>Task Reports</h1>
HTML;

$tpl['task_reports_hdr_staff'] = <<<HTML
<h1>Task Reports: {username}</h1>
HTML;

$tpl['create_todo'] = <<<HTML
<form method="post" action="{url_task_todo}">
<input type="hidden" name="task_id" value="{task_id}" />
<input type="hidden" name="create" value="true" />
<div>
	Description: <input type="text" name="desc" value="{desc}" />
	<input type="submit" value="Create Todo" />
</div>
</form>
HTML;

$tpl['task_todo'] = <<<HTML
<h1>Task: <a href="{url_task_todo}"><i>{name}</i></a></h1>
{add}
<table cellpadding="10" cellspacing="0" class="tasks_tbl">
<tr>
	<th class="age">Id</th>
	<th class="start">Status</th>
	<th class="description">Todo</th>
	<th class="end">Action</th>
</tr>
{tasks}
</table>
HTML;

$tpl['task_row_todo'] = <<<HTML
<tr class="{row_class}">
	<td>{todo_id}</td>
	<td><span style="color: {color};">{status}</span></td>
	<td><strong>{desc}</strong></td>
	<td>
		<form action="{url_task_todo}" method="post" style="display: inline; margin: 0; padding: 0;">
		<input type="hidden" name="todo_id" value="{todo_id}" />
		<input type="hidden" name="{action}" value="true" />
		<input type="submit" value="{button}" />
		</form>
	</td>
</tr>
HTML;
