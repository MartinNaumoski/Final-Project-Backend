<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookReservations extends Model
{
    public $incrementing = true;
    public $timestamps = true;
    protected $table = 'book_reservations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reserved_from',
        'reserved_to',
        'user_id',
        'book_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'reserved_to',
        'reserved_from',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'books_users', 'book_id', 'user_id')->withTimestamps();
    }

    public function reserveUser()
    {
        return $this->hasOne('App\User', 'user_id');
    }

    public function reserveBook()
    {
        return $this->hasOne('App\Books', 'book_id');
    }
}
