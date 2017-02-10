<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Storage;

class ProductController extends Controller
{
    public function index() 
    {
    	$list = [];
    	if (Storage::has('products.json')) {
    		$contents = Storage::get('products.json');
    		$list = json_decode($contents, true);
    	}
    	
    	return view('product.index')->with('list', $list);
    }
    
    public function saveProduct(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
        	// fail
        }
        
        $name = $request->input('name');
        $id = $request->input('id', 0);
        $quantity = $request->input('quantity', 0);
        $price = $request->input('price', 0);
        $date = date('Y-m-d H:i:s');
        
        // save to json
        $list = [];
        if (Storage::has('products.json')) {
        	$contents = Storage::get('products.json');
        	$list = json_decode($contents, true);
        }
        
        $key = -1;
        if ($id != 0) {
        	foreach ($list as $k => $t) {
        		if ($t['id'] == $id) {
        			$key = $k;
        			break;
        		}
        	}
        }
        
        if ($key > -1) {
        	$list[$key] = [
        		'id' => $id,
        		'name' => $name,
        		'quantity' => $quantity,
        		'price' => $price,
        		'date' => $date,
        	];
        }
        else {
        	if (end($list))
        		$id = end($list) ['id'] + 1;
        	else 
        		$id = 1;
        	$list[] = [
        		'id' => $id,
        		'name' => $name,
        		'quantity' => $quantity,
        		'price' => $price,
        		'date' => $date,
        	];
        }
        
        $total = 0;
        foreach ($list as $t) {
        	$total += $t['quantity'] * $t['price'];
        }
                
        Storage::put('products.json', json_encode($list));
        
        return [
        	'res' => 'success',
        	'id' => $id,
        	'name' => $name,
        	'quantity' => $quantity,
        	'price' => $price,
        	'date' => $date,
        	'total' => $total,
        ];
    }
}
