<!DOCTYPE html>
<?php include_once "include.php"; ?>
<html lang="en">
    <head>
        <meta charset="utf-8">
         <title>Balolsav <?php echo getYear() ?></title>
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le styles -->
        <link href="bootstrap.css" rel="stylesheet">

        <style type="text/css">
            body {
                padding-top: 60px;
            }
        </style>
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
                    <h5>Results</h5>
                    <ul>
                        <li><a id="enterResult" href="report/resultentry.php">Enter/Edit Result</a></li>
                    </ul>
                    <h5>Print Reports</h5>
                    <ul>
                        <li><a href="#">Schools</a></li>
                        <li><a href="#">Items</a></li>
                        <li><a href="#">Participants</a></li>
                        <li><a href="#">Certificate</a></li>
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
                <footer>
                    <p>&copy; Rotary Club of Cherthala</p>
                </footer>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function(){

            });
        </script>
    </body>
</html>

