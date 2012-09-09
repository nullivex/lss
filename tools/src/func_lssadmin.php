<?php

function buildDeb($dir){
	if(!is_dir($dir)) throw new Exception('Valid working dir required',ERR_DIR_REQUIRED);
	
	$deb_dir = $dir.'lss_'.LSSTOOLS_VERSION;
	run("cd ".ROOT);
	
	UI::out("Clearing folders\n");
	run("rm -rf $deb_dir/usr");
	run("mkdir -p $deb_dir/usr/lss");
	
	UI::out("Copying new source code\n");
	run("cp -av tools $deb_dir/usr/lss");
	run("rm -rfv $deb_dir/usr/lss/bin/scripts");

	UI::out("Packaging man files\n");
	_installManFile($dir,'lss.man',$deb_dir.'/usr/share/man/man1');
	_installManFile($dir,'lssadmin.man',$deb_dir.'/usr/share/man/man1');

	UI::out("Building deb package\n");
	run("dpkg -b $deb_dir ".basename($deb_dir)."_all.deb");
	UI::out("Done\n");
}

function buildCore($dir,$mirror){
	if(!is_dir($dir)) throw new Exception('Valid working dir required',ERR_DIR_REQUIRED);
	if(is_null($dir)) throw new Exception('Mirror required',ERR_MIRROR_REQUIRED);
	//dos2unix all source files
	UI::out("Making sure all files are unix formatted\n");
	run("find $dir -type f -name \"*.php\" | xargs -I{} dos2unix {}");

	//refactor
	UI::out("Refactoring... ");
	foreach(explode("\n",CORE_PKGS) as $pkg)
		run("lss --refactor=$pkg --dir $dir");
	UI::out("done\n");

	//export
	UI::out("Exporting... ");
	foreach(explode("\n",CORE_PKGS) as $pkg)
		run("lss --export=$pkg --mirror $mirror");
	UI::out("done\n");

	//update db
	UI::out("Updating PKG DB...");
	run("lss --build-db --export-db --mirror ");
	UI::out("done\n");

	//complete
	UI::out("Completed\n");
}

function testCore($dir,$mirror,$target){
	//clear cache
	run("lss --clear-cache");
	
	//chain and build core
	buildCore($dir,$mirror);
	
	//clearing target
	UI::out("!!WARNING!! $target is about to be completely destroyed!!!\n");
	if(!defined('ANSWER_YES')){
		if(!$ui->ask("Are you sure you want to continue?",array('y','n'),false)) exit;
	} else {
		UI::out("Prompts have been disabled, press CTL+C within 3 seconds to abort\n");
		sleep(3);
	}
	run("rm -rf $target/*");
	run("rm -rf $target/.lss*");
	
	//update
	run("lss --update");
	
	//install packages
	run("lss -y -i ".implode(',',explode("\n",CORE_PKGS)));
	
	UI::out("Complete\n");
}

function installMan($dir){
	if(!is_dir($dir)) throw new Exception('Valid working dir required',ERR_DIR_REQUIRED);
	UI::out("Starting Man Page Update\n");
	_installManFile($dir,'lss.man');
	_installManFile($dir,'lssadmin.man');
	run("mandb");
	UI::out("Man Page Updated!\n");
}

function _installManFile($dir,$file,$install_path='/usr/share/man/man1'){
	$dstfile = str_replace('.man','.1',$file);
	run("cd $dir");
	run("cp $file $dstfile");
	run("gzip -f $dstfile");
	run("cp $dstfile.gz $install_path");
}

function splitApp($map){}

function usage(){ 
	UI::out(<<<'HELP'
SYNOPSIS
    lss [OPTIONS] --build-deb --dir <debroot_dir>

    lss [OPTIONS] --build-core --dir <core_dir> --mirror <local_mirror>

    lss [OPTIONS] --test-core --dir <core_dir> --mirror <local_mirror>

    lss [OPTIONS] --install-man --dir <man_src_dir>

    lss [OPTIONS] --split-app=<app_map>

    lss [OPTIONS] [DEV]
OPTIONS
    --help     -h ..............   display help info
    --yes      -y ..............   answer yes to all user prompts
    -v -vv -vvv   ..............   increase output
    -q -qq -qqq   ..............   decrease outpu
RUN-TIME SETTINGS
    --dir         <dir>.........   Specify a working directory (SEE SYNOPSIS for dir types, SEE EXAMPLES for usage)
    --mirror      <mirror>......   Specify a local mirror for exporting (SEE EXAMPLES for usage)
ACTIONS
    --build-db --dir <debroot_dir>
        Builds a debian package from the current dev tree. (REQUIRES FULL DEV TREE)
    --build-core --dir <core_dir> --mirror <local_mirror>
        Build all the core packages (used for testing and core development) (REQUIRES FULL DEV TREE)
    --test-core --dir <core_dir> --mirror <local_mirror>
        Test the core, will use the current default target and WILL DESTROY the current default target. (REQUIRES FULL DEV TREE)
    --install-man --dir <man_src_dir>
        (RE)Install manual pages for testing. (REQUIRES FULL DEV TREE)
    --split-app=<app_map>
        Split an existing application into packages using the app map.
        See http://wiki.openlss.org/doku.php?id=docs:howto:package_existing_applications for more details.

HELP
);
}