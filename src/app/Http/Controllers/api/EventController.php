<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.api');
    }


    /**
     * Returns a list of events
     * Client can set optional GET parameters:
     *   - perPage: number of event per page
     *   - sortOrder: sort column of events
     *     - dir: sort direction (asc or desc)
     *   - search: search string in the 'comment' column
     *   - author: user id of the author
     *   - type: (array) actions of the event
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Get the number of events per page from the GET parameter or from the app config
        if ($request->query('perPage') && is_numeric($request->query('perPage')))
            $nbPerPage = $request->query('perPage');
        else
            $nbPerPage = config('modelQuery.event_perPage');

        // Get the sorting options from the GET parameter
        $sortColumn = $request->query('sort') ?: config('modelQuery.event_sortColumn');
        $sortDir = $request->query('dir') ?: config('modelQuery.event_sortOrder');

        $idAuthor = $request->query('author') ?: null;
        $type = $request->query('type') ?: [];

        if($request->query('search')){
            // Send events where a column contains the search term
            $searchTerm = $request->query('search');

            $events = Event::where('comment', 'like', '%' . $searchTerm . '%')->author($idAuthor)->type($type)->orderBy($sortColumn, $sortDir)->paginate($nbPerPage);
        }
        else {
            $events = Event::orderBy($sortColumn, $sortDir)->author($idAuthor)->type($type)->paginate($nbPerPage);
        }
        
        return new JsonResponse($events, 200);
    }

    /**
     * Returns a single event
     * @param  Event $event
     * @return JsonResponse
     */
    public function show(Event $event): JsonResponse
    {
        return new JsonResponse(['data' => $event], 200);
    }

    /**
     * Stores a event in the database
     * @param  int $idAuthor
     * @param  string $action
     * @param  string $comment
     * @param  int $amount
     * @param  array $arrTargets
     * @return bool
     */
    public static function store(int $idAuthor, string $action, array $arrTargets = [], int $amount = null, string $comment = null): bool
    {
        $targetValidator = Validator::make($arrTargets, [
            'idPrinter' => ['numeric', 'exists:App\Models\Printer,idPrinter'],
            'idSupply' => ['numeric', 'exists:App\Models\Supply,idSupply'],
            'idModel' => ['numeric', 'exists:App\Models\PrinterModel,idPrinterModel'],
            'idUser' => ['numeric', 'exists:App\Models\User,idUser'],
        ]);

        if ($targetValidator->fails()) {
            Log::error("Invalid target for event, validator errors:"  . json_encode($targetValidator->errors()->messages()) . ", targets: " . json_encode($arrTargets));
            return false;
        }
        else{
            $event = new Event();

            $event->idUser_author = $idAuthor;
            $event->action = $action;
            $event->comment = $comment;
            $event->amount = $amount;
    
            if (array_key_exists('idPrinter', $arrTargets)) {
                $event->idPrinter_target = $arrTargets['idPrinter'];
            }
            if (array_key_exists('idSupply', $arrTargets)) {
                $event->idSupply_target = $arrTargets['idSupply'];
            }
            if (array_key_exists('idModel', $arrTargets)) {
                $event->idPrinterModel_target = $arrTargets['idModel'];
            }
            if (array_key_exists('idUser', $arrTargets)) {
                $event->idUser_target = $arrTargets['idUser'];
            }
    
            $event->save();
            return true;    
        }
    }
    
}
