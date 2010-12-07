<?php
/*
File: templates/default/inc/func.php
This script is copyright to ArcadeTradeScript.com. Unauthorized use or distribution of this script is illegal.
*/

 
function showGame($templateBase,$fileType,$gameFile,$gameWidth,$gameHeight,$customCode) {
	if ($fileType == 1) {
	?>
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,16,0" id="game"  height="<?=$gameHeight;?>" width="<?=$gameWidth;?>">
			<param name="movie" value="<?=$gameFile;?>" />
			<param name="quality" value="high" />
			<param name="allowscriptaccess" value="never" />
			<embed src="<?=$gameFile;?>?affiliate_id=3c32ddf5c83a38be" height="<?=$gameHeight;?>" width="<?=$gameWidth;?>" quality="high" name="FlashContent" allowscriptaccess="never" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
		</object>
	<?
	} elseif ($fileType == 2) {
	?>
		<object classid="clsid:166B1BCA-3F9C-11CF-8075-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=9,0,0,0" width="<?=$gameWidth;?>" height="<?=$gameHeight;?>">
			<param name="swRemote" value="swsaveenabled='true' swvolume='true' swrestart='true' swpauseplay='true' swfastforward='true' swcontextmenu='true' " />
			<param name="swStretchStyle" value="fill" />
			<param name="swURL" value="" />
			<param name="bgColor" value="#FFD08A" />
			<param name="src" value="<?=$gameFile;?>" />
			<embed src="<?=$gameFile;?>?affiliate_id=3c32ddf5c83a38be" swurl="" bgcolor="#141414" width="<?=$gameWidth;?>" height="<?=$gameHeight;?>" swremote="swsaveenabled='true' swvolume='true' swrestart='true' swpauseplay='true' swfastforward='true' swcontextmenu='true'" swstretchstyle="fill" type="application/x-director" pluginspage="http://www.macromedia.com/shockwave/download/"></embed>
		  </object>
	<?
	} elseif ($fileType == 3) {
	?>
		<object id="MediaPlayer1" classid="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95" standby="Loading Microsoft Windows Media Player components..." width="450"  height="300">
			<param name="fileName" value="<?=$gameFile;?>?affiliate_id=3c32ddf5c83a38be">
			<param name="animationatStart" value="true">
			<param name="transparentatStart" value="true">
			<param name="autoStart" value="true">
			<param name="showControls" value="true">
			<param name="ShowStatusBar" value="True">
			<embed type="application/x-mplayer2" pluginspage="http://microsoft.com/windows/mediaplayer/en/download" 
			id="MediaPlayer"
			width="450"  
			height="325"  
			src="<?=$gameFile;?>"  
			autosize="1"  
			autostart="1"  
			clicktoplay="1" 
			displaysize="0"  
			enablecontextmenu="1" 
			enablefullscreencontrols="1" 
			enabletracker="1"  
			mute="0" 
			playcount="1" 
			showcontrols="1"  
			showaudiocontrols="1"  
			showdisplay="0"  
			showgotocar="0"  
			showpositioncontrols="1"  
			showstatusBar="1"  
			showtracker="1"></embed>
		</object>
	<?
	} elseif ($fileType == 4) {
	?>
		<img src="<?=$gameFile;?>?affiliate_id=3c32ddf5c83a38be" height="<?=$gameHeight;?>" width="<?=$gameWidth;?>" alt="<?=$gametitle;?>" title="<?=$gametitle;?>" />
	<?
	} elseif ($fileType == 5) {
		echo $customCode;
	}
}

