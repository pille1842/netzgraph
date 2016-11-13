var appModule = angular.module('graph', [])
appModule.controller('graphCtrl', ['$scope', '$http', '$compile', function ($scope, $http, $compile) {

    
    loadProgressBar = function(){
               document.getElementById('loadingBar').style.opacity = 100;
               //document.getElementById('loadingBar').style.display = 'inline-block'
                $scope.network.on("stabilizationProgress", function (params) {
                document.getElementById("mynetwork").style.visibility = "hidden";
                var maxWidth = 496;
                var minWidth = 20;
                var widthFactor = params.iterations / params.total;
                var width = Math.max(minWidth, maxWidth * widthFactor);

                document.getElementById('bar').style.width = width + 'px';
                document.getElementById('text').innerHTML = Math.round(widthFactor * 100) + '%';
            });
            $scope.network.once("stabilizationIterationsDone", function () {
                document.getElementById("mynetwork").style.visibility = "visible";
                document.getElementById('text').innerHTML = '100%';
                document.getElementById('bar').style.width = '496px';
                document.getElementById('loadingBar').style.opacity = 0;
                // really clean the dom element
                //setTimeout(function () { document.getElementById('loadingBar').style.display = 'none'; }, 500);
            });
    }

    $scope.init = function () {

		$scope.target = "person"
        var jsonNodes = 0;
        var jsonEdges = 0;
        $scope.nodes = 0;
        $scope.edges = 0;
        $http.get('/api/relations/factions').success(function (data, status, headers, config) {
            jsonNodes = data.nodes;
            jsonEdges = data.edges;
            var options = data.options;
            $scope.nodes = new vis.DataSet(jsonNodes);
            $scope. edges = new vis.DataSet(jsonEdges);
            var container = document.getElementById('mynetwork');
            var data = {
                nodes: $scope.nodes,
                edges: $scope.edges
            };

            $scope.network = new vis.Network(container, data, options);
            $scope.network.setOptions(options);
            loadProgressBar();

            $scope.network.on("click", function (params) {
                if (params.nodes.length > 0) {
         /*           nodes.get(params.nodes[0]);
                    id = params.nodes[0]*/
                    url = $scope.nodes.get(params.nodes[0]).url
                    if (url.length > 0) {
                        $http.get(url).success(function (data, status, headers, config){
                            var options = data.options;
                            $scope.nodes = new vis.DataSet(data.nodes);
                            $scope.edges  = new vis.DataSet(data.edges);
                            $scope.network.setData({nodes:$scope.nodes , edges:$scope.edges})
                            $scope.network.setOptions(options);
                            loadProgressBar();
                        })
                    }
                }
            });
        })
    }


    $scope.search = function () {
        $http.get('/api/search/?q=' + $scope.searchfield + '&target=' + $scope.target).success(function (data, status, headers, config) {
            var result = data;
            
            var searchResultsContainer = document.getElementById("dropdownId");
            while (searchResultsContainer.firstChild) {
                searchResultsContainer.removeChild(searchResultsContainer.firstChild);
            }

            $scope.results = result
			if (result.length == 0) {
				$scope.hide = false
			} else {
				$scope.hide = true
			}
        })
    }

    $scope.setResult = function(url){
        $http.get(url).success(function (data, status, headers, config){
            jsonNodes = data.nodes;
            jsonEdges = data.edges;
            var options = data.options;
            var nodes = new vis.DataSet(jsonNodes);
            var edges = new vis.DataSet(jsonEdges);
            $scope.network.setData({nodes:nodes, edges:edges})
            $scope.network.setOptions(options);
            loadProgressBar();
        })
    }
	
	$scope.test = function() {
		$scope.hide = false
	}
	
	$scope.onPersonClicked = function() {
		$scope.target = "person"
		document.getElementById("srch-term").value = ""
		document.getElementById("srch-term").setAttribute("placeholder","Search Person ...")
	}
	
	$scope.onReligionClicked = function() {
		$scope.target = "religion"
		document.getElementById("srch-term").value = ""
		document.getElementById("srch-term").setAttribute("placeholder","Search Religion ...")
	}
	
	$scope.onProfessionClicked = function() {
		$scope.target = "profession"
		document.getElementById("srch-term").value = ""
		document.getElementById("srch-term").setAttribute("placeholder","Search Profession ...")
	}
	
	$scope.onTitleClicked = function() {
		$scope.target = "title"
		document.getElementById("srch-term").value = ""
		document.getElementById("srch-term").setAttribute("placeholder","Search Title ...")
	}
	
	$scope.onFactionProfessionClicked = function() {
		$scope.target = "faction,profession"
	}
	
	$scope.onFactionEarningsClicked = function() {
		$scope.target = "faction,earnings"
	}
	
	$scope.onFactionBirthdayClicked = function() {
		$scope.target = "faction,birthday"
	}
	
	
	
}]);
