<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function noticeBoxEdit()
    {
        return view('app.landing.noticeBox.edit');
    }

    public function noticeBoxUpdate(Request $request)
    {
        $myfile = fopen(base_path("resources/views/app/landing/noticeBox/content.blade.php"), "w") or die("Unable to open file!");
        $txt = $request['noticeBoxContent'];
//        $txt = "<div class='noticeBox'>".$request['noticeBoxContent']."</div>";
        fwrite($myfile, $txt);
        fclose($myfile);
//        return view('app.landing.noticeBox');
    }

    public function dmcaIndex()
    {
        return view('app.landing.dmca.index');
    }

    public function dmcaEdit()
    {
        return view('app.landing.dmca.edit');
    }

    public function dmcaUpdate(Request $request)
    {
        $myfile = fopen(base_path("resources/views/app/landing/dmca/content.blade.php"), "w") or die("Unable to open file!");
        $txt = $request['dmcaContent'];
//        $txt = "<div class='noticeBox'>".$request['noticeBoxContent']."</div>";
        fwrite($myfile, $txt);
        fclose($myfile);
//        return view('app.landing.noticeBox');
    }

    public function rulesIndex()
    {
        return view('app.landing.rules.index');
    }

    public function rulesEdit()
    {
        return view('app.landing.rules.edit');
    }

    public function rulesUpdate(Request $request)
    {
        $myfile = fopen(base_path("resources/views/app/landing/rules/content.blade.php"), "w") or die("Unable to open file!");
        $txt = $request['rulesContent'];
//        $txt = "<div class='noticeBox'>".$request['noticeBoxContent']."</div>";
        fwrite($myfile, $txt);
        fclose($myfile);
//        return view('app.landing.noticeBox');
    }
}