function otherGames($baseDir,$templateBase,$seoFriendly,$preGamePageStatus,$catID,$max) {
	$loopNum = 1;
	$result = mysql_query("SELECT * FROM ats_games WHERE gStatus = 1 AND cat = '$catID' ORDER BY rand() LIMIT $max");
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$gameID = $row['gid'];
			$gameName = remove_slashes($row['gName']);
			$gameDesc = remove_slashes($row['gDesc']);
			$gameAltDesc = remove_slashes($row['altDesc']);
			$gameKeywords = $row['gKeywords'];
			$gameTags = $row['gTags'];
			$gameHow = remove_slashes($row['gHow']);
			$gameFile = $row['gFile'];
			$gameFileHosted = $row['gFileHosted'];
			$gameWidth = $row['width'];
			$gameHeight = $row['height'];
			$fileType = $row['fileType'];
			$tradeType = $row['tradeType'];
			$gameIcon1 = $row['gIcon1'];
			$gameIcon1Hosted = $row['gIcon1Hosted'];
			$gameIcon2 = $row['gIcon2'];
			$gameIcon2Hosted = $row['gIcon2Hosted'];
			$gameIcon3 = $row['gIcon3'];
			$gameIcon3Hosted = $row['gIcon3Hosted'];
			$timesPlayed = $row['played'];
			$timesPlayedToday = $row['playedToday'];
			$rating = $row['rating'];
			$timesRated = $row['timesRated'];
			$customCode = remove_slashes($row['customCode']);
			$addedOn = $row['addedOn'];
			
			if ($gameIcon1Hosted == 1) {
				$gameIcon1 = $baseDir."content/icons/".$gameIcon1;
			}
			
			if ($gameIcon2Hosted == 1) {
				$gameIcon2 = $baseDir."content/icons/".$gameIcon2;
			}
			
			if ($gameIcon3Hosted == 1) {
				$gameIcon3 = $baseDir."content/icons/".$gameIcon3;
			}
			
			if ($seoFriendly == 1) {
				$gameLink = $baseDir.seoGameLink($catID,$tradeType,$gameFile,$preGamePageStatus).seo_str_plain("play-".$gameName);
			} else {
				$gameLink = $baseDir."index.php?a=play&amp;id=$gameID";
			}
			
			if ($x == 4) {
				echo '</div><div style="width: 100%; float: left;">';
			}
			
			?>
<td align="center" valign="top"><a href="<?=$gameLink;?>"><img src='<?=$gameIcon1;?>' alt='<?=$gameName;?> Icon' style="width:90px;height:75px;" /></a></td>

			<?
			
			$loopNum ++;
		}
	}
}

function templatesDropDown($baseDir,$templateBase,$template,$adminLoggedIn) {
	if  ($adminLoggedIn != "") {
		$result = mysql_query("SELECT * FROM ats_templates ORDER BY tName ASC") or die(mysql_error());
	} else {
		$result = mysql_query("SELECT * FROM ats_templates WHERE tStatus = 1 ORDER BY tName ASC") or die(mysql_error());
	}
	
	if (mysql_num_rows($result)) {
		echo '<select name="switchTemplate" onchange="location.href = \''.$baseDir.'index.php?a=settemplate&amp;id=\' +this.value;">';
		while ($row = mysql_fetch_array($result)) {
			if ($row['folder'] == $template) {
				echo '<option value="'.$row['tid'].'" selected>'.$row['tName'].'</option>';
			} else {
				echo '<option value="'.$row['tid'].'">'.$row['tName'].'</option>';
			}
		}
		echo '</select>';
	}
}

function tradesDropDown($baseDir,$templateBase,$template) {
	echo '<select name="currentPartners" onchange="location.href = \''.$baseDir.'index.php?a=newplugs&amp;id=\' +this.value;">';
	echo '<option value="">Select your site to add plugs to it</option>';
	$result = mysql_query("SELECT * FROM ats_trades WHERE tradeStatus = 1 ORDER BY tradeurl ASC");
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			echo '<option value="'.$row['tradeid'].'">'.ucwords(plug_domain2($row['tradeurl'])).'</option>';
		}
	}
	echo '</select>';
}

