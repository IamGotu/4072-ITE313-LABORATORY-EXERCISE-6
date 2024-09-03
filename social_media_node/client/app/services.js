app.factory('AuthService', function($http) {
    return {
        login: function(user) {
            return $http.post('/api/login', user);
        },
        register: function(user) {
            return $http.post('/api/register', user);
        },
        getProfile: function() {
            return $http.get('/api/profile');
        }
    };
});
