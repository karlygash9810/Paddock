<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Sheep;
use Illuminate\Http\Request;

class PaddockController extends Controller
{
    /*public $paddokRepository;
    public function __construct(PaddockRespositoryInterface $paddokRepository){
        $this->paddokRepository = $paddokRepository;
    }*/
    public function index()
    {
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

    public function getLastSheepNo() {
        $no = 0;
        foreach (Sheep::where('is_active', true)->get() as $sheep) {
            $sheep_no = intval(explode(" ", $sheep->name)[1]);
            if ($sheep_no > $no) {
                $no = $sheep_no;
            }
        }
        return $no;
    }

    public function update(Request $request) {
        # get paddocks info
        $paddocks = array(
            1 => array(),
            2 => array(),
            3 => array(),
            4 => array()
        );
        foreach (Sheep::where('is_active', true)->get() as $sheep) {
            $paddocks[$sheep->paddock][] = $sheep->name;
        }


        # logic 1
        $total = 0;
        # min, max i sozdanie
        $paddock_max_no = 1;
        $paddock_max = 0;

        $paddock_min_no = 1;
        $paddock_min = 10;
        foreach ($paddocks as $paddock_no => $paddock_sheeps) {
            if (sizeof($paddock_sheeps) > 1) {
                $sheep_no = $this->getLastSheepNo() + 1;

                $sheep = new Sheep;
                $sheep->name = "Sheep " . strval($sheep_no);
                $sheep->paddock = $paddock_no;
                $sheep->save();

                $paddocks[$paddock_no][] = "Sheep " . strval($sheep_no);
            }
            $total += sizeof($paddocks[$paddock_no]);
            if (sizeof($paddocks[$paddock_no]) > $paddock_max) {
                $paddock_max = sizeof($paddocks[$paddock_no]);
                $paddock_max_no = $paddock_no;
            }
            if (sizeof($paddocks[$paddock_no]) < $paddock_min) {
                $paddock_min = sizeof($paddocks[$paddock_no]);
                $paddock_min_no = $paddock_no;
            }
        }

        $history_elem = History::orderBy('created_at', 'desc')->first();

        if (($history_elem->day + 1) % 10 == 0) {
            # get all sheeps where paddockSheep count is bigger than 1
            $sheep_list = [];
            foreach ($paddocks as $paddock_no => $paddock_sheeps) {
                if (sizeof($paddock_sheeps) > 1) {
                    $sheep_list = array_merge($sheep_list, $paddock_sheeps);
                }
            }

            # select && make inactive
            $random_sheep_index = random_int(0, sizeof($sheep_list) - 1);
            $selected_sheep = Sheep::where('name', $sheep_list[$random_sheep_index])->first();
            $selected_sheep->is_active = false;
            $selected_sheep->save();

            # Убрать овечку
            foreach ($paddocks as $paddock_no => $paddock_sheeps) {
                if (($index = array_search($sheep_list[$random_sheep_index], $paddock_sheeps)) !== false) {
                    unset($paddocks[$paddock_no][$index]);
                }
            }

            # replace logic
            $paddocks_with_one_sheep = [];
            foreach ($paddocks as $paddock_no => $paddock_sheeps) {
                if (sizeof($paddock_sheeps) == 1) {
                    $paddocks_with_one_sheep[] = $paddock_no;
                }
            }
            foreach($paddocks_with_one_sheep as $paddock_with_one_sheep) {
                $paddock_max = 0;
                $paddock_max_no = 0;
                foreach ($paddocks as $paddock_no => $paddock_sheeps) {
                    if ($paddock_no != $paddock_with_one_sheep) {
                        if (sizeof($paddock_sheeps) > $paddock_max) {
                            $paddock_max = sizeof($paddock_sheeps);
                            $paddock_max_no = $paddock_no;
                        }
                    }
                }

                $selected_sheep_index = random_int(0, sizeof($paddocks[$paddock_max_no]));

                # update in db
                $selected_sheep = Sheep::where(
                    'name',
                    $paddocks[$paddock_max_no][$selected_sheep_index]
                )->first();
                $selected_sheep->paddock = $paddock_with_one_sheep;
                $selected_sheep->save();

                # update in local structure
                $paddocks[$paddock_with_one_sheep][] = $paddocks[$paddock_max_no][$selected_sheep_index];
                unset($paddocks[$paddock_max_no][$selected_sheep_index]);

                # calculate statistics for history
                $total = 0;
                $paddock_max_no = 1;
                $paddock_max = 0;
                $paddock_min_no = 1;
                $paddock_min = 10000000;
                foreach ($paddocks as $paddock_no => $paddock_sheeps) {
                    $total += sizeof($paddock_sheeps);
                    if (sizeof($paddock_sheeps) > $paddock_max) {
                        $paddock_max = sizeof($paddock_sheeps);
                        $paddock_max_no = $paddock_no;
                    }
                    if (sizeof($paddock_sheeps) < $paddock_min) {
                        $paddock_min = sizeof($paddock_sheeps);
                        $paddock_min_no = $paddock_no;
                    }
                }
            }
            # create history
            $history = new History;
            $history->day = $history_elem->day + 1;
            $history->total = $total;
            $history->dead = 1;
            $history->alive = $total - 1;
            $history->paddock_max = $paddock_max_no;
            $history->paddock_min = $paddock_min_no;
            $history->save();
        } else {
            # create history
            $history = new History;
            $history->day = $history_elem->day + 1;
            $history->total = $total;
            $history->dead = 0;
            $history->alive = $total;
            $history->paddock_max = $paddock_max_no;
            $history->paddock_min = $paddock_min_no;
            $history->save();
        }

        return [];
    }
}
