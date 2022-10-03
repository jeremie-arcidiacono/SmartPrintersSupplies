<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\PrinterModel;
use App\Models\Supply;
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
     *   - sortOrder: sort column of models
     *     - dir: sort direction (asc or desc)
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

        // Get the sorting option from the GET parameter
        $sortColumn = $request->query('sort') ?: config('modelQuery.printerModel_sortColumn');
        $sortDir = $request->query('dir') ?: config('modelQuery.printerModel_sortOrder');

        if($request->query('search')){
            // Send models where the name contains the search term
            $searchTerm = $request->query('search');

            $printerModels = PrinterModel::where('name', 'like', '%' . $searchTerm . '%')->orderBy($sortColumn, $sortDir)->paginate($nbPerPage);
        }
        else {
            $printerModels = PrinterModel::orderBy($sortColumn, $sortDir)->paginate($nbPerPage);
        }
        
        return new JsonResponse($printerModels, 200);
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
            'brand' => ['required', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()->messages()], 422);
        }
        else {
            $validated = $validator->validated();

            $printerModel = new PrinterModel;

            $printerModel->name = $validated['name'];
            $printerModel->brand = $validated['brand'];

            $printerModel->save();
            
            EventController::store(Auth::id(), 'create', ['idModel' => $printerModel->idPrinterModel]);
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
            'brand' => ['required', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()->messages()], 422);
        }
        else {
            $validated = $validator->validated();

            $printerModel->name = $validated['name'];
            $printerModel->brand = $validated['brand'];
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
                EventController::store(Auth::id(), 'delete', ['idModel' => $printerModel->idPrinterModel]);
                return new JsonResponse([], 200);
            }
        }  
    }

    /**
     * Returns the list of supplies used by the printer model.
     * @param  PrinterModel $printerModel
     * @return JsonResponse
     */
    public function indexCompatibility(PrinterModel $printerModel): JsonResponse
    {
        return new JsonResponse(['data' => $printerModel->supplies], 200);
    }

    /**
     * Adds a supply to the printer model.
     * @param  Request $request
     * @param  PrinterModel $printerModel
     * @return JsonResponse
     */
    public function storeCompatibility(Request $request, PrinterModel $printerModel): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'idSupply' => ['required', 'numeric', 'exists:supplies,idSupply'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()->messages()], 422);
        }
        else {
            $validated = $validator->validated();

            $printerModel->supplies()->attach($validated['idSupply']);
            return new JsonResponse([], 200);
        }
    }

    /**
     * Removes a supply from the printer model.
     * @param  PrinterModel $printerModel
     * @param  Supply $supply
     * @return JsonResponse
     */
    public function destroyCompatibility(PrinterModel $printerModel, Supply $supply): JsonResponse
    {
        $printerModel->supplies()->detach($supply->idSupply);
        return new JsonResponse([], 200);
    }
}
