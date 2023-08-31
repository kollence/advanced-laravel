<div id="reply-{{$reply->id}}" class="p-5 text-gray-900 dark:text-gray-100 ">
    <h5 style="font-size: 20px;" class="flex justify-between">
        <a style=" color: orange;" href="{{route('profile.show', $reply->user->name)}}">{{ $reply->user->name }}</a>
        <div class="mt-1">said {{ $reply->created_at->diffForHumans() }} </div>
    </h5>
    <div class="body">
        <div id="content-{{$reply->id}}">
            {{ $reply->body }}
        </div>
    </div>
    <div class="mt-2 flex justify-between align-middle" style="border-top: thin solid gray; border-bottom: thin solid gray;">


        <div class="flex">
            <div class="mt-1 mr-5">
                @can('update', $reply)
                <button id="edit-reply-{{$reply->id}}" data-id="{{$reply->id}}" onclick="openEdit(this)" class="hover:text-blue-300">
                    <svg fill="#e0e0e0" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="22px" height="22px" viewBox="0 0 494.936 494.936" xml:space="preserve" stroke="#e0e0e0">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <g>
                                <g>
                                    <path d="M389.844,182.85c-6.743,0-12.21,5.467-12.21,12.21v222.968c0,23.562-19.174,42.735-42.736,42.735H67.157 c-23.562,0-42.736-19.174-42.736-42.735V150.285c0-23.562,19.174-42.735,42.736-42.735h267.741c6.743,0,12.21-5.467,12.21-12.21 s-5.467-12.21-12.21-12.21H67.157C30.126,83.13,0,113.255,0,150.285v267.743c0,37.029,30.126,67.155,67.157,67.155h267.741 c37.03,0,67.156-30.126,67.156-67.155V195.061C402.054,188.318,396.587,182.85,389.844,182.85z"></path>
                                    <path d="M483.876,20.791c-14.72-14.72-38.669-14.714-53.377,0L221.352,229.944c-0.28,0.28-3.434,3.559-4.251,5.396l-28.963,65.069 c-2.057,4.619-1.056,10.027,2.521,13.6c2.337,2.336,5.461,3.576,8.639,3.576c1.675,0,3.362-0.346,4.96-1.057l65.07-28.963 c1.83-0.815,5.114-3.97,5.396-4.25L483.876,74.169c7.131-7.131,11.06-16.61,11.06-26.692 C494.936,37.396,491.007,27.915,483.876,20.791z M466.61,56.897L257.457,266.05c-0.035,0.036-0.055,0.078-0.089,0.107 l-33.989,15.131L238.51,247.3c0.03-0.036,0.071-0.055,0.107-0.09L447.765,38.058c5.038-5.039,13.819-5.033,18.846,0.005 c2.518,2.51,3.905,5.855,3.905,9.414C470.516,51.036,469.127,54.38,466.61,56.897z"></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </button>
                @endcan
            </div>
            <div class="mr-5">
                @can('delete', $reply)
                <button type="button" onclick='removeReply("{{$reply->id}}")' class="flex bg-transparent hover:bg-red-600 font-bold text-red-300 p-1 rounded-full shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 7h16" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                        <path d="M10 12l4 4m0 -4l-4 4" />
                    </svg>
                </button>
                @endcan
            </div>
        </div>

        <div class="flex">
            @auth
            <button type="button" onclick='markAsFavorite("{{$reply->id}}", this)' data-count="{{$reply->favorites_count}}" class="flex bg-transparent {{($reply->isFavorited()) ? '' : 'hover:bg-blue-600'}}  font-bold py-2 px-4 rounded-full shadow-md">
                <small class="mr-3" id="favorited-{{$reply->id}}">{{$reply->favorites_count}}</small> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hand-thumbs-up" viewBox="0 0 16 16">
                    <path d="M8.864.046C7.908-.193 7.02.53 6.956 1.466c-.072 1.051-.23 2.016-.428 2.59-.125.36-.479 1.013-1.04 1.639-.557.623-1.282 1.178-2.131 1.41C2.685 7.288 2 7.87 2 8.72v4.001c0 .845.682 1.464 1.448 1.545 1.07.114 1.564.415 2.068.723l.048.03c.272.165.578.348.97.484.397.136.861.217 1.466.217h3.5c.937 0 1.599-.477 1.934-1.064a1.86 1.86 0 0 0 .254-.912c0-.152-.023-.312-.077-.464.201-.263.38-.578.488-.901.11-.33.172-.762.004-1.149.069-.13.12-.269.159-.403.077-.27.113-.568.113-.857 0-.288-.036-.585-.113-.856a2.144 2.144 0 0 0-.138-.362 1.9 1.9 0 0 0 .234-1.734c-.206-.592-.682-1.1-1.2-1.272-.847-.282-1.803-.276-2.516-.211a9.84 9.84 0 0 0-.443.05 9.365 9.365 0 0 0-.062-4.509A1.38 1.38 0 0 0 9.125.111L8.864.046zM11.5 14.721H8c-.51 0-.863-.069-1.14-.164-.281-.097-.506-.228-.776-.393l-.04-.024c-.555-.339-1.198-.731-2.49-.868-.333-.036-.554-.29-.554-.55V8.72c0-.254.226-.543.62-.65 1.095-.3 1.977-.996 2.614-1.708.635-.71 1.064-1.475 1.238-1.978.243-.7.407-1.768.482-2.85.025-.362.36-.594.667-.518l.262.066c.16.04.258.143.288.255a8.34 8.34 0 0 1-.145 4.725.5.5 0 0 0 .595.644l.003-.001.014-.003.058-.014a8.908 8.908 0 0 1 1.036-.157c.663-.06 1.457-.054 2.11.164.175.058.45.3.57.65.107.308.087.67-.266 1.022l-.353.353.353.354c.043.043.105.141.154.315.048.167.075.37.075.581 0 .212-.027.414-.075.582-.05.174-.111.272-.154.315l-.353.353.353.354c.047.047.109.177.005.488a2.224 2.224 0 0 1-.505.805l-.353.353.353.354c.006.005.041.05.041.17a.866.866 0 0 1-.121.416c-.165.288-.503.56-1.066.56z" />
                </svg>
            </button>
            @else
            You need to <a href="{{ route('login') }}" class="text-sm px-2 text-gray-700 dark:text-blue-300">Log in</a> so you could take actions
            @endauth
        </div>
    </div>
