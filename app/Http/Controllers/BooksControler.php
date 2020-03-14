<?php

namespace App\Http\Controllers;

use App\BookReservations;
use App\Books;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BooksControler extends Controller
{

    public function getAllBooks()
    {
        $books = Books::with(['users' => function ($query) {
            $query->where('user_id', Auth::user()->id);
        }])->get();
        return $books->map(function ($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'url' => $book->url,
                'short_description' => $book->short_description,
                'long_description' => $book->long_description,
                'authors' => $book->authors,
                'categories' => $book->categories,
                'published_date' => $book->published_date,
                'pageCount' => $book->pageCount,
                'is_favorite' => $book->users->isNotEmpty() ? true : false
            ];
        });
    }

    public function addBooksBulk(Request $request)
    {
        foreach ($request->all() as $item) {
            Books::create([
                'title' => array_key_exists('title', $item) ? $item['title'] : null,
                'pageCount' => array_key_exists('pageCount', $item) ? $item['pageCount'] : null,
                'published_date' => array_key_exists('publishedDate', $item) ? Carbon::parse($item['publishedDate']['$date']) : null,
                'url' => array_key_exists('thumbnailUrl', $item) ? $item['thumbnailUrl'] : null,
                'short_description' => array_key_exists('shortDescription', $item) ? $item['shortDescription'] : null,
                'long_description' => array_key_exists('longDescription', $item) ? $item['longDescription'] : null,
                'authors' => array_key_exists('authors', $item) ? $item['authors'] : null,
                'categories' => array_key_exists('categories', $item) ? $item['categories'] : null
            ]);
        }
    }

    public function getSpecificBooks($id)
    {
        $book =  Books::with(['users' => function ($query) {
            $query->where('user_id', Auth::user()->id);
        }])->find($id);
        return [
            'id' => $book->id,
            'title' => $book->title,
            'url' => $book->url,
            'short_description' => $book->short_description,
            'long_description' => $book->long_description,
            'authors' => $book->authors,
            'categories' => $book->categories,
            'published_date' => $book->published_date,
            'pageCount' => $book->pageCount,
            'is_favorite' => $book->users->isNotEmpty() ? true : false
        ];
    }

    public function addBook(Request $request)
    {
        $item = $request->all();
        $book = Books::create([
            'title' => array_key_exists('title', $item) ? $item['title'] : null,
            'pageCount' => array_key_exists('pageCount', $item) ? $item['pageCount'] : null,
            'published_date' => array_key_exists('publishedDate', $item) ? Carbon::parse($item['publishedDate']['$date']) : null,
            'url' => array_key_exists('thumbnailUrl', $item) ? $item['thumbnailUrl'] : null,
            'short_description' => array_key_exists('shortDescription', $item) ? $item['shortDescription'] : null,
            'long_description' => array_key_exists('longDescription', $item) ? $item['longDescription'] : null,
            'authors' => array_key_exists('authors', $item) ? $item['authors'] : null,
            'categories' => array_key_exists('categories', $item) ? $item['categories'] : null
        ]);
        return $book;
    }

    public function makeFavouriteBook(Request $request)
    {
        if ($request->has('book_id')) {
            $user = Auth::user();
            $exists = DB::table('books_users')
                    ->whereUserId($user->id)
                    ->whereBookId($request->input('book_id'))
                    ->count() > 0;
            if ($exists) {
                $user->books()->detach($request->input('book_id'));
            } else {
                $user->books()->attach($request->input('book_id'));
            }
        }
        return \Response::json(["message" =>'Success'], 201);
    }

    public function getFavorites()
    {
        return Books::whereHas('users', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->get();
    }

    public function reserveBook(Request $request)
    {
        if (BookReservations::where('book_id',$request->book_id)->whereDate('reserved_from','<=' ,$request->reserved_to)->whereDate('reserved_to','>=' , $request->reserved_from)->exists()) {
            return \Response::json(["message" =>'Book already reserved'], 403);
        } else {
            BookReservations::create([
                'user_id' => Auth::user()->id,
                'book_id' => $request->book_id,
                'reserved_from' => Carbon::parse($request->reserved_from),
                'reserved_to' => Carbon::parse($request->reserved_to),
            ]);
            return \Response::json(["message" =>'Book succesfully reserved'], 201);
        }
    }
    public function getMyReservation(Request $request)
    {
        $user = DB::table('book_reservations')->where('user_id', Auth::user()->id)->get('book_id');
        return $user;
    }

    public function getInternetCategory(){
        $books = Books::where('categories','["Internet"]')->get();
        return $books;
    }
    public function getWebDevelopmentCategory(){
        $books = Books::where('categories','["Web Development"]')->get();
        return $books;
    }
    public function getMicrosoftCategory(){
        $books = Books::where('categories','["Microsoft"]')->get();
        return $books;
    }
    public function getJavaCategory(){
        $books = Books::where('categories','["Java"]')->get();
        return $books;
    }

}
