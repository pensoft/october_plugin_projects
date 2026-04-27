<?php namespace Pensoft\Projects\Components;

use Cms\Classes\ComponentBase;
use Pensoft\Projects\Models\Project;
use RainLab\Translate\Classes\Translator;

/**
 * Item Component
 */
class ProjectsItem extends ComponentBase
{
    public $record;
    public $translator;

    public function componentDetails(): array
    {
        return [
            'name' => 'Item Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties(): array
    {
        return [
            'slug' => [
                'title'       => 'Slug',
                'description' => 'The slug of the record',
                'default'     => '{{ :slug }}',
                'type'        => 'string'
            ]
        ];
    }

    public function onRun(): void
    {
        $this->translator = Translator::instance();
        $this->page['lang'] = $this->translator->getLocale();
        $this->record = $this->loadRecord();
        $this->page['record'] = $this->record;
    }

    protected function loadRecord()
    {
        return Project::where('slug', $this->property('slug'))->where('published', true)->first();
    }
}
