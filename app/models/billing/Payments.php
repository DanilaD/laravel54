<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Validator;
use Excel;
use Carbon\Carbon;

class Payments extends Model
{
 /*
  * The table associated with the model.
  */
    protected $table = 'billing_clients';
    public $fillable = ['id', 'client_id', 'amount', 'assessments', 'invoice_date', 'payment_date'];
    protected $dates = ['created_at', 'updated_at'];

    public static function ShowPayments(Request $request) {

        $filter = [

            'payment_id' => $request->id,
            'clients_id' => $request->clients_id,
            'invoice_date_start' => $request->invoice_date_start,
            'invoice_date_end' => $request->invoice_date_end,
            'invoice_date_no' => $request->invoice_date_no,
            'payment_date_start' => $request->payment_date_start,
            'payment_date_end' => $request->payment_date_end,
            'payment_date_no' => $request->payment_date_no

        ];

        // for Excel and pagination
        $limit = (isset($request->export)) ? '' : 30;

        $result = Payments::orderBy('id', 'desc')
            ->leftJoin('clients', 'clients.id', '=', 'billing_clients.client_id')
            ->select('billing_clients.id', 'clients.client_name', 'amount', 'assessments', 'invoice_date', 'payment_date')
            ->where(
                function ($query) use ($filter)
                {
                    if ($filter['payment_id']) {
                        $query->where('billing_clients.id', $filter['payment_id']);
                    }
                    if ($filter['clients_id']) {
                        $query->where('clients.id', $filter['clients_id']);
                    }
                    if ($filter['invoice_date_start'] || $filter['invoice_date_end']) {

                        if ($filter['invoice_date_start']) {
                            $query->whereDate('billing_clients.invoice_date', '>=', $filter['invoice_date_start']);
                        }
                        if ($filter['invoice_date_end']) {
                            $query->whereDate('billing_clients.invoice_date', '<=', $filter['invoice_date_end']);
                        }
                        if ($filter['invoice_date_no']) {
                            $query->orwhereNull('billing_clients.invoice_date');
                        }

                    }elseif ($filter['invoice_date_no']) {
                        $query->whereNull('billing_clients.invoice_date');
                    }
                    if ($filter['payment_date_start'] || $filter['payment_date_end']) {

                        if ($filter['payment_date_start']) {
                            $query->whereDate('billing_clients.payment_date', '>=', $filter['payment_date_start']);
                        }
                        if ($filter['payment_date_end']) {
                            $query->whereDate('billing_clients.payment_date', '<=', $filter['payment_date_end']);
                        }
                        if ($filter['payment_date_no']) {
                            $query->orwhereNull('billing_clients.payment_date');
                        }

                    }elseif ($filter['payment_date_no']) {
                        $query->whereNull('billing_clients.payment_date');
                    }
                }
            )
            ->paginate($limit);

        // export data
        if (isset($request->export)) {

            self::ExportData($result, $filter);

        }

        return $result;

    }

    public static function BuildNameForReport($filter) {

        if (empty($filter)) return false;

        // data for file's name
        foreach ($filter AS $key => $val) {
            if ($key == 'clients_id' && ($val)) {
                // get doctor's name
                $result_sql = Client::where('id', $val)->select('client_name')->get();
                $val = (isset($result_sql[0])) ? $result_sql[0]['client_name'] : '';
            }
            if ($val) $names[] = $key . '=' . $val;
        }

        if (empty($names)) return false;

        return implode(' ', $names);
    }

    public static function ExportData($data, $filter) {

        // get parametrs for display in sheet
        $get_data_from_filter = self::BuildNameForReport($filter);

        $date = Carbon::now();

        // time for file
        $date_file = $date->format('Y_m_d_h_i');

        // time for sheet
        $date_sheet = $date->format('Y-m-d h.i');

        // name of file
        $file = 'Payments_clients_' . $date_file;

        Excel::create($file, function($excel) use ($data, $date_sheet, $get_data_from_filter) {

            // Set the title
            $excel->setTitle('Payments / clients');

            // name of sheet
            $name = 'Payments on ' . $date_sheet;

            // insert data on each sheet
            $excel->sheet($name, function($sheet) use ($data, $get_data_from_filter) {

                // for show parametrs from filter
                /*
                $sheet->appendRow(array($get_data_from_filter));
                $sheet->mergeCells('A1:E1');
                */

                // second row
                $sheet->prependRow(1, array(
                    '#',
                    'Client name',
                    'Assessments',
                    'Amount',
                    'Invoice Date',
                    'Payment Date'
                ));

                // data for sheet
                foreach($data as $key => $val) {
                    $data = [
                        $val['id'],
                        $val['client_name'],
                        $val['assessments'],
                        $val['amount'],
                        $val['invoice_date'],
                        $val['payment_date']
                    ];
                    $sheet->rows(array($data));
                }

            });

        })->download('xls');

    }

    public static function AddPayments(Request $request) {

        self::SavePayments($request);

    }

    public static function EditPayments(Request $request) {

        return Payments::find($request->id);

    }

    public static function SavePayments(Request $request) {

        // rules for request 'client name'
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|numeric',
            'amount' => 'required|numeric|between:0,9999999.99',
            'assessments' => 'required|numeric'
        ]);

        // check rules
        if ($validator->fails()) {
            return FALSE;
        }

        $payment_date = empty($request->payment_date) ? Null : $request->payment_date;

        $answer = Payments::updateOrCreate(['id' => $request->id],
            [
                'client_id' => $request->client_id,
                'amount' => $request->amount,
                'assessments' => $request->assessments,
                'invoice_date' => $request->invoice_date,
                'payment_date' => $payment_date
            ]);

        if ($answer) {
            return true;
        }

    }

    public static function DeletePayments(Request $request) {

        Payments::where('id', $request->id)->delete();

    }

}
