<?php namespace Pensoft\Projects\Models;

use Model;
use October\Rain\Database\Traits\Sortable;
use Validator;

/**
 * Project Model
 */
class Project extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use Sortable;

    /**
     * @var string table associated with the model
     */
    public $table = 'pensoft_projects_projects';

    /**
     * @var array guarded attributes aren't mass assignable
     */
    protected $guarded = ['*'];

    /**
     * @var array fillable attributes are mass assignable
     */
    protected $fillable = [];

    /**
     * @var array rules for validation
     */
    public $rules = [];

    /**
     * @var array Translatable fields
     */
    public $translatable = [
        'content',
        'title'
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array jsonable attribute names that are json encoded and decoded from the database
     */
    protected $jsonable = [];

    /**
     * @var array appends attributes to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array hidden attributes removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array dates attributes that should be mutated to dates
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var array hasOne and other relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
        'cover' => 'System\Models\File'
    ];
    public $attachMany = [];


    /**
     * Add translation support to this model, if available.
     *
     * @return void
     */
    public static function boot()
    {
        Validator::extend(
            'json',
            function ($attribute, $value, $parameters) {
                json_decode($value);

                return json_last_error() == JSON_ERROR_NONE;
            }
        );

        // Call default functionality (required)
        parent::boot();

        // Check the translate plugin is installed
        if (!class_exists('RainLab\Translate\Behaviors\TranslatableModel')) {
            return;
        }

        // Extend the constructor of the model
        self::extend(
            function ($model) {
                // Implement the translatable behavior
                $model->implement[] = 'RainLab.Translate.Behaviors.TranslatableModel';
            }
        );
    }
}