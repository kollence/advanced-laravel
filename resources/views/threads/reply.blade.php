<div id="reply-{{$reply->id}}" class="p-5 text-gray-900 dark:text-gray-100 ">
    <h5 style="font-size: 20px;" class="flex justify-between">
        <a style=" color: orange;" href="{{route('profile.show', $reply->user->id)}}">{{ $reply->user->name }}</a>
        <div class="mt-1">said  {{ $reply->created_at->diffForHumans() }} </div>
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
            <button id="edit-reply-{{$reply->id}}" data-id="{{$reply->id}}" onclick="openEdit(this)" >Edit</button>
            @endcan
        </div>
        @can('delete', $reply)
        <form action="{{route('replies.delete', $reply->id)}}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure you want to delete this comment?');" class="flex bg-transparent hover:bg-red-600 font-bold text-red-300 p-1 rounded-full shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"/> <path d="M4 7h16" /> <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /> <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /> <path d="M10 12l4 4m0 -4l-4 4" /> </svg>
                </button>
        </form>
        @endcan
        </div> 

        <div class="flex">
            <form action="{{route('favorites.store', $reply->id)}}" method="post">
                @csrf
                <button type="submit" class="flex bg-transparent {{($reply->isFavorited()) ? '' : 'hover:bg-blue-600'}}  font-bold py-2 px-4 rounded-full shadow-md" {{($reply->isFavorited()) ? 'disabled' : ''}}>
                <small class="mr-3">{{$reply->favorites_count}}</small> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hand-thumbs-up" viewBox="0 0 16 16"> <path d="M8.864.046C7.908-.193 7.02.53 6.956 1.466c-.072 1.051-.23 2.016-.428 2.59-.125.36-.479 1.013-1.04 1.639-.557.623-1.282 1.178-2.131 1.41C2.685 7.288 2 7.87 2 8.72v4.001c0 .845.682 1.464 1.448 1.545 1.07.114 1.564.415 2.068.723l.048.03c.272.165.578.348.97.484.397.136.861.217 1.466.217h3.5c.937 0 1.599-.477 1.934-1.064a1.86 1.86 0 0 0 .254-.912c0-.152-.023-.312-.077-.464.201-.263.38-.578.488-.901.11-.33.172-.762.004-1.149.069-.13.12-.269.159-.403.077-.27.113-.568.113-.857 0-.288-.036-.585-.113-.856a2.144 2.144 0 0 0-.138-.362 1.9 1.9 0 0 0 .234-1.734c-.206-.592-.682-1.1-1.2-1.272-.847-.282-1.803-.276-2.516-.211a9.84 9.84 0 0 0-.443.05 9.365 9.365 0 0 0-.062-4.509A1.38 1.38 0 0 0 9.125.111L8.864.046zM11.5 14.721H8c-.51 0-.863-.069-1.14-.164-.281-.097-.506-.228-.776-.393l-.04-.024c-.555-.339-1.198-.731-2.49-.868-.333-.036-.554-.29-.554-.55V8.72c0-.254.226-.543.62-.65 1.095-.3 1.977-.996 2.614-1.708.635-.71 1.064-1.475 1.238-1.978.243-.7.407-1.768.482-2.85.025-.362.36-.594.667-.518l.262.066c.16.04.258.143.288.255a8.34 8.34 0 0 1-.145 4.725.5.5 0 0 0 .595.644l.003-.001.014-.003.058-.014a8.908 8.908 0 0 1 1.036-.157c.663-.06 1.457-.054 2.11.164.175.058.45.3.57.65.107.308.087.67-.266 1.022l-.353.353.353.354c.043.043.105.141.154.315.048.167.075.37.075.581 0 .212-.027.414-.075.582-.05.174-.111.272-.154.315l-.353.353.353.354c.047.047.109.177.005.488a2.224 2.224 0 0 1-.505.805l-.353.353.353.354c.006.005.041.05.041.17a.866.866 0 0 1-.121.416c-.165.288-.503.56-1.066.56z"/> </svg>
                </button>
            </form>
        </div>  
    </div>
</div> 

<script>
    function openEdit(button) {
        const replyId = button.getAttribute('data-id');
        const contentDiv = document.getElementById(`content-${replyId}`);
        
        const contentText = contentDiv.textContent.trim();
        
        const formHTML = `
        <form action="{{ route('replies.update',  $reply->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div id="editForm-${replyId}">
                <textarea  name="body" id="body" class="rounded-md form-control w-full" style="color: black;" placeholder="Have something to say?" rows="2">${contentText}</textarea>
                <button type="button" onclick="cancelEdit(${replyId}, '${contentText}')" class="bg-slate-700 text-sm border rounded-md p-1">Cancel</button>
                <button type="submit" class="bg-slate-700 text-sm border rounded-md p-1">Save</button>
            </div>
        <form>
        `;
        
        contentDiv.innerHTML = formHTML;
    }
    
    // function saveEdit(replyId) {
    //     const editForm = document.getElementById(`editForm-${replyId}`);
    //     const editedContent = editForm.querySelector('textarea').value;
    //     const updatedContentHTML = `<p>${editedContent}</p>`;
            
    //     const contentDiv = document.getElementById(`content-${replyId}`);
    //     contentDiv.innerHTML = updatedContentHTML;
    // }
    
    function cancelEdit(replyId, originalContent) {
        const updatedContentHTML = `<p>${originalContent}</p>`;
        
        const contentDiv = document.getElementById(`content-${replyId}`);
        contentDiv.innerHTML = updatedContentHTML;
    }
</script>
<!-- <script>
    function openEdit(button) {
        const replyId = button.getAttribute('data-id');
        const contentDiv = document.getElementById(`content-${replyId}`);
        
        const contentText = contentDiv.textContent.trim();
        
        const formHTML = `
            <form id="editForm-${replyId}">
                <textarea  name="body" id="body" class="rounded-md form-control w-full" style="color: black;" placeholder="Have something to say?" rows="2">${contentText}</textarea>
                <div>
                    <button type="button" onclick="cancelEdit(${replyId}, '${contentText}')" class="bg-slate-700 text-sm border rounded-md p-1">Cancel</button>
                    <button type="submit"  class="bg-slate-700 text-sm border rounded-md p-1">Save</button>
                </div>
            </form>
        `;
        
        contentDiv.innerHTML = formHTML;
        
        const editForm = document.getElementById(`editForm-${replyId}`);
        
        function cancelEdit(replyId, originalContent) {
            const updatedContentHTML = `<p>${originalContent}</p>
                <button id="edit-reply-${replyId}" data-id="${replyId}" onclick="openEdit(this)">Edit</button>`;
            
            const contentDiv = document.getElementById(`content-${replyId}`);
            contentDiv.innerHTML = updatedContentHTML;
        }
    }
</script>  -->