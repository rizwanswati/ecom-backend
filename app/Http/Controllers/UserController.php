<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    public function registerUsers(Request $request)
    {
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->save();
        return $user;
    }

    public function logIn(Request $request)
    {
        $creds = $request->all();
        $data = array('id' => -1);
        if (DB::table('users')->where('email', '=', $creds['email'])->exists()) {
            $userRecord = DB::table('users')->where('email', '=', $creds['email'])->first();
            if ($userRecord->password == $creds['password']) {
                $data = array();
                $data = $userRecord;
            }
        }
        return $data;
    }

    public function getAllProducts(){
       return Products::all();
    }

    public function addProduct(Request $request)
    {
        $products = new Products;

        $filename = $request->file('prod_image')->getClientOriginalName();
        $path = $request->file('prod_image')->storeAs('/public', $filename);

        $products->name = $request->input('name');
        $products->price = $request->input('price');
        $products->description = $request->input('description');
        $products->img = $filename;
        $products->save();

        return response($path,200);
    }

    private function printData($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}
