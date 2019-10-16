<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use File;

class PopularTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tags:popular';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arr_for_db = [];
        $results = DB::select('select * from new_catalog');
        foreach($results as $db) {
            print_r($db->title);
            print_r("\r\n");
        }
        foreach($results as $db) {
            array_push($arr_for_db, $db->title);
        }

        $all_rows = [];
        $file_name = [];
        $files_path = glob(public_path().'/se-*.txt');
        $i=count($files_path);
        foreach ($files_path as $fp) {
           array_push($file_name, basename($fp));
        }
        $create = count($file_name)+1;
        foreach ($file_name as $fn) {
          //исходник - превращаем в массив сторк
          $rfile = File::get(public_path()."/".$fn);
          $fl = preg_split("/\r\n|\n|\r/", $rfile);
          $all_rows = array_merge($all_rows, $fl);
          //кол-во строк в файле
          print_r("----кол-во строк в данном-----");
          print_r(count($fl));
          print_r("\r\n");
          print_r($arr_for_db);
          print_r("\r\n");
        }
        $all_rows = array_merge($all_rows, $arr_for_db);

        print_r("----Всего строк в файлах -----");
        $files_path = glob(public_path().'/pop_reqs.txt');
        //исходник - превращаем в массив сторк
        $rfile = File::get(public_path().'/pop_reqs.txt');
        $fl = preg_split("/\r\n|\n|\r/", $rfile);

        print_r(count($all_rows));
        print_r("\r\n");
        print_r($fl);
        print_r("\r\n");


    }
}
