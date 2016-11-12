var appModule = angular.module('graph', [])
appModule.controller('graphCtrl', ['$scope', '$http', '$compile', function ($scope, $http, $compile) {

    $scope.init = function () {

        var options = {
            autoResize: true,
            height: '100%',
            width: '100%',
            locale: 'de',
            nodes: {
                borderWidth: 4,
                size: 30,
                color: {
                    border: 'lightgray',
                }
            },
            edges: {
                color: 'lightgray',

            },
            clickToUse: false,
            layout: {
                improvedLayout: true,
                randomSeed:2
            }
        }

            
       
        var data = {
            
        }

        var jsonNodes = 0;
        var jsonEdges = 0;
        $http.get('/api/relations/allpersons', data).success(function (data, status, headers, config) {
            jsonNodes = data.data;
            jsonEdges = data.nodes;

                  // create an array with nodes
        var nodes = new vis.DataSet(jsonNodes);

        // create an array with edges
        var edges = new vis.DataSet(jsonEdges);


        // create a network
        var container = document.getElementById('mynetwork');

        // provide the data in the vis format
        var data = {
            nodes: nodes,
            edges: edges
        };


        // initialize your network!
        var network = new vis.Network(container, data, options);
        network.setOptions(options);
        network.on("stabilizationProgress", function (params) {
            var maxWidth = 496;
            var minWidth = 20;
            var widthFactor = params.iterations / params.total;
            var width = Math.max(minWidth, maxWidth * widthFactor);

            document.getElementById('bar').style.width = width + 'px';
            document.getElementById('text').innerHTML = Math.round(widthFactor * 100) + '%';
        });
        network.once("stabilizationIterationsDone", function () {
            document.getElementById('text').innerHTML = '100%';
            document.getElementById('bar').style.width = '496px';
            document.getElementById('loadingBar').style.opacity = 0;
            // really clean the dom element
            setTimeout(function () { document.getElementById('loadingBar').style.display = 'none'; }, 500);
        });



        network.on("click", function (params) {

        });
            
        })


  

 }


   $scope.search = function() {
    alert("Hello! I am an alert box!!");
  }
}]);

