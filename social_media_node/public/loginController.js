app.controller('LoginController', ['$scope', '$http', '$window', function($scope, $http, $window) {
    $scope.email = '';
    $scope.password = '';
    $scope.message = '';

    $scope.login = function() {
        $http.post('/auth/login', { email: $scope.email, password: $scope.password })
            .then(function(response) {
                if (response.data.success) {
                    $window.location.href = '/welcome.php';
                } else {
                    $scope.message = response.data.message;
                }
            });
    };
}]);
