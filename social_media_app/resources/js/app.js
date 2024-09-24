import './bootstrap';
import Alpine from 'alpinejs';
import angular from 'angular';

window.Alpine = Alpine;

Alpine.start();

angular.module('socialApp', [])
.controller('PostController', function($scope, $http) {
    $scope.posts = [];
    $scope.newPost = {
        content: '',
        visibility: 'Public' // Pre-select 'Public'
    };

    $scope.getPosts = function() {
        $http.get('/posts')
        .then(function(response) {
            $scope.posts = response.data;
        }, function(error) {
            console.error('Error fetching posts:', error);
            alert('Error fetching posts');
        });
    };

    $scope.createPost = function() {
        $http.post('/posts', $scope.newPost)
        .then(function(response) {
            $scope.posts.unshift(response.data);
            $scope.newPost = {}; // Clear form
        }, function(error) {
            console.error('Error creating post:', error);
            alert('Error creating post');
        });
    };

    $scope.likePost = function(post) {
        $http.post('/posts/' + post.id + '/like')
            .then(function(response) {
                // Toggle like/unlike logic based on the response
                if (response.data.message === 'Post liked') {
                    post.likes_count++;
                    post.userHasLiked = true; // Keep track that the user has liked the post
                } else if (response.data.message === 'Post unliked') {
                    post.likes_count--;
                    post.userHasLiked = false; // Keep track that the user has unliked the post
                }
            }, function(error) {
                console.error('Error toggling like:', error);
                alert('Error toggling like');
            });
    };

    $scope.addComment = function(post) {
        $http.post('/posts/' + post.id + '/comment', { comment: post.newComment })
            .then(function(response) {
                post.comments.push(response.data); // Add the new comment to the post's comments array
                post.newComment = ''; // Clear the input field after successful comment submission
            }, function(error) {
                console.error('Error adding comment:', error);
                alert('Error adding comment');
            });
    };

    $scope.deletePost = function(post) {
        if (confirm('Are you sure you want to delete this post?')) {
            $http.delete('/posts/' + post.id)
            .then(function(response) {
                // Remove the post from the list after deletion
                var index = $scope.posts.indexOf(post);
                if (index > -1) {
                    $scope.posts.splice(index, 1);
                }
                alert(response.data.message);
            }, function(error) {
                console.error('Error deleting post:', error);
                alert('Error deleting post');
            });
        }
    };

    $scope.getPosts();  // Fetch posts when the controller initializes
})
.controller('FriendController', function($scope, $http) {
    $scope.suggestedFriends = [];
    $scope.message = '';

    // Function to fetch suggested friends
    $scope.getSuggestedFriends = function() {
        $http.get('/friends/suggested')
            .then(function(response) {
                $scope.suggestedFriends = response.data; // Store the suggested friends
            })
            .catch(function(error) {
                console.error('Error fetching suggested friends:', error);
            });
    };

    // Function to add a friend
    $scope.addFriend = function(friendId) {
        $http.post('/friends/add/' + friendId)
            .then(function(response) {
                $scope.message = response.data.message; // Display success message
                $scope.getSuggestedFriends(); // Refresh the list
            })
            .catch(function(error) {
                if (error.status === 409) {
                    $scope.message = error.data.message; // Handle already friends
                } else if (error.status === 400) {
                    $scope.message = error.data.message; // Handle self-friend request
                } else {
                    $scope.message = 'An error occurred while sending the friend request.';
                }
            });
    };

    // Fetch suggested friends on load
    $scope.getSuggestedFriends();
});

// CSRF Token configuration
angular.module('socialApp').config(function($httpProvider) {
    $httpProvider.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
});
