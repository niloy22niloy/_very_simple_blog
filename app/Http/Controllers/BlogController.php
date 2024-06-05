<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogEditReq;
use App\Models\BlogAdd;
use App\Models\CategoryModel;
use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    //
    public function blog_details($slug){
        try {
            $blog_find = BlogAdd::where('slug', $slug)->firstOrFail();
            
            // Your existing code
            $count = 1;
            $ba = BlogAdd::all();
            $counts = 0;
            $category = CategoryModel::all();
            
            $blog = BlogAdd::where('slug', $slug)->first();
            $popular_posts = BlogAdd::orderBy('count', 'desc')->where('slug', '!=', $slug)->take(2)->get();
            
            $newCount = $blog->count + 1; // Increment the count
            $blog->update([
                'count' => $newCount,
            ]);
      
        $comments = Comments::with('rel_to_user')
        ->where('post_id', $blog->id)
        ->whereNull('parent_id')
        ->get();

    // Fetch nested comments (comments with parent_id)
    $nestedComments = Comments::with('rel_to_user')
        ->where('post_id', $blog->id)
        ->whereNotNull('parent_id')
        ->get();

         $count_comment = Comments::where('post_id',$blog->id)->count();

    // Organize nested comments by parent_id
      // Organize nested comments by parent_id
      $nestedCommentsByParentId = [];
      foreach ($nestedComments as $nestedComment) {
           $nestedCommentsByParentId[$nestedComment->parent_id][] = $nestedComment;
      }
  
      // Attach nested comments to their respective parents
      foreach ($comments as $comment) {
          $this->attachNestedComments($comment, $nestedCommentsByParentId);
      }
  
      // Debugging: Print the comments with nested structure
      $debugOutput = $this->buildDebugOutput($comments);
      ($debugOutput); 
        

        
            
            return view('Frontend.blog_details', [
                'blog' => $blog,
                'comments' => $comments,
                'nestedComments'=>$nestedComments,
                'nestedCommentsByParentId' => $nestedCommentsByParentId,
                'category' => CategoryModel::all(),
                'popular_posts' => BlogAdd::orderBy('count', 'desc')->where('slug', '!=', $slug)->take(2)->get(),
                'count_comment'=>$count_comment,
            ]);
        } catch (\Exception $e) {
            // Handle the exception
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
}
private function attachNestedComments($comment, $nestedCommentsByParentId)
{
    if (isset($nestedCommentsByParentId[$comment->id])) {
        $comment->children = $nestedCommentsByParentId[$comment->id];
        foreach ($comment->children as $child) {
            $this->attachNestedComments($child, $nestedCommentsByParentId);
        }
    } else {
        $comment->children = [];
    }
}
private function buildDebugOutput($comments, $level = 0)
{
    $output = '';
    foreach ($comments as $comment) {
        $output .= str_repeat(' ', $level * 4) . "Comment ID: {$comment->id} (Parent ID: {$comment->parent_id})\n";
        if (!empty($comment->children)) {
            $output .= str_repeat(' ', $level * 4) . "Replies: ";
            foreach ($comment->children as $child) {
                $output .= $child->id . ', ';
            }
            $output = rtrim($output, ', ') . "\n";
            $output .= $this->buildDebugOutput($comment->children, $level + 1);
        }
    }
    return $output;
}
    
    function user_comment(Request $request,$id){
       
        if(Auth::guard('user')->user()->id == $request->parent_id){
            return back()->with('error',"Sorry You cannot Give Reply To your own comment");
        }
        if($request->comment != null){
            Comments::create([
                'user_id'=>Auth::guard('user')->user()->id,
                'post_id'=>$id,
                'parent_id'=>$request->parent_id,
                'comment'=>$request->comment,
            ]);
            return back()->with('success',"Successfully Added Comment");
           
        }else{
            return back()->with('error',"You have  given empty comment!!");
        }
       
    }
    public function user_single_comment(Request $request,$id){
        
        Comments::create([
            'user_id'=>Auth::guard('user')->user()->id,
            'post_id'=>$id,
            'parent_id'=>null,
            'comment'=>$request->message,
        ]);
        return back();
    }
    public function category_base_post($name){
        $category = CategoryModel::all();
        $find_cat = CategoryModel::where('category_name',$name)->first();
        $blog = BlogAdd::where('category_id',$find_cat->id)->get();
        return view('Frontend.category_based_post',[
            'category'=>$category,
            'blog'=>$blog,
        ]);
    }
    public function deletePost(Request $request, $id) {
        try {
            // Retrieve the post by ID and delete it
            $post = BlogAdd::findOrFail($id);
            $post->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Post deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting post: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the post.'
            ], 500); // Internal Server Error
        }
    }
    public function blog_edit(BlogEditReq $request,$id){
        
        $blog = BlogAdd::find($id);

        // Retrieve the original values of the fields
        $originalValues = [
            'user_id' => $blog->user_id,
            'title' => $blog->title,
            'category_id' => $blog->category_id,
            'description' => $blog->description,
            'image' => $blog->image, // Add previous image to the original values
            // Add other fields here as needed
        ];
        
        // Retrieve the previous image file name
        $previousImage = $blog->image;
        
        if ($request->hasFile('blog_thumbnail')) {
            // Upload the new image
            $extension = $request->file('blog_thumbnail')->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $request->file('blog_thumbnail')->move(public_path('Blog_thumbnail_image'), $filename);
        
            // Update the database record with the new image file name
            $blog->image = $filename;
        }
        
        // Update other fields
        $blog->user_id = Auth::guard('user')->id();
        $blog->title = $request->title;
        $blog->category_id = $request->category_id;
        $blog->description = $request->Blog_Description;
        // Update other fields as needed
        
        // Check if any changes were made
        $changesMade = false;
        foreach ($originalValues as $field => $value) {
            if ($blog->$field != $value) {
                $changesMade = true;
                break;
            }
        }
        
        // If no changes were made, return with a message
        if (!$changesMade) {
            return back()->with('success', 'No changes were made.');
        }
        
        $blog->save();
        
        // Delete the previous image file from the file system
        if ($request->hasFile('blog_thumbnail') && $previousImage) {
            $previousImagePath = public_path('Blog_thumbnail_image') . '/' . $previousImage;
            if (file_exists($previousImagePath)) {
                unlink($previousImagePath);
            }
        }
        
        return back()->with('success', "Successfully Updated " . $request->title);
}
public function edit_comment(Request $request,$id){
    Comments::where('post_id',$id)->where('user_id',$request->user_id)->where('parent_id',$request->parent_id)->update([
        'comment'=>$request->reply,
    ]);
    return back()->with('success',"Successfully Edited The Comment");
}
}
