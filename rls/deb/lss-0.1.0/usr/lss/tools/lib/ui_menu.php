<?php
require_once(ROOT.'/tools/lib/func_dialog.php');

class UIMenu extends UI implements UIInt {

	//content buffer
	private $content = array();
	private $outbuf_filename = '';

	protected function _init(){
		$this->dialog_init();
		if($this::$debug) $this->out("[UIMenu init()]\n");
	}

	protected function _deinit($death_by_signal=false){
		if($this::$debug) $this->out("[UIMenu deinit()]\n");
	}

	public function __out($string,$err=false){
		if($err) $string = '\Zr\Z1'.$string.'\Zn';
		file_put_contents($this->outbuf_filename,$string,FILE_APPEND);
//		if($this::$debug) getc(STDIN);
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

//Dialog functions
	private function dialog_exec($args){
		if(is_array($args))
		 $args = join(' ',array_merge($args));
		$pipes = array(NULL,NULL,NULL);
		// Allow user to interact with dialog
		$i = fopen ('php://stdin' ,'r');
		$o = fopen ('php://stdout','w');
		// But tell PHP to redirect stderr so we can read it
		print $args;
		$p = proc_open('dialog '.$args,array(
			0=>$i,
			1=>$o,
			2=>array('pipe','w')
		),$pipes);
		// Wait for and read result
		$rv = stream_get_contents($pipes[2]);
		// Close all handles
		fclose($pipes[2]);
		fclose($o);
		fclose($i);
		proc_close($p);
		// Return result
		return $rv;
	}

	private function dialog_init(){
		$this->outbuf_filename = tempnam('/dev/shm/','DLG');
		$args = array();
		$args[] = '--clear';
		if($this->backtitle !== false)
			$args[] = sprintf('--backtitle "%s"',$this->backtitle);
		$args[] = sprintf('--begin %d %d',2,0);
		$args[] = '--scrollbar';
		$args[] = sprintf('--tailbox "%s" %d %d',$this->outbuf_filename,-1,-1);
		$this->dialog_exec($args);
	}

	private function dialog_deinit(){
		
	}

	private function dialog_textbox($content='',$begin=array(2,0),$size=array(-1,-1)){
		//dialog --backtitle "This is my Title" --begin 2 0 --scrollbar --textbox /var/log/syslog -1 -1
		$f = tempnam('/dev/shm/','DLG');
		file_put_contents($f,$content);
		$args = array();
		$args[] = '--clear';
		if($this->backtitle !== false)
			$args[] = sprintf('--backtitle "%s"',$backtitle);
		$args[] = sprintf('--begin %d %d',$begin[0],$begin[1]);
		$args[] = '--scrollbar';
		$args[] = sprintf('--textbox "%s" %d %d',$f,$size[0],$size[1]);
		$rv = $this->dialog_exec($args);
		unlink($f);
		return $rv;
	}

	private function dialog_tailboxbg($content='',$begin=array(2,0),$size=array(-1,-1)){
		//dialog --backtitle "This is my Title" --begin 2 0 --scrollbar --textbox /var/log/syslog -1 -1
		$f = tempnam('/dev/shm/','DLG');
		file_put_contents($f,$content);
		$args = array();
		$args[] = '--clear';
		if($this->backtitle !== false)
			$args[] = sprintf('--backtitle "%s"',$backtitle);
		$args[] = sprintf('--begin %d %d',$begin[0],$begin[1]);
		$args[] = '--scrollbar';
		$args[] = sprintf('--textbox "%s" %d %d',$f,$size[0],$size[1]);
		$rv = $this->dialog_exec($args);
		unlink($f);
		return $rv;
	}

}
