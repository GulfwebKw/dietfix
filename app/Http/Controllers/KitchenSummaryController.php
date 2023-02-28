<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class KitchenSummaryController extends MainController
{
    public function getGetPreparation()
    {
        if ($this->notKitchen()) {
            return $this->dontAllow();
        }
        $orders = $this->get_current_orders();

        $mealsRefined["meals"] = [];

        if(!$orders->isEmpty()) {
            $meals = [];
            foreach ($orders as $order) {

                $mealsRefined["meals"][$order->meal->{'title'.LANG}] = [] ;
                //echo empty($order->meal->{'title'.LANG});

                $meals[$order->meal_id]['meal'] = $order->meal->{'title'.LANG};
                $key = $order->item_id.'_'.$order->portion_id.'_'.$order->user->salt;
                foreach ($order->addons as $addon) {
                    $key .= '_'.$addon->id;
                }
                $meals[$order->meal_id]['orders'][$key]['title'] = $order->category->{'title'.LANG} . ' ' .$order->item->{'title'.LANG};
                $meals[$order->meal_id]['orders'][$key]['portion'] = ($order->portion) ? $order->portion->{'title'.LANG} : false;
                $addons = '';
                foreach ($order->addons as $addon) {
                    $addons .= ' ' . $addon->{'title'.LANG};
                }
                $meals[$order->meal_id]['orders'][$key]['addons'] = $addons;
                $meals[$order->meal_id]['orders'][$key]['salt'] = $order->user->salt;
                @$meals[$order->meal_id]['orders'][$key]['qty']++;
                $meals[$order->meal_id]['orders'][$key]['no_salt_local']= '';
                $meals[$order->meal_id]['orders'][$key]['no_salt']= '';
                $meals[$order->meal_id]['orders'][$key]['medium_salt_local']= '';
                $meals[$order->meal_id]['orders'][$key]['medium_salt']= '';

                $meals[$order->meal_id]['orders'][$key]['no_salt_local_addon']= '';
                $meals[$order->meal_id]['orders'][$key]['no_salt_addon']= '';
                $meals[$order->meal_id]['orders'][$key]['medium_salt_local_addon']= '';
                $meals[$order->meal_id]['orders'][$key]['medium_salt_addon']= '';


                if($order->user->salt === "Medium Salt")
                {
                    $meals[$order->meal_id]['orders'][$key]['medium_salt']=  $meals[$order->meal_id]['orders'][$key]['qty'];
                    //$meals[$order->meal_id]['orders'][$key]['medium_salt_addon']= $addons;
                }
                else if($order->user->salt === "Medium Salt - Local")
                {
                    $meals[$order->meal_id]['orders'][$key]['medium_salt_local']=  $meals[$order->meal_id]['orders'][$key]['qty'];
                    //$meals[$order->meal_id]['orders'][$key]['medium_salt_local_addon']= $addons;
                }
                else if($order->user->salt === "No Salt - Local")
                {
                    $meals[$order->meal_id]['orders'][$key]['no_salt_local']=  $meals[$order->meal_id]['orders'][$key]['qty'];
                    //$meals[$order->meal_id]['orders'][$key]['no_salt_local_addon']= $addons;
                }
                else if($order->user->salt === "No Salt")
                {
                    $meals[$order->meal_id]['orders'][$key]['no_salt']=  $meals[$order->meal_id]['orders'][$key]['qty'];
                    //$meals[$order->meal_id]['orders'][$key]['no_salt_addon']= $addons;
                }

            }
        }


        // compose mealsRefined according to meal and item name
        $qtyArr = [];
        foreach($meals AS $meal)
        {

            $mealsRefined["meals"][$meal["meal"]]["orders"] = [];
            $saltTypeAddonsQtyArr[$meal["meal"]][] = [];
            foreach($meal["orders"] AS $key=>$value)
            {
                $qtyArr[trim($value['title'])][] = $value['qty'];
                $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["qty"] = array_sum($qtyArr[trim($value['title'])])+1;
                if(!empty(trim($value["addons"])))
                {
                    $saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])][trim($value["addons"])]["qty"][] = $value["qty"];
                    $saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons"][trim($value["addons"])] = $value["addons"];
                    $saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons_salt_total_qty"][trim($value["addons"])] = array_sum($saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])][trim($value["addons"])]["qty"]);

                }
                else
                {
                    $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_qty"][]= $value["qty"] ;
                    $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"] = array_sum($mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_qty"]);
                }

                if($value["salt"] === "No Salt - Local")
                {

                    if(isset($saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons_salt_total_qty"][trim($value["addons"])]))
                    {

                        $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["no_salt_local"] = $value["qty"];//1
                        if(isset($mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"]))
                        {

                            $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["no_salt_local"] = $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"];
                            $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["no_salt_local"] .= ', '. $saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons_salt_total_qty"][trim($value["addons"])].' '. $saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons"][trim($value["addons"])];

                        }
                    }
                    else
                    {
                        $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["no_salt_local"] = $value["qty"]; // 2

                        if(isset($mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"]))
                        {
                            $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["no_salt_local"] = $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"]; //3

                            if(isset($saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons"]))
                            {
                                foreach($saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons"] AS $addOnK=>$addOnV)
                                {
                                    $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["no_salt_local"] .= ', '. $saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons_salt_total_qty"][trim($addOnK)].' '. $addOnV;
                                }
                            }

                        }
                    }


                }

                if($value["salt"] === "No Salt")
                {

                    if(isset($saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons_salt_total_qty"][trim($value["addons"])]))
                    {

                        $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["no_salt"] = $value["qty"];//1
                        if(isset($mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"]))
                        {

                            $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["no_salt"] = $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"];
                            $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["no_salt"] .= ', '. $saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons_salt_total_qty"][trim($value["addons"])].' '. $saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons"][trim($value["addons"])];

                        }
                    }
                    else
                    {
                        $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["no_salt"] = $value["qty"]; // 2

                        if(isset($mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"]))
                        {
                            $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["no_salt"] = $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"]; //3

                            if(isset($saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons"]))
                            {
                                foreach($saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons"] AS $addOnK=>$addOnV)
                                {
                                    $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["no_salt"] .= ', '. $saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons_salt_total_qty"][trim($addOnK)].' '. $addOnV;
                                }
                            }

                        }
                    }


                }

                if($value["salt"] === "Medium Salt")
                {

                    if(isset($saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons_salt_total_qty"][trim($value["addons"])]))
                    {

                        $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["medium_salt"] = $value["qty"];//1
                        if(isset($mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"]))
                        {

                            $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["medium_salt"] = $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"];
                            $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["medium_salt"] .= ', '. $saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons_salt_total_qty"][trim($value["addons"])].' '. $saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons"][trim($value["addons"])];

                        }
                    }
                    else
                    {
                        $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["medium_salt"] = $value["qty"]; // 2

                        if(isset($mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"]))
                        {
                            $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["medium_salt"] = $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"]; //3

                            if(isset($saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons"]))
                            {
                                foreach($saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons"] AS $addOnK=>$addOnV)
                                {
                                    $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["medium_salt"] .= ', '. $saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons_salt_total_qty"][trim($addOnK)].' '. $addOnV;
                                }
                            }

                        }
                    }


                }

                if($value["salt"] === "Medium Salt - Local")
                {

                    if(isset($saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons_salt_total_qty"][trim($value["addons"])]))
                    {

                        $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["medium_salt_local"] = $value["qty"];//1
                        if(isset($mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"]))
                        {

                            $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["medium_salt_local"] = $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"];
                            $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["medium_salt_local"] .= ', '. $saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons_salt_total_qty"][trim($value["addons"])].' '. $saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons"][trim($value["addons"])];

                        }
                    }
                    else
                    {
                        $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["medium_salt_local"] = $value["qty"]; // 2

                        if(isset($mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"]))
                        {
                            $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["medium_salt_local"] = $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])][trim($value["salt"])."_total_qty"]; //3

                            if(isset($saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons"]))
                            {
                                foreach($saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons"] AS $addOnK=>$addOnV)
                                {
                                    $mealsRefined["meals"][$meal["meal"]]["orders"][trim($value["title"])]["medium_salt_local"] .= ', '. $saltTypeAddonsQtyArr[$meal["meal"]][trim($value["title"])."_".trim($value["salt"])]["addons_salt_total_qty"][trim($addOnK)].' '. $addOnV;
                                }
                            }

                        }
                    }


                }


            }
        }

        return View::make('kitchen.preparation_summary')
            ->with('orders',$mealsRefined)
            ->with('type','preparation')
            ->withTitle(trans('main.Preparation Summary'));
    }
}
