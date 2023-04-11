<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $posts = Post::all();
    return response()->view('admin.post.index', compact('posts'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $data['postCategories'] = PostCategory::all();
    $data['blogTags'] = BlogTag::all();
    return response()->view('admin.post.create', $data);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $request->validate([
      'name'         => 'required|max:255',
      'image'        => 'nullable|image',
      'details'      => 'nullable',
      'category_id'  => 'required',
      'seo_key_word' => 'nullable',
      'status'       => 'required',
    ]);

    $filePath = null;
    if ($request->hasFile('image')) {
      $filePath = Rand() . '.' . $request->image->getClientOriginalExtension();
      $filePath = $request->image->storeAs('images/post_img', $filePath, 'public');
    }

    // dynamic category entry
    if (!PostCategory::find($request->input('category_id'))) {
      $newCategory = PostCategory::create([
        'name' => $request->input('category_id'),
      ]);
      $request->category_id = $newCategory->id;
    }

    $keyWords = null;
    if ($request->input('seo_key_word')) {
      foreach ($request->input('seo_key_word') as $keyWord) {
        $keyWords .= $keyWord . ',';
      }
      $request->seo_key_word = $keyWords;
    }

    $post = Post::create([
      'image'        => $filePath,
      'name'         => $request->input('name'),
      'details'      => $request->input('details'),
      'category_id'  => $request->category_id,
      'seo_key_word' => rtrim($request->seo_key_word, ','),
      'status'       => $request->input('status'),
    ]);

    $tags = [];
    if ($request->input('tag_id')) {
      foreach ($request->input('tag_id') as $i => $tag) {
        if (BlogTag::find($tag)) {
          $tags[$i] = $tag;
        } else {
          $newTag = BlogTag::create([
            'name' => $tag,
          ]);
          $tags[$i] = $newTag->id;
        }
      }
    }

    $post->blogTags()->sync($tags);

    return back()->with('success', 'Post Created Successfully.');
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function edit(Post $post)
  {
    $data['postCategories'] = PostCategory::all();
    $data['blogTags'] = BlogTag::all();
    $data['oldTags'] = DB::table('post_blog_tags')->where('post_id', $post->id)->pluck('blog_tag_id');
    $data['old_seo_key_word'] = collect(explode(',', $post->seo_key_word));
    $data['post'] = $post;

    return response()->view('admin.post.edit', $data);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Post $post)
  {
    $request->validate([
      'name'         => 'required|max:255',
      'image'        => 'nullable|image',
      'details'      => 'nullable',
      'category_id'  => 'required',
      'seo_key_word' => 'nullable',
      'status'       => 'required',
    ]);

    //initial value old image
    $filePath = $post->image;
    if ($request->hasFile('image')) {
      if (File::exists('storage/' . $filePath)) {
        File::delete('storage/' . $filePath);
      }
      $filePath = Rand() . '.' . $request->image->getClientOriginalExtension();
      $filePath = $request->image->storeAs('images/post_img', $filePath, 'public');
    }

    // dynamic category entry
    if (!PostCategory::find($request->input('category_id'))) {
      $newCategory = PostCategory::create([
        'name' => $request->input('category_id'),
      ]);
      $request->category_id = $newCategory->id;
    }

    $keyWords = null;
    if ($request->input('seo_key_word')) {
      foreach ($request->input('seo_key_word') as $keyWord) {
        $keyWords .= $keyWord . ',';
      }
      $request->seo_key_word = $keyWords;
    }

    $tags = [];
    if ($request->input('tag_id')) {
      foreach ($request->input('tag_id') as $i => $tag) {
        if (BlogTag::find($tag)) {
          $tags[$i] = $tag;
        } else {
          $newTag = BlogTag::create([
            'name' => $tag,
          ]);
          $tags[$i] = $newTag->id;
        }
      }
    }
    $post->blogTags()->sync($tags);

    $post->update([
      'image'        => $filePath,
      'name'         => $request->input('name'),
      'details'      => $request->input('details'),
      'category_id'  => $request->category_id,
      'seo_key_word' => rtrim($request->seo_key_word, ','),
      'status'       => $request->input('status'),
    ]);

    return back()->with('success', 'Post Updated Successfully.');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
