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
    public function addLocation($request)
    {
        try {
            $locationData = $this->repo->addLocationInsert($request);
    
            if (!$locationData) {
                return ['status' => 'error', 'msg' => 'Location Not Added.'];
            }
    
            $last_id = $locationData['id'];
            $qr_svg  = $locationData['qr_svg'];
            $ImageName = $locationData['ImageName']; // ✅ FIXED: get ImageName from array, not from $last_id
    
            // Save QR code file
            $path = public_path(Config::get('DocumentConstant.QR_ADD')); 
            $qrImageName = 'hotel_qr_' . $last_id . '.svg';
            $fullPath  = $path . '/' . $qrImageName; 
            file_put_contents($fullPath, $qr_svg); 
    
            // Update QR code path in DB
            Hotels::where('id', $last_id)->update([
                'qr_code_path' => $qrImageName
            ]);
    
            // Upload hotel image
            $path = Config::get('DocumentConstant.HOTEL_IMAGE_ADD');
            uploadImage($request, 'image', $path, $ImageName);
    
            return ['status' => 'success', 'msg' => 'Location Added Successfully.'];
    
        } catch (\Exception $e) {
            \Log::error('Error in addLocation: ' . $e->getMessage());
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    
// public function addLocation($request)
// {
//     try {
//         $locationData = $this->repo->addLocationInsert($request);

//         if (!$locationData) {
//             return ['status' => 'error', 'msg' => 'Location Not Added.'];
//         }

//         $last_id = $locationData['id'];
//         $qr_svg  = $locationData['qr_svg'];

//         $path = public_path(Config::get('DocumentConstant.QR_ADD')); 
//         $imageName = 'hotel_qr_' . $last_id . '.svg';
//         $fullPath  = $path . '/' . $imageName; 

//         file_put_contents($fullPath, $qr_svg); 
        
//         Hotels::where('id', $last_id)->update([
//             'qr_code_path' => $imageName
//         ]);


//         $path = Config::get('DocumentConstant.HOTEL_IMAGE_ADD');
//         $ImageName = $last_id['ImageName'];
//         uploadImage($request, 'image', $path, $ImageName);

//         return ['status' => 'success', 'msg' => 'Location Added Successfully.'];

//     } catch (Exception $e) {
//         \Log::error('Error in addLocation: ' . $e->getMessage());
//         return ['status' => 'error', 'msg' => $e->getMessage()];
//     }
//   }
    
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
