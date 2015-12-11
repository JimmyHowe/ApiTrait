<?php namespace JimmyHowe\Utilities\ApiTrait;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

trait ApiTrait
{
    /**
     * Status Code
     * @var int
     */
    protected $statusCode = Response::HTTP_OK;

    /**
     * Validation Errors
     * @var
     */
    protected $validationErrors;

    /**
     * Get the Status Code
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
    protected function setStatusCode( $statusCode )
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
    protected function setValidationErrors( $validationErrors )
    {
        $this->validationErrors = $validationErrors;
    }

    /**
     * Respond
     *
     * @param       $data
     * @param array $headers
     *
     * @return JsonResponse
     */
    protected function respond( $data, $headers = [ ] )
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * Respond With Success
     *
     * @param string $message
     * @param array  $data
     *
     * @return JsonResponse
     */
    protected function respondWithSuccess( $message = "Success!", $data = [ ] )
    {
        return $this->respond([
            'status'  => 'success',
            'success' => [
                'message' => $message,
                'code'    => $this->getStatusCode()
            ],
            'data' => $data
        ]);
    }

    /**
     * Respond With Error
     *
     * @param string $message
     * @param array  $data
     *
     * @return JsonResponse
     */
    protected function respondWithError( $message = "Error!", $data = [ ] )
    {
        return $this->respond([
            'status' => 'error',
            'error'  => [
                'message' => $message,
                'code'    => $this->getStatusCode()
            ],
            'data' => $data
        ]);
    }

    /**
     * Respond With Error Bag
     *
     * @param $message
     *
     * @return JsonResponse
     */
    protected function respondWithErrorBag( $message = "Validation Error." )
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
     * Respond OK [200]
     *
     * @param string $message
     * @param array  $data
     *
     * @return JsonResponse
     */
    protected function respondOK( $message = 'OK', $data = [ ] )
    {
        return $this->respondWithSuccess($message, $data);
    }

    /**
     * Respond Created [201]
     *
     * @param string $message
     * @param array  $data
     *
     * @return JsonResponse
     */
    protected function respondCreated( $message = 'Created.', $data = [ ] )
    {
        return $this->setStatusCode(Response::HTTP_CREATED)->respondWithSuccess($message, $data);
    }

    /**
     * Respond Accepted [202]
     *
     * @param string $message
     * @param array  $data
     *
     * @return JsonResponse
     */
    protected function respondAccepted( $message = "Accepted.", $data = [ ] )
    {
        return $this->setStatusCode(Response::HTTP_ACCEPTED)->respondWithSuccess($message, $data);
    }

    /**
     * Respond with No Content Error [202]
     *
     * @param string $message
     * @param array  $data
     *
     * @return JsonResponse
     */
    protected function respondNoContent( $message = 'No Content.', $data = [ ] )
    {
        return $this->setStatusCode(Response::HTTP_NO_CONTENT)->respondWithError($message, $data);
    }

    /**
     * Respond with Found [302]
     *
     * @param string $message
     * @param array  $data
     *
     * @return JsonResponse
     */
    protected function respondFound( $message = "Found.", $data = [ ] )
    {
        return $this->setStatusCode(Response::HTTP_FOUND)->respondWithSuccess($message, $data);
    }

    /**
     * Respond Unauthorized [401]
     *
     * @param string $message
     * @param array  $data
     *
     * @return mixed
     */
    protected function respondUnauthorized( $message = "Unauthorized.", $data = [ ] )
    {
        return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)->respondWithError($message, $data);
    }

    /**
     * Respond with Not Found [404]
     *
     * @param string $message
     * @param array  $data
     *
     * @return JsonResponse
     */
    protected function respondNotFound( $message = 'Resource Not Found.', $data = [ ] )
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)->respondWithError($message, $data);
    }

    /**
     * Respond with Unprocessable Entity [422]
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function respondUnprocessableEntity( $message = 'Unprocessable Entity!' )
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message);
    }

    /**
     * Respond with Internal Server Error
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function respondInternalError( $message = 'Internal Error.' )
    {
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    /**
     * Respond with Validation Fail
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function respondValidationFailed( $message = 'Validation Failed!' )
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithErrorBag($message);
    }

    /**
     * Respond Updated
     *
     * @param string $message
     * @param array  $data
     *
     * @return JsonResponse
     */
    protected function respondUpdated( $message = 'Updated.', $data = [ ] )
    {
        return $this->respondWithSuccess($message, $data);
    }

    /**
     * Respond Deleted
     *
     * @param string $message
     * @param array  $data
     *
     * @return JsonResponse
     */
    protected function respondDeleted( $message = 'Deleted.', $data = [ ] )
    {
        return $this->respondNoContent($message, $data);
    }

    /**
     * Validate Request Against Rules
     *
     * @param Request $request
     * @param array   $rules
     *
     * @return Validator
     */
    protected function validateAgainstRules( Request $request, array $rules )
    {
        $validator = Validator::make($request->all(), $rules);

        $this->setValidationErrors($validator->errors());

        return $validator;
    }

    /**
     * Validate Request Against Reduced Rules
     *
     * @param Request $request
     * @param array   $rules
     *
     * @return mixed
     */
    protected function validateAgainstReducedRules( Request $request, array $rules )
    {
        $reducedRules = array_only($rules, array_keys($request->all()));

        return $this->validateAgainstRules($request, $reducedRules);
    }

    /**
     * Returns the Pagination Data
     *
     * @param $paginatedItems
     *
     * @return array
     */
    protected function getPaginationData( LengthAwarePaginator $paginatedItems )
    {
        $paginationInfo = [
            'items'   => $paginatedItems->total(),
            'pages'   => ceil($paginatedItems->total() / $paginatedItems->perPage()),
            'each'    => $paginatedItems->perPage(),
            'current' => $paginatedItems->currentPage(),
        ];

        return $paginationInfo;
    }

}