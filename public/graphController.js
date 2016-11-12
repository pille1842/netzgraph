var appModule = angular.module('graph', [])
appModule.controller('graphCtrl', ['$scope', '$http', '$compile', function ($scope, $http, $compile) {

    
    loadProgressBar = function(){
                setTimeout(function () { document.getElementById('loadingBar').style.display = 'show'; }, 500);
                $scope.network.on("stabilizationProgress", function (params) {
                var maxWidth = 496;
                var minWidth = 20;
                var widthFactor = params.iterations / params.total;
                var width = Math.max(minWidth, maxWidth * widthFactor);

                document.getElementById('bar').style.width = width + 'px';
                document.getElementById('text').innerHTML = Math.round(widthFactor * 100) + '%';
            });
            $scope.network.once("stabilizationIterationsDone", function () {
                document.getElementById('text').innerHTML = '100%';
                document.getElementById('bar').style.width = '496px';
                document.getElementById('loadingBar').style.opacity = 0;
                // really clean the dom element
                setTimeout(function () { document.getElementById('loadingBar').style.display = 'none'; }, 500);
            });
    }

    $scope.init = function () {

        var jsonNodes = 0;
        var jsonEdges = 0;
        $http.get('/api/relations/factions').success(function (data, status, headers, config) {
            jsonNodes = data.nodes;
            jsonEdges = data.edges;
            var options = data.options;
            var nodes = new vis.DataSet(jsonNodes);
            var edges = new vis.DataSet(jsonEdges);
            var container = document.getElementById('mynetwork');
            var data = {
                nodes: nodes,
                edges: edges
            };

            $scope.network = new vis.Network(container, data, options);
            $scope.network.setOptions(options);
            loadProgressBar();

            $scope.network.on("click", function (params) {
            });
        })
    }


    $scope.search = function () {
        $http.get('/api/search/?q=' + $scope.searchfield).success(function (data, status, headers, config) {
            var result = data;
            
            var searchResultsContainer = document.getElementById("dropdownId");
            while (searchResultsContainer.firstChild) {
                searchResultsContainer.removeChild(searchResultsContainer.firstChild);
            }

            $scope.results = result
			$scope.hide = true
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
	
}]);
