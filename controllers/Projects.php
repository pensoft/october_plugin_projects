<?php namespace Pensoft\Projects\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Behaviors\ReorderController;

/**
 * Projects Backend Controller
 */
class Projects extends Controller
{
    public $implement = [
        FormController::class,
        ListController::class,
        ReorderController::class,
    ];

    /**
     * @var string formConfig file
     */
    public string $formConfig = 'config_form.yaml';

    /**
     * @var string listConfig file
     */
    public string $listConfig = 'config_list.yaml';

    /**
     * @var string reorderConfig file
     */
    public string $reorderConfig = 'config_reorder.yaml';

    /**
     * __construct the controller
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Pensoft.Projects', 'projects', 'projects');
    }
}
