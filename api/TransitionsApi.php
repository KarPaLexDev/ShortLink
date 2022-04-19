<?php

namespace api;

require_once 'api/BaseApi.php';
require_once 'traits/CheckAuthTrait.php';
require_once 'models/search/TransitionSearch.php';

use models\search\TransitionSearch;
use traits\CheckAuthTrait;

class TransitionsApi extends BaseApi
{
    use CheckAuthTrait;

    /**
     * @var string
     */
    public $apiName = 'transitions';

    /**
     * @return string|null
     */
    public function getAction(): ?string
    {
        $method = $this->method;
        switch ($method) {
            case 'GET':
                if ($this->requestUri) {
                    return null;
                } else {
                    return 'indexAction';
                }
            default:
                return null;
        }
    }

    /**
     * @return false|string
     */
    public function indexAction()
    {
        if (!$this->checkAuthorization()) {
            return $this->response('unauthorized', 401);
        };
        $transitionSearchModel = new TransitionSearch($this->db, $this->requestParams);
        if ($transitionSearchModel->validate() && $transitions = $transitionSearchModel->search()) {
            $result = [];
            foreach ($transitions as $transition) {
                $result[] = $transition->getAttributes();
            }
            return $this->response($result, 200);
        }
        $errors = $transitionSearchModel->getErrors();
        return $this->response(!empty($errors) ? ['errors' => $errors] : [], !empty($errors) ? 404 : 200);
    }
}