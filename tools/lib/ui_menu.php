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
//		ncurses_savetty();
		ncurses_cbreak();
		ncurses_noecho();
		ncurses_nonl();
//		ncurses_intrflush(STDSCR,false);
		ncurses_keypad(STDSCR,true);
		ncurses_getmaxyx(STDSCR,&$this->rows,&$this->cols);
//		ncurses_curs_set(0);
		$this->y = $this->rows-1;
		$this->x = 0;
	}

	protected function _deinit(){
//		ncurses_move(0,0);
//		ncurses_insdelln(-1);
		ncurses_refresh();
//		ncurses_resetty();
	$this->__out("[END]");
		ncurses_getch();
		ncurses_end();
	}

	public function __out($string,$err=false){
		if($err) ncurses_wattron(STDSCR,NCURSES_A_REVERSE);
		$y_off = 0;
		while(strrpos($string,"\n",-1) !== false){
			$y_off++;
			$string = substr($string,0,-1);
		}
		$y_flow = ($this->y+$y_off) - ($this->rows - 1);
		if($y_flow >= 0){
			ncurses_move(0,0);
			ncurses_deleteln(-1*$y_flow);
			//if(strlen($string))
				$this->y -= $y_flow;
		}
		ncurses_move($this->y,$this->x);
		ncurses_addstr($string);
//		ncurses_getyx(STDSCR,&$this->y,&$this->x);
		$this->y += $y_off;
		$this->x = ($y_off) ? 0 : ($this->x + strlen($string));
//		if(!strlen($string))
//			$this->x = 0;
		ncurses_move($this->y,$this->x);
		if($err) ncurses_wattroff(STDSCR,NCURSES_A_REVERSE);
		ncurses_refresh();
//		ncurses_getch();
	}

	public function ask($q,$a,$default=false){
		$a[0] = ($default) ? strtoupper($a[0]) : strtolower($a[0]);
		$a[1] = ($default) ? strtolower($a[1]) : strtoupper($a[1]);
		$b = array(strtolower(substr($a[0],0,1)),strtolower(substr($a[1],0,1)));
		$this->__out($q.' ('.$a[0].'/'.$a[1].'): ');
		$in = (@posix_isatty(STDOUT)) ? null : $b[($default)?0:1];
		while(is_null($in)){
			$in = ncurses_getch();
			if($in == 13) $in = ord($b[($default)?0:1]);
			$in = strtolower(chr($in));
			if(!in_array($in,$b)) $in = null;
		}
		$this->__out($in."\n");
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
