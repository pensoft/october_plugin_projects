<?php namespace Pensoft\Projects\Components;

use Cms\Classes\ComponentBase;
use Pensoft\Projects\Models\Project;
use RainLab\Translate\Classes\Translator;

/**
 * ProjectsList Component
 */
class ProjectsList extends ComponentBase
{
    public $records;
    public $translator;

    public function onRun(): void
    {
        $this->addJs('/plugins/pensoft/projects/assets/filter.js');
        $this->addJs('/plugins/pensoft/projects/assets/yearpicker.js');
        $this->addCss('/plugins/pensoft/projects/assets/yearpicker.css');
        $this->records = $this->searchRecords();
        $this->translator = Translator::instance();
        $this->page['records'] = $this->records;
        $this->page['lang'] = $this->translator->getLocale();
    }

    public function componentDetails(): array
    {
        return [
            'name' => 'Projects List',
            'description' => 'Search filter sort and display Project List records'
        ];
    }

    public function onSearchRecords(): array
    {
        $searchTerms = post('searchTerms');
        $sortField = post('sortField', 'title');
        $sortDirection = post('sortDirection', 'asc');
        $startDate = post('startDate');
        $endDate = post('endDate');
        $this->translator = Translator::instance();
        $this->page['records'] = $this->searchRecords($searchTerms, $sortField, $sortDirection, $startDate, $endDate);
        $this->page['lang'] = $this->translator->getLocale();
        return ['#recordsContainer' => $this->renderPartial('@records')];
    }

    protected function searchRecords(
        $searchTerms = '',
        $sortField = 'title',
        $sortDirection = 'asc',
        $startDate = '',
        $endDate = ''
    ) {
        $searchTerms = is_string($searchTerms) ? json_decode($searchTerms, true) : (array)$searchTerms;

        return Project::searchTerms($searchTerms)
            ->dateRange($startDate, $endDate)
            ->published()
            ->ordered($sortField, $sortDirection)
            ->get();
    }


    public function onGetKeywords()
    {
        $uniqueKeywords = Project::getKeywordsHighlights();
        return response()->json($uniqueKeywords);
    }

    public function defineProperties(): array
    {
        return [];
    }
}