function showCategoryMenu($baseDir,$seoFriendly,$action,$catID,$templateBase) {
	$list = "";
	$loopNum = 1;
	$result = mysql_query("SELECT * FROM ats_categories WHERE cStatus = 1 ORDER BY cName ASC");
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$catID = $row['cid'];
			$catName = remove_slashes($row['cName']);
			
			if ($seoFriendly == 1) {
				$link = $baseDir.seo_str_plain($catName);
			} else {
				$link = $baseDir.'index.php?a=gamelist&amp;id='.$catID;
			}
			$catName = str_ireplace(" Games","",$catName);
			
			
			$numGames = mysql_query("SELECT * FROM ats_games WHERE gStatus = 1 AND cat = '$catID'");
			if (mysql_num_rows($numGames)) {
				if ($loopNum != 1) {
					$list .= "";
				}
				if ($action == "gamelist" && $catID == $catID) {
					$list .= '<li><a href="'.$link.'">'.$catName.'</a></li>';
				} else {
					$list .= '<li><a href="'.$link.'">'.$catName.'</a></li>';
				}
			}
			
			$loopNum ++;
		}
		if ($seoFriendly == 1) {
			$link2 = $baseDir."most-popular";
			$link3 = $baseDir."top-rated";
		} else {
			$link2 = $baseDir."index.php?a=gamelist&amp;topPlayed=1";
			$link3 = $baseDir."index.php?a=gamelist&amp;topRated=1";
		}
		$list .= '<a href="'.$link2.'">Most Popular</a>';
		$list .= '<a href="'.$link3.'">Top Rated</a>';
	}
	return $list;
}

function showPageMenu($baseDir,$seoFriendly,$templateBase,$addHome) {
	$list = "";
	if ($addHome == 1) {
		$list .= '<a href="'.$baseDir.'">Home</a>';
	}
	
	$loopNum = 1;
	$result = mysql_query("SELECT * FROM ats_pages WHERE pageStatus = 1 ORDER BY pageName ASC");
	$totalPages = mysql_num_rows($result);
	if ($totalPages > 0) {
		while ($row = mysql_fetch_array($result)) {
			$pageName = remove_slashes($row['pageName']);
			
			if ($seoFriendly == 1) {
				$link = $baseDir."pages/".seo_str_plain($pageName);
			} else {
				$link = $baseDir.'index.php?a=pages&amp;id='.$row['pid'];
			}
			
			if ($loopNum == 1 && $addHome == 1) {
				$list .= " | ";
			}
			
			if ($loopNum == $totalPages) {
				$list .= '<a href="'.$link.'">'.$pageName.'</a>';
			} else {
				$list .= '<a href="'.$link.'">'.$pageName.'</a> | ';
			}
			
			$loopNum ++;
		}
	}
	return $list;
}

function showGameTags($baseDir,$templateBase,$seoFriendly,$tags){
	if ($tags != "") {
		if (preg_match("/,/",$tags) == false) {
			$sep_tags = explode(" ", $tags);
		} else {
			$tags = preg_replace("/,[\s+]/",",",$tags);
			$sep_tags = explode(",", $tags);
		}
		$TotalTags = count($sep_tags);
		$tagLinks = "";
		for($i=0; $i < $TotalTags; $i++){
			$tag = seo_str_plain(trim($sep_tags[$i]));
			if($seoFriendly == 1) {
				$tagLink = $baseDir."tags/".$tag;
			} else {
				$tagLink = $baseDir."index.php?a=gamelist&tag=$tag";
			}
			
			if ($sep_tags[$i] != "" && preg_match("/^ +$/",$sep_tags[$i]) == false) {
				$tagLinks .= "<a href=\"$tagLink\">$sep_tags[$i]</a>";
				if($i < $TotalTags-1) {
					$tagLinks .=", ";
				}
			}
	
		}
	} else {
		$tagLinks = "";
	}
	return $tagLinks;
}

