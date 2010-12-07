<?php
/*
File: templates/default/inc/game-list.php
This script is copyright to ArcadeTradeScript.com. Unauthorized use or distribution of this script is illegal.
*/

$listings = "";
if (mysql_num_rows($result)) {
	$loopNum = 1;
	while ($row = mysql_fetch_array($result)) {
		$gameID = $row['gid'];
		$gameName = stripslashes($row['gName']);
		$gameDesc = stripslashes($row['gDesc']);
		$gameAltDesc = stripslashes($row['altDesc']);
		$gameKeywords = $row['gKeywords'];
		$played = $row['played'];
		$gameTags = $row['gTags'];
		$gameHow = stripslashes($row['gHow']);
		$gameCat = $row['cat'];
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
		$customCode = stripslashes($row['customCode']);
		$addedOn = $row['addedOn'];
		
		if ($seoFriendly == 1) {
			$gameLink = $baseDir.seoGameLink($gameCat,$tradeType,$gameFile,$preGamePageStatus).seo_str_plain("play-".$gameName);
		} else {
			$gameLink = $baseDir."index.php?a=play&amp;id=$gameID";
		}
		
		if ($gameIcon1Hosted == 1) {
			$gameIcon1 = $baseDir."content/icons/".$gameIcon1;
		}
		
		if ($gameIcon2Hosted == 1) {
			$gameIcon2 = $baseDir."content/icons/".$gameIcon2;
		}
		
		if ($gameIcon3Hosted == 1) {
			$gameIcon3 = $baseDir."content/icons/".$gameIcon3;
		}
		
		if(strlen($gameName) >= 17){
			$gameName = substr($gameName,0,17);
		}
		
		$listings .= '
						<td style="width:25%; padding-right:9px; padding-left:9px;">
                            <a href="'.$gameLink.'"><img src="'.$gameIcon1.'" width="110" height="90" class="small_thumb" alt="'.$gameName.' Icon" /></a><br />
	<div style="line-height:15px;">
        <b><a href="'.$gameLink.'">'.$gameName.'</a></b><br />
        Plays:  '.$played.'<br />
        Show Stars
    </div>
                        </td>';
		if($loopNum % 4 == 0){
			$listings .= "</tr><tr>";
		}
		$loopNum++;
	}
		
	if (!isset($_GET['topPlayed']) && !isset($_GET['topRated'])) {
		$pages = $totalGames / $gamesPerPage;
		if (preg_match("/\./",$pages) && $pages >= 1) {
		   $pages2 = explode(".",$pages);
		   $pages = $pages2[0] + 1;
		} elseif ($pages < 1) {
		   $pages = 1;
		}
		
		$backPage = $qPage - 1;
		$nextPage = $qPage + 1;
		if ($seoFriendly == 1 && $qTerm == "") {
			$backPage = $pageBaseDir."page-$backPage";
			$nextPage = $pageBaseDir."page-$nextPage";
		} else {
			$backPage = $pageBaseDir."&page=$backPage";
			$nextPage = $pageBaseDir."&page=$nextPage";
		}
		
		
		$pageList .= '<div class="pageNums">';
		
		if ($qPage != 1) {
			$pageList .= '<a href="'.$backPage.'">&lt; Back</a> ';
		}
		
		for ($i=1; $i<=$pages; $i++) {
			if ($i == $qPage) {
				if ($i == 1) {
					$pageList .= '<span style="color: #000000;">'.$i.'</span>';
				} else {
					$pageList .= ', <span style="color: #000000;">'.$i.'</span>';
				}
			} else {
				if ($i == 1) {
					if ($seoFriendly == 1 && $qTerm == "") {
						$pageList .= '<a href="'.$pageBaseDir.'page-'.$i.'">'.$i.'</a>';
					} else {
						$pageList .= '<a href="'.$pageBaseDir.'&page='.$i.'">'.$i.'</a>';
					}
				} else {
					if ($seoFriendly == 1 && $qTerm == "") {
						$pageList .= ', <a href="'.$pageBaseDir.'page-'.$i.'">'.$i.'</a>';
					} else {
						$pageList .= ', <a href="'.$pageBaseDir.'&page='.$i.'">'.$i.'</a>';
					}
				}
			}
		}
		
		if ($qPage != $pages) {
			$pageList .= ' <a href="'.$nextPage.'">Next &gt;</a>';
		}
		$pageList .= '</div>';
	}
} else {
	$listings = '<div style="width: 100%; font-weight: bold; color: #000000; text-align: center; float: left;">There are no games here yet.</div>';
}
?>
<div class="categoryinfo">
<div class="categorycol">

	<h2 class="widgettitle">Category > Category Name</h2>

<div>

<div style="text-align:center;">
    <a href="#"><b>Most Recent</b></a> | 
    <a href="#">Most Plays</a> | 
    <a href="#">Top Rated</a> | 
    <a href="#">Recently Played</a> | 
    <a href="#">Featured</a> | 
    <a href="#">Alphabetical</a>

</div><br />


<table cellspacing='0' cellpadding='3' style="width:1%;">
<tr>
<?=$listings;?>
</tr></table>
<div style="float: right;">
	<?=$pageList;?>
</div>
<div style="clear: both;"></div>

</div>


</div>



</div>

<div class="categoryright">

<div class="categoryrightcol">
<img src="http://www.webpencil.com/banneroutlines/425x500.gif" width="425" />
</div>




	</div> 



