<?php

namespace App\Http\Controllers\api;

use App\Enum\EventAction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
     *   - sortOrder: sort column of printers
     *     - dir: sort direction (asc or desc)
     *   - search: search string
     *     - searchColumn: column to search in
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Get the number of printers per page from the GET parameter or from the app config
        if ($request->query('perPage') && is_numeric($request->query('perPage'))) {
            $nbPerPage = $request->query('perPage');
        }
        else {
            $nbPerPage = config('modelQuery.printer_perPage');
        }

        // Get the sorting options from the GET parameter
        $sortColumn = $request->query('sort') ?: config('modelQuery.printer_sortColumn');
        $sortDir = $request->query('dir') ?: config('modelQuery.printer_sortOrder');

        if ($request->query('search')) {
            // Send printers where a column contains the search term
            $searchTerm = $request->query('search');
            $searchColumn = $request->query('searchColumn');

            $printers = Printer::where($searchColumn, 'like', '%' . $searchTerm . '%')->orderBy(
                $sortColumn,
                $sortDir
            )->paginate($nbPerPage);
        }
        elseif ($request->query('searchModel')) {
            // Send printers where the model contains the search term
            $searchTerm = $request->query('searchModel');

            $printers = Printer::whereRelation('model', 'name', 'like', '%' . $searchTerm . '%')->orderBy(
                $sortColumn,
                $sortDir
            )->paginate($nbPerPage);
        }
        else {
            // Send all printers
            $printers = Printer::orderBy($sortColumn, $sortDir)->paginate($nbPerPage);
        }

        return new JsonResponse($printers, 200);
    }

    /**
     * Returns a single printer
     *
     * @param Printer $printer
     * @return JsonResponse
     */
    public function show(Printer $printer): JsonResponse
    {
        return new JsonResponse(['data' => $printer], 200);
    }

    /**
     * Stores a printer in the database
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'idModel' => ['required', 'numeric', 'exists:App\Models\PrinterModel,idPrinterModel'],
            'room' => ['nullable', 'string', 'max:50'],
            'serialNumber' => ['required', 'string', 'max:100', 'unique:printers'],
            'cti' => ['required', 'integer', 'digits:6', 'unique:printers']
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()->messages()], 422);
        }
        else {
            $validated = $validator->validated();

            $printer = new Printer();

            $printer->printer_model_idPrinterModel = $validated['idModel'];
            $printer->room = $validated['room'];
            $printer->serialNumber = $validated['serialNumber'];
            $printer->cti = $validated['cti'];

            $printer->save();

            EventController::store(Auth::id(), EventAction::create, ['idPrinter' => $printer->idPrinter]);
            return new JsonResponse([], 200);
        }
    }

    /**
     * Updates a printer in the database
     * @param Request $request
     * @param Printer $printer
     * @return JsonResponse
     */
    public function update(Request $request, Printer $printer): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'idModel' => ['numeric', 'exists:App\Models\PrinterModel,idPrinterModel'],
            'room' => ['nullable', 'string', 'max:50'],
            'serialNumber' => ['string', 'max:100', Rule::unique('printers')->ignore($printer)],
            'cti' => ['integer', 'digits:6', Rule::unique('printers')->ignore($printer)]
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()->messages()], 422);
        }
        else {
            $validated = $validator->validated();

            if ($request->has('idModel')) {
                $printer->printer_model_idPrinterModel = $validated['idModel'];
            }
            if ($request->has('room')) {
                $printer->room = $validated['room'];
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
     * @param Printer $printer
     * @return JsonResponse
     */
    public function destroy(Printer $printer): JsonResponse
    {
        if ($printer->trashed()) {
            return new JsonResponse(['errors' => 'This printer has already been deleted'], 422);
        }
        else {
            $printer->delete();
            EventController::store(Auth::id(), EventAction::delete, ['idPrinter' => $printer->idPrinter]);
            return new JsonResponse([], 200);
        }
    }

    /**
     * Return a list of the recent events of a printer
     * @param Printer $printer
     * @return JsonResponse
     */
    public function events(Printer $printer): JsonResponse
    {
        return new JsonResponse(
            ['data' => $printer->events()->where('action', EventAction::changeAmount)->latest()->get()], 200
        );
    }

    /**
     * Returns a list of printers with the highest consumption of consumables
     * Client can set optional GET parameters:
     *   - limit: number of printers to return
     *   - supply: id of the supply to filter on
     *   - startDate: start date of the period to filter on (inclusive)
     *   - endDate: end date of the period to filter on (inclusive)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function mostActive(Request $request): JsonResponse
    {
        $limit = $request->query('limit') ?: config('modelQuery.printer_nbMostActive');
        $supply = $request->query('supply');
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        // Make endDate inclusive
        if ($endDate) {
            $endDate = Carbon::parse($endDate)->addDay();
        }

        $printers = Printer::withSum([
            'events' => function (Builder $query) use ($supply, $startDate, $endDate) {
                if ($supply) {
                    $query->where('idSupply_target', $supply);
                }
                if ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('created_at', '<=', $endDate);
                }
            }
        ], 'amount')->get()->whereNotNull('events_sum_amount');


        // Get the true sum (a positive value) of the consumed supplies
        $printers = $printers->map(function ($printer) {
            $printer->events_sum_amount = abs($printer->events_sum_amount);
            return $printer;
        });

        $printers = $printers->sortByDesc('events_sum_amount')->take($limit)->values()->all();

        return new JsonResponse(['data' => $printers], 200);
    }
}
