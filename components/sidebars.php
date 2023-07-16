<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<?php
    $thePage = "home";
    if(isset($page)) { $thePage = $page; }
    if(isset($_GET["p"]) && !isset($page)) { $thePage = $page; }

    if(!isset($databaseArray)) { $databaseArray = array(); }
?>

<div class="sidebar1">
    <br>
    <a href="?p=home"><h1 class="sidebar-icon-white"><i class="bi bi-house-door<?php if($thePage == "home") { echo "-fill"; } ?>"></i></h1></a>
    <a href="?p=db"><h1 class="sidebar-icon-white"><i class="bi bi-database<?php if($thePage == "db") { echo "-fill"; } ?>"></i></h1></a>
    <a href="?p=settings"><h1 class="sidebar-icon-white"><i class="bi bi-gear<?php if($thePage == "settings") { echo "-fill"; } ?>"></i></h1></a>
    <a href="?r=logout"><h1 class="sidebar-icon-white"><i class="bi bi-box-arrow-right"></i></h1></a>
</div>

<?php if($thePage == "db") { ?>
<div class="sidebar2 col-white">
    <p style="font-size: 10px;">&nbsp;</p>
    <h3 class="sidebar2-header spacing-0">Databases</h3>
    <p><a href="?p=db&action=addDbModal" class="semiHiddenLinkStyling"><i class="bi bi-plus-circle-fill"></i> New</a></p>
    <div class="dblist">
        <?php 
            // output all databases
            foreach ($databaseArray as $key => $value) {
                $base64Val = base64_encode($value);
                $dbClassSuffix = "";
                if(isset($_GET['db']) && base64_decode($_GET['db']) == $value) {
                    $dbClassSuffix = "-silver";
                }
                echo "<p><a href=\"?p=db&db=$base64Val\" class=\"semiHiddenLinkStyling$dbClassSuffix\">$value</a></p>";
            }
        ?>
    </div>
</div>
<?php } ?>