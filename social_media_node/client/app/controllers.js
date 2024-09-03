app.controller('AuthController', function($scope, $http, $location) {
    $scope.register = function() {
        $http.post('/api/register', $scope.user).then(function(response) {
            $location.path('/login');
        }, function(error) {
            console.log('Error:', error);
        });
    };

    $scope.login = function() {
        $http.post('/api/login', $scope.user).then(function(response) {
            $location.path('/profile');
        }, function(error) {
            console.log('Error:', error);
        });
    };
});

app.controller('ProfileController', function($scope, $http) {
    $http.get('/api/profile').then(function(response) {
        $scope.user = response.data;
    }, function(error) {
        console.log('Error:', error);
    });
});
