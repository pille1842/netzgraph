<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Bundesgraph</title>

	<!-- Bootstrap Core CSS -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.17.0/vis.min.js"> </script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular-route.js"><script>
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



</head>

<body ng-app="graph">
<div ng-controller='graphCtrl'>
	<div class="navbar navbar-inverse navbar-static-top" role="navigation">
		
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </button>
			<a href="#" class="navbar-left"><img src="logo.png"></a>
		</div>

		<div class="collapse navbar-collapse navbar-ex1-collapse">
			<div class="col-sm-9 col-md-9">
				<form class="navbar-form " role="search">
					<div class="form-group" style="width:100%;">

						<div class="input-group" style="width:100%;">

							<input ng-model="searchfield" type="text" class="form-control" placeholder="Search" name="/api/search?q=" id="srch-term" autocomplete="off">
							<div class="input-group-btn">
								<button ng-href='#here' ng-click='search()' class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
							</div>
						</div>

                        		
						<div class="col-sm-10 col-md-10">
						<div ng-show="searchfield.length" class="dropdown-content" style="width:99%;" id="dropdownId">
                            <a ng-repeat="x in records">{{x}}</h1>
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