function topReferrers($baseDir,$templateBase,$seoFriendly,$maxTopLinks,$sortTopLinks,$topLinksReal) {
	if ($maxTopLinks == 0) {
		return;
	}
	
	if ($sortTopLinks == "") {
		$sortTopLinks = "inToday DESC";
	}
	
	$list = "";
	$loopNum = 1;
	$linkNum = 1;
	$result = mysql_query("SELECT * FROM ats_trades WHERE tradeStatus = 1 ORDER BY $sortTopLinks");
	$totalTrades = mysql_num_rows($result);
	if ($totalTrades > 0) {
		while ($row = mysql_fetch_array($result)) {
			$tradeID = $row['tradeid'];
			$tradeURL = $row['tradeurl'];
			$tradeAnchor = $row['anchor'];
			$inToday = $row['inToday'];
			$inOverall = $row['inOverall'];
			$credits = $row['credits'];
			$tradeDesc = $row['description'];
			
			if (!isset($_COOKIE['ref_'.$tradeID]) && !isset($_COOKIE['sentto_'.$tradeID])) {
				if ($topLinksReal == 1) {
					$list .= $linkNum.". <a href=\"$tradeURL\" title=\"$tradeAnchor\">$tradeAnchor</a><br />";
				} else {
					$list .= $linkNum.". <a href=\"".$baseDir."out.php?id=$tradeID\" title=\"$tradeAnchor\">$tradeAnchor</a><br />";
				}
				$linkNum ++;
			}
			$loopNum ++;
			
			if ($linkNum == $maxTopLinks || $loopNum == $maxTopLinks) {
				break;
			}
		}
		
		
	}
	if ($seoFriendly == 1) {
		$link2 = $baseDir."trade-traffic";
		$link3 = $baseDir."trade-stats";
	} else {
		$link2 = $baseDir."index.php?a=trade-traffic";
		$link3 = $baseDir."index.php?a=trade-stats";
	}
	$list .= '<a href="'.$link2.'">Trade Traffic</a><br />';
	$list .= '<a href="'.$link3.'">Trade Stats</a>';
	return $list;
}

function allReferrers($baseDir,$templateBase,$sortTopLinks,$statsLinksReal) {
	if ($sortTopLinks == "") {
		$sortTopLinks = "credits DESC";
	}
	
	$list = "";
	$result = mysql_query("SELECT * FROM ats_trades WHERE tradeStatus = 1 ORDER BY $sortTopLinks");
	$totalTrades = mysql_num_rows($result);
	if ($totalTrades > 0) {
		while ($row = mysql_fetch_array($result)) {
			$tradeID = $row['tradeid'];
			$tradeURL = $row['tradeurl'];
			$tradeAnchor = $row['anchor'];
			$tradeDesc = $row['description'];
			
			if ($topLinksReal == 1) {
				$list .= "<a href=\"$tradeURL\" title=\"$tradeAnchor\" style=\"font-weight: bold;\">$tradeAnchor</a> - $tradeDesc<br />";
			} else {
				$list .= "<a href=\"".$baseDir."out.php?id=$tradeID\" title=\"$tradeAnchor\" style=\"font-weight: bold;\">$tradeAnchor</a> - $tradeDesc<br />";
			}
			$linkNum ++;
		}
	}
	return $list;
}

function newestGames($baseDir,$templateBase,$seoFriendly,$preGamePageStatus,$catID,$maxNewestGames) {
	if ($catID != "" && $catID != 0) {
		$catQuery = "AND cat = '$catID'";
	}
	
	$list = "";
	$loopNum = 1;
	$result = mysql_query("SELECT * FROM ats_games WHERE gStatus = 1 $catQuery ORDER BY addedOn DESC LIMIT $maxNewestGames");
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$gameID = $row['gid'];
			$gameName = remove_slashes($row['gName']);
			$gameTags = $row['gTags'];
			$gameCat = $row['cat'];
			$gameDesc = remove_slashes($row['gDesc']);
			$gameCatName = getGameCat($gameCat);
			$gameFile = $row['gFile'];
			$tradeType = $row['tradeType'];
			$gameIcon1 = $row['gIcon1'];
			$gameIcon1Hosted = $row['gIcon1Hosted'];
			$gameIcon2 = $row['gIcon2'];
			$gameIcon2Hosted = $row['gIcon2Hosted'];
			$gameIcon3 = $row['gIcon3'];
			$gameIcon3Hosted = $row['gIcon3Hosted'];
			$timesPlayed = $row['played'];
			$timesPlayedToday = $row['playedToday'];
			$rating = $row['rating'];
			$addedOn = $row['addedOn'];
			
			if ($tradeType == 2 && $preGamePageStatus == "0") {
				$gameLink = $gameFile;
			} else {
				if ($seoFriendly == 1) {
					$gameLink = $baseDir.seo_str($gameCatName).seo_str_plain("play-".$gameName);
				} else {
					$gameLink = $baseDir."index.php?a=play&amp;id=$gameID";
				}
			}
			
			if ($gameIcon1Hosted == 1) {
				$gameIcon1 = $baseDir."content/icons/".$gameIcon1;
			}
			
			if(strlen($gameDesc) > 135){
				$gameDesc = substr($gameDesc,0,135);
			}
			
			if(strlen($gameName) >= 25){
				$gameName = substr($gameName,0,25);
			}
			
			
			$list .= '    <tr>

    <td style="width: 50%;"><table><tbody><tr><td rowspan="2" style="width: 1%; vertical-align: top;">
<a href="'.$gameLink.'"><img src="'.$gameIcon1.'" alt="'.$gameName.' Icon" style="width: 110px; height: 90px;"></a></td><td style="width: 99%; vertical-align: top;" align="left" height="80%">
<a href="'.$gameLink.'"><b>'.$gameName.'</b></a><br>
    '.$gameDesc.'<br /></td>
    </tr>

    </tbody></table></td>

    </tr>';
			$loopNum ++;
		}
	}
	
	return $list;
}

