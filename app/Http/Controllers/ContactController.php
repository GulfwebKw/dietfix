<?php

namespace App\Http\Controllers;

use App\Models\Admin\AdminMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ContactController extends MainController
{
    public function getIndex()

    {

        return View::make('contact')
            ->withTitle(trans('main.Contact us'));

    }

    public function getTest()

    {
        return View::make('contact')
            ->withTitle(trans('main.Contact us'));

    }



    public function postIndex()

    {

        $validator = Validator::make(Input::all(), (
            array(
            'name' => 'required|min:2|max:100',
            'email' => 'required|min:2|max:100',
            'phone' => 'min:2|max:100',
            'user_message' => 'required',
            'g-recaptcha-response' => 'required|recaptcha',
        )
        ));

        if ($validator->fails()) {

            $messages = $validator->messages();

            return Redirect::to('contact')->withErrors($validator)->withInput();

        }



        $adminMessage = new AdminMessage;

        $adminMessage->messageFrom = Input::get('email');

        $adminMessage->messageTitle = 'رسالة من الموقع';

        $adminMessage->messageContent = 'المرسل ';

        $adminMessage->messageContent .= Input::get('name');

        $adminMessage->messageContent .= '<br />';

        $adminMessage->messageContent .= 'الهاتف ';

        $adminMessage->messageContent .= Input::get('phone');

        $adminMessage->messageContent .= '<br />';

        $adminMessage->messageContent .= 'الرسالة ';

        $adminMessage->messageContent .= Input::get('user_message');

        $adminMessage->readed = 0;

        $data['theMessage'] = Input::all();



        $adminMessage->save();



        $data['settings'] = $this->_setting;


//
//        Mail::send('emails.admin_message', $data, function($message) use ($data)
//
//        {
//
//            $message
//
//                ->from(Input::get('email'), Input::get('name'))
//
//                ->to(config('settings.adminEmail.value'), config('settings.siteNameAr.value'))
//
//                ->subject('رسالة من موقعك');
//
//        });

        return view('contact',['messages'=>[trans('main.Message Sent.. Thank You')],'title'=>trans('main.Contact us')]);

    }


    public function getRand()
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 2; $i++)
        {
            $img = $faker->image($dir = public_path('media/sections'), $width = 800, $height = 600, 'technics');
            $img = str_replace(public_path('media/sections/'), '', $img);
            $section = Section::create(array(
                'titleAr' => $faker->sentence($nbWords = rand(2,4)),
                'titleEn' => $faker->sentence($nbWords = rand(2,4)),
                'img' => $img,
                'ordering' => $faker->randomNumber(2),
                'active' => 1,
            ));
            $section_id = $section->id;

            echo "Section Added $section->titleEn \n";

            for ($x=0; $x < 2; $x++) {
                $img2 = $faker->image($dir = public_path('media/categories'), $width = 800, $height = 600);
                $img2 = str_replace(public_path('media/categories/'), '', $img2);
                $category = Category::create(array(
                    'titleAr' => $faker->sentence($nbWords = rand(2,4)),
                    'titleEn' => $faker->sentence($nbWords = rand(2,4)),
                    'img' => $img,
                    'ordering' => $faker->randomNumber(2),
                    'section_id' => $section_id,
                    'active' => 1,
                ));
                $category_id = $category->id;

                echo "Category Added $category->titleEn \n";

                for ($z=0; $z < 3; $z++) {
                    $img3 = $faker->image($dir = public_path('media/shops'), $width = 800, $height = 600);
                    $img3 = str_replace(public_path('media/shops/'), '', $img3);
                    $shop = Shop::create(array(
                        'titleAr' => $faker->sentence($nbWords = rand(2,4)),
                        'titleEn' => $faker->sentence($nbWords = rand(2,4)),
                        'detailsAr' => $faker->sentence($nbWords = rand(20,30)),
                        'detailsEn' => $faker->sentence($nbWords = rand(20,30)),
                        'notesAr' => $faker->sentence($nbWords = rand(20,30)),
                        'notesEn' => $faker->sentence($nbWords = rand(20,30)),
                        'photo' => $img3,
                        'phone' => $faker->randomNumber(8),
                        'views' => $faker->randomNumber(2),
                        'ordering' => $faker->randomNumber(2),
                        'active' => 1,
                        'published_at' => date('Y-m-d H:i:s',strtotime('-1 day')),
                        'expire_at' => date('Y-m-d H:i:s',strtotime('+2 months')),
                    ));
                    $shop_id = $shop->id;

                    echo "Shop Added $shop->titleEn \n";

                    ShopCategory::create([
                        'shop_id' => $shop_id,
                        'category_id' => $category_id,
                    ]);
                }

            }

        }
        // dd($faker->sentence($nbWords = rand(2,4)));
    }

    public function getProd()
    {
        $faker = Faker\Factory::create();

        $shops = Shop::all();
        foreach ($shops as $shop) {
            $shop_id = $shop->id;
            for ($i = 0; $i < 3; $i++)
            {
                $product = Product::create(array(
                    'titleAr' => $faker->sentence($nbWords = rand(2,4)),
                    'titleEn' => $faker->sentence($nbWords = rand(2,4)),
                    'detailsAr' => $faker->sentence($nbWords = rand(20,30)),
                    'detailsEn' => $faker->sentence($nbWords = rand(20,30)),
                    'price' => $faker->randomNumber(2),
                    'views' => $faker->randomNumber(2),
                    'shop_id' => $shop_id,
                    'ordering' => $faker->randomNumber(2),
                    'available' => 1,
                    'active' => 1,
                ));
                $product_id = $product->id;

                for ($x=0; $x < 2; $x++) {
                    $img = $faker->image($dir = public_path('media/products'), $width = 800, $height = 600, 'technics');
                    $img = str_replace(public_path('media/products/'), '', $img);
                    $photo = ProductPhoto::create([
                        'product_id' => $product_id,
                        'photo' => $img,
                    ]);
                }
            }
        }
        // dd($faker->sentence($nbWords = rand(2,4)));
    }

}
