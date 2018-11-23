var app = angular.module("myapp", []);

app.config(['$routeProvider', function($routeProvider){
	$routeProvider
	.when('/first', {
		template: "<h1>This is first page</h1>"
	})
	.otherwise({
		template: "There is no page."
	})
}]);

app.controller('BindingController', ['$scope', function($scope){
	$scope.first = "Two Way";
	$scope.second = "One Way";
}]);