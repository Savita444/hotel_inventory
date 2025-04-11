<?php
namespace App\Http\Services\SuperAdmin;

use App\Http\Repository\SuperAdmin\HotelRepository;

class HotelServices
{

    protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct()
    {
        $this->repo = new HotelRepository();
    }

    public function index()
    {
        try {
            $data_location = $this->repo->getLocationList();
            // dd($data_location);
            return $data_location;
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }

    // public function addLocation($request)
    // {
    //     try {
    //         $last_id = $this->repo->addLocationInsert($request);

    //         $path = Config::get('DocumentConstant.QR_ADD');
    //         $ImageName = $last_id['ImageName'];
    //         uploadImage($request, 'qr_code_path', $path, $ImageName);
    //         // dd($last_id);
    //         if ($last_id) {
    //             return ['status' => 'success', 'msg' => 'Location Added Successfully.'];
    //         } else {
    //             return ['status' => 'error', 'msg' => 'Location get Not Added.'];
    //         }
    //         // }

    //     } catch (Exception $e) {
    //         return ['status' => 'error', 'msg' => $e->getMessage()];
    //     }
    // }
   // Service Method
// Service Method
public function addLocation($request)
{
    try {
        $result = $this->repo->addLocationInsert($request);
// dd($request);
// die();
        if (!$result) {
            return ['status' => 'error', 'msg' => 'Location not added.'];
        }

        return [
            'status' => 'success',
            'msg'    => 'Location Added Successfully.',
            'data'   => $result
        ];

    } catch (\Exception $e) {
        return ['status' => 'error', 'msg' => 'Something went wrong: ' . $e->getMessage()];
    }
}





    
    public function editLocation($request)
    {
        try {
            $data_location = $this->repo->editLocation($request);
            // dd($data_location);
            return $data_location;
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }

    public function updateLocation($request)
    {
        try {
            $user_register_id = $this->repo->updateLocation($request);
            return ['status' => 'success', 'msg' => 'Location Updated Successfully.'];
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }

    public function deleteLocation($id)
    {
        try {
            $delete = $this->repo->deleteLocation($id);
            if ($delete) {
                return ['status' => 'success', 'msg' => 'Location Deleted Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Location Not Deleted.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
}
