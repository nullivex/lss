<?php

function buildDeb($dir){
	if(!is_dir($dir)) throw new Exception('Valid working dir required',ERR_DIR_REQUIRED);
	
	$deb_dir = $dir.'/lss-'.LSSTOOLS_VERSION;
	
	UI::out("Clearing folders\n");
	run("rm -rf $deb_dir/usr");
	run("mkdir -p $deb_dir/usr/lss");
	
	UI::out("Copying new source code\n");
	run("cp -av ".ROOT."/bin $deb_dir/usr/lss");
	run("cp -av ".ROOT."/tools $deb_dir/usr/lss");

	UI::out("Packaging man files\n");
	_installManFile(ROOT.'/docs','lss.man',$deb_dir.'/usr/share/man/man1');
	_installManFile(ROOT.'/docs','lssadmin.man',$deb_dir.'/usr/share/man/man1');

	UI::out("Building deb package\n");
	run("dpkg -b $deb_dir ".dirname($deb_dir).'/'.basename($deb_dir)."_all.deb");
	UI::out("Done\n");
}

function buildCore($dir,$mirror,$pkgs=null){
	if(!is_dir($dir)) throw new Exception('Valid working dir required',ERR_DIR_REQUIRED);
	if(is_null($dir)) throw new Exception('Mirror required',ERR_MIRROR_REQUIRED);
	if(!is_array($pkgs)) $pkgs = explode("\n",file_get_contents($dir.'/.core_pkgs'));
	//dos2unix all source files
	UI::out("Making sure all files are unix formatted\n");
	run("find $dir -type f -name \"*.php\" | xargs -I{} dos2unix {}");

	//refactor
	UI::out("Refactoring... ");
	foreach($pkgs as $pkg)
		run("lss --refactor=$pkg --dir $dir");
	UI::out("done\n");

	//export
	UI::out("Exporting... ");
	foreach($pkgs as $pkg)
		run("lss --export=$pkg --mirror $mirror");
	UI::out("done\n");

	//update db
	UI::out("Updating PKG DB...");
	run("lss --build-db --export-db --mirror $mirror");
	UI::out("done\n");

	//complete
	UI::out("Completed\n");
}

function testCore($dir,$mirror,$target){
	$pkgs = explode("\n",file_get_contents($dir.'/.core_pkgs'));
	//clear cache
	run("lss --clear-cache");
	
	//chain and build core
	buildCore($dir,$mirror,$pkgs);
	
	//clearing target
	UI::out("!!WARNING!! $target is about to be completely destroyed!!!\n");
	if(!defined('ANSWER_YES')){
		if(!UI::_get()->ask("Are you sure you want to continue?",array('y','n'),false)) exit;
	} else {
		UI::out("Prompts have been disabled, press CTL+C within 3 seconds to abort\n");
		sleep(3);
	}
	run("rm -rf $target/*");
	run("rm -rf $target/.lss*");
	
	//update
	run("lss --update");
	
	//install packages
	run("lss -y -i ".implode(',',$pkgs));
	
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
	run("cp $dir/$file $dir/$dstfile");
	run("gzip -f $dir/$dstfile");
	run("mkdir -p $install_path");
	run("cp $dir/$dstfile.gz $install_path");
}

function splitApp($mapfile){
	require_once(ROOT.'/tools/lib/gendef.php');
	require_once(ROOT.'/tools/lib/tml.php');
	if(!file_exists($mapfile)) throw new Exception('App Map file does not exist',ERR_APP_MAP_NOT_FOUND);
	//load and parse the map into an mdarray
	$map = TML::toArray(file_get_contents($mapfile));
	if(!isset($map['app'])) throw new Exception('Invalid map file',ERR_MAP_FILE_INVALID);
	$mirror = mda_get($map,'app.mirror');
	$source = mda_get($map,'app.source');
	//start making packages
	foreach(mda_get($map,'app.packages') as $pkgname => $pkg){
		UI::out("\n\n");
		//delete any existing package
		run("lss --delete=$pkgname");
		//create the package
		run("lss --create=$pkgname");
		//set version
		run("lss --set=$pkgname --name info.version --value ".mda_get($pkg,'version'));
		//set description
		run("lss --set=$pkgname --name info.description --value ".mda_get($pkg,'description'));
		//add dependencies
		if(is_array(mda_get($pkg,'depends')))
			foreach(mda_get($pkg,'depends') as $dep => $dep_ver)
				run("lss --add=$pkgname --name dep.$dep.versions --value $dep_ver");
		//add manifest
		if(is_array(mda_get($pkg,'manifest')))
			foreach(mda_get($pkg,'manifest') as $file)
				run("lss --add=$pkgname --name manifest --value $file");
	}
	//refactor packages
	foreach(array_keys(mda_get($map,'app.packages')) as $pkgname){
		UI::out("\n\n");
		run("lss --refactor=$pkgname --dir=$source");
	}
	//export packages
	foreach(array_keys(mda_get($map,'app.packages')) as $pkgname){
		UI::out("\n\n");
		run("lss --export=$pkgname --mirror=$mirror");
	}
	//build-db
	run("lss --build-db");
	//export db
	run("lss --export-db --mirror=$mirror");
	//clear local cache, update
	run("lss --clear-cache --update");
}

function usage(){ 
	UI::out(<<<'HELP'
SYNOPSIS
    lss [OPTIONS] --build-deb --dir <debroot_dir>

    lss [OPTIONS] --build-core --dir <core_dir> --mirror <local_mirror>

    lss [OPTIONS] --test-core --dir <core_dir> --mirror <local_mirror>

    lss [OPTIONS] --install-man --dir <man_src_dir>

    lss [OPTIONS] --split-app=<app_map>

OPTIONS
    --help     -h ..............   display help info
    --yes      -y ..............   answer yes to all user prompts
    -v -vv -vvv   ..............   increase output
    -q -qq -qqq   ..............   decrease output

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