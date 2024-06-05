<div class="pt-5 mt-5">
    <h3 class="mb-5 font-weight-bold">
      
        {{ ($count_comment) }} {{ ($count_comment) == 1 ? 'Comment' : 'Comments' }}
    </h3>
    <ul class="comment-list">
        @foreach($comments as $comment)
            <li class="comment">
                <div class="vcard bio">
                    <img src="{{ Avatar::create($comment->rel_to_user->name)->toBase64() }}" alt="Image placeholder">
                </div>
                <div class="comment-body">
                    <h3>{{ $comment->rel_to_user->name }}</h3>
                    <div class="meta">{{ $comment->created_at->format('M d, Y') }}</div>
                    <p>{{ $comment->comment }}</p>
                    @if(Auth::guard('user')->check())
                        <!-- Reply input form for the comment -->
                        <p><a href="#" class="reply">Reply</a></p>
                        <div class="reply-input" style="display: none;">
                            <form action="{{ route('user.comment', $blog->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                <input type="text" placeholder="Write a reply" name="comment" class="form-control">
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Submit</button>
                            </form>
                        </div>
                    @endif
                </div>
    
                <!-- Render child comments recursively -->
                @if($comment->children)
                    <ul class="children">
                        @foreach($comment->children as $childComment)
                            @include('Frontend.partials_comments', ['comment' => $childComment])
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
    <!-- END comment-list -->
    @if(Auth::guard('user')->check())
    <div class="comment-form-wrap pt-5">
      <h3 class="mb-5">Leave a comment</h3>
      <form action="{{route('user.single_comment',$blog->id)}}" method="POST" class="p-3 p-md-4 bg-light">
        @csrf
      
        

        <div class="form-group">
          <label for="message">Message</label>
          <textarea name="message" id="message" cols="30" rows="10" class="form-control"></textarea>
        </div>
        <div class="form-group">
          <input type="submit" value="Post Comment" class="btn py-3 px-4 btn-primary">
        </div>

      </form>
    </div>
    @else
   <br>** Please Login To comments 
    @endif
  </div>
  <script>
    // Add click event listener to reply links
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('reply')) {
            event.preventDefault(); // Prevent default link behavior

            // Get the closest comment body
            var commentBody = event.target.closest('.comment-body');

            // Get the reply input field within the closest comment body
            var replyInput = commentBody.querySelector('.reply-input');

            // Toggle the display of the reply input field
            if (replyInput.style.display === 'none') {
                replyInput.style.display = 'block';
            } else {
                replyInput.style.display = 'none';
            }
        }
    });
</script>