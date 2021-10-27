<?php
namespace App\Repositories;
use App\Models\History;
use App\Models\Sheep;
use App\Repositories\PaddockRespositoryInterface;
use Illuminate\Http\Request;


class PaddockRepository implements PaddockRespositoryInterface
{
    public function index(){
        if (Sheep::count() === 0) {
            # распределение
            $sheeps = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
            $paddocks = array(
                1 => array(),
                2 => array(),
                3 => array(),
                4 => array()
            );
            for ($i = 0; $i < sizeof($sheeps); $i++) {
                $paddock = random_int(1, 4);
                if ($i > 6) {
                    foreach ($paddocks as $paddock_no => $paddock_sheeps) {
                        if (sizeof($paddock_sheeps) === 0) {
                            $paddock = $paddock_no;
                            break;
                        }
                    }
                }
                $paddocks[$paddock][] = $sheeps[$i];
            }

            # min, max i sozdanie
            $paddock_max_no = 1;
            $paddock_max = 0;

            $paddock_min_no = 1;
            $paddock_min = 10;
            foreach ($paddocks as $paddock_no => $paddock_sheeps) {
                if (sizeof($paddock_sheeps) > $paddock_max) {
                    $paddock_max = sizeof($paddock_sheeps);
                    $paddock_max_no = $paddock_no;
                }
                if (sizeof($paddock_sheeps) < $paddock_min) {
                    $paddock_min = sizeof($paddock_sheeps);
                    $paddock_min_no = $paddock_no;
                }
                foreach ($paddock_sheeps as $sh) {
                    $sheep = new Sheep;
                    $sheep->name = "Sheep " . strval($sh);
                    $sheep->paddock = $paddock_no;
                    $sheep->save();
                }
            }

            # create history
            $history = new History;
            $history->day = 1;
            $history->total = 10;
            $history->dead = 0;
            $history->alive = 10;
            $history->paddock_max = $paddock_max_no;
            $history->paddock_min = $paddock_min_no;
            $history->save();
        }
        return Sheep::where('is_active', true)->get();
    }
    public function getLastSheepNo(){
        $no = 0;
        foreach (Sheep::where('is_active', true)->get() as $sheep) {
            $sheep_no = intval(explode(" ", $sheep->name)[1]);
            if ($sheep_no > $no) {
                $no = $sheep_no;
            }
        }
        return $no;
    }


}


?>
