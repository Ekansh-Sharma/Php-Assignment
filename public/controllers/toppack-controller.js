function toppackController($scope, ToppackFactory) {
  'use strict';

  $scope.searchRepo = function() {
    var successCallback = function(data) {
      $scope.repositories = data;
    }
    var errorCallback = function(error) {
      $scope.searchError = "Couldnt find any repositories"
    }
    ToppackFactory.getRepositories($scope.searchTerm, successCallback, errorCallback);
  };

  $scope.importRepository = function(repository, index) {
    var successCallback = function(response) {
      $scope.repositories[index].imported = true;
      $scope.repositories[index].importError = false;
    }

    var errorCallback = function(error) {
      $scope.repositories[index].importError = true;
    }
    ToppackFactory.importRepository(repository, successCallback, errorCallback);
  }
}
angular.module("toppack").controller("ToppackController", toppackController);