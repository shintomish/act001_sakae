<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use App\Models\Customer;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

        Log::info('ChatController index START');
        // return Message::orderBy('id', 'desc')->get();
        // $messages = Message::orderBy('id', 'desc')->get();
        // $messages = Message::with('user')->orderBy('id', 'desc')->get();
        $messages = Message::select(
                'messages.id              as id'
                // ,'messages.organization_id as organization_id'
                ,'messages.user_id         as user_id'
                ,'messages.customer_id     as customer_id'
                ,'messages.body            as m_body'
                ,'messages.created_at      as m_created_at'
                ,'users.id                 as users_id'
                ,'users.name               as users_name'
                ,'customers.id             as customers_id'
                ,'customers.business_name  as business_name'
            )
            ->leftJoin('users', function ($join) {
                $join->on('messages.user_id', '=', 'users.id');
            })
            ->leftJoin('customers', function ($join) {
                $join->on('messages.customer_id', '=', 'customers.id');
            })
            ->whereNull('customers.deleted_at')
            ->whereNull('users.deleted_at')
            // ->whereNull('messages.deleted_at')
            ->orderBy('messages.id', 'desc')
            ->orderBy('messages.customer_id', 'asc')
            ->paginate(300);

            $organization_id = 1;
            $users = User::where('organization_id','=',$organization_id)
                            // ->where('login_flg','=', 1 )  //顧客
                            ->whereNull('deleted_at')
                            ->get();
            $customers = Customer::where('organization_id','=',$organization_id)
                            ->whereNull('deleted_at')
                            ->get();

        $common_no = '00_7';
        $compacts = compact( 'messages','common_no','users','customers' );

        Log::info('ChatController index END');

        return view('chat.index', $compacts );
    }
}
