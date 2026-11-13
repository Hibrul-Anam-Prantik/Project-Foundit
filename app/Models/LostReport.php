<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * R1: Lost Item Report Model.
 *
 * Represents a student's lost item report including metadata, photo path,
 * and lifecycle tracking across 'searching', 'matched', and 'recovered'.
 */
class LostReport extends Model
{
    use HasFactory;

    public const STATUS_SEARCHING = 'searching';
    public const STATUS_MATCHED = 'matched';
    public const STATUS_RECOVERED = 'recovered';

    public const STATUSES = [
        self::STATUS_SEARCHING => 'Searching',
        self::STATUS_MATCHED => 'Matched',
        self::STATUS_RECOVERED => 'Recovered',
    ];

    /**
     * Fallback categories used until R7 (Category management) is merged.
     */
    public const DEFAULT_CATEGORIES = [
        'electronics' => 'Electronics',
        'books' => 'Books & Stationery',
        'id-cards' => 'ID Cards & Documents',
        'accessories' => 'Accessories & Wallets',
        'clothes' => 'Clothing & Bags',
        'keys' => 'Keys',
        'other' => 'Other',
    ];

    /**
     * Fallback campus locations used as suggestions until R13 (Locations) is merged.
     */
    public const DEFAULT_LOCATIONS = [
        'Main Cafeteria',
        'Library (Level 3)',
        'Auditorium',
        'UB2 Building',
        'UB6 Building',
        'Lecture Theatre 1',
        'Campus Courtyard',
    ];

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'item_name',
        'category',
        'description',
        'lost_date',
        'location',
        'photo_path',
        'status',
        'category_id',
        'building_id',
        'room_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'lost_date' => 'date',
    ];

    /**
     * Student who submitted the lost item report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope reports to a specific owner.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Apply search, status, and category filters from the index query string.
     *
     * @param  array<string, mixed>  $filters
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (! empty($filters['search'])) {
            $term = trim((string) $filters['search']);
            $query->where(function (Builder $sub) use ($term) {
                $sub->where('item_name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('location', 'like', "%{$term}%");
            });
        }

        if (! empty($filters['status']) && array_key_exists($filters['status'], self::STATUSES)) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['category']) && $filters['category'] !== 'all') {
            $query->where('category', $filters['category']);
        }

        return $query;
    }

    /**
     * Resolve public URL for the uploaded photo if one exists.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo_path) {
            return null;
        }

        return Storage::disk('public')->url($this->photo_path);
    }

    /**
     * Human-readable label for the current status.
     */
    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    /**
     * CSS badge modifier class matching the report status.
     */
    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_MATCHED => 'badge-matched',
            self::STATUS_RECOVERED => 'badge-recovered',
            default => 'badge-searching',
        };
    }

    /**
     * Human-readable label for the item's category.
     */
    public function categoryLabel(): string
    {
        $categories = self::availableCategories();

        return $categories[$this->category] ?? ucfirst(str_replace('-', ' ', $this->category));
    }

    /**
     * Provide category options, seamlessly adopting R7's `categories` table if present.
     *
     * @return array<string, string>
     */
    public static function availableCategories(): array
    {
        try {
            if (Schema::hasTable('categories')) {
                $rows = \Illuminate\Support\Facades\DB::table('categories')
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->pluck('name', 'slug')
                    ->toArray();

                if (! empty($rows)) {
                    return $rows;
                }
            }
        } catch (\Throwable) {
            // Fall back safely when DB is unavailable or before R7 is migrated
        }

        return self::DEFAULT_CATEGORIES;
    }

    /**
     * Provide campus location suggestions, adopting R13's `buildings` table if present.
     *
     * @return array<int, string>
     */
    public static function availableLocations(): array
    {
        try {
            if (Schema::hasTable('buildings')) {
                $buildings = \Illuminate\Support\Facades\DB::table('buildings')
                    ->orderBy('name')
                    ->pluck('name')
                    ->toArray();

                if (! empty($buildings)) {
                    return $buildings;
                }
            }
        } catch (\Throwable) {
            // Fall back safely before R13 is migrated
        }

        return self::DEFAULT_LOCATIONS;
    }
}
