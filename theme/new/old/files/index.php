<?php include("header.php"); ?>

            <!--
                <form name="searchForm" action="<?=$baseDir;?>index.php" method="get">
                	<input type="hidden" name="a" id="a" value="gamelist" />
                    <input type="text" name="q" id="q" class="search" />
                    <input type="submit" name="submit" value="GO" class="btn" />
                </form>-->



                <?
                if ($pageFile != "") {
                    include("$pageFile"); 
                } else {
                    include("$templatePath/files/sidebar.html");
                    ?>

					<?=$pageCode;?>

                    <?
                }
                ?>

<?php include("footer.php"); ?>