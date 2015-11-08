# ApiTrait
Laravel Api Trait

Simple laravel Api Trait to add to controllers for basic API functionallity

    class UserController extends Controller
    {
        use ApiTrait;
    }

Respond

    $this->respond($data, $headers = [])

Helper functions

    respondNotFound($message = 'Resource Not Found.')
    respondNoContent($message = 'No Content.')
    respondWithError($message = "Error.")
    respondInternalError($message = 'Internal Error.')
    respondUnprocessableEntity($message = 'Unprocessable Entity!')
    respondValidationFailed($message = 'Validation Failed!')
    respondWithErrorBag($message = "Validation Error.")
    respondCreated($message = 'Created Successfully.')
    respondSuccess($message = "Success.")
    respondUpdated($message = 'Updated Successfully.')
    respondDeleted($message = 'Delete Successful.')

# Validation

## For Create

    class UserController extends Controller
    {
        use ApiTrait;
        
        public function create(Request $request)
        {
            $rules = (new CreateUserRequest)->rules;
            
            if($this->validateAgainstRules($request, $rules)->fails())
            {
                return $this->respondValidationFailed("Cant create user.");
            }
            
            return $this->respondCreated("User created.");
        }
    }

## For Update

The method validateAgainstReducedRules() will only validate against the input fields given

    class UserController extends Controller
        {
            use ApiTrait;
            
            public function update(Request $request)
            {
                $rules = (new CreateUserRequest)->rules;
                
                if($this->validateAgainstReducedRules($request, $rules)->fails())
                {
                    return $this->respondValidationFailed("Cant create user.");
                }
                
                return $this->respondUpdated("User updated.");
            }
        }

# Pagination

    class UserController extends Controller
    {
        use ApiTrait;
        
        public function index()
        {
            $paginatedUsers = User::paginate();
            
            return $this->respond([
                'data' => $paginatedUsers,
                'pages => $this->pageInfo($paginatedUsers)
            ]);
        }
    }