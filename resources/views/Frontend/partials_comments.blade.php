<li class="comment">
    <div class="vcard bio">
        <img src="{{ Avatar::create($comment->rel_to_user->name)->toBase64() }}" alt="Image placeholder">
    </div>
    <div class="comment-body">
        <h3>{{ $comment->rel_to_user->name }}</h3>
        <div class="meta">{{ $comment->created_at->format('M d, Y') }}</div>
        <p>{{ $comment->comment }}</p>
        @if(Auth::guard('user')->check())
            <!-- Reply input form for the nested comment -->
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