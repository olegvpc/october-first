<?php namespace Olegvpc\Contact\Models;

use Model;
use October\Rain\Database\Traits\Sortable;
use October\Rain\Database\Traits\Validation;
use System\Models\File;

/**
 * Banner Model
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $name
 * @property string $url
 * @property int|null $sort_order
 * @property int $active

 * @mixin \Eloquent
 */
class Contact extends Model
{
    // use Validation;

    // use Sortable;

    // const SORT_ORDER = 'sort_order';

    /**
     * @var string The database table used by the model.
     */
    public $table = 'olegvpc_contacts';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['*'];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    // protected $jsonable = ['text_block_margins'];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    // public $attachOne = [
    //     'image' => File::class
    // ];
    public $attachMany = [];

    // public function scopeActive($q)
    // {
    //     $q->whereActive(1);
    // }

    // public function toArray()
    // {
    //     return [
    //         'id' => $this->id,
    //         'url' => url($this->url),
    //         'image' => $this->image->getPath(),
    //         'text' => $this->image->title,
    //     ];
    // }
}
