<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;

class ScrabingController extends Controller
{
    public function scrab(Request $request)
    {
        $tr = "";
        $htmlgen = "";
        $ignoreFirst = true;
        if ($request->has('url')) {
            $client = new Client();
            $crawler = $client->request('GET', $request->url);
            $head_text = $crawler->filter('h1')->text();

            $data = $crawler->filter('figure>table>tbody>tr')->each(function ($row) {
                return $row->filter('td')->each(function ($cell) {
                    return $cell->text();
                });
            });
            // return response()->json($data);
            foreach ($data as $key => $item) {
                if ($ignoreFirst) {
                    $ignoreFirst = false;
                    continue;
                }
                if ($item) {
                    $tr .= '<tr>
                            <td>' . (isset($item[0]) ? $item[0] : "") . '</td>
                            <td>' . (isset($item[1]) ? $item[1] : "") . '</td>
                        </tr>';
                }
            }

            $htmlgen =  $this->htmlGen($tr, $head_text);
        }
        return view('welcome', compact('htmlgen'));
    }

    public function htmlGen($tr, $head_text)
    {
        return '
        <h2 class="h1 text-lowercase"><strong>' . $head_text . ' price in bd</strong></h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    ' . $tr . '
                </tbody>
            </table>
        </div>';
    }
}