function topRated($baseDir,$templateBase,$seoFriendly,$preGamePageStatus,$catID,$maxTopRatedGames) {
	if ($catID != "" && $catID != 0) {
		$catQuery = "AND cat = '$catID'";
	}
	
	$list = "";
	$loopNum = 1;
	$result = mysql_query("SELECT * FROM ats_games WHERE gStatus = 1 $catQuery ORDER BY rating DESC, voteScore DESC LIMIT $maxTopRatedGames");
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$gameID = $row['gid'];
			$gameName = remove_slashes($row['gName']);
			$gameTags = $row['gTags'];
			$gameCat = $row['cat'];
			$gameCatName = getGameCat($gameCat);
			$gameFile = $row['gFile'];
			$tradeType = $row['tradeType'];
			$gameIcon1 = $row['gIcon1'];
			$gameIcon1Hosted = $row['gIcon1Hosted'];
			$gameIcon2 = $row['gIcon2'];
			$gameIcon2Hosted = $row['gIcon2Hosted'];
			$gameIcon3 = $row['gIcon3'];
			$gameIcon3Hosted = $row['gIcon3Hosted'];
			$timesPlayed = $row['played'];
			$timesPlayedToday = $row['playedToday'];
			$rating = $row['rating'];
			$addedOn = $row['addedOn'];
			
			if ($tradeType == 2 && $preGamePageStatus == "0") {
				$gameLink = $gameFile;
			} else {
				if ($seoFriendly == 1) {
					$gameLink = $baseDir.seo_str($gameCatName).seo_str_plain("play-".$gameName);
				} else {
					$gameLink = $baseDir."index.php?a=play&amp;id=$gameID";
				}
			}
			
			if ($gameIcon1Hosted == 1) {
				$gameIcon1 = $baseDir."content/icons/".$gameIcon1;
			}
			
			$list .= '<div class="game" align="center">
                            <a href="'.$gameLink.'"><img src="'.$gameIcon1.'" width="100" height="100" border="0" alt="'.$gameName.' Icon" /></a><br />
                            <a href="'.$gameLink.'">'.$gameName.'</a>
                        </div>';
			$loopNum ++;
		}
	}
	
	return $list;
}

