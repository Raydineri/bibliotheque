<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'isbn', 'description', 'total_copies',
        'available_copies', 'published_year', 'cover_image',
        'author_id', 'category_id'
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    // Scope : livres disponibles
    public function scopeAvailable($query)
    {
        return $query->where('available_copies', '>', 0);
    }

    // Accessor
    public function getIsAvailableAttribute(): bool
    {
        return $this->available_copies > 0;
    }
}
