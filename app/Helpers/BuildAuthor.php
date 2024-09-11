<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use App\Http\Models\Author;

class BuildAuthor {
    private $author = null;

    function found($request) {
        $authorFound = Http::get("https://openlibrary.org/search/authors.json", [
            "q" => $request->author
        ])->object();

        $this->author = $authorFound->docs[0];

        return $authorFound->numFound != 0;
    }

    function build() {
        $authorData = Http::withUrlParameters([
            'endpoint' => "https://openlibrary.org",
            'resource' => 'authors',
            'key' => $this->author->key . ".json"
        ])->get("{+endpoint}/{resource}/{key}")->object();
        $newAuthor = new Author;
        $newAuthor->name = $authorData->name ?? "N/A";
        $newAuthor->bio = $authorData->bio->value ?? "N/A";
        $newAuthor->country = "N/A";
        $newAuthor->key = $this->author->key;

        return $newAuthor;
    }

    function getKey() {
        return $this->author->key;
    }
}