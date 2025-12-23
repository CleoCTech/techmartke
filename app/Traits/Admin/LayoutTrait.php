<?php

namespace App\Traits\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

trait LayoutTrait
{
    public $listData = [], $cardData, $viewData = [], $prevRecord = [], $searchParams = [], $return, $actionRoutes = [], $defaultModel, $setup, $isSearch, $isReltionship, $relationName, $paginateRelation;
    public $pkeyCol, $pKey = null, $isCommit = true, $pageTitle;
    public $defReportPage = 'Admin/Reports/Filters/Default';
    public $extraConditions = [];
    public function def_constructor()
    {
        $this->defaultModel = $this->settings['model'];
        $this->setup['columns'] = $this->defaultModel::getColumns();
        $this->setup['settings'] = $this->settings;
        $instance = new $this->defaultModel;
        $this->pKeyCol = $instance->getKeyName();
    }

    public function def_index()
    {
        $this->setup['pageType'] = 'list';
        $this->setup['pageTitle'] = $this->settings['caption'] . ' List';
        $this->searchParams();

        $query = $this->defaultModel::query();
        // ✅ Apply additional WHERE conditions set from the Controller
        if (!empty($this->extraConditions)) {
            foreach ($this->extraConditions as $condition) {
                $query->where($condition['column'], $condition['operator'], $condition['value']);
            }
        }

        if (!$this->isSearch) {
            if ($this->isReltionship && !empty($this->relationName)) {
                // ✅ Handle multiple relationships
                $relations = array_map('trim', explode(',', $this->relationName));
                $query->with($relations);
            }

            $query->orderBy(
                $this->settings['orderBy']['column'],
                $this->settings['orderBy']['order']
            );

            // $this->listData = $query->paginate(config('app.maxRecsPerPage'));
            $this->listData = $query->paginate(10);
        } else {
            // Load relationships even when searching
            if ($this->isReltionship && !empty($this->relationName)) {
                $relations = array_map('trim', explode(',', $this->relationName));
                $query->with($relations);
            }
            
            foreach ($this->params as $param) {
                if ($param['column'] !== 'all') {
                    $query->where($param['column'], 'LIKE', '%' . $param['value'] . '%');
                }
            }
            $this->listData = $query->paginate(config('app.maxRecsPerPage'));
        }

        $this->viewData = [
            'listData' => $this->listData,
            'setup'    => $this->setup,
        ];
    }

    public function def_create()
    {
        $this->setup['pageType'] = 'create';
        $this->setup['pageTitle'] = 'Create ' . $this->settings['caption'];
        $this->viewData = [
            'cardData' => null,
            'setup'    => $this->setup,
        ];
    }

    public function def_show($uuid)
    {
        $this->setup['pageType'] = 'view';
        $this->setup['pageTitle'] = $this->settings['caption'] . ' Details';

        $query = $this->defaultModel::query();

        if ($this->isReltionship && !empty($this->relationName)) {
            $relations = array_map('trim', explode(',', $this->relationName));
            $query->with($relations);
        }

        $cardData = $query->where('uuid', $uuid)->firstOrFail();

        $this->viewData = [
            'cardData' => $cardData,
            'setup'    => $this->setup,
            'selected' => $cardData ? [$cardData->uuid . '#0'] : [],
        ];
    }

    public function def_edit($uuid)
    {
        $this->setup['pageType'] = 'edit';
        $this->setup['pageTitle'] = 'Edit ' . $this->settings['caption'];

        $query = $this->defaultModel::query();

        if ($this->isReltionship && !empty($this->relationName)) {
            $relations = array_map('trim', explode(',', $this->relationName));
            $query->with($relations);
        }

        $cardData = $query->where('uuid', $uuid)->firstOrFail();

        $this->viewData = [
            'cardData' => $cardData,
            'setup'    => $this->setup,
            'selected' => $cardData ? [$cardData->uuid . '#0'] : [],
        ];
    }

    public function searchParams()
    {
        $currentUrl = url()->full();
        $this->isSearch = false;

        if (strpos($currentUrl, 'search=') !== false) {
            $this->isSearch = true;
            $queryString = parse_url($currentUrl, PHP_URL_QUERY);
            parse_str($queryString, $queryParams);

            $this->params = [];

            if (isset($queryParams['search'])) {
                $searchValues = explode('%20', $queryParams['search']);
                foreach ($searchValues as $pair) {
                    [$column, $value] = explode('%3D', $pair);
                    $this->params[] = ['column' => $column, 'value' => $value];
                }
            }
        }
    }
    public function defReport(){
        $this->setup['pageType'] = 'report';
        $this->setup['pageTitle'] = $this->settings['caption'].' Report';
        $operators = [
            ['operator' => '=','caption' => 'Equals'],
            ['operator' => '!=','caption' => 'Not equals'],
            ['operator' => '>','caption' => 'Greater than'],
            ['operator' => '<','caption' => 'Less than'],
            ['operator' => '>=','caption' => 'Greater than or equal to'],
            ['operator' => '<=','caption' => 'Less than or equal to'],
            ['operator' => 'startsWith','caption' => 'Starts With'],
            ['operator' => 'endsWith','caption' => 'Ends With'],
            ['operator' => 'contains','caption' => 'Contains'],
        ];
        $dataItems = [];
        $dataItems[0] = ['model' => $this->setup['settings']['model'], 'columns' => $this->setup['columns'],'caption' => $this->setup['settings']['caption'].' Dataset'];
        $this->setup['columns'] = [];
        $this->viewData = [
            'setup' => $this->setup,
            'operators' => $operators,
            'dataItems' => $dataItems,
            'filterPage' => 'default',
            'previewUrl' => '/system/report/generate-report',
            'layoutView' => 'reports.admin.services'

        ];
    }
    public function notification($type){
        $notification = null;
        if($type == 'success'){
            $notification = ['type' => 'success', 'message' => $this->pKey == null? config('app.defaultErrors')['crud']['created']:config('app.defaultErrors')['crud']['updated']];
        }
        elseif($type == 'error'){
            $notification = ['type' => 'error', 'message' => config('app.defaultErrors')['default']];
        }
        elseif($type == 'deleteSuccess'){
            $notification = ['type' => 'success', 'message' => config('app.defaultErrors')['crud']['deleted']];
        }
        elseif($type == 'generalSuccess'){
            $notification = ['type' => 'success', 'message' => 'Operation completed successfully'];
        }
        return $notification;
    }
}
