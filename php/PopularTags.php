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
      function cyrillic($string) {
         $converter = array(
         'а' => 'a',   'б' => 'b',   'в' => 'v',
         'г' => 'g',   'д' => 'd',   'е' => 'e',
         'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
         'и' => 'i',   'й' => 'y',   'к' => 'k',
         'л' => 'l',   'м' => 'm',   'н' => 'n',
         'о' => 'o',   'п' => 'p',   'р' => 'r',
         'с' => 's',   'т' => 't',   'у' => 'u',
         'ф' => 'f',   'х' => 'h',   'ц' => 'c',
         'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
         'ь' => '',    'ы' => 'y',   'ъ' => '',
         'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
         'і' => 'i',   'ї' => 'yi',  'ґ' => 'g',
         'є' => 'ye',

         'А' => 'A',   'Б' => 'B',   'В' => 'V',
         'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
         'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
         'И' => 'I',   'Й' => 'Y',   'К' => 'K',
         'Л' => 'L',   'М' => 'M',   'Н' => 'N',
         'О' => 'O',   'П' => 'P',   'Р' => 'R',
         'С' => 'S',   'Т' => 'T',   'У' => 'U',
         'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
         'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
         'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
         'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
         'І' => 'I',   'Ї' => 'Yi',  'Ґ' => 'G',
         'Є' => 'Ye'
        );
             $string = preg_replace('/-{1,}/', ' ', $string);
             $string = preg_replace('/[^\p{L}0-9 ]/iu', '', $string);
             $string = strtr($string, $converter);
             return mb_strtolower(preg_replace('/ {1,}/', '-', $string));
         }

        //categories form db to array
        $arr_for_db = [];
        $results = DB::select('select * from new_catalog');

        //create links from categories
        foreach($results as $db) {
            array_push($arr_for_db, "https://ua-tao.com/".cyrillic($db->title)."_s");
        }

        $all_rows = [];
        $file_name = [];

        //get list of all files in array
        $files_path = glob(public_path().'/se-*.txt');
        foreach ($files_path as $fp) {
           array_push($file_name, basename($fp));
        }

        //tags from all files to one array
        foreach ($file_name as $fn) {
          $rfile = File::get(public_path()."/".$fn);
          $fl = preg_split("/\r\n|\n|\r/", $rfile);
          $all_rows = array_merge($all_rows, $fl);
        }

        //merge tags from files and DB
        $all_rows = array_merge($all_rows, $arr_for_db);

        //tags from popular.txt
        $popular = [];
        $rfile = File::get(public_path().'/pop_reqs.txt');
        $fl = preg_split("/\r\n|\n|\r/", $rfile);

        //create links from popular tags
        foreach($fl as $tag) {
            array_push($popular, "https://ua-tao.com/".cyrillic($tag)."_s");
        }

        //array with unique links(tags)
        $result = array_diff($popular, $all_rows);

        //append links(tags) to txt file
        foreach($result as $res) {
        File::append(public_path()."/new-se-1.txt",$res.PHP_EOL);
      }
    }
}
