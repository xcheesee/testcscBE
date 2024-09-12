<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreBookRequest;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::paginate(5);
        return BookResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $book = new Book;
        $book->title = $request->title;
        $book->desc = $request->desc;
        $book->price = $request->price;
        $book->stock = $request->stock;

        if($request->author != null) {
            $authorFound = Http::get("https://openlibrary.org/search/authors.json", [
                "q" => $request->author
            ])->object();

            if($authorFound->numFound != 0 ) {
                $author = Author::where('key', $authorFound->docs[0]->key)->first();
                if($author != null) {
                    $book->author_id = $author->id;
                } else {
                    $authorData = Http::withUrlParameters([
                        'endpoint' => "https://openlibrary.org",
                        'resource' => 'authors',
                        'key' => $authorFound->docs[0]->key . ".json"
                    ])->get("{+endpoint}/{resource}/{key}")->object();
                    $newAuthor = new Author;
                    $newAuthor->name = $authorData->name ?? "N/A";
                    $newAuthor->bio =  $authorData->bio->value ?? "N/A";
                    $newAuthor->country = $authorData->birth_date ?? "N/A";
                    $newAuthor->key = $authorFound->docs[0]->key;
                    if($newAuthor->save()) {
                        $book->author_id = $newAuthor->id;
                    }
                }
            }
        }

        if($book->save()) {
            return 'Success!';
        }
        return "Error";
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new BookResource(Book::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBookRequest $request, string $id)
    {
        $book = Book::find($id);

        if($book == null) {
            return response()->json(['error' => true, 'data' => ['Livro nao encontrado'], 404]);
        }

        $book->title = $request->title;
        $book->desc = $request->desc;
        $book->price = $request->price;
        $book->stock = $request->stock;

        if($request->author != null) {
            $authorFound = Http::get("https://openlibrary.org/search/authors.json", [
                "q" => $request->author
            ])->object();

            if($authorFound->numFound != 0 ) {
                $author = Author::where('key', $authorFound->docs[0]->key)->first();
                if($author != null) {
                    $book->author_id = $author->id;
                } else {
                    $authorData = Http::withUrlParameters([
                        'endpoint' => "https://openlibrary.org",
                        'resource' => 'authors',
                        'key' => $authorFound->docs[0]->key . ".json"
                    ])->get("{+endpoint}/{resource}/{key}")->object();
                    $newAuthor = new Author;
                    $newAuthor->name = $authorData->name ?? "N/A";
                    $newAuthor->bio =  $authorData->bio->value ?? "N/A";
                    $newAuthor->country = $authorData->birth_date ?? "N/A";
                    $newAuthor->key = $authorFound->docs[0]->key;
                    if($newAuthor->save()) {
                        $book->author_id = $newAuthor->id;
                    }
                }
            }
        } else {
            $book->author_id = null;
        }

        if($book->save()) {
            return 'Success!';
        }
        return "Error";

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $book = Book::find($id);
        if($book == null) {
            return response()->json(['error' => true, 'data' => ['Livro nao encontrado'], 404]);
        }
        if($book->delete()) {
            return 'Success';
        }
        return 'Error';

    }
}
