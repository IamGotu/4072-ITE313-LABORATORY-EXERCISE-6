<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="py-6">
        <div ng-app="socialApp" ng-controller="PostController" class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- New Post Form -->
            <form ng-submit="createPost()" class="bg-white p-6 rounded-lg shadow-md">
                <textarea ng-model="newPost.content" id="newPostContent" name="newPostContent" class="w-full bg-white p-6 rounded-lg shadow-md" placeholder="What's on your mind?" required></textarea>

                <!-- Wrap select and button in a flex container with justify-between -->
                <div class="flex justify-between mt-2">
                    <select ng-model="newPost.visibility" id="newPostVisibility" name="newPostVisibility" class="w-11/12 rounded-lg">
                        <option value="Public">Public</option>
                        <option value="Friends">Friends</option>
                        <option value="Only me">Only me</option>
                    </select>
                    
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg ml-4">
                        {{ __('Post') }}
                    </button>
                </div>
            </form>

            <!-- Display Posts -->
            <div ng-repeat="post in posts" class="bg-white p-6 rounded-lg shadow-md mt-4">
                <!-- User Info with Dropdown -->
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <span class="text-xl font-bold">@{{ post.user.first_name }} @{{ post.user.middle_name }} @{{ post.user.last_name }} @{{ post.user.suffix }}</span>
                        <br>
                        <span class="text-xs text-gray-500">@{{ post.created_at | date:'medium' }} - @{{ post.visibility }}</span>
                    </div>
                    <div>
                        <!-- Only show the dropdown for editing/deleting if the current user is the author -->
                        <div ng-if="post.user_id === currentUserId" class="relative" ng-init="post.showDropdown = false">
                            <button ng-click="post.showDropdown = !post.showDropdown" class="text-gray-600 hover:text-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <!-- Dropdown menu (for delete/update) -->
                            <div ng-if="post.showDropdown" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg">
                                <ul class="py-1">
                                    <li class="px-4 py-2 text-sm text-gray-700 cursor-pointer" ng-click="editPost(post)">Edit</li> <!-- Edit button triggers editPost -->
                                    <li class="px-4 py-2 text-sm text-gray-700 cursor-pointer" ng-click="deletePost(post)">Delete</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal for Delete Confirmation -->
                <div ng-if="isModalVisible" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                        <h2 class="text-xl font-bold mb-4">Message</h2>
                        <p>@{{ modalMessage }}</p>
                        <div class="mt-4 flex justify-between">
                            <!-- Confirm Button -->
                            <button ng-click="confirmDelete()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                                Confirm
                            </button>
                            <!-- Cancel Button -->
                            <button ng-click="closeModal()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Editable Post Content -->
                <div ng-if="post.isEditing">
                    <form ng-submit="updatePost(post)" class="mt-4">
                        <textarea ng-model="post.content" id="post-content-@{{ post.id }}" name="content" class="w-full p-2 border rounded-md mb-2" required></textarea>
                        
                        <!-- Wrap select and buttons in a flex container with justify-between -->
                        <div class="flex justify-between mt-2">
                            <select ng-model="post.visibility" id="post-visibility-@{{ post.id }}" name="visibility" class="w-3/4 rounded-lg py-1 px-2 text-xs" style="margin-right: 10px;">
                                <option value="Public">Public</option>
                                <option value="Friends">Friends</option>
                                <option value="Only me">Only me</option>
                            </select>
                            <div class="flex space-x-2">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded-lg text-xs">
                                    {{ __('Update Post') }}
                                </button>
                                <button type="button" ng-click="cancelEdit(post)" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded-lg text-xs">
                                    {{ __('Cancel') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Default Post Content (when not editing) -->
                <div ng-if="!post.isEditing">
                    <p class="text-xl font-semibold mb-4">@{{ post.content }}</p>
                </div>

                <!-- Like and Comment Buttons -->
                <div class="flex justify-between mt-4 border-t-2 border-b-2 pt-4 pb-4">
                    <div class="flex-1 border-r-2 pr-4">
                        <button ng-click="likePost(post)" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg w-full text-center">
                            {{ __('Like') }} @{{ post.likes_count }}
                        </button>
                    </div>
                    <div class="flex-1 pl-4">
                        <button ng-click="toggleComments(post)" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg w-full text-center">
                            {{ __('Comments') }}
                        </button>
                    </div>
                </div>

                <!-- Comments Section -->
                <div ng-if="post.showComments">
                    <!-- Display Comments -->
                    <ul class="mt-4">
                        <li ng-repeat="comment in post.comments" class="border-b pb-4 mb-4 rounded-lg p-4 bg-gray-100">
                            <!-- Comment User Info -->
                            <div class="flex items-center">
                                <span class="text-sm font-semibold mr-2">
                                    @{{ comment.user.first_name }} @{{ comment.user.middle_name }} @{{ comment.user.last_name }} @{{ comment.user.suffix }}
                                </span>
                            </div>

                            <!-- Comment Text -->
                            <p class="text-sm mt-2 italic">"@{{ comment.comment }}"</p>

                            <!-- Comment Actions: Date, Like, Reply, Edit, Delete -->
                            <div class="flex items-center justify mt-2 text-xs text-gray-600">
                                <span class="mr-4">@{{ comment.created_at | date:'medium' }}</span>
                                <button ng-click="likeComment(comment)" class="text-gray-600">
                                @{{ comment.likes_count }} {{ __('Like') }}
                                </button>
                                <button ng-click="replyToComment(comment)" class="text-gray-600 ml-2">
                                    {{ __('Reply') }}
                                </button>
                                <div ng-if="comment.user_id === currentUserId" class="space-x-2">
                                    <button ng-click="editComment(comment)" class="text-gray-600 ml-2">
                                        {{ __('Edit') }}
                                    </button>
                                    <button ng-click="deleteComment(comment)" class="text-gray-600 ml-2">
                                        {{ __('Delete') }}
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <!-- Write a New Comment -->
                    <form ng-submit="addComment(post)" class="mt-4">
                        <div class="flex items-center mt-2">
                            <!-- Input field with matching height -->
                            <input type="text" ng-model="post.newComment" id="newCommentInput" name="newComment" placeholder="Add a comment"
                                class="w-11/12 py-2 px-3 border rounded-md text-sm h-12">
                            
                            <!-- Button with matching height -->
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg ml-2 text-xs h-12">
                                {{ __('Comment') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
    // Pass the authenticated user's ID to a global JavaScript variable
    window.currentUserId = @json(auth()->user()->id);

    window.currentUser = {
        firstName: "{{ auth()->user()->first_name }}",
        middleName: "{{ auth()->user()->middle_name }}",
        lastName: "{{ auth()->user()->last_name }}",
        suffix: "{{ auth()->user()->suffix }}"
    };
    </script>
</x-app-layout>