var appModule = angular.module('graph', [])
appModule.controller('graphCtrl', ['$scope', '$http', '$compile', function ($scope, $http, $compile) {

    $scope.init = function () {

        



        var data = {

        }

        var jsonNodes = 0;
        var jsonEdges = 0;
        $http.get('/api/relations/factions', data).success(function (data, status, headers, config) {
            jsonNodes = data.nodes;
            jsonEdges = data.edges;
            var options = data.options;
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


    $scope.search = function () {
        var data = {

        }

        $http.get('/api/search/?q=' + $scope.searchfield, data).success(function (data, status, headers, config) {
            var result = data;
            
            var searchResultsContainer = document.getElementById("dropdownId");
            while (searchResultsContainer.firstChild) {
                searchResultsContainer.removeChild(searchResultsContainer.firstChild);
            }

               $scope.results = result

     /*       for (i in result) {


                var my_form = document.createElement("a"); 
                my_form.setAttribute("href", result[i].url)
                my_form.setAttribute("ng-href",'#here')
                my_form.setAttribute("ng-click", "alert(1)")
                my_form.setAttribute("style", "display:block;")
                my_text = document.createTextNode(result[i].caption)
               
                my_form.appendChild(my_text)

                searchResultsContainer.appendChild(my_form)

            }*/
        })
    }

    $scope.alert = function(){
        alert("Hello! I am an alert box!!");
    }



}]);
