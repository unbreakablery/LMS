<?php 

use App\Models\User;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

if (!function_exists('storeUser')) {
    function storeUser($data)
    {
        $userId = $data->user_id;
        $firstName = $data->first_name;
        $lastName = $data->last_name;
        $email = $data->email;
        
        if (empty($userId)) {
            $password = Hash::make($data->password);

            $user = new User();
            $user->first_name = $firstName;
            $user->last_name = $lastName;
            $user->email = $email;
            $user->password = $password;

            $user->save();
        } else {
            $user = User::find($userId);

            $user->first_name = $firstName;
            $user->last_name = $lastName;
            $user->email = $email;

            $user->save();
        }
        
        return $user->id;
    }
}

if (!function_exists('removeUser')) {
    function removeUser($id)
    {
        $user = User::find($id);
        
        if (empty($user)) {
            return false;
        }
        
        // Change role and active
        // Not remove from table, so it is recoverable
        $user->active = 0;
        $user->role = '0';
                
        $user->save();
        return true;
    }
}

if (!function_exists('storeUserPassword')) {
    function storeUserPassword($data)
    {
        $password = $data->get('password');
        if (empty($password)) {
            return false;
        }
        
        // Update user password
        $user = User::where('id', Auth::user()->id)->get()->first();

        if (empty($user)) {
            return false;
        }
        
        $user->password = Hash::make($password);
        $user->update();
        
        return true;
    }
}

if (!function_exists('updateUser')) {
    function updateUser($data)
    {
        $userId = $data->get('id');
        if (empty($userId)) {
            return false;
        }
        
        // Update user
        $user = User::where('id', $userId)->get()->first();

        if (empty($user)) {
            return false;
        }
        
        $user->role = $data->get('role');
        $user->active = $data->get('active');
        $user->update();

        $u_user = new \StdClass();
        $u_user->first_name = $user->first_name;
        $u_user->last_name = $user->last_name;
        $u_user->role = $user->role;
        $u_user->role_name = getUserRoleName($user->role);
        $u_user->active = $user->active;
        $u_user->active_name = getUserActiveName($user->active);
        $u_user->active_class = getUserActiveClass($user->active);
        
        return $u_user;
    }
}

if (!function_exists('formatWithZero')) {
    function formatWithZero($number) {
        return ($number < 10) ? '0' . $number : $number;
    }
}

if (!function_exists('getUserRoleName')) {
    function getUserRoleName($role) {
        $roles = config('app_setting.roles');
        if (empty($roles[$role])) {
            return 'Unknown User';
        }
        return $roles[$role];
    }
}

if (!function_exists('getUserActiveClass')) {
    function getUserActiveClass($active) {
        if ($active) {
            return 'text-success';
        } else {
            return 'text-danger';
        }
    }
}

if (!function_exists('getUserActiveName')) {
    function getUserActiveName($active) {
        if ($active) {
            return 'Active';
        } else {
            return 'Inactive';
        }
    }
}

if (!function_exists('setActiveUser')) {
    function setActiveUser($id, $active = 1)
    {
        $user = User::where('id', $id)->get()->first();
        if (!empty($user)) {
            $user->active = $active;
            $user->update();
        }

        return $user;
    }
}

if (!function_exists('getUserById')) {
    function getUserById($userId)
    {
        if (empty($userId)) {
            return null;
        }

        $user = User::where('id', $userId)->get()->first();
        return $user;
    }
}

if (!function_exists('getAllUsers')) {
    function getAllUsers()
    {
        $users = User::where('role', '!=', '-1')
                    ->where('role', '!=', '1')
                    ->get()
                    ->all();
        return $users;
    }
}

if (!function_exists('getAllCategories')) {
    function getAllCategories()
    {
        $categories = Category::with('equipments')
                    ->orderBy('p_cat')
                    ->get()
                    ->all();
        return $categories;
    }
}

if (!function_exists('getCategory')) {
    function getCategory($id)
    {
        $category = Category::with(['subCategories', 'equipments'])
                            ->where('id', $id)
                            ->get()
                            ->first();
        return $category;
    }
}

