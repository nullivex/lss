<?php

class UIMenu extends UI implements UIInt {

	//content buffer
	private $content = array();
	
	//screen definitions
	private $screen_atr = NCURSES_A_NORMAL;
	private $backtitle = false;
	
	//coordinate tracking
	private $cols = -1;
	private $rows = -1;
	private $view_row = 0;
	private $view_col = 0;
	private $x = 0;
	private $y = 0;

	protected function _init(){
		ncurses_init();
		ncurses_savetty();
		ncurses_keypad(STDSCR,true);
		ncurses_cbreak();
		ncurses_noecho();
		ncurses_nonl();
		$this->_resize();
//		ncurses_curs_set(0);
		$this->y = $this->rows-1;
		$this->x = 0;
		if($this::$debug) $this->out("[UIMenu init()]\n");
	}

	protected function _deinit($death_by_signal=false){
		if($this::$debug) $this->out("[UIMenu deinit()]\n");
		if(!$death_by_signal){
			$this->out("[END]",true);
			ncurses_timeout(250);
			while($in=ncurses_getch()){
				pcntl_signal_dispatch();
				if(in_array($in,array(13,27,ord('q'))))
					break;
			}
		}
		ncurses_refresh();
		ncurses_resetty();
		ncurses_end();
	}

	protected function _resize(){
//		list($prev_rows,$prev_cols)=array($this->rows,$this->cols);
		ncurses_getmaxyx(STDSCR,&$this->rows,&$this->cols);
		ncurses_erase();
		for($c=1;$c<$this->cols;$c++)
			for($r=1;$r<$this->rows;$r++)
				ncurses_mvaddstr($r,$c,' ');
		if($this->backtitle != false){
			int i;
			ncurses_wattrset(STDSCR,$this->screen_atr);
			ncurses_mvwaddstr(STDSCR,0,1,$this->backtitle);
			ncurses_wmove(STDSCR, 1, 1);
			for($i=1;$i<$this->cols-1;$i++)
				ncurses_waddch(STDSCR, ACS_HLINE);
		}
		ncurses_wnoutrefresh(STDSCR);

		$cr = $this->view_row;
		while(1){
			$this->screen[$this->content[$cr];
		}
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
			$this->y -= $y_flow;
		}
		ncurses_move($this->y,$this->x);
		ncurses_addstr($string);
		$this->y += $y_off;
		$this->x = ($y_off) ? 0 : ($this->x + strlen($string));
		ncurses_move($this->y,$this->x);
		if($err) ncurses_wattroff(STDSCR,NCURSES_A_REVERSE);
		ncurses_refresh();
		if($this::$debug) ncurses_getch();
	}

	public function __in(&$rv,$len=-1){
		
	}

	public function ask($q,$a,$default=false){
		$a[0] = ($default) ? strtoupper($a[0]) : strtolower($a[0]);
		$a[1] = ($default) ? strtolower($a[1]) : strtoupper($a[1]);
		$b = array(strtolower(substr($a[0],0,1)),strtolower(substr($a[1],0,1)));
		$this->out($q.' ('.$a[0].'/'.$a[1].'): ');
		$in = ($this->is_a_tty) ? null : $b[($default)?0:1];
		while(is_null($in)){
			$in = ncurses_getch();
			if($in == 13) $in = ord($b[($default)?0:1]);
			$in = strtolower(chr($in));
			if(!in_array($in,$b)) $in = null;
		}
		$this->out($in."\n");
		if($in !== $b[0]) return false;
		return true;
	}

	public function input($q,&$a){
		//TODO: this.
	}

	public function password($q,&$a){
		//TODO: this.
	}

	public function select($q,&$a,$default=0){
		//TODO: this.
	}

}
