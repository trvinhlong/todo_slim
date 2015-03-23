angular.module('CrudApp', ['ngRoute']).
        config(['$routeProvider', function ($routeProvider) {
                $routeProvider.
                        when('/', {templateUrl: 'assets/tpl/lists.html', controller: ListCtrl}).
                        when('/add', {templateUrl: 'assets/tpl/add-new.html', controller: AddCtrl}).
                        when('/edit/:id', {templateUrl: 'assets/tpl/edit.html', controller: EditCtrl}).
                        otherwise({redirectTo: '/'});
            }]);

function ListCtrl($scope, $http, $location) {
    $http.get('api/tasks').success(function (data) {
        $scope.tasks = data;
    });
    $scope.del = function (index, id) {
        var deleteTask = confirm('Are you sure you want to delete?');
        if (deleteTask) {
            $http.delete('api/tasks/' + id);
        $scope.tasks.splice(index, 1);
        }      
   };    
}

function AddCtrl($scope, $http, $location) {
    $scope.master = {};
    $scope.activePath = null;

    $scope.add = function (task, AddNewForm) {
        $http.post('api/add', task).success(function () {
            $scope.reset();
            $scope.activePath = $location.path('/');
        });
        $scope.reset = function () {
            $scope.task = angular.copy($scope.master);
        };
        $scope.reset();
    };
}

function EditCtrl($scope, $http, $location, $routeParams) {
    var id = $routeParams.id;
    $scope.activePath = null;

    $http.get('api/tasks/' + id).success(function (data) {
        $scope.tasks = data;
    });
    $scope.update = function (task) {
        $http.put('api/tasks/' + id, task).success(function (data) {
            $scope.tasks = data;
            $scope.activePath = $location.path('/');
        });
    };
//    $scope.delete = function (task) {
//        var deleteTask = confirm('Are you absolutely sure you want to delete?');
//        if (deleteTask) {
//            $http.delete('api/tasks/' + task.id).success(function () {console.log(123)});
//            $scope.activePath = $location.path('/');
//        }
//    };
}