function topPlayed($baseDir,$templateBase,$seoFriendly,$preGamePageStatus,$catID,$maxTopPlayedGames) {
	if ($catID != "" && $catID != 0) {
		$catQuery = "AND cat = '$catID'";
	}
	
	$list = "";
	$loopNum = 1;
	$result = mysql_query("SELECT * FROM ats_games WHERE gStatus = 1 $catQuery ORDER BY played DESC LIMIT $maxTopPlayedGames");
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {

			$gameID = $row['gid'];
			$gameName = remove_slashes($row['gName']);
			
			$gameTags = $row['gTags'];
			$gameCat = $row['cat'];
			$gameCatName = getGameCat($gameCat);
			$gameFile = $row['gFile'];
			$tradeType = $row['tradeType'];
			$gameIcon1 = $row['gIcon1'];
			$gameIcon1Hosted = $row['gIcon1Hosted'];
			$gameIcon2 = $row['gIcon2'];
			$gameIcon2Hosted = $row['gIcon2Hosted'];
			$gameIcon3 = $row['gIcon3'];
			$gameIcon3Hosted = $row['gIcon3Hosted'];
			$timesPlayed = $row['played'];
			$timesPlayedToday = $row['playedToday'];
			$rating = $row['rating'];
			$addedOn = $row['addedOn'];
			
			if ($tradeType == 2 && $preGamePageStatus == "0") {
				$gameLink = $gameFile;
			} else {
				if ($seoFriendly == 1) {
					$gameLink = $baseDir.seo_str($gameCatName).seo_str_plain("play-".$gameName);
				} else {
					$gameLink = $baseDir."index.php?a=play&amp;id=$gameID";
				}
			}
			
			if ($gameIcon1Hosted == 1) {
				$gameIcon1 = $baseDir."content/icons/".$gameIcon1;
			}
			
			if(strlen($gameName) > 16){
				$gameName = substr($gameName,0,16);
			}
			
			$list .= '<td align="center" valign="middle" style="height:90px"><a href="'.$gameLink.'">'.$gameName.'<br /><img src="'.$gameIcon1.'" width="90" height="75" border="0" alt="Play '.$gameName.'" /></a></td>';
			if($loopNum % 3 == 0){
				$list .= "</tr><tr>";
			}
			$loopNum ++;
		}
	}
	
	return $list;
}

function randomGames($baseDir,$templateBase,$seoFriendly,$preGamePageStatus,$catID,$max) {
	if ($catID != "" && $catID != 0) {
		$catQuery = "AND cat = '$catID'";
	}
	
	$list = "";
	$loopNum = 1;
	$result = mysql_query("SELECT * FROM ats_games WHERE gStatus = 1 $catQuery ORDER BY rand() LIMIT $max");
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$gameID = $row['gid'];
			$gameName = remove_slashes($row['gName']);
			$gameTags = $row['gTags'];
			$gameCat = $row['cat'];
			$gameCatName = getGameCat($gameCat);
			$gameFile = $row['gFile'];
			$tradeType = $row['tradeType'];
			$gameIcon1 = $row['gIcon1'];
			$gameIcon1Hosted = $row['gIcon1Hosted'];
			$gameIcon2 = $row['gIcon2'];
			$gameIcon2Hosted = $row['gIcon2Hosted'];
			$gameIcon3 = $row['gIcon3'];
			$gameIcon3Hosted = $row['gIcon3Hosted'];
			$timesPlayed = $row['played'];
			$timesPlayedToday = $row['playedToday'];
			$rating = $row['rating'];
			$addedOn = $row['addedOn'];
			
			if ($tradeType == 2 && $preGamePageStatus == "0") {
				$gameLink = $gameFile;
			} else {
				if ($seoFriendly == 1) {
					$gameLink = $baseDir.seo_str($gameCatName).seo_str_plain("play-".$gameName);
				} else {
					$gameLink = $baseDir."index.php?a=play&amp;id=$gameID";
				}
			}
			
			if ($gameIcon1Hosted == 1) {
				$gameIcon1 = $baseDir."content/icons/".$gameIcon1;
			}
			
			$list .= '<div class="game" align="center">
                            <a href="'.$gameLink.'"><img src="'.$gameIcon1.'" width="100" height="100" border="0" alt="'.$gameName.' Icon" /></a><br />
                            <a href="'.$gameLink.'">'.$gameName.'</a>
                        </div>';
			$loopNum ++;
		}
	}
	
	return $list;
}

