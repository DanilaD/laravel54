<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Validator;
use Excel;
use App\User;
use Carbon\Carbon;

class PaymentsDoctor extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'billing_doctors';
    public $fillable = ['id', 'doctor_id', 'amount', 'assessments', 'payment_date'];
    protected $dates = ['created_at', 'updated_at'];

    public static function ShowPayments(Request $request) {

        $filter =
            [
                'payment_id' => $request->id,
                'doctors_id' => $request->doctor_id,
                'payment_date_start' => $request->payment_date_start,
                'payment_date_end' => $request->payment_date_end,
                'payment_date_no' => $request->payment_date_no
            ];

        // for Excel and pagination
        $limit = (isset($request->export)) ? '' : 30;

        $result = PaymentsDoctor::orderBy('id', 'desc')
            ->leftJoin('users', 'users.id', '=', 'billing_doctors.doctor_id')
            ->select('billing_doctors.id', 'users.first', 'users.last', 'amount', 'assessments', 'payment_date')
            ->where(
                function ($query) use ($filter)
                {
                    if ($filter['payment_id']) {
                        $query->where('billing_doctors.id', $filter['payment_id']);
                    }
                    if ($filter['doctors_id']) {
                        $query->where('users.id', $filter['doctors_id']);
                    }

                    if ($filter['payment_date_start'] || $filter['payment_date_end']) {

                        if ($filter['payment_date_start']) {
                            $query->whereDate('billing_doctors.payment_date', '>=', $filter['payment_date_start']);
                        }
                        if ($filter['payment_date_end']) {
                            $query->whereDate('billing_doctors.payment_date', '<=', $filter['payment_date_end']);
                        }
                        if ($filter['payment_date_no']) {
                            $query->orwhereNull('billing_doctors.payment_date');
                        }

                    }elseif ($filter['payment_date_no']) {
                        $query->whereNull('billing_doctors.payment_date');
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
            if ($key == 'doctors_id' && ($val)) {
                // get doctor's name
                $result_sql = User::where('id', $val)->select('first', 'last')->get();
                $val = (isset($result_sql[0])) ? $result_sql[0]['first']. ',' . $result_sql[0]['last'] : '';
            }
            if ($val) $names[] = $key . '=' . $val;
        }

        if (empty($names)) return false;

        return '_' . implode('__', $names);
    }

    public static function ExportData($data, $filter) {

        $get_data_from_filter = self::BuildNameForReport($filter);

        $date = Carbon::now();

        // time for file
        $date_file = $date->format('Y_m_d_h_i');

        // time for sheet
        $date_sheet = $date->format('Y-m-d h.i');

        // name of file
        $file = 'Payments_' . $date_file . '_' . $get_data_from_filter;

        Excel::create($file, function($excel) use ($data, $date_sheet) {

            // Set the title
            $excel->setTitle('Payments / doctors');

            // name of sheet
            $name = 'Payments on ' . $date_sheet;

            // insert data on each sheet
            $excel->sheet($name, function($sheet) use ($data) {

                // first row
                $sheet->prependRow(1, array(
                    '#',
                    'Doctor',
                    'Amount',
                    'Assessments',
                    'Payment Date'
                ));

                // data for sheet
                foreach($data as $key => $val) {
                    $data = [
                        $val['id'],
                        $val['first']. ' ' .$val['last'],
                        $val['amount'],
                        $val['assessments'],
                        $val['payment_date']
                    ];
                    $sheet->rows(array($data));
                }

            });

        })->download('xls');

    }

    public static function AddPayments(Request $request) {

        // rules for request 'client name'
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|numeric',
            'assessments' => 'numeric',
            'amount' => 'required|numeric|between:0,9999999.99',
        ]);

        // check rules
        if ($validator->fails()) {
            return FALSE;
        }

        PaymentsDoctor::CreatePayments([
            'doctor_id' => $request->client_id,
            'amount' => $request->amount,
            'assessments' => $request->assessments,
            'payment_date' => $request->payment_date
        ]);
    }

    public static function EditPayments(Request $request) {

        return PaymentsDoctor::find($request->id);

    }

    public static function SavePayments(Request $request) {

        // rules for request 'client name'
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|numeric',
            'assessments' => 'numeric',
            'amount' => 'required|numeric|between:0,9999999.99',
        ]);

        // check rules
        if ($validator->fails()) {
            return FALSE;
        }

        $payment_date = empty($request->payment_date) ? Null : $request->payment_date;

        $answer = PaymentsDoctor::updateOrCreate(['id' => $request->id],
            [
                'doctor_id' => $request->doctor_id,
                'amount' => $request->amount,
                'assessments' => $request->assessments,
                'payment_date' => $payment_date
            ]);

        if ($answer) {
            return true;
        }

    }

    public static function DeletePayments(Request $request) {

        Payments::where('id', $request->id)->delete();

    }

    /*
     * insert data into Db
     * parameters: array $data
     * consist: doctor_id, amount, assessments, payment_date
     *
     */

    public static function SavaDataFromReport($data) {

        foreach ($data AS $val) {
            // only insert new row
            $row = PaymentsDoctor::firstOrCreate(
                ['doctor_id' => $val[0], 'payment_date' => $val[3]]
            );
            $row->amount = $val[1];
            $row->assessments = $val[2];
            $row->save();
        }

        /*
        // prepare all rows for insert into Db
        foreach ($data AS $val) {
            $toSql[] = [
                'doctor_id' => $val[0],
                'amount'=> $val[1],
                'assessments' => $val[2],
                'payment_date' => $val[3],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()

            ];
        }
        // insert in Db
        PaymentsDoctor::insert($toSql);
        */
    }
}
