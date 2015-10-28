<?php namespace JimmyHowe\Utilities\ApiTrait;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

trait ApiTrait
{
    /**
     * Status Code
     *
     * @var int
     */
    protected $statusCode = Response::HTTP_OK;

    /**
     * Validation Errors
     *
     * @var
     */
    protected $validationErrors;

    /**
     * Get the Status Code
     *
     * @return int
     */
    protected function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set the Status Code
     *
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getValidationErrors()
    {
        return $this->validationErrors;
    }

    /**
     * @param mixed $validationErrors
     */
    public function setValidationErrors($validationErrors)
    {
        $this->validationErrors = $validationErrors;
    }

    /**
     * Respond with Not Found Error
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondNotFound($message = 'Resource not found.')
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)->respondWithError($message);
    }

    /**
     * Respond with No Content Error
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondNoContent($message = 'No Content.')
    {
        return $this->setStatusCode(Response::HTTP_NO_CONTENT)->respondWithError($message);
    }

    /**
     * Respond With Error
     *
     * @param $message
     *
     * @return mixed
     */
    protected function respondWithError($message)
    {
        return $this->respond([
            'status' => 'error',
            'error'  => [
                'message' => $message,
                'code'    => $this->getStatusCode()
            ]
        ]);
    }

    /**
     * Respond
     *
     * @param       $data
     * @param array $headers
     *
     * @return mixed
     */
    protected function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * Respond with Internal Server Error
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondInternalError($message = 'Internal Error.')
    {
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    /**
     * Respond with Unprocessable Entity
     *
     * @param string $message
     *
     * @return mixed
     */
    protected function respondUnprocessableEntity($message = 'Unprocessable Entity!')
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message);
    }

    /**
     * Respond with Validation Fail
     *
     * @param string $message
     *
     * @return mixed
     */
    protected function respondValidationFailed($message = 'Validation Failed!')
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithErrorBag($message);
    }

    /**
     * Respond With Error Bag
     *
     * @param $message
     *
     * @return mixed
     */
    protected function respondWithErrorBag($message)
    {
        $data = [
            'status' => 'error',
            'error'  => [
                'message' => $message,
                'code'    => $this->getStatusCode()
            ],
            'errors' => $this->getValidationErrors()
        ];

        return $this->respond($data);
    }

    /**
     * Respond Created
     *
     * @param string $message
     *
     * @return mixed
     */
    protected function respondCreated($message = 'Created Successfully.')
    {
        return $this->setStatusCode(Response::HTTP_CREATED)->respondSuccess($message);
    }

    /**
     * Respond Success
     *
     * @param $message
     *
     * @return mixed
     */
    protected function respondSuccess($message)
    {
        return $this->respond([
            'status'  => 'success',
            'success' => [
                'message' => $message,
                'code'    => $this->getStatusCode()
            ]
        ]);
    }

    /**
     * Respond Updated
     *
     * @param string $message
     *
     * @return mixed
     */
    protected function respondUpdated($message = 'Updated Successfully')
    {
        return $this->setStatusCode(Response::HTTP_OK)->respondSuccess($message);
    }

    /**
     * Respond Deleted
     *
     * @param string $message
     *
     * @return mixed
     */
    protected function respondDeleted($message = 'Delete Successful.')
    {
        return $this->setStatusCode(Response::HTTP_NO_CONTENT)->respondSuccess($message);
    }

    /**
     * Validate Request Against Reduced Rules
     *
     * @param Request $request
     * @param array   $rules
     *
     * @return mixed
     */
    protected function validateAgainstReducedRules(Request $request, array $rules)
    {
        $reducedRules = array_only($rules, array_keys($request->all()));

        return $this->validateAgainstRules($request, $reducedRules);
    }

    /**
     * Validate Request Against Rules
     *
     * @param Request $request
     * @param array   $rules
     *
     * @return mixed
     */
    protected function validateAgainstRules(Request $request, array $rules)
    {
        $validator = Validator::make($request->all(), $rules);

        $this->setValidationErrors($validator->errors());

        return $validator;
    }

    /**
     * Returns the Paginator Info
     *
     * @param $paginatedItems
     *
     * @return array
     */
    protected function getPageInfo($paginatedItems)
    {
        $pageInfo = [
            'pages'   => ceil($paginatedItems->total() / $paginatedItems->perPage()),
            'items'   => $paginatedItems->total(),
            'current' => $paginatedItems->currentPage(),
            'limit'   => $paginatedItems->perPage()
        ];

        return $pageInfo;
    }

}