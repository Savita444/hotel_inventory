<?php
namespace App\Http\Repository\SuperAdmin;

use App\Models\ActivityLog;
use App\Models\InventoryHistory;
use App\Models\Locations;
use App\Models\LocationWiseInventory;
use App\Models\MasterKitchenInventory;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Config;
use Illuminate\Support\Carbon;
use Session;

class ShoppingListRepository
{
    public function editItem($reuest)
    {
        try {
            // $data_district = [];

            $data_users_data = MasterKitchenInventory::where('master_kitchen_inventory.id', '=', $reuest->locationId)
                ->select('master_kitchen_inventory.*')
                ->get()
                ->toArray();

            $data_location = $data_users_data[0];
            return $data_location;
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }

    

    public function addKitchenInventoryBySuperAdmin($request)
    {
        // dd($request);
        try {
            $sess_user_id     = session()->get('login_id');
            $sess_user_name   = session()->get('user_name');
            $sess_location_id = session()->get('location_selected_id');
            $location_selected_name = session()->get('location_selected_name');
            $inventoryIds     = $request->input('master_inventory_id');
            $quantities       = $request->input('quantity');
            $master_price         = $request->input('master_price');
            $master_quantity      = $request->input('master_quantity');
            $category_name        = $request->input('category_name');
            $unit_name            = $request->input('unit_name');
            $item_name            = $request->input('item_name');
           
            $data = [];
            foreach ($inventoryIds as $index => $inventoryId) {
                try {
                    $LocationWiseInventoryData               = new LocationWiseInventory();
                    $LocationWiseInventoryData->user_id      = $sess_user_id;
                    $LocationWiseInventoryData->inventory_id = $inventoryIds[$index];
                    $LocationWiseInventoryData->hotel_id  = $sess_location_id;
                    $LocationWiseInventoryData->quantity     = $quantities[$index];

                    $LocationWiseInventoryData->master_quantity = $master_quantity[$index];
                    $LocationWiseInventoryData->master_price    = $master_price[$index];
                    $LocationWiseInventoryData->unit_name       = $unit_name[$index];
                    $LocationWiseInventoryData->category_name   = $category_name[$index];

                    $LocationWiseInventoryData->approved_by = 1;
                    $LocationWiseInventoryData->created_at  = Carbon::now('America/New_York');
                    $LocationWiseInventoryData->save();
                    $last_insert_id = $LocationWiseInventoryData->id;
                } catch (\Exception $e) {
                    info("Locationwise Inventory ".$e->getMessage());
                }


                try {
                    $InventoryHistoryData               = new InventoryHistory();
                    $InventoryHistoryData->user_id      = $sess_user_id;
                    $InventoryHistoryData->inventory_id = $inventoryIds[$index];
                    $InventoryHistoryData->location_id  = $sess_location_id;
                    $InventoryHistoryData->quantity     = $quantities[$index];
                    $InventoryHistoryData->approved_by  = 1;
                    $InventoryHistoryData->created_at   = Carbon::now('America/New_York');
                    $InventoryHistoryData->save();
                    
                    // Store data for PDF
                    $historyData[] = [  
                        'master_qty'   => $master_quantity[$index],
                        'item_name' => $item_name[$index],
                        'quantity'     => $quantities[$index],
                        'price'        => $master_price[$index],
                        'category_name' => $category_name[$index],
                        'unit'          => $unit_name[$index]
                    ];
                } catch (\Exception $e) {
                    info("Inventory History ".$e->getMessage());
                }

            }

            $groupedByCategory = [];
            foreach ($historyData as $item) {
                $category = $item['category_name'];
                if (!isset($groupedByCategory[$category])) {
                    $groupedByCategory[$category] = [];
                }
                $groupedByCategory[$category][] = $item;
            }

            

            if ($last_insert_id) {

                if(session()->get('user_role') == 1) {
                    $role_name = " (Super Admin)";
                } else if(session()->get('user_role') == 2) {
                    $role_name = " (Admin)";
                } else {
                    $role_name = " (Night Manager)";
                }

                
                $LogMsg = config('constants.MANAGER.1111');


                $FinalLogMessage                   = $sess_user_name. $role_name . ' ' . $LogMsg . ' ' ."for location ".session()->get('location_selected_name') ;
                $ActivityLogData                   = new ActivityLog();
                $ActivityLogData->user_role          = session()->get('user_role');
                $ActivityLogData->location          = session()->get('location_selected_id');
                $ActivityLogData->user_id          = $sess_user_id;
                $ActivityLogData->activity_message = $FinalLogMessage;
                $ActivityLogData->created_at       = Carbon::now('America/New_York');
                $ActivityLogData->save();
            }

            try {
                $logoPath   = asset('/img/main_logo.png');
                $logoBase64 = base64_encode(file_get_contents($logoPath));
                $htmlContent = view('inventory_history_pdf', ['historyData' => $groupedByCategory,
                                            'location'            => $location_selected_name,
                                            'currentDate'         => now()->toDateString(),
                                            'logo'                => $logoBase64])->render();
                $pdf     = PDF::loadHTML($htmlContent);
                $pdfData = $pdf->output();
            
                $pdfBase64 = base64_encode($pdfData);
            } catch (\Exception $e) {
                info("PDF Genration Error ".$e->getMessage());
            }

            $responseData = [
                'pdf'         => $pdfBase64,
                'location'    => $location_selected_name,
                'currentDate' => now()->toDateString(),
            ];

            return $responseData;
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }

    public function updateKitchenInventoryBySuperAdmin($request)
    {
        try {
            $sess_user_id       = session()->get('login_id');
            $sess_user_name     = session()->get('user_name');
            $sess_location_id   = session()->get('location_selected_id');
            $location_selected_name = session()->get('location_selected_name');
            $inventoryIds       = $request->input('location_wise_inventory_id');
            $quantities         = $request->input('quantity');
            $MasterInventoryIds = $request->input('master_inventory_id');
            
            $master_price         = $request->input('master_price');
            $master_quantity      = $request->input('master_quantity');
            $category_name        = $request->input('category_name');
            $unit_name            = $request->input('unit_name');
            $item_name            = $request->input('item_name');
           

            foreach ($inventoryIds as $index => $inventoryId) {
                $existingInventory = LocationWiseInventory::where('id', $inventoryId)
                    ->first();
                if ($existingInventory) {
                    LocationWiseInventory::where('id', $inventoryId)
                        ->update([
                            'quantity'        => $quantities[$index],
                            'master_quantity' => $master_quantity[$index],
                            'master_price'    => $master_price[$index],
                            'approved_by'     => '1',
                            'updated_at'      => Carbon::now('America/New_York'),
                        ]);
                } else {
                    $LocationWiseInventoryData               = new LocationWiseInventory();
                    $LocationWiseInventoryData->user_id      = $sess_user_id;
                    $LocationWiseInventoryData->inventory_id = $inventoryIds[$index];
                    $LocationWiseInventoryData->location_id  = $sess_location_id;
                    $LocationWiseInventoryData->quantity     = $quantities[$index];

                    $LocationWiseInventoryData->master_quantity = $master_quantity[$index];
                    $LocationWiseInventoryData->master_price    = $master_price[$index];
                    $LocationWiseInventoryData->unit_name       = $unit_name[$index];
                    $LocationWiseInventoryData->category_name   = $category_name[$index];

                    $LocationWiseInventoryData->approved_by = 1;
                    $LocationWiseInventoryData->created_at  = Carbon::now('America/New_York');
                    $LocationWiseInventoryData->save();
                    $last_insert_id = $LocationWiseInventoryData->id;
                }

                $InventoryHistoryData               = new InventoryHistory();
                $InventoryHistoryData->user_id      = $sess_user_id;
                $InventoryHistoryData->inventory_id = (int) $MasterInventoryIds[$index];
                $InventoryHistoryData->location_id  = $sess_location_id;
                $InventoryHistoryData->quantity     = $quantities[$index];
                $InventoryHistoryData->approved_by  = 1;
                $InventoryHistoryData->created_at   = Carbon::now('America/New_York');
                $InventoryHistoryData->save();


                $historyData[] = [  
                    'master_qty'   => $master_quantity[$index],
                    'item_name' => $item_name[$index],
                    'quantity'     => $quantities[$index],
                    'price'        => $master_price[$index],
                    'category_name' => $category_name[$index],
                    'unit'          => $unit_name[$index]
                ];
            }

            if(session()->get('user_role') == 1) {
                $role_name = " (Super Admin)";
            } else if(session()->get('user_role') == 2) {
                $role_name = " (Admin)";
            } else {
                $role_name = " (Night Manager)";
            }

            $LogMsg                            = config('constants.SUPER_ADMIN.1112');
            $FinalLogMessage                   = $sess_user_name.$role_name . ' ' . $LogMsg . ' ' ."for location ".session()->get('location_selected_name') ;
            $ActivityLogData                   = new ActivityLog();
            $ActivityLogData->user_role        = session()->get('user_role');
            $ActivityLogData->location         = session()->get('location_selected_id');
            $ActivityLogData->user_id          = $sess_user_id;
            $ActivityLogData->activity_message = $FinalLogMessage;
            $ActivityLogData->created_at       = Carbon::now('America/New_York');
            $ActivityLogData->save();

            
            $groupedByCategory = [];
            foreach ($historyData as $item) {
                $category = $item['category_name'];
                if (!isset($groupedByCategory[$category])) {
                    $groupedByCategory[$category] = [];
                }
                $groupedByCategory[$category][] = $item;
            }

            try {
                $logoPath   = asset('/img/main_logo.png');
                $logoBase64 = base64_encode(file_get_contents($logoPath));
                $htmlContent = view('inventory_history_pdf', ['historyData' => $groupedByCategory,
                                            'location'            => $location_selected_name,
                                            'currentDate'         => now()->toDateString(),
                                            'logo'                => $logoBase64])->render();
                $pdf     = PDF::loadHTML($htmlContent);
                $pdfData = $pdf->output();
                $pdfBase64 = base64_encode($pdfData);
            } catch (\Exception $e) {
                info("PDF Genration Error ".$e->getMessage());
            }

            $responseData = [
                'pdf'         => $pdfBase64,
                'location'    => $location_selected_name,
                'currentDate' => now()->toDateString(),
            ];

            return $responseData;
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }
}
