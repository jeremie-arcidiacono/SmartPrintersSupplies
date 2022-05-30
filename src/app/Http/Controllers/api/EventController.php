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

    
}
