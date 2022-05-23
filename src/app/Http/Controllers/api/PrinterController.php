<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Printer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PrinterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.api');
    }


    /**
     * Returns a list of printers
     * Client can set optional GET parameters:
     *   - perPage: number of printers per page
     *   - sortrder: sort column of printers
     *     - dir: sort direction (asc or desc)
     *   - search: search string
     *     - searchColumn: column to search in
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Get the number of printers per page from the GET parameter or from the app config
        if ($request->query('perPage') && is_numeric($request->query('perPage')))
            $nbPerPage = $request->query('perPage');
        else
            $nbPerPage = config('printers.per_page');

        // Get the sorting options from the GET parameter
        if ($request->query('sort')) {
            $sortColumn = $request->query('sort');
            if ($request->query('dir') && $request->query('dir') !== "") {
                $sortDir = $request->query('dir');
            }
            else {
                $sortDir = config('printers.sort_order');
            }
        }

        if($request->query('search')){
            // Send printers where a column contains the search term
            $searchTerm = $request->query('search');
            $searchColumn = $request->query('searchColumn');

            if (isset($sortColumn)) {
                $printers = Printer::where($searchColumn, 'like', '%' . $searchTerm . '%')->orderBy($sortColumn, $sortDir)->paginate($nbPerPage);
            }
            else {
                // No sort parameter: the printers are sent without being ordered
                $printers = Printer::where($searchColumn, 'like', '%' . $searchTerm . '%')->paginate($nbPerPage);
            }
        }
        else {
            // No search parameter: all printers are sent
            if (isset($sortColumn)) {
                $printers = Printer::orderBy($sortColumn, $sortDir)->paginate($nbPerPage);
            }
            else {
                $printers = Printer::paginate($nbPerPage);
            }
        }

        return new JsonResponse($printers, 200);
    }

    /**
     * Returns a single printer
     * 
     * @param  Printer $printer
     * @return JsonResponse
     */
    public function show(Printer $printer): JsonResponse
    {
        return new JsonResponse(['data' => $printer], 200);
    }

    /**
     * Stores a printer in the database
     * @param  Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'model' => ['required', 'string', 'max:60'],
            'serialNumber' => ['required', 'string', 'max:100', 'unique:printers'],
            'cti' => ['required', 'integer', 'digits:6', 'unique:printers']
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()->messages()], 422);
        }
        else {
            $validated = $validator->validated();

            $printer = new Printer;

            $printer->model = $validated['model'];
            $printer->serialNumber = $validated['serialNumber'];
            $printer->cti = $validated['cti'];

            $printer->save();
            return new JsonResponse([], 200);
        }
    }

    /**
     * Updates a printer in the database
     * @param  Request $request
     * @param  Printer $printer
     * @return JsonResponse
     */
    public function update(Request $request, Printer $printer)
    {
        $validator = Validator::make($request->all(), [
            'model' => ['string', 'max:60'],
            'serialNumber' => ['string', 'max:100', Rule::unique('printers')->ignore($printer)],
            'cti' => ['integer', 'digits:6', Rule::unique('printers')->ignore($printer)]
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()->messages()], 422);
        }
        else {
            $validated = $validator->validated();

            if ($request->has('model')) {
                $printer->model = $validated['model'];
            }
            if ($request->has('serialNumber')) {
                $printer->serialNumber = $validated['serialNumber'];
            }
            if ($request->has('cti')) {
                $printer->cti = $validated['cti'];
            }

            $printer->save();
            return new JsonResponse([], 200);
        }
    }

    /**
     * Soft deletes a printer from the database
     * @param  Printer $printer
     * @return JsonResponse
     */
    public function destroy(Printer $printer)
    {
        if ($printer->trashed()) {
            return new JsonResponse(['errors' => 'This printer has already been deleted'], 422);
        }
        else {
            $printer->delete();
            return new JsonResponse([], 200);
        }  
    }
}