function beingPlayedNow($baseDir,$templateBase,$seoFriendly,$preGamePageStatus,$catID,$max) {
	if ($catID != "" && $catID != 0) {
		$catQuery = "AND cat = '$catID'";
	}
	
	$list = "";
	$loopNum = 1;
	$result = mysql_query("SELECT * FROM ats_games WHERE gStatus = 1 $catQuery ORDER BY lastPlayed DESC LIMIT $max");
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$gameID = $row['gid'];
			$gameName = remove_slashes($row['gName']);
			$gameTags = $row['gTags'];
			$gameCat = $row['cat'];
			$gameCatName = getGameCat($gameCat);
			$gameFile = $row['gFile'];
			$tradeType = $row['tradeType'];
			$gameIcon1 = $row['gIcon1'];
			$gameIcon1Hosted = $row['gIcon1Hosted'];
			$gameIcon2 = $row['gIcon2'];
			$gameIcon2Hosted = $row['gIcon2Hosted'];
			$gameIcon3 = $row['gIcon3'];
			$gameIcon3Hosted = $row['gIcon3Hosted'];
			$timesPlayed = $row['played'];
			$timesPlayedToday = $row['playedToday'];
			$rating = $row['rating'];
			$addedOn = $row['addedOn'];
			
			if ($tradeType == 2 && $preGamePageStatus == "0") {
				$gameLink = $gameFile;
			} else {
				if ($seoFriendly == 1) {
					$gameLink = $baseDir.seo_str($gameCatName).seo_str_plain("play-".$gameName);
				} else {
					$gameLink = $baseDir."index.php?a=play&amp;id=$gameID";
				}
			}
			
			if ($gameIcon1Hosted == 1) {
				$gameIcon1 = $baseDir."content/icons/".$gameIcon1;
			}
			
			$list .= '<div class="game" align="center">
                            <a href="'.$gameLink.'"><img src="'.$gameIcon1.'" width="100" height="100" border="0" alt="Play '.$gameName.'" /></a><br />
                            <a href="'.$gameLink.'">'.$gameName.'</a>
                        </div>';
			$loopNum ++;

		}
	}
	
	return $list;
}

function featuredGames($baseDir,$templateBase,$seoFriendly,$preGamePageStatus,$catID,$max) {
	if ($catID != "" && $catID != 0) {
		$catQuery = "AND cat = '$catID'";
	}
	
	$list = "";
	$loopNum = 1;
	$result = mysql_query("SELECT * FROM ats_games WHERE gStatus = 1 AND featured = 1 $catQuery ORDER BY rand() LIMIT $max");
	if (mysql_num_rows($result)) {
		while ($row = mysql_fetch_array($result)) {
			$gameID = $row['gid'];
			$gameName = remove_slashes($row['gName']);
			
			$gameTags = $row['gTags'];
			$gameCat = $row['cat'];
			$gameCatName = getGameCat($gameCat);
			$gameFile = $row['gFile'];
			$tradeType = $row['tradeType'];
			$gameIcon1 = $row['gIcon1'];
			$gameIcon1Hosted = $row['gIcon1Hosted'];
			$gameIcon2 = $row['gIcon2'];
			$gameIcon2Hosted = $row['gIcon2Hosted'];
			$gameIcon3 = $row['gIcon3'];
			$gameIcon3Hosted = $row['gIcon3Hosted'];
			$timesPlayed = $row['played'];
			$timesPlayedToday = $row['playedToday'];
			$rating = $row['rating'];
			$addedOn = $row['addedOn'];
			
			if ($tradeType == 2 && $preGamePageStatus == "0") {
				$gameLink = $gameFile;
			} else {
				if ($seoFriendly == 1) {
					$gameLink = $baseDir.seo_str($gameCatName).seo_str_plain("play-".$gameName);
				} else {
					$gameLink = $baseDir."index.php?a=play&amp;id=$gameID";
				}
			}
			
			if ($gameIcon1Hosted == 1) {
				$gameIcon1 = $baseDir."content/icons/".$gameIcon1;
			}
			
			if(strlen($gameName) > 16){
				$gameName = substr($gameName,0,16);
			}
			
			$list .= '<td align="center" valign="middle" style="height:90px"><a href="'.$gameLink.'">'.$gameName.'<br /><img src="'.$gameIcon1.'" alt="Play '.$gameName.'" style="width:90px;height:75px;" /></a></td>';
			if($loopNum % 3 == 0){
				$list .= "</tr><tr>";
			}
			$loopNum ++;
		}
	}
	
	return $list;
}

