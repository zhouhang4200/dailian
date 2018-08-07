<?php

namespace App\Http\Controllers\Back;

use App\Models\FineTicket;
use App\Http\Controllers\Controller;

/**
 * Class FineTicketController
 * @package App\Http\Controllers\Back
 */
class FineTicketController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.fine-ticket.index', [
            'fineTickets' => FineTicket::condition(request()->all())->paginate(),
        ]);
    }

    public function create()
    {

    }

    public function store()
    {

    }

}
