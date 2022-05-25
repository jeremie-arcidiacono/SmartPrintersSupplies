<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\PrinterModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PrinterModelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.api');
    }

    /**
     * Returns a list of models
     * Client can set optional GET parameters:
     *   - perPage: number of models per page
     *   - dir: sort direction (asc or desc)
     *   - search: search string
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Get the number of models per page from the GET parameter or from the app config
        if ($request->query('perPage') && is_numeric($request->query('perPage')))
            $nbPerPage = $request->query('perPage');
        else
            $nbPerPage = config('modelQuery.printerModel_perPage');

        // Get the sorting options from the GET parameter
        if ($request->query('dir') && $request->query('dir') !== "") {
            $sortDir = $request->query('dir');
        }
        else {
            $sortDir = config('modelQuery.printerModel_sortOrder');
        }

        if($request->query('search')){
            // Send models where the name contains the search term
            $searchTerm = $request->query('search');

                // No sort parameter: the models are sent without being ordered
            $printerModel = PrinterModel::where('name', 'like', '%' . $searchTerm . '%')->paginate($nbPerPage);
        }
        else {
            // No search parameter: all models are sent
            if (isset($sortDir)) {
                $printerModel = PrinterModel::orderBy('name', $sortDir)->paginate($nbPerPage);
            }
            else {
                $printerModel = PrinterModel::paginate($nbPerPage);
            }
        }
        
        return new JsonResponse($printerModel, 200);
    }

    /**
     * Returns a single model
     * 
     * @param  PrinterModel $printerModel
     * @return JsonResponse
     */
    public function show(PrinterModel $printerModel): JsonResponse
    {
        return new JsonResponse(['data' => $printerModel], 200);
    }

    /**
     * Stores a model in the database
     * @param  Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:60', 'unique:printerModels'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()->messages()], 422);
        }
        else {
            $validated = $validator->validated();

            $printerModel = new PrinterModel;

            $printerModel->name = $validated['name'];

            $printerModel->save();
            return new JsonResponse([], 200);
        }
    }

    /**
     * Updates a model in the database
     * @param  Request $request
     * @param  PrinterModel $printerModel
     * @return JsonResponse
     */
    public function update(Request $request, PrinterModel $printerModel)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:60', Rule::unique('printerModels')->ignore($printerModel)],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()->messages()], 422);
        }
        else {
            $validated = $validator->validated();

            $printerModel->name = $validated['name'];
            $printerModel->save();
            return new JsonResponse([], 200);
        }
    }

    /**
     * Soft deletes a model from the database (only if there is no other printer use it)
     * @param  PrinterModel $printerModel
     * @return JsonResponse
     */
    public function destroy(PrinterModel $printerModel)
    {
        if ($printerModel->trashed()) {
            return new JsonResponse(['errors' => 'This model of printer has already been deleted'], 422);
        }
        else {
            if ($printerModel->printers->count() > 0) {
                return new JsonResponse(['errors' => 'This model of printer has ' . $printerModel->printers->count() . ' active printers ! Delete the printers before'], 422);
            }
            else {
                $printerModel->delete();
                return new JsonResponse([], 200);
            }
        }  
    }
}
