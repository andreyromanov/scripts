<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use File;

class ImportTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tags:import';

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
        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
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
        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        'І' => 'I',   'Ї' => 'Yi',  'Ґ' => 'G',
        'Є' => 'Ye'
    );
            $string = preg_replace('/-{1,}/', ' ', $string);
            $string = preg_replace('/[^\p{L}0-9 ]/iu', '', $string);
            $string = strtr($string, $converter);
            return mb_strtolower(preg_replace('/ {1,}/', '-', $string));
        }

        //работа с файлами
        $lim1 = 0;
        $lim2 = 500;
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
        }
while (count($results = DB::select('select text from search_history LIMIT '.$lim1.','.$lim2.''))!=0) {
        //массив для проверенных значений
        $array3 = [];
        //строки из базы -новое
        $arr_for_db = [];
        $results = DB::select('select text from search_history LIMIT '.$lim1.','.$lim2.'');
        foreach($results as $db) {
            array_push($arr_for_db, $db->text);
        }
        //проверка массива из БД
        foreach($arr_for_db as $arr2) {
            if ((filter_var($arr2, FILTER_VALIDATE_URL) === FALSE) and (count(preg_split('/[^0-9a-zA-Zа-яёА-ЯЁ]+/u', $arr2))<6) and(!preg_match("/\p{Han}+/u", $arr2))  and(!preg_match("/[^0-9a-zA-Zа-яёґєіїА-ЯЁҐЄІЇ ]/u", $arr2))) {
                //массив с проверенными ссылками
                array_push($array3, "https://ua-tao.com/".cyrillic($arr2)."_s");
            } else {}
        }
        //разница
        $result = array_diff($array3, $all_rows);

foreach($result as $res) {
$files_path = glob(public_path().'/se-*.txt');
        foreach ($files_path as $fp) {
           array_push($file_name, basename($fp));
        }
     foreach ($file_name as $fn) {
     //исходник - превращаем в массив сторк
        $rfile = File::get(public_path()."/se-".$i.".txt");
        $fl = preg_split("/\r\n|\n|\r/", $rfile);
        if(count($fl)<=40000){
        //кол-во строк в файле 
        File::append(public_path()."/se-".$i.".txt",$res.PHP_EOL);
        break;
        } else{
        $i++;
        File::append(public_path()."/se-".$i.".txt",$res.PHP_EOL);
        break;
    }
    
   }
  }
$lim1+=500;
     }
  }
}