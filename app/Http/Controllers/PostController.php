<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\PostTag;
use App\Models\PostCategory;
use Illuminate\Validation\Rules\File;

class PostController extends Controller
{
    public function index(){
        $data = Post::with(['categories', 'tags'])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('post_title', 'ILIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function artikel(){
        $data = Post::where('post_status', 'publish')->with(['categories', 'tags'])->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('post_title', 'ILIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function category(){
        $data = Category::with('subcategory')->whereNull('category_id')->orderBy('id')->get();
        return response()->json(['data' => $data]);
    }
    public function save_category(){
        request()->validate(
            [
                'name' => 'required|string',
            ],
            [
                'nama' => 'Nama Kategori tidak boleh kosong!'
            ]
        );
        Category::create([
            'name' => request()->name,
            'category_id' => request()->category_id,
        ]);
        $data = Category::with('subcategory')->whereNull('category_id')->orderBy('id')->get();
        return response()->json(['data' => $data]);
    }
    public function save(){
        request()->validate(
            [
                'post_title' => 'required|string',
                'post_content' => 'required',
                'post_image' => ['required', File::image()]
            ],
            [
                'post_title' => 'Judul Kategori tidak boleh kosong!',
                'post_content' => 'Konten Artikel tidak boleh kosong!',
                'post_image' => 'Gambar/Foto Artikel tidak boleh kosong!'
            ]
        );
        $post_image = request()->post_image->store('public/post-images');
        $string = Str::of($post_image)->basename();
        if(request()->id){
            $post = Post::find(request()->id);
            $post->post_author = auth()->user()->user_id;
            $post->post_content = request()->post_content;
            $post->post_title = request()->post_title;
            $post->post_status = request()->post_status;
            $post->post_image = $string;
            $post->save();
        } else {
            $post = Post::create([
                'post_author' => auth()->user()->user_id,
                'post_content' => request()->post_content,
                'post_title' => request()->post_title,
                'post_status' => request()->post_status,
                'post_image' => $string,
            ]);
        }
        foreach(json_decode(request()->category_id) as $category_id){
            PostCategory::updateOrCreate([
                'post_id' => $post->id,
                'category_id' => $category_id,
            ]);
        }
        $tag_id = [];
        foreach(json_decode(request()->tags) as $tag){
            $new_tag = Tag::updateOrCreate([
                'name' => $tag,
            ]);
            $tag_id[] = $new_tag->id;
            PostTag::updateOrCreate([
                'post_id' => $post->id,
                'tag_id' => $new_tag->id,
            ]);
        }
        if(request()->id){
            PostCategory::where('post_id', request()->id)->whereNotIn('category_id', json_decode(request()->category_id))->delete();
            PostTag::where('post_id', request()->id)->whereNotIn('tag_id', $tag_id)->delete();
        }
        $data = [
            'success' => TRUE,
            'string' => $string,
        ];
        return response()->json($data);
    }
    public function destroy(){
        $find = Post::find(request()->id);
        if($find){
            if($find->delete()){
                $data = [
                    'icon' => 'success',
                    'text' => 'Artikel berhasil dihapus',
                    'title' => 'Berhasil',
                ];
            } else {
                $data = [
                    'icon' => 'error',
                    'text' => 'Artikel gagal dihapus. Silahkan coba beberapa saat lagi',
                    'title' => 'Gagal',
                ];
            }
        } else {
            $data = [
                'icon' => 'error',
                'text' => 'ID Artikel tidak ditemukan',
                'title' => 'Gagal',
            ];
        }
        return response()->json($data);
    }
    public function show(){
        $data = Post::with(['categories', 'tags'])->find(request()->id);
        return response()->json($data);
    }
    public function baca(){
        $data = [
            'post' => Post::with(['categories', 'tags'])->where('post_name', request()->slug)->first(),
            'latest' => $data = Post::with(['categories', 'tags'])->orderBy('created_at', 'DESC')->take(5)->where('post_name', '<>', request()->slug)->where('post_status', 'publish')->get(),
        ];
        return response()->json($data);
    }
}
