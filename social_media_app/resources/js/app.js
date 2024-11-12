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
    $scope.currentUserId = window.currentUserId;
    $scope.currentUser = window.currentUser;

    // Function to fetch posts
    $scope.getPosts = function() {
        $http.get('/posts')
            .then(function(response) {
                $scope.posts = response.data;
                // Ensure each post has a comments array initialized
                $scope.posts.forEach(function(post) {
                    post.comments = post.comments || []; // Initialize comments if not present
                });
            }, function(error) {
                console.error('Error fetching posts:', error);
                alert('Error fetching posts');
            });
    };

    // Function to create a new post
    $scope.createPost = function() {
        $http.post('/posts', $scope.newPost)
            .then(function(response) {
                response.data.user = {
                    first_name: $scope.currentUser.firstName,
                    middle_name: $scope.currentUser.middleName,
                    last_name: $scope.currentUser.lastName,
                    suffix: $scope.currentUser.suffix
                };

                // Ensure likes_count is initialized
                response.data.likes_count = response.data.likes_count || '';  // Initialize if not present

                // Add user data along with the post
                $scope.posts.unshift(response.data);
                $scope.newPost = {};
            }, function(error) {
                console.error('Error creating post:', error);
                alert('Error creating post');
            });
    };

    // Function to toggle editing mode
    $scope.editPost = function(post) {
        post.originalContent = post.content;
        post.originalVisibility = post.visibility;
        post.showDropdown = false;
        post.isEditing = true;
    };

    // Function to cancel post edit
    $scope.cancelEdit = function(post) {
        post.content = post.originalContent;
        post.visibility = post.originalVisibility;
        post.isEditing = false;
    };

    // Function to update post
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

    // Function to like or unlike a post
    $scope.likePost = function(post) {
        console.log("Liking/unliking post", post.id);  // Debugging log
        // Send a POST request to the backend to like or unlike the post
        $http.post('/posts/' + post.id + '/like')
            .then(function(response) {
                console.log(response);  // Log response for debugging
                if (response.data.message === 'Post liked') {
                    post.likes_count++;  // Increment the like count
                    post.userHasLiked = true;  // Set userHasLiked to true
                } else if (response.data.message === 'Post unliked') {
                    post.likes_count--;  // Decrement the like count
                    post.userHasLiked = false;  // Set userHasLiked to false
                }
            }, function(error) {
                // Handle any error in liking/unliking
                console.error('Error toggling like:', error);
                alert('Error toggling like');
            });
    };

    // Function to toggle visibility of the comments section
    $scope.toggleComments = function(post) {
        post.showComments = !post.showComments; // Toggle visibility
    };

    // Function to add a comment to a post
    $scope.addComment = function(post) {
        $http.post('/posts/' + post.id + '/comment', { comment: post.newComment })
            .then(function(response) {
                // Ensure the comments array exists and then push the new comment
                if (!post.comments) {
                    post.comments = [];
                }
                
                // Add user data along with the comment
                response.data.user = {
                    first_name: $scope.currentUser.firstName,
                    middle_name: $scope.currentUser.middleName,
                    last_name: $scope.currentUser.lastName,
                    suffix: $scope.currentUser.suffix
                };
                
                post.comments.push(response.data); // Add the new comment to the post's comments array
                post.newComment = ''; // Clear the input field
            }, function(error) {
                console.error('Error adding comment:', error);
                alert('Error adding comment');
            });
    };

    // Function to edit a comment
    $scope.editComment = function(comment) {
        // Set editing mode and save original content
        comment.isEditing = true;
        comment.originalContent = comment.comment;
    };

    // Function to save edited comment
    $scope.saveComment = function(comment) {
        $http.put('/comments/' + comment.id, { comment: comment.comment })
            .then(function(response) {
                comment.isEditing = false; // Exit editing mode
            })
            .catch(function(error) {
                console.error('Error updating comment:', error);
                alert('Error updating comment');
                comment.comment = comment.originalContent; // Revert to original content if save fails
            });
    };

    // Function to cancel edit
    $scope.cancelEditComment = function(comment) {
        comment.isEditing = false;
        comment.comment = comment.originalContent; // Revert to original content
    };

    // Function to delete a comment
    $scope.deleteComment = function(comment, post) {
        $http.delete('/comments/' + comment.id)
            .then(function(response) {
                // Remove comment from the post's comments array
                const index = post.comments.indexOf(comment);
                if (index > -1) {
                    post.comments.splice(index, 1); // Remove the comment from the array
                }
            })
            .catch(function(error) {
                console.error('Error deleting comment:', error);
                alert('Error deleting comment');
            });
    };

    // Function to delete a post
    $scope.deletePost = function(post) {
        $scope.modalMessage = "Are you sure you want to delete this post?";
        $scope.isModalVisible = true;  // This will show the modal
        $scope.postToDelete = post; // Store post data to delete later
    };

    // Function to confirm post deletion
    $scope.confirmDelete = function() {
        $http.delete('/posts/' + $scope.postToDelete.id)
        .then(function(response) {
            var index = $scope.posts.indexOf($scope.postToDelete);
            if (index !== -1) {
                $scope.posts.splice(index, 1);  // Remove the post from the array
            }
            $scope.closeModal(); // Close the modal after deletion
        })
        .catch(function(error) {
            console.error('Error deleting post:', error);
            $scope.closeModal(); // Close the modal even in case of error
        });
    };

    // Function to close modal
    $scope.closeModal = function() {
        $scope.isModalVisible = false;  // Close the modal by hiding it
    };

    // Fetch posts when the controller initializes
    $scope.getPosts();
});

// FriendController remains the same for handling friend actions

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