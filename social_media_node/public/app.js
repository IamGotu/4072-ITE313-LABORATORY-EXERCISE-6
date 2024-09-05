var app = angular.module('socialMediaApp', []);

app.controller('LoginController', ['$scope', '$http', '$window', function($scope, $http, $window) {
    $scope.login = function() {
        $http.post('/auth/login', {
            email: $scope.email,
            password: $scope.password
        }).then(function(response) {
            if (response.data.success) {
                // Redirect to the welcome page
                $window.location.href = '/welcome.html';
            } else {
                // Display error message if login fails
                $scope.errorMessage = response.data.message;
            }
        }).catch(function(error) {
            console.error('Login request error:', error);
            $scope.errorMessage = 'An error occurred during login. Please try again.';
        });
    };

    $scope.goToRegister = function() {
        // Redirect to the registration page
        $window.location.href = 'register.html';
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
                // Display success message and redirect to login page
                $scope.registerMessage = 'Registration successful. You can now log in.';
                setTimeout(function() {
                    window.location.href = 'index.html';
                }, 2000); // Redirect after 2 seconds
            } else {
                // Display error message if registration fails
                $scope.registerMessage = response.data.message;
            }
        }).catch(function(error) {
            console.error('Registration request error:', error);
            $scope.registerMessage = 'An error occurred during registration. Please try again.';
        });
    };
}]);
