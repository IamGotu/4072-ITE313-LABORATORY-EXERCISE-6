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
            }).catch(function(error) {
                console.error('Login request error:', error);
            });
    };
}]);

app.controller('RegisterController', ['$scope', '$http', function($scope, $http) {
    $scope.register = function() {
        $http.post('/auth/register', {
            name: $scope.name,
            email: $scope.email,
            password: $scope.password
        }).then(function(response) {
            if (response.data.success) {
                $scope.registerMessage = 'Registration successful. You can now log in.';
            } else {
                $scope.registerMessage = response.data.message;
            }
        }).catch(function(error) {
            console.error('Registration request error:', error);
        });
    };
}]);
