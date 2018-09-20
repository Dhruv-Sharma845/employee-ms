<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ParentApiController {
    /**
     * @var integer HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;

    public function getStatusCode() {
        return $this->statusCode;
    }

    protected function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respond($data, $headers = []) {
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
    * Returns a 422 Unprocessable Entity
    *
    * @param string $message
    *
    * @return Symfony\Component\HttpFoundation\JsonResponse
    */
    public function respondValidationError($message = 'Validation errors')
    {
        return $this->setStatusCode(422)->respondWithErrors($message);
    }

    /**
    * Returns a 404 Not Found
    *
    * @param string $message
    *
    * @return Symfony\Component\HttpFoundation\JsonResponse
    */
    public function respondNotFound($message = 'Not found!')
    {   
        return $this->setStatusCode(404)->respondWithErrors($message);
    }

    /**
    * Returns a 201 Created
    *
    * @param array $data
    *
    * @return Symfony\Component\HttpFoundation\JsonResponse
    */
    public function respondCreated($data = [])
    {
        return $this->setStatusCode(201)->respond($data);
    }

    public function respondUpdated($data = [])
    {
        return $this->setStatusCode(201)->respond($data);
    }

    // this method allows us to accept JSON payloads in POST requests 
    // since Symfony 4 doesn't handle that automatically:

    protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request)    
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}
?>