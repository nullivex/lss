<?php
/*
 * Tacker - Simple task tracker system
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

class Tasks {

	public $db;

	public static function _get(){
		return new Tasks();
	}

	public static function assigned($task_id=false,$staff_id=false){
		$assigned = '';
		foreach(Login::$staff as $staff){
			$params = array();
			$params['value'] = $staff['staff_id'];
			$params['desc'] = $staff['username'];
			$params['checked'] = '';
			if($staff['staff_id'] == $staff_id) $params['checked'] = 'selected="selected"';
			$assigned .= Tpl::_get()->parse('global','select_option',$params,true);
		}
		if($task_id) $params['name'] = 'assigned['.$task_id.']';
		else $params['name'] = 'staff_id';
		$params['options'] = $assigned;
		return Tpl::_get()->parse('global','select',$params,true);
	}

	public function __construct(){
		$this->db = Db::_get();
	}

	public function createParams(){
		return array(
			'desc'		=>	'',
			'duration'	=>	1,
			'start'		=>	''
		);
	}

	public function manageParams($task_id){
		$sql = 'select * from tasks where task_id = ?';
		$query = $this->db->prepare($sql);
		$query->execute(array($task_id));
		$result = $query->fetch(); $query->closeCursor();
		if(!isset($result['task_id']) || empty($result['task_id'])) throw new Exception('task not found');
		return $result;
	}

	public function taskList($staff_id=false,$completed=false,$reviewed=false,$desc=false,$limit=null){
		$finished = $completed ? 1 : 0;
		$reviewed = $reviewed ? 1 : 0;
		$sql = '
			select t.*, s.username as username
			from tasks as t
			left join staff as s on s.staff_id = t.staff_id
			where t.is_active = ? and t.is_finished = ? and t.is_reviewed = ?';
		$values = array(1,$finished,$reviewed);
		if($staff_id){
			$sql .= ' and t.staff_id = ? ';
			$values[] = $staff_id;
		}
		if($desc) $sql .= ' order by t.start desc ';
		else $sql .= ' order by t.start asc ';
		if($limit) $sql .= 'limit '.$limit.' ';
		$query = $this->db->prepare($sql);
		$query->execute($values);
		return $query->fetchAll();
	}

	public function taskRoadmap($end=false,$start=false,$limit=null){
		if(!$start) $start = time();
		if(!$end) $end = strtotime('today');
		$reviewed = 1; $finished = 1; $active = 1;
		$sql = '
			select t.*, s.username as username
			from tasks as t
			left join staff as s on s.staff_id = t.staff_id
			where t.is_active = ? and t.is_finished = ? and t.is_reviewed = ? and t.start < ? and t.end > ?';
		$values = array($active,$finished,$reviewed,$start,$end);
		$sql .= ' order by t.start desc ';
		if($limit) $sql .= 'limit '.$limit.' ';
		$query = $this->db->prepare($sql);
		$query->execute($values);
		return $query->fetchAll();
	}

	public function create($data){
		if(!isset($data['staff_id']) || empty($data['staff_id'])) throw new Exception('tasks: staff id not set');
		if(!isset($data['desc']) || empty($data['desc'])) throw new Exception('tasks: desc must be set');
		if(!isset($data['duration']) || empty($data['duration'])) throw new Exception('tasks: duration must be set');
		if(!isset($data['start'])) throw new Exception('tasks: start not set');
		if(empty($data['start'])) $data['start'] = null;
		else $data['start'] = strtotime($data['start']);
		$query = $this->db->prepare('
			insert into tasks
			(`staff_id`,`desc`,`start`,`duration`) values (?,?,?,?)
		');
		$query->execute(array(
			$data['staff_id'],
			$data['desc'],
			$data['start'],
			$data['duration']
		));

	}

	public function update($data){

		//update assignments
		if(isset($data['assigned'])){
			foreach($data['assigned'] as $task_id => $staff_id){
				$query = $this->db->prepare('update tasks set `staff_id` = ? where `task_id` = ?');
				$query->execute(array($staff_id,$task_id));
			}
		}

		//update descriptions
		if(isset($data['desc'])){
			foreach($data['desc'] as $task_id => $desc){
				$query = $this->db->prepare('update `tasks` set `desc` = ? where `task_id` = ?');
				$query->execute(array($desc,$task_id));
			}
		}

		//update start
		if(isset($data['start'])){
			foreach($data['start'] as $task_id => $start){
				$query = $this->db->prepare('update `tasks` set `start` = ? where `task_id` = ?');
				$query->execute(array(strtotime($start),$task_id));
			}
		}

		//update end
		if(isset($data['end'])){
			foreach($data['end'] as $task_id => $end){
				$query = $this->db->prepare('update `tasks` set `end` = ? where `task_id` = ?');
				$query->execute(array(strtotime($end),$task_id));
			}
		}

		//update durations
		if(isset($data['duration'])){
			foreach($data['duration'] as $task_id => $duration){
				$query = $this->db->prepare('update `tasks` set `duration` = ? where `task_id` = ?');
				$query->execute(array($duration,$task_id));
			}
		}
		
	}

	public function start($data){
		if(!isset($data['action'])) throw new Exception('tasks: no actions');
		if(!isset($data['task'])) throw new Exception('tasks: no tasks');
		foreach($data['task'] as $task_id){
			$query = $this->db->prepare('update `tasks` set start = ?, end = ?, is_finished = ? where task_id = ?');
			$query->execute(array(time(),null,0,$task_id));
		}
	}

	public function finish($data,$finish=true){
		if(!isset($data['action'])) throw new Exception('tasks: no actions');
		if(!isset($data['task'])) throw new Exception('tasks: no tasks');
		$finish = $finish ? 1 : 0;
		foreach($data['task'] as $task_id){
			$query = $this->db->prepare('update `tasks` set is_finished = ?, end = ? where task_id = ?');
			$query->execute(array($finish,time(),$task_id));
		}
	}

	public function review($data){
		if(!isset($data['action'])) throw new Exception('tasks: no actions');
		if(!isset($data['task'])) throw new Exception('tasks: no tasks');
		foreach($data['task'] as $task_id){
			$query = $this->db->prepare('update `tasks` set is_reviewed = ? where task_id = ?');
			$query->execute(array(1,$task_id));
		}
	}

	public function delete($data){
		if(!isset($data['action'])) throw new Exception('tasks: no actions');
		if(!isset($data['task'])) throw new Exception('tasks: no tasks');
		foreach($data['task'] as $task_id){
			$query = $this->db->prepare('update `tasks` set is_active = ? where task_id = ?');
			$query->execute(array(0,$task_id));
		}
	}

	public function reports($staff_id=false){
		$timeframe = array("day"     =>   1
						  ,"week"    =>   7
						  ,"biweek"  =>  14
						  ,"month"   =>  30
						  ,"quarter" =>  90
						  ,"year"    => 365
						  );
		$reports = array();
		$now = time();

		$reports['tasks_complete']			=	$this->taskCountByStatus(1,1,$staff_id);
		$reports['tasks_finished']			=	$this->taskCountByStatus(1,0,$staff_id);
		$reports['tasks_open']				=	$this->taskCountByStatus(0,0,$staff_id);
		$reports['tasks_deleted']			=	$this->taskCountByActive(0,$staff_id);
		$reports['tasks_avg_duration']		=	$this->taskAvgDuration($staff_id);
		$reports['tasks_total_duration']	=	$this->taskTotalDuration($staff_id);
		$reports['tasks_allotted_duration']	=	$this->taskAllottedDuration($staff_id);
		$reports['tasks_loss']				=	$this->taskLoss($reports['tasks_total_duration'],$reports['tasks_allotted_duration']);
		$reports['tasks_loss_color']		=	$this->taskLossColor($reports['tasks_loss']);
		$reports['tasks_loss']				=	$this->taskLossPrefix($reports['tasks_loss']);
		foreach($timeframe as $name=>$days){
			$seconds_ago = $now - ($days * 86400);
			$reports['tasks_complete_'.$name]     = $this->taskCountByStatus(1,1,$staff_id,$seconds_ago);
			$reports['tasks_finished_'.$name]     = $this->taskCountByStatus(1,0,$staff_id,$seconds_ago);
			$reports['tasks_open_'.$name]         = $this->taskCountByStatus(0,0,$staff_id,$seconds_ago);
			$reports['tasks_deleted_'.$name]      = $this->taskCountByActive(0,$staff_id,$seconds_ago);
			$reports['tasks_avg_duration_'.$name] = $this->taskAvgDuration($staff_id,$seconds_ago);
			$reports['tasks_total_duration_'.$name] = $this->taskTotalDuration($staff_id,$seconds_ago);
			$reports['tasks_allotted_duration_'.$name] = $this->taskAllottedDuration($staff_id,$seconds_ago);
			$reports['tasks_loss_'.$name] = $this->taskLoss($reports['tasks_total_duration_'.$name],$reports['tasks_allotted_duration_'.$name]);
			$reports['tasks_loss_color_'.$name] = $this->taskLossColor($reports['tasks_loss_'.$name]);
			$reports['tasks_loss_'.$name] = $this->taskLossPrefix($reports['tasks_loss_'.$name]);
		}
		return $reports;
	}

	protected function taskCountByStatus($finished=0,$reviewed=0,$staff_id=false,$seconds_ago=false){
		$where = "where is_finished = ? and is_reviewed = ? and is_active = ?";
		$args = array($finished,$reviewed,1);
		if($staff_id){
			$where .=" and staff_id = ?";
			$args[] = $staff_id;
		}
		if($seconds_ago){
			$where .= " and (start > ? or (end > ? and end is not null))";
			$args[] = $seconds_ago;
			$args[] = $seconds_ago;
		}
		$query = $this->db->prepare('select count(*) as amount from tasks '.$where);
		$query->execute($args);
		$tmp = $query->fetch();
		return $tmp['amount'];
	}

	protected function taskCountByActive($active=1,$staff_id=false,$seconds_ago=false){
		$where = "where is_active = ?";
		$args = array($active);
		if($staff_id){
			$where .= " and staff_id = ?";
			$args[] = $staff_id;
		}
		if($seconds_ago){
			$where .= " and (start > ? or (end > ? and end is not null))";
			$args[] = $seconds_ago;
			$args[] = $seconds_ago;
		}
		$query = $this->db->prepare('select count(*) as amount from tasks '.$where);
		$query->execute($args);
		$tmp = $query->fetch();
		return $tmp['amount'];
	}

	protected function taskAvgDuration($staff_id=false,$seconds_ago=false){
		$where = "where is_finished = ?";
		$args = array(1);
		if($staff_id){
			$where .= " and staff_id = ?";
			$args[] = $staff_id;
		}
		if($seconds_ago){
			$where .= " and (end > ? and end is not null)";
			$args[] = $seconds_ago;
		}
		$query = $this->db->prepare('select * from tasks '.$where);
		$query->execute($args);
		$duration = 0;
		$tasks = 0;
		foreach($query->fetchAll() as $task){
			$duration += $task['end'] - $task['start'];
			$tasks++;
		}
		if(!$tasks) return 'na';
		$avg = round($duration/$tasks);
		return hours($avg);
	}

	protected function taskTotalDuration($staff_id=false,$seconds_ago=false){
		$where = "where is_finished = ? and end is not null ";
		$args = array(1);
		if($staff_id){
			$where .= " and staff_id = ?";
			$args[] = $staff_id;
		}
		if($seconds_ago){
			$where .= " and (start > ? or end > ?)";
			$args[] = $seconds_ago;
			$args[] = $seconds_ago;
		}
		$query = $this->db->prepare('select * from tasks '.$where);
		$query->execute($args);
		$duration = 0;
		$tasks = 0;
		foreach($query->fetchAll() as $task){
			$duration += $task['end'] - $task['start'];
			$tasks++;
		}
		if(!$tasks) return 'na';
		return hours($duration);
	}

	protected function taskAllottedDuration($staff_id=false,$seconds_ago=false){
		$sql = 'select sum(duration) as amount from tasks where is_finished = ? ';
		$args = array(1);
		if($staff_id){
			$sql .= ' and staff_id = ?';
			$args[] = $staff_id;
		}
		if($seconds_ago){
			$sql .= ' and (start > ? or (end > ? and end is not null))';
			$args[] = $seconds_ago;
			$args[] = $seconds_ago;
		}
		$query = $this->db->prepare($sql);
		$query->execute($args);
		$result = $query->fetch();
		if(!isset($result['amount'])) return 'na';
		else return hours($result['amount'],3600);
	}

	protected function taskLoss($avg,$allotted){
		return preg_replace('/\D/','',$allotted) - preg_replace('/\D/','',$avg);
	}

	protected function taskLossColor($loss){
		if($loss >= 0) return 'green';
		else return 'red';
	}

	protected function taskLossPrefix($loss){
		if($loss >= 0) return '+'.$loss;
		else return '-'.($loss*-1);
	}

	public function todoList($task_id){
		$sql = 'select * from todo where task_id = ? order by is_complete asc';
		$query = $this->db->prepare($sql);
		$query->execute(array($task_id));
		return $query->fetchAll();
	}

	public function createTodoParams($task_id){
		return array('task_id'=>$task_id,'desc'=>'');
	}

	public function createTodo($data){
		data($data); Validate::prime($data);
		Validate::go('task_id')->not('blank')->type('num');
		Validate::go('desc')->not('blank');
		Validate::paint();
		$sql = 'insert into todo (task_id,`desc`)values(?,?)';
		$query = $this->db->prepare($sql);
		$query->execute(array(data('task_id'),data('desc')));
		return $this->db->lastInsertId();
	}

	public function closeTodo($data){
		data($data);
		if(!data('todo_id')) throw new Exception('no todo id');
		$sql = 'update todo set is_complete = ? where todo_id = ?';
		$query = $this->db->prepare($sql);
		$query->execute(array(1,data('todo_id')));
		return data('todo_id');
	}

	public function openTodo($data){
		data($data);
		if(!data('todo_id')) throw new Exception('no todo id');
		$sql = 'update todo set is_complete = ? where todo_id = ?';
		$query = $this->db->prepare($sql);
		$query->execute(array(0,data('todo_id')));
		return data('todo_id');
	}
	
}
