<?php namespace Pensoft\Projects\Models;

use Model;
use October\Rain\Database\Traits\Sortable;
use Validator;
use RainLab\Translate\Classes\Translator;
use \October\Rain\Database\Traits\Sluggable;
use Illuminate\Support\Carbon;
/**
 * Project Model
 */
class Project extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use Sortable;
    use Sluggable;

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
        'title',
        'intro',
        'partners',
        'contact',
        'content',
        'publications',
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    /**
     * @var array jsonable attribute names that are json encoded and decoded from the database
     */
    protected $jsonable = ['keywords_en'];

    /**
     * @var array appends attributes to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array hidden attributes removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    public function setKeywordsBgAttribute($value)
    {
        $this->attributes['keywords_bg'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function getKeywordsBgAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = ['slug' => 'title'];

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
        'cover' => \System\Models\File::class,
        'illustration' => \System\Models\File::class,
    ];
    public $attachMany = [];


    /**
     * Add translation support to this model, if available.
     */
    public static function boot(): void
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

    public static function getKeywordsHighlights()
    {
        $translator = Translator::instance();
        $locale = $translator->getLocale();

        $keywordsField = $locale === 'bg' ? 'keywords_bg' : 'keywords_en';

        $allKeywords = Project::all()->pluck($keywordsField)->flatten()->filter(function ($value) {
            return !is_null($value) && $value !== '';
        })->unique()->values()->all();

        return array_map(function ($keyword) {
            return array_map(function ($item) {
                return ['value' => $item, 'text' => $item];
            }, explode(',', $keyword));
        }, $allKeywords);
    }

    public static function getUniqueKeywordsOptionsEN()
    {
        $allKeywords = [];
        $projects = self::all(['keywords_en']);

        foreach ($projects as $project) {
            if (is_string($project->keywords_en)) {
                $keywords = explode(',', $project->keywords_en);
            } else {
                $keywords = $project->keywords_en;
            }

            $allKeywords = array_merge($allKeywords, $keywords);
        }

        $uniqueKeywords = array_unique($allKeywords);
        return array_combine($uniqueKeywords, $uniqueKeywords);
    }

    public static function getUniqueKeywordsOptionsBG()
    {
        $allKeywords = [];
        $projects = self::all(['keywords_bg']);

        foreach ($projects as $project) {
            if (is_string($project->keywords_bg)) {
                $keywords = explode(',', $project->keywords_bg);
            } else {
                $keywords = $project->keywords_bg;
            }

            $keywords = is_array($keywords) ? $keywords : [];

            $allKeywords = array_merge($allKeywords, $keywords);
        }

        $uniqueKeywords = array_unique($allKeywords);
        return array_combine($uniqueKeywords, $uniqueKeywords);
    }

    public function scopeSearchTerms($query, $searchTerms)
    {
        if (!empty($searchTerms) && is_array($searchTerms)) {
            $translator = Translator::instance();
            $locale = $translator->getLocale();
            $keywordsField = $locale === 'bg' ? 'keywords_bg' : 'keywords_en';

            foreach ($searchTerms as $term) {
                $query->orWhere($keywordsField, 'LIKE', "%{$term}%");
            }
        }

        return $query;
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        if (!empty($startDate)) {
            $startOfYear = Carbon::createFromFormat('Y', $startDate)->startOfYear();
            $query->where('start', '>=', $startOfYear);
        }

        if (!empty($endDate)) {
            $endOfYear = Carbon::createFromFormat('Y', $endDate)->endOfYear();
            $query->where('end', '<=', $endOfYear);
        }

        return $query;
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeOrdered($query, $sortField, $sortDirection)
    {
        return $query->orderBy($sortField, $sortDirection);
    }

}
