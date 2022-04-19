<?php

namespace api;

require_once 'api/BaseApi.php';
require_once 'traits/CheckAuthTrait.php';

use models\Link;
use models\search\LinkSearch;
use models\User;
use traits\CheckAuthTrait;

class LinksApi extends BaseApi
{
    use CheckAuthTrait;

    /**
     * @var string
     */
    public $apiName = 'links';

    /**
     * @return string|null
     */
    public function getAction(): ?string
    {
        $method = $this->method;
        switch ($method) {
            case 'GET':
                if ($this->requestUri) {
                    return 'viewAction';
                } else {
                    return 'indexAction';
                }
            case 'POST':
                return 'createAction';
            case 'PUT':
                return 'updateAction';
            case 'DELETE':
                return 'deleteAction';
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
        $linkSearchModel = new LinkSearch($this->db, $this->requestParams);
        if ($linkSearchModel->validate() && $links = $linkSearchModel->search()) {
            $result = [];
            foreach ($links as $link) {
                $result[] = $link->getAttributes();
            }
            return $this->response($result, 200);
        }
        $errors = $linkSearchModel->getErrors();
        return $this->response(!empty($errors) ? ['errors' => $errors] : [], !empty($errors) ? 404 : 200);
    }

    /**
     * @return false|string
     */
    public function viewAction()
    {
        $id = array_shift($this->requestUri);
        $token = $this->getBearerToken();
        if ($id) {
            $user = User::findByToken($this->db, $token);
            $link = Link::findById($this->db, $id);
            if ($link) {
                $result = $user ? $link->getAttributes() : ['url' => $link->url];
                return $this->response($result, 404);
            }
        }
        return $this->response('link not found', 404);
    }

    /**
     * @return false|string
     */
    public function createAction()
    {
        $link = new Link($this->db, $this->requestParams);
        $link->hash = rand(1000000, 9999999);
        if ($link->validate() && $link->save()) {
            return $this->response(['hash' => $link->hash], 200);
        }
        return $this->response(['errors' => $link->getErrors()], 404);
    }

    /**
     * @return false|string
     */
    public function updateAction()
    {
        if (!$this->checkAuthorization()) {
            return $this->response('unauthorized', 401);
        };
        $id = array_shift($this->requestUri);
        $link = Link::findById($this->db, $id);
        if (!$link) {
            return $this->response('link not found', 404);
        }
        $link->load($this->requestParams);
        if ($link->validate() && $link->save()) {
            return $this->response('link saved', 200);
        }
        return $this->response(['errors' => $link->getErrors()], 404);
    }

    /**
     * @return false|string
     */
    public function deleteAction()
    {
        if (!$this->checkAuthorization()) {
            return $this->response('unauthorized', 401);
        };
        $id = array_shift($this->requestUri);
        $link = Link::findById($this->db, $id);
        if (!$link) {
            return $this->response('link not found', 404);
        }
        if ($link->delete($this->db)) {
            return $this->response('link deleted', 200);
        }
        return $this->response('error', 404);
    }
}