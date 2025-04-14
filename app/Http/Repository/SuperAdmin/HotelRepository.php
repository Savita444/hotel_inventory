<?php
namespace App\Http\Repository\SuperAdmin;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use App\Models\Hotels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;use Session;
use SimpleQRCode;
use Config;

class HotelRepository
{
    public function getLocationList()
    {
        try {
            $data_location = Hotels::select('id', 'hotel_name','description','type','contact_no','address','email', 'website','qr_code_path')
                ->where('is_deleted', '0')
                ->orderBy('id', 'desc')
                ->paginate(10);
            // ->get();

            return $data_location;
        } catch (\Exception $e) {
            info($e->getMessage());
        }

    }

    // public function addLocationCheck($request)
    // {
    //     try {
    //         return Hotels::where('hotels', '=', $request['hotels'])
    //             ->select('id')->get();
    //     } catch (\Exception $e) {
    //         info($e->getMessage());
    //     }
    // }
    public function addLocationCheck($request)
    {
        try {
            return Hotels::where('hotel_name', $request['hotel_name'])
                ->where('description', $request['description'])
                ->where('contact_no', $request['contact_no'])
                ->where('address', $request['address'])
                ->where('email', $request['email'])
                ->select('id')
                ->get();
                
        } catch (\Exception $e) {
            info($e->getMessage());
            return null;
        }
    }
    
    public function editLocation($reuest)
    {
        try {
            $data_users_data = Hotels::where('hotels.id', '=', $reuest->locationId)
                ->select(
                    'hotels.hotel_name', 'id'
                )->get()
                ->toArray();

            $data_location = $data_users_data[0];
            return $data_location;
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }

    // public function addLocationInsert($request)
    // {
    //     try {
    //         $data                    = [];
    //         $location_data           = new Hotels();
    //         $location_data->hotel_name = $request['hotel_name'];
    //         $location_data->description = $request['description'];
    //         $location_data->contact_no = $request['contact_no'];
    //         $location_data->type = $request['type'];
    //         $location_data->address = $request['address'];
    //         $location_data->email = $request['email'];
    //         $location_data->website = $request['website'];
    //         $location_data->save();
    //         $last_insert_id = $location_data->id;

    //         $qrCode = QrCode::size(300)->generate($last_insert_id);
    //         $ImageName = $last_insert_id .'_' . rand( 100000, 999999 ) . '_qr.' . $request->qr_code_path->extension();

    //         $finalOutput = Hotels::find( $last_insert_id );
    //         // Assuming $request directly contains the ID
    //         $finalOutput->qr_code_path = $ImageName;
    //         // Save the image filename to the database
    //         $finalOutput->save();

    //         $data[ 'ImageName' ] = $ImageName;
    //         return $data;

            
        

    //         $sess_user_id     = session()->get('login_id');
    //         $sess_user_name   = session()->get('user_name');
    //         $sess_location_id = session()->get('location_selected_id');

    //         $LogMsg = config('constants.SUPER_ADMIN.1116');

    //         $FinalLogMessage                   = $sess_user_name . ' ' . $LogMsg;
    //         $ActivityLogData                   = new ActivityLog();
    //         $ActivityLogData->user_id          = $sess_user_id;
    //         $ActivityLogData->activity_message = $FinalLogMessage;
    //         $ActivityLogData->save();

    //         return $last_insert_id;
    //     } catch (\Exception $e) {
    //         info($e->getMessage());
    //     }
    // }
public function addLocationInsert($request)
{
    DB::beginTransaction();

    try {
        $location = new Hotels();
        $location->hotel_name  = $request['hotel_name'];
        $location->description = $request['description'];
        $location->contact_no  = $request['contact_no'];
        $location->type        = $request['type'];
        $location->address     = $request['address'];
        $location->email       = $request['email'];
        $location->website     = $request['website'];
        $location->save();

        $last_insert_id = $location->id;
        
        $url = route('items.hotel_id', ['hotel_id' => $last_insert_id]);

        $qrCode = \QrCode::format('svg')->size(300)->generate($url);

        // Log activity
        $sess_user_id   = session()->get('login_id');
        $sess_user_name = session()->get('user_name');
        $logMsg         = config('constants.SUPER_ADMIN.1116');
        $finalLogMsg    = $sess_user_name . ' ' . $logMsg;

        $activityLog = new ActivityLog();
        $activityLog->user_id = $sess_user_id;
        $activityLog->activity_message = $finalLogMsg;
        $activityLog->save();

        DB::commit();

        return [
            'id'     => $last_insert_id,
            'qr_svg' => $qrCode
        ];

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error in addLocationInsert: ' . $e->getMessage());
        return false;
    }
}

    public function updateLocation($request)
    {
        try {
            $user_data = Hotels::where('id', $request['edit_id'])
                ->update([
                    'hotel_name' => $request['hotel_name'],
                    // 'description' => $request['description'],
                    // 'type' => $request['type'],
                    // 'contact_no' => $request['contact_no'],
                    // 'address' => $request['address'],
                    // 'email' => $request['email'],
                    // 'website' => $request['website'],
                ]);

            $sess_user_id     = session()->get('login_id');
            $sess_user_name   = session()->get('user_name');
            $sess_location_id = session()->get('location_selected_id');

            $LogMsg = config('constants.SUPER_ADMIN.1117');

            $FinalLogMessage                   = $sess_user_name . ' ' . $LogMsg;
            $ActivityLogData                   = new ActivityLog();
            $ActivityLogData->user_id          = $sess_user_id;
            $ActivityLogData->activity_message = $FinalLogMessage;
            $ActivityLogData->save();

            return $request->edit_id;
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }

    public function deleteLocation($id)
    {
        try {
            $all_data = [];

            $student_data = Hotels::find($id);

            // Delete the record from the database
            $is_deleted               = $student_data->is_deleted == 1 ? 0 : 1;
            $student_data->is_deleted = $is_deleted;
            $student_data->save();

            $sess_user_id     = session()->get('login_id');
            $sess_user_name   = session()->get('user_name');
            $sess_location_id = session()->get('location_selected_id');

            $LogMsg = config('constants.SUPER_ADMIN.1118');

            $FinalLogMessage                   = $sess_user_name . ' ' . $LogMsg;
            $ActivityLogData                   = new ActivityLog();
            $ActivityLogData->user_id          = $sess_user_id;
            $ActivityLogData->activity_message = $FinalLogMessage;
            $ActivityLogData->save();

            return $student_data;
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }
}
