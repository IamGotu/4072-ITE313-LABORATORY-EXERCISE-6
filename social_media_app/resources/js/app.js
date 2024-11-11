import './bootstrap';
import Alpine from 'alpinejs';
import angular from 'angular';

window.Alpine = Alpine;
Alpine.start();

const app = angular.module('socialApp', []);

// CSRF Token configuration
app.config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}]);

app.controller('PostController', function($scope, $http) {
    $scope.posts = [];
    $scope.newPost = {
        content: '',
        visibility: 'Public' // Pre-select 'Public'
    };

    // Function to fetch posts
    $scope.getPosts = function() {
        $http.get('/posts')
            .then(function(response) {
                $scope.posts = response.data;
            }, function(error) {
                console.error('Error fetching posts:', error);
                alert('Error fetching posts');
            });
    };

    $scope.cancelEdit = function(post) {
        // Revert changes by restoring original content and visibility
        post.content = post.originalContent;
        post.visibility = post.originalVisibility;
        post.isEditing = false; // Exit the editing mode
    };
    
    // Function to toggle editing mode
    $scope.editPost = function(post) {
        post.originalContent = post.content;
        post.originalVisibility = post.visibility;
        post.showDropdown = false;
        post.isEditing = true;
    };

    $scope.updatePost = function(post) {
        $http.put('/posts/' + post.id, {
            content: post.content,
            visibility: post.visibility
        }).then(function(response) {
            post.isEditing = false;
        }).catch(function(error) {
            console.error("Error updating post:", error);
        });
    };

    // Function to create a new post
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

    // Function to toggle visibility of the comments section
    $scope.toggleComments = function(post) {
        post.showComments = !post.showComments; // Toggle visibility
    };

    // Function to like a post
    $scope.likePost = function(post) {
        $http.post('/posts/' + post.id + '/like')
            .then(function(response) {
                if (response.data.message === 'Post liked') {
                    post.likes_count++;
                    post.userHasLiked = true;
                } else if (response.data.message === 'Post unliked') {
                    post.likes_count--;
                    post.userHasLiked = false;
                }
            }, function(error) {
                console.error('Error toggling like:', error);
                alert('Error toggling like');
            });
    };

    // Function to add a comment to a post
    $scope.addComment = function(post) {
        $http.post('/posts/' + post.id + '/comment', { comment: post.newComment })
            .then(function(response) {
                post.comments.push(response.data); // Add the new comment to the post's comments array
                post.newComment = ''; // Clear the input field
            }, function(error) {
                console.error('Error adding comment:', error);
                alert('Error adding comment');
            });
    };

    // Function to delete a post
    $scope.deletePost = function(post) {
        if (confirm('Are you sure you want to delete this post?')) {
            $http.delete('/posts/' + post.id)
                .then(function(response) {
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
});

app.controller('FriendController', function($scope, $http) {
    $scope.suggestedFriends = [];
    $scope.message = '';

    // Function to fetch suggested friends
    $scope.getSuggestedFriends = function() {
        $http.get('/friends/suggested')
            .then(function(response) {
                $scope.suggestedFriends = response.data;
            })
            .catch(function(error) {
                console.error('Error fetching suggested friends:', error);
            });
    };

    // Function to add a friend
    $scope.addFriend = function(friendId) {
        $http.post('/friends/add/' + friendId)
            .then(function(response) {
                $scope.message = response.data.message;
                $scope.getSuggestedFriends();
            })
            .catch(function(error) {
                if (error.status === 409) {
                    $scope.message = error.data.message; // Already friends
                } else if (error.status === 400) {
                    $scope.message = error.data.message; // Self-friend request
                } else {
                    $scope.message = 'An error occurred while sending the friend request.';
                }
            });
    };

    // Fetch suggested friends on load
    $scope.getSuggestedFriends();
});

angular.module('socialApp').config(function($httpProvider) {
    $httpProvider.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
});