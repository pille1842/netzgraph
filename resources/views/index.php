<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Bare - Start Bootstrap Template</title>

	<!-- Bootstrap Core CSS -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.17.0/vis.min.js"> </script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/2.2.0/ui-bootstrap.min.js"></script>

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

	<script type="text/javascript" src="./graphController.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.17.0/vis.min.css" rel="stylesheet" type="text/css" />
    <link href="./style.css" rel="stylesheet" type="text/css" />

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<!--	<style type="text/css">
		.container {
			min-height: 100%;
			overflow: hidden
		}
		
		.height-full {
			height: 100vh;
		}
		
		.navbar {
			margin-bottom: 0px;
		}

        #loadingBar {
            /*background-color:rgba(200,200,200,0.8);*/
            -webkit-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            -ms-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
            transition: all 0.5s ease;
            opacity:1;
        }
        #wrapper {
            position:relative;
            width:900px;
            height:900px;
        }

        #text {
            position:absolute;
            top:0px;
            left:530px;
            width:30px;
            height:50px;
            margin:auto auto auto auto;
            font-size:22px;
            color: #000000;
        }


        div.outerBorder {
            position:relative;
            top:400px;
            width:600px;
            height:44px;
            margin:auto auto auto auto;
            border:8px solid rgba(0,0,0,0.1);
            background: rgb(252,252,252); /* Old browsers */
            background: -moz-linear-gradient(top,  rgba(252,252,252,1) 0%, rgba(237,237,237,1) 100%); /* FF3.6+ */
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(252,252,252,1)), color-stop(100%,rgba(237,237,237,1))); /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(top,  rgba(252,252,252,1) 0%,rgba(237,237,237,1) 100%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(top,  rgba(252,252,252,1) 0%,rgba(237,237,237,1) 100%); /* Opera 11.10+ */
            background: -ms-linear-gradient(top,  rgba(252,252,252,1) 0%,rgba(237,237,237,1) 100%); /* IE10+ */
            background: linear-gradient(to bottom,  rgba(252,252,252,1) 0%,rgba(237,237,237,1) 100%); /* W3C */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fcfcfc', endColorstr='#ededed',GradientType=0 ); /* IE6-9 */
            border-radius:72px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
        }

        #border {
            position:absolute;
            top:4px;
            left:10px;
            width:500px;
            height:23px;
            margin:auto auto auto auto;
            box-shadow: 0px 0px 4px rgba(0,0,0,0.2);
            border-radius:10px;
        }

        #bar {
            position:absolute;
            top:0px;
            left:0px;
            width:20px;
            height:20px;
            margin:auto auto auto auto;
            border-radius:11px;
            border:2px solid rgba(30,30,30,0.05);
            background: rgb(0, 173, 246); /* Old browsers */
            box-shadow: 2px 0px 4px rgba(0,0,0,0.4);
        }

        		
		.dropdown {
			position: relative;
			display: inline-block;
		}

		.dropdown-content {
			display: none;
			position: absolute;
			background-color: #f9f9f9;
			min-width: 160px;
			box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
			padding: 12px 16px;
		}

		.dropdown-content {
			display: block;
		}

        	.btn-link{
		  border:none;
		  outline:none;
		  background:none;
		  cursor:pointer;
		  color:#0000EE;
		  padding:0;
		  text-decoration:underline;
		  font-family:inherit;
		  font-size:inherit;
		}
	</style>-->


</head>

<body <div ng-app="graph">
<div ng-controller='graphCtrl'>
	<div class="navbar navbar-inverse navbar-static-top" role="navigation">

		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </button>
			<a class="navbar-brand" rel="home" href="/" title="Aahan Krish's Blog - Homepage">ITSMEEE</a>
		</div>

		<div class="collapse navbar-collapse navbar-ex1-collapse">
			<div class="col-sm-10 col-md-10">
				<form class="navbar-form " role="search">
					<div class="form-group" style="width:100%;">

						<div class="input-group" style="width:100%;">

							<input ng-model="somefield" type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">
							<div class="input-group-btn">
								<button ng-href='#here' ng-click='search()' class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
							</div>
						</div>
<!--aa-->
                        		
						<div class="col-sm-10 col-md-10">
						<div ng-show="somefield.length" class="dropdown-content" style="width:99%;", id="dropdownId">
                            
                        </div>
                        </div>
					</div>
				</form>
			</div>

		</div>
	</div>
		<div data-ng-init="init()">

			<div id="loadingBar">
					<div class="outerBorder">
						<div id="text">0%</div>
						<div id="border">
							<div id="bar"></div>
						</div>
					</div>
			</div>
			<div class="container-fluid height-full" id="mynetwork">
				
			</div>
		</div>
	</div>
	<!-- /.container -->

	<!-- jQuery Version 1.11.1 -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

	<!-- Bootstrap Core JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>

</html>