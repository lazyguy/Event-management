<!DOCTYPE html>
<?php include_once "include.php"; ?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Balolsav <?php echo getYear() ?></title>
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="bootstrap.css" rel="stylesheet">

        <style type="text/css">
            body {
                padding-top: 60px;
            }
        </style>
        <link rel="shortcut icon" href="images/logo.ico">
    </head>

    <body>
        <div class="topbar">
            <div class="topbar-inner">
                <div class="container-fluid">
                    <a class="brand" href="index.php">Balolsav <?php echo getYear() ?></a>
                    <ul class="nav">
                        <li class="active"><a href="home.php">Home</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="sidebar" >
                <div class="well">
                    <h5>Participants</h5>
                    <ul>
                        <li><a id="printRegCard" href="report/printregcard.php">Print Registration card</a></li>
                    </ul>
                    <h5>Results</h5>
                    <ul>
                        <li><a id="enterResult" href="report/resultentry.php">Enter/Edit Result</a></li>
                        <li><a id="winnerList" href="report/winners.php">Winners</a></li>
                        <li><a href="report/printcert.php">Print Certificates</a></li>
                    </ul>
                    <h5>Reports</h5>
                    <ul>
                        <li><a href="report/partByEvent.php">Participants</a></li>
                        <li><a href="report/summary.php">Summary of Registration</a></li>
                        <li><a href="report/pointstable.php">Points Table</a></li>
                    </ul>

                </div>
            </div>
            <div class="content" >
                <div id="reportContents">
                    <div class="hero-unit">
                        <h2>Reports</h2>
                        <p>Use Links in side bar to enter results and print reports.</p>
                    </div>
                </div>
                <?php include_once "footer.html" ?>
            </div>
        </div>
    </body>
</html>

