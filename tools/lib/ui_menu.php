<?php

class UIMenu extends UI implements UIInt {

	//window handles
	private $win_main = false;
	
	//content buffer
	private $content = array();
	
	//coordinate tracking
	private $cols = -1;
	private $rows = -1;
	private $x = 0;
	private $y = 0;

	protected function _init(){
		ncurses_init();
		ncurses_savetty();
		ncurses_cbreak();
		ncurses_noecho();
		ncurses_erase();
		ncurses_getmaxyx(STDSCR,&$this->rows,&$this->cols);
		ncurses_border(0,0, 0,0, 0,0, 0,0); //border the main window
		//title the main window
		ncurses_attron(NCURSES_A_REVERSE);
		ncurses_mvaddstr(0,1,' OpenLSS v'.LSSTOOLS_VERSION.' ');
		ncurses_attroff(NCURSES_A_REVERSE);
		ncurses_curs_set(0);
		ncurses_refresh();
		ncurses_flushinp();
		$this->x = 2;
		$this->y = 2;
	}

	protected function _deinit(){
		ncurses_refresh();
		ncurses_resetty();
		ncurses_end();
	}

	public function __out($string,$err=false){
		if($err) $string = '[ERR]'.$string;
		$y_off = 0;
		while(strrpos($string,"\n",-1) !== false){
			$y_off++;
			$string = substr($string,0,-1);
		}
		while($this->y >= $this->rows-1){
			$this->y--;
			ncurses_scrl(2);
		}
		ncurses_move($this->y,$this->x);
		ncurses_addstr($string);
		ncurses_refresh();
		$this->y += $y_off;
		$this->x = ($y_off) ? 2 : ($this->x + strlen($string));
//		fwrite(($err)?STDERR:STDOUT,$string);
//		fflush(($err)?STDERR:STDOUT);
	}

	public function ask($q,$a,$default=false){
		$a[0] = ($default) ? strtoupper($a[0]) : strtolower($a[0]);
		$a[1] = ($default) ? strtolower($a[1]) : strtoupper($a[1]);
		$b = array(strtolower(substr($a[0],0,1)),strtolower(substr($a[1],0,1)));
		$in = null;
		while(is_null($in)){
			print $q.' ('.$a[0].'/'.$a[1].'): ';
			@flush();
			@ob_flush();
			$in = strtolower(substr(trim(fgets(STDIN)),0,1));
			if($in == '') return $default;
			if(!in_array($in,$b)) $in = null;
		}
		if($in !== $b[0]) return false;
		return true;
	}

	public function input($q,&$a){
		$command = "/usr/bin/env bash -c 'echo OK'";
		if (rtrim(shell_exec($command)) !== 'OK') {
			trigger_error("Can't invoke bash");
			return;
		}
		$command = "/usr/bin/env bash -c 'read -s \""
			. addslashes($prompt)
			. "\" myinput && echo \$myinput'";
		$val = rtrim(shell_exec($command));
		echo "\n";
		return $val;
	}

	public function password($q,&$a){
		$command = "/usr/bin/env bash -c 'echo OK'";
		if (rtrim(shell_exec($command)) !== 'OK') {
			trigger_error("Can't invoke bash");
			return;
		}
		$command = "/usr/bin/env bash -c 'read -s -p \""
			. addslashes($prompt)
			. "\" mypassword && echo \$mypassword'";
		$val = rtrim(shell_exec($command));
		echo "\n";
		return $val;
	}

	public function select($q,&$a,$default=0){
		//TODO: this.
	}

}
