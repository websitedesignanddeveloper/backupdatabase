<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Storage; 
use File;  
use DB;

class DBController extends Controller
{
	public function __construct(){
		 
	}
	
	public function load()
	{
		//load view 
		return view("dbmanage");
	}
	
	public function backupdb(Request $request)
	{ 
		//script to take db backup and save in csv format 
		//path - Storage/app/public/database_backup 
		
		$arrTables = array(); 
		$tables = DB::select('SHOW TABLES');
		foreach($tables as $table)
		{ 
			foreach ($table as $key => $value)
			{ 
 				$arrTables[] = $value;
			}
		} 
		
		$return = '';
		$return .= "-- \n";
		$return .= "-- Database: `".env('DB_DATABASE')."` \n";
		$return .= "-- \n\n";
		 	
		$return .= "-- -------------------------------------------------------- \n\n";
		 
		$return .= 'SET FOREIGN_KEY_CHECKS = 0;' . "\r\n";
		$return .= 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";' . "\r\n";
		$return .= 'SET AUTOCOMMIT = 0;' . "\r\n";
		$return .= 'START TRANSACTION;' . "\r\n";
		  
		foreach($arrTables as $table) 
		{
 			$arrResult = DB::table($table)->get();
 			$num_fields = count($arrResult); 
			
			
			$return .= "-- \n";
			$return .= "-- Table structure for table `".$table."` \n";
			$return .= "-- \n\n";
 
			$return .= 'DROP TABLE IF EXISTS '.$table.';'; 
			
			$row2 = DB::select("SHOW CREATE TABLE ".$table);
			 
			foreach($row2 as $statement)
			{  
				foreach($statement as $k1=>$v1)
				{
					if($k1=="Create Table")
					{ 
						$return .= "\n\n".$v1.";\n\n";
					}
				}				
			}   
			
			foreach($arrResult as $k2=>$v2)
			{ 			 
				$i = 0;  
				
				$keys = $values = ''; 
				foreach($v2 as $k3=>$v3) 
				{    
					$i++;
					
					if($i!=1){
						$keys .= ',';  
						$values .= ',';  
					}
					
					if(isset($k3)) 
					{
						$keys .= '`'.$k3.'`';
					} 
					else 
					{ 
						$keys .= '';
					} 
					
					
					if(isset($v3)) 
					{
						$values .= '"'.$v3.'"';
					} 
					else 
					{ 
						$values .= '""';
					}   
				} 
				
				$return .= 'INSERT INTO '.$table.' ('.$keys.') VALUES('.$values;  
				 
				$return .= ");\n"; 
			}
			$return .= "\n\n\n"; 
		}
		
		// enable foreign keys
        $return .= 'SET FOREIGN_KEY_CHECKS = 1;' . "\r\n";
        $return.= 'COMMIT;';
		 
		//Save file to storage folder
		$extension = "sql"; 
        $filename = uniqid() . '_' . time() . '.' . $extension;
		$target_path = 'database_backup/'.$filename; 
	
		Storage::disk('public')->put($target_path, $return); 
		
		$array = array("success"=>1,"message"=>"Database backup done successfully."); 
		return $array; 
	}
}
