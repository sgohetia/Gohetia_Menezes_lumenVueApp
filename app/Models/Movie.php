<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'movies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'movie_image',
        'rating',
        'release_date',
        'director_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get the director that directed the movie.
     */
    public function director()
    {
        return $this->belongsTo(Director::class);
    }

    /**
     * The cast members that belong to the movie.
     */
    public function cast()
    {
        return $this->belongsToMany(CastMember::class, 'movie_cast', 'movie_id', 'cast_member_id');
    }
}