function searchGames($baseDir,$templateBase,$seoFriendly,$preGamePageStatus,$catID,$max) {
	if ($catID != "" && $catID != 0) {
		$catQuery = "AND cat = '$catID'";
	}
	
	$list = "";
	$loopNum = 1;
	$result = mysql_query("
		SELECT g.*
		FROM ats_games AS g 
		WHERE 
			g.gStatus = 1 AND 
			g.gName LIKE '%".$_GET['q']."%'
		ORDER BY rand()"
	);
	if (mysql_num_rows($result) or die(mysql_error())) {
		$names = array();
		$i = 0;
		while ($row = mysql_fetch_array($result)) {
			if($i >= $max){
				break;
			}
			if(in_array($row['gName'],$names)){
				continue; 
			} else {
				$names[] = $row['gName'];
				$i++;
			}
			$gameID = $row['gid'];
			$gameName = remove_slashes($row['gName']);
			
			$gameTags = $row['gTags'];
			$gameCat = $row['cat'];
			$gameCatName = getGameCat($gameCat);
			$gameFile = $row['gFile'];
			$tradeType = $row['tradeType'];
			$gameIcon1 = $row['gIcon1'];
			$gameIcon1Hosted = $row['gIcon1Hosted'];
			$gameIcon2 = $row['gIcon2'];
			$gameIcon2Hosted = $row['gIcon2Hosted'];
			$gameIcon3 = $row['gIcon3'];
			$gameIcon3Hosted = $row['gIcon3Hosted'];
			$timesPlayed = $row['played'];
			$timesPlayedToday = $row['playedToday'];
			$rating = $row['rating'];
			$addedOn = $row['addedOn'];
			
			if ($tradeType == 2 && $preGamePageStatus == "0") {
				$gameLink = $gameFile;
			} else {
				if ($seoFriendly == 1) {
					$gameLink = $baseDir.seo_str($gameCatName).seo_str_plain("play-".$gameName);
				} else {
					$gameLink = $baseDir."index.php?a=play&amp;id=$gameID";
				}
			}
			
			if ($gameIcon1Hosted == 1) {
				$gameIcon1 = $baseDir."content/icons/".$gameIcon1;
			}
			
			if(strlen($gameName) > 16){
				$gameName = substr($gameName,0,16);
			}
			
			$list .= '
				<td style="width: 14%; padding-right: 5px; padding-left: 5px;">
					<a href="'.$gameLink.'">'.$gameName.'<br /><img src="'.$gameIcon1.'" alt="Play '.$gameName.'" style="width:110px;height:90px;" /></a><br>
					<div style="line-height: 10px;">
       					Plays:  '.$timesPlayed.'<br>
       					Show Stars
					</div>
				</td>
			';

			$loopNum ++;
		}
	}
	
	return $list;
}

function ratingImage($baseDir,$templateBase,$rated) {
	if ($rated == 0 || $rated == "" || $rated < 1.5) {
		$img = $templateBase."images/rate-bar1.jpg";
		return $img;
	}
	
	if ($rated < 2 && $rated >= 1.5) {
		$img = $templateBase."images/rate-bar2.jpg";
		return $img;
	}
	
	if ($rated < 2.5 && $rated >= 2) {
		$img = $templateBase."images/rate-bar3.jpg";
		return $img;
	}
	
	if ($rated < 3 && $rated >= 2.5) {
		$img = $templateBase."images/rate-bar4.jpg";
		return $img;
	}
	
	if ($rated < 3.5 && $rated >= 3) {
		$img = $templateBase."images/rate-bar5.jpg";
		return $img;
	}
	
	if ($rated < 4 && $rated >= 3.5) {
		$img = $templateBase."images/rate-bar6.jpg";
		return $img;
	}
	
	if ($rated < 4.5 && $rated >= 4) {
		$img = $templateBase."images/rate-bar7.jpg";
		return $img;
	}
	
	if ($rated < 5 && $rated >= 4.5) {
		$img = $templateBase."images/rate-bar8.jpg";
		return $img;
	}
	
	if ($rated == 5) {
		$img = $templateBase."images/rate-bar9.jpg";
		return $img;
	}
}
?>
