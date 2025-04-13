<?php
namespace App\Http\Services\SuperAdmin;

use App\Http\Repository\SuperAdmin\HotelRepository;
use Config;
use App\Models\Hotels;
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
//    public function addLocation($request)
//    {
//        try {
//            $locationData = $this->repo->addLocationInsert($request);
   
//            if (!$locationData) {
//                return ['status' => 'error', 'msg' => 'Location Not Added.'];
//            }
   
//            $last_id = $locationData['id'];
//            $qr_svg  = $locationData['qr_svg'];
   
//            $path = Config::get('DocumentConstant.QR_ADD');
//            $imageName = 'hotel_qr_' . $last_id . '.svg';
//            $fullPath  = $path . $imageName;
   
//            if (!file_exists($path)) {
//                mkdir($path, 0777, true);
//            }
   
//            file_put_contents($fullPath, $qr_svg);
   
//            // Update path in database
//            Hotels::where('id', $last_id)->update([
//                'qr_code_path' => $imageName
//            ]);
   
//            return ['status' => 'success', 'msg' => 'Location Added Successfully.'];
   
//        } catch (Exception $e) {
//            return ['status' => 'error', 'msg' => $e->getMessage()];
//        }
//    }
   


public function addLocation($request)
{
    try {
        $locationData = $this->repo->addLocationInsert($request);

        if (!$locationData) {
            return ['status' => 'error', 'msg' => 'Location Not Added.'];
        }

        $last_id = $locationData['id'];
        $qr_svg  = $locationData['qr_svg'];

        $path = public_path(Config::get('DocumentConstant.QR_ADD')); 
        $imageName = 'hotel_qr_' . $last_id . '.svg';
        $fullPath  = $path . '/' . $imageName; 

        // Write the SVG content to a file
        file_put_contents($fullPath, $qr_svg); // Save the SVG content

        // Update the database with the file name/path
        Hotels::where('id', $last_id)->update([
            'qr_code_path' => $imageName
        ]);

        return ['status' => 'success', 'msg' => 'Location Added Successfully.'];

    } catch (Exception $e) {
        \Log::error('Error in addLocation: ' . $e->getMessage());
        return ['status' => 'error', 'msg' => $e->getMessage()];
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
