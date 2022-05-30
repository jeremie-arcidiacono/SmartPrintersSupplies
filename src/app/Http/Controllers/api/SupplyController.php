<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Supply;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SupplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.api');
    }

    /**
     * Returns a list of supplies
     * Client can set optional GET parameters:
     *   - perPage: number of supplies per page
     *   - sort: sort column of supplies
     *     - dir: sort direction (asc or desc)
     *   - search: search string
     *   - quantityMin: minimum quantity of supplies
     *   - quantityMax: maximum quantity of supplies
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Get the number of supplies per page from the GET parameter or from the app config
        if ($request->query('perPage') && is_numeric($request->query('perPage')))
            $nbPerPage = $request->query('perPage');
        else
            $nbPerPage = config('modelQuery.supply_perPage');

        // Get the sorting options from the GET parameter
        $sortColumn = $request->query('sort') ?: config('modelQuery.supply_sortColumn');
        $sortDir = $request->query('dir') ?: config('modelQuery.supply_sortOrder');

        $quantityMin = null;
        $quantityMax = null;
        if ($request->query('quantityMin') && is_numeric($request->query('quantityMin'))) {
            $quantityMin = $request->query('quantityMin');
        }
        if ($request->query('quantityMax') && is_numeric($request->query('quantityMax'))) {
            $quantityMax = $request->query('quantityMax');
        }

        if($request->query('search')){
            // Send supplies where the code contains the search term
            $searchTerm = $request->query('search');

            $supplies = Supply::where('code', 'like', '%' . $searchTerm . '%')->minqty($quantityMin)->maxqty($quantityMax)->orderBy($sortColumn, $sortDir)->paginate($nbPerPage);
        }
        else {
            $supplies = Supply::orderBy($sortColumn, $sortDir)->minqty($quantityMin)->maxqty($quantityMax)->paginate($nbPerPage);
        }
        
        return new JsonResponse($supplies, 200);
    }

    /**
     * Returns a single supply
     * 
     * @param  Supply $supply
     * @return JsonResponse
     */
    public function show(Supply $supply): JsonResponse
    {
        return new JsonResponse(['data' => $supply], 200);
    }

    /**
     * Stores a supply in the database
     * @param  Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required', 'string', 'max:20', 'unique:supplies'],
            'quantity' => ['required', 'integer', 'min:0']
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()->messages()], 422);
        }
        else {
            $validated = $validator->validated();

            $supply = new Supply;

            $supply->code = $validated['code'];
            $supply->quantity = $validated['quantity'];

            $supply->save();

            EventController::store(Auth::id(), 'create', ['idSupply' => $supply->idSupply]);
            return new JsonResponse([], 200);
        }
    }

    /**
     * Updates a supplies in the database
     * @param  Request $request
     * @param  Supply $supply
     * @return JsonResponse
     */
    public function update(Request $request, Supply $supply)
    {
        $validator = Validator::make($request->all(), [
            'code' => ['string', 'max:20', Rule::unique('supplies')->ignore($supply)],
            'quantity' => ['integer', 'min:0'],
            'addQuantity' => ['integer', 'min:0'],
            'removeQuantity' => ['integer', 'min:0'],
            'idPrinter' => ['integer', 'exists:App\Models\Printer,idPrinter']
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()->messages()], 422);
        }
        else {
            $validated = $validator->validated();

            if ($request->has('code')) {
                $supply->code = $validated['code'];
            }

            if ($request->has('quantity')) {
                EventController::store(Auth::id(), 'changeAmount', ['idSupply' => $supply->idSupply], $validated['quantity'] - $supply->quantity);
                $supply->quantity = $validated['quantity'];
            } 
            else if ($request->has('addQuantity')) {
                $supply->quantity += $validated['addQuantity'];
                EventController::store(Auth::id(), 'changeAmount', ['idSupply' => $supply->idSupply, 'idPrinter' => $validated['idPrinter']], $validated['addQuantity']);
            } 
            else if ($request->has('removeQuantity')) {
                $supply->quantity -= $validated['removeQuantity'];
                EventController::store(Auth::id(), 'changeAmount', ['idSupply' => $supply->idSupply, 'idPrinter' => $validated['idPrinter']], -$validated['removeQuantity']);
            }
            
            $supply->save();
            return new JsonResponse([], 200);
        }
    }

    /**
     * Soft deletes a supply from the database
     * @param  Supply $supply
     * @return JsonResponse
     */
    public function destroy(Supply $supply)
    {
        if ($supply->trashed()) {
            return new JsonResponse(['errors' => 'This supplies has already been deleted'], 422);
        }
        else {
            $supply->delete();
            EventController::store(Auth::id(), 'delete', ['idSupply' => $supply->idSupply]);
            return new JsonResponse([], 200);
        }
    }

    /**
     * Returns the list of printer models that use this supply
     * @param  Supply $supply
     * @return JsonResponse
     */
    public function indexCompatibility(Supply $supply): JsonResponse
    {
        return new JsonResponse(['data' => $supply->models], 200);
    }
}