if (!function_exists('buildTree')) {
    function buildTree(array $flatList)
    {
        $grouped = [];
        foreach ($flatList as $node){
            $grouped[$node['parentID']][] = $node;
        }

        $fnBuilder = function($siblings) use (&$fnBuilder, $grouped) {
            foreach ($siblings as $k => $sibling) {
                $id = $sibling['id'];
                if(isset($grouped[$id])) {
                    $sibling['children'] = $fnBuilder($grouped[$id]);
                }
                $siblings[$k] = $sibling;
            }
            return $siblings;
        };

        return $fnBuilder($grouped[0]);
    }
}

if (!function_exists('storeCategory')) {
    function storeCategory($data)
    {
        $catId = $data->get('cat_id');

        if (empty($catId)) {
            $category = new Category();
            $category->cat_name = $data->get('cat_name');
            $category->p_cat = $data->get('p_cat');
            
            $category->save();
        } else {
            $category = Category::find($catId);
            $category->cat_name = $data->get('cat_name');
            $category->p_cat = $data->get('p_cat');

            $category->save();
        }

        return $category;
    }
}

if (!function_exists('deleteCategory')) {
    function deleteCategory($id)
    {
        $category = Category::find($id);
        $category->delete();
        return;
    }
}

if (!function_exists('getAllEquipment')) {
    function getAllEquipment()
    {
        $equipment = Equipment::with('category')
                            ->get()
                            ->all();
        return $equipment;
    }
}

if (!function_exists('getStatusName')) {
    function getStatusName($kind, $status)
    {
        switch ($kind) {
            case 'equipment':
                switch ($status) {
                    case '0':
                        return 'Available';
                        break;
                    case '1':
                        return 'In Booking';
                        break;
                    case '2':
                        return 'Booked/Pickup';
                        break;
                }
                return 'Unknown';
                break;
            case 'booking':
                switch ($status) {
                    case '0':
                        return 'In Booking';
                        break;
                    case '1':
                        return 'Booked/Approved';
                        break;
                    case '2':
                        return 'Rejected';
                        break;
                    case '3':
                        return 'Cancelled';
                        break;
                    case '4':
                        return 'Returned';
                        break;
                }
                return 'Unknown';
                break;
            case 'notification':
                break;
        }
        
        return 'Unknown';
    }
}

if (!function_exists('getStatusClassName')) {
    function getStatusClassName($kind, $status)
    {
        switch ($kind) {
            case 'equipment':
                switch ($status) {
                    case '0':
                        return 'text-success';
                        break;
                    case '1':
                        return 'text-danger';
                        break;
                    case '2':
                        return '';
                        break;
                }
                return 'text-warning';
                break;
            case 'booking':
                switch ($status) {
                    case '0':
                        return 'text-primary';
                        break;
                    case '1':
                        return 'text-success';
                        break;
                    case '2':
                        return 'text-danger';
                        break;
                    case '3':
                        return 'text-secondary';
                        break;
                    case '4':
                        return 'text-white';
                        break;
                }
                return 'text-warning';
                break;
            case 'notification':
                break;
        }
        
        return 'Unknown';
    }
}

if (!function_exists('getEquipmentById')) {
    function getEquipmentById($id)
    {
        $equipment = Equipment::with('category')->find($id);

        if (!empty($equipment)) {
            $equipment->getStatusName();
        }

        return $equipment;
    }
}

if (!function_exists('getAllBookings')) {
    function getAllBookings()
    {
        $bookings = Booking::with(['user', 'equipment', 'staff_user'])
                            ->orderBy('booking_date', 'desc')
                            ->orderBy('id', 'desc')
                            ->get()
                            ->all();
        return $bookings;
    }
}

if (!function_exists('getBookingById')) {
    function getBookingById($id)
    {
        $booking = Booking::with(['user', 'equipment', 'staff_user'])->find($id);

        if (!empty($booking)) {
            $booking->getStatusName();
        }

        return $booking;
    }
}

if (!function_exists('getBookingsByUser')) {
    function getBookingsByUser($booking_user)
    {
        $bookings = Booking::with(['user', 'equipment', 'staff_user'])
                            ->where('booking_user', $booking_user)
                            ->orderBy('booking_date', 'desc')
                            ->orderBy('id', 'desc')
                            ->get()
                            ->all();
        return $bookings;
    }
}

