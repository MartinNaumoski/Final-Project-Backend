<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Books extends Model {
    public $incrementing = true;
    public $timestamps = true;
    protected $table = 'books';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'url',
        'short_description',
        'long_description',
        'authors',
        'categories',
        'published_date',
        'pageCount',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    protected $casts = [
        'authors' => 'array',
        'categories' => 'array'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'published_date',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'books_users', 'book_id', 'user_id')->withTimestamps();
    }

    public function reservedBooks()
    {
        return $this->hasMany('App\BookReservations', 'book_id');
    }
}
