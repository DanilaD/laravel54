<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Validator;

class Client extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'clients';
    public $fillable = ['id', 'client_name'];
    protected $dates = ['created_at', 'updated_at'];

    public static function ShowClients() {

        return Client::orderBy('id', 'desc')->paginate(30);

    }


    public static function AddClient(Request $request)
    {

    }

    public static function EditClient(Request $request) {

        $id = $request->id;

        $client = Client::find($id);

        return $client;
    }


    public static function DeleteClient(Request $request) {

        Client::where('id', $request->id)->delete();

    }

    public static function SaveClient(Request $request) {

        // rules for request 'client name'
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|unique:clients|max:255'
        ]);

        // check rules
        if ($validator->fails()) {
            return FALSE;
        }

        $answer = Client::updateOrCreate(['id' => $request->id], ['client_name' => $request->client_name]);

        if ($answer) return TRUE;

    }
}

