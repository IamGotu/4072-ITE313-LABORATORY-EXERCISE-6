<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="py-6">
        <div ng-app="socialApp" ng-controller="PostController" class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- New Post Form -->
            <form ng-submit="createPost()" class="bg-white p-6 rounded-lg shadow-md">
                <textarea ng-model="newPost.content" class="w-full bg-white p-6 rounded-lg shadow-md" placeholder="What's on your mind?" required></textarea>
                <select ng-model="newPost.visibility" class="mt-2 w-full rounded-lg">
                    <option value="Public">Public</option>
                    <option value="Friends">Friends</option>
                    <option value="Only me">Only me</option>
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg mt-4">
                    {{ __('Post') }}
                </button>
            </form>

            <!-- Display Posts -->
            <div ng-repeat="post in posts" class="bg-white p-6 rounded-lg shadow-md mt-4">
                <!-- User Info with Dropdown -->
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <span class="text-xl font-bold">@{{ post.user.first_name }} @{{ post.user.middle_name }} @{{ post.user.last_name }} @{{ post.user.suffix }}</span>
                        <br>
                        <span class="text-sm text-gray-500">@{{ post.created_at | date:'medium' }} - @{{ post.visibility }}</span>
                    </div>
                    <div>
                        <!-- Post Content with Dropdown Actions -->
                        <div class="relative" ng-init="post.showDropdown = false">
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

                <!-- Editable Post Content -->
                <div ng-if="post.isEditing">
                    <form ng-submit="updatePost(post)" class="mt-4">
                        <textarea ng-model="post.content" id="post-content-@{{ post.id }}" name="content" class="w-full p-2 border rounded-md mb-2" required></textarea>
                        <select ng-model="post.visibility" id="post-visibility-@{{ post.id }}" name="visibility" class="mt-2 w-full rounded-lg">
                            <option value="Public">Public</option>
                            <option value="Friends">Friends</option>
                            <option value="Only me">Only me</option>
                        </select>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg mt-4">
                            {{ __('Update Post') }}
                        </button>
                    </form>
                </div>

                <!-- Default Post Content (when not editing) -->
                <div ng-if="!post.isEditing">
                    <p class="text-xl font-semibold mb-4">@{{ post.content }}</p>
                </div>

                <!-- Like and Comment Buttons -->
                <div class="flex justify-start space-x-4 mb-4">
                    <button ng-click="likePost(post)" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        {{ __('Like') }} (@{{ post.likes_count }})
                    </button>
                    <button ng-click="toggleComments(post)" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                        {{ __('Comments') }}
                    </button>
                </div>

                <!-- View Comments Section -->
                <div ng-if="post.showComments">
                    <ul class="mt-4">
                        <li ng-repeat="comment in post.comments" class="border-b pb-4 mb-4">
                            <small><span class="font-semibold">@{{ comment.user.name }}</span> on <span>@{{ comment.created_at | date:'medium' }}</span></small>
                            <p class="text-xl font-semibold mt-2">@{{ comment.comment }}</p>
                            <!-- Reply Button for Each Comment -->
                            <button ng-click="replyToComment(comment)" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded-lg mt-2">
                                {{ __('Reply') }}
                            </button>
                        </li>
                    </ul>

                    <!-- Write a New Comment -->
                    <form ng-submit="addComment(post)" class="mt-4">
                        <input type="text" ng-model="post.newComment" placeholder="Add a comment" class="w-full p-2 border rounded-md mb-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                            {{ __('Post Comment') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</x-app-layout>