</div>

<script>
    function removeReply(replyId) {
        const replyDiv = document.getElementById(`reply-${replyId}`);
        const contHtml = document.getElementById('comments_number');
        const formData = new FormData();
        formData.append('_method', 'DELETE');
        formData.append('_token', '{{ csrf_token() }}');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/replies/${replyId}`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData,
                    })
                    .then(response => {
                        if (response.status === 204) {
                            replyDiv.remove();
                            return response.text();
                        } else {
                            throw new Error('Error deleting reply');
                        }
                    })
                    .then(data => {
                        const count = document.querySelectorAll('#reply-holder > div').length;
                        contHtml.textContent = 'Comments: ' + count;
                        console.log(count);
                    })
                    .catch(error => {
                        console.error('Error deleting reply:', error);
                    });
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            }
        })
    }

    function openEdit(button) {
        const replyId = button.getAttribute('data-id');
        const contentDiv = document.getElementById(`content-${replyId}`);

        const contentText = contentDiv.textContent.trim();

        const formHTML = `
        <form action="" method="POST">
            @csrf
            @method('PATCH')
            <div id="editForm-${replyId}">
                <textarea  name="body" id="body-${replyId}" class="rounded-md form-control w-full" style="color: black;" placeholder="Have something to say?" rows="2">${contentText}</textarea>
                <button type="button" onclick="cancelEdit(${replyId}, '${contentText}')" class="bg-slate-700 text-sm border rounded-md p-1">Cancel</button>
                <button type="button" onclick="saveEdit(${replyId})" class="bg-slate-700 text-sm border rounded-md p-1">Save</button>
            </div>
        <form>
        `;
        contentDiv.innerHTML = formHTML;
    }

    function saveEdit(replyId) {
        const contentDiv = document.getElementById(`content-${replyId}`);
        const editedContent = document.getElementById(`body-${replyId}`).value;

        const formData = new FormData();
        formData.append('_method', 'PATCH');
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('body', editedContent);

        fetch(`/replies/${replyId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            })
            .then(response => {
                if (response.status === 200) {
                    return response.json();
                } else {
                    throw new Error('Error updating reply');
                }
            })
            .then(response => {
                if (response.success) {
                    contentDiv.textContent = response.reply.body; // Update the content with edited value
                } else {
                    console.error('Update failed:', response);
                }
            })
            .catch(error => {
                console.error('Error updating reply:', error);
            });
    }

    function cancelEdit(replyId, originalContent) {
        const updatedContentHTML = `<p>${originalContent}</p>`;

        const contentDiv = document.getElementById(`content-${replyId}`);
        contentDiv.innerHTML = updatedContentHTML;
    }

    function markAsFavorite(replyId, button) {
        const replyDiv = document.getElementById(`reply-${replyId}`);
        const countFavorited = document.getElementById(`favorited-${replyId}`);

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        if (button.getAttribute('data-count') == 1) {
            formData.append('_method', 'DELETE');
        }

        fetch(`/replies/${replyId}/favorites`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            })
            .then(response => response.json())
            .then(response => {
                if (response.isFavorited !== undefined) {
                    countFavorited.innerHTML = response.isFavorited;
                    button.setAttribute('data-count', response.isFavorited);
                } else {
                    console.error('Error marking reply as favorite:', response);
                }
            })
            .catch(error => {
                console.error('Error marking reply as favorite:', error);
            });
    }
</script>