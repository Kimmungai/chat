<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\users;
use App\User;
use App\Order;
use App\BidCompany;
use App\Bid;
use Auth;
use App\Mail\EmailVerification;
use Mail;
use Session;
use DB;

class users extends Controller
{
    public function index()
    {
      $client_data=Order::paginate(10);
      $count=0;
      foreach($client_data as $client_datum)
      {
        $price_agreed[$count]=BidCompany::where('order_id','=',$client_datum['id'])->where('price_agreed','<>','')->value('price_agreed');
        $num_companies_bidding[$count]=count(BidCompany::with('Bid')->where('order_id','=',$client_datum['id'])->get());
        $company_id=$client_datum['user_id'];
        $company_name[$count]=User::where('id','=',$company_id)->value('company_name');
        $count++;
      }
      if(Auth::user())
      {
        if((Auth::user()->user_category)==0)
        {
          return view('company-top', compact('client_data','price_agreed','num_companies_bidding','company_name'));//company data
        }
        else if((Auth::user()->user_category)==1)
        {
          return view('client-top', compact('client_data','price_agreed','company_name'));
        }
      }
      return view('client-top', compact('client_data','price_agreed','company_name'));
      //return $client_data;
    }
    public function client_order($order_id)
    {
        $user_id = Auth::id();
        $client_order=Order::with(['BidCompany','Bid'])->where('bid_status','<>',2)->where('id','=',$order_id)->get();
        return view('client_order_view', compact('client_order'));
    }
    public function client_orders_all()
    {
        $user_id = Auth::id();
        $all_user_orders=Order::with(['BidCompany','Bid'])->where('bid_status','<>',2)->orderBy('id','Desc')->get();
        return view('client_order_view_all',compact('all_user_orders'));
    }
    public function open_client_bids()
    {
      $user_id = Auth::id();
      $all_user_orders=Order::with(['BidCompany','Bid'])->where('user_id', '=', $user_id)->where('bid_status','=',0)->where('bid_status','<>',2)->orderBy('id','Desc')->get();
      return view('client_order_view_all',compact('all_user_orders'));
    }
    public function closed_client_bids()
    {
      $user_id = Auth::id();
      $all_user_orders=Order::with(['BidCompany','Bid'])->where('user_id', '=', $user_id)->where('bid_status','=',1)->where('bid_status','<>',2)->orderBy('id','Desc')->get();
      return view('client_order_view_all',compact('all_user_orders'));
    }
    public function set_pass(Request $request)
    {
        $user=new User;
        if($request->input('password')==$request->input('password_check'))
        {
          $user_id = Auth::id();
          $updated_password=bcrypt($request->input('password'));
          DB::table('users')->where('id', $user_id)->update(['password' => $updated_password]);
          return redirect('/');
        }
        else
        {
          return back();
        }
    }
    public function client_myapage()
    {
      $user_details=Auth::user();
      return view('user-info',compact('user_details'));
    }
    public function update_reg_details(Request $request)
    {
      $user_id=Auth::id();
      User::where('id','=',$user_id)->update([
        'company_name'=>$request->input('company_name'),
        'company_name_furigana'=>$request->input('company_name_furigana'),
        'last_name'=>$request->input('last_name'),
        'first_name'=>$request->input('first_name'),
        'last_name_furigana'=>$request->input('last_name_furigana'),
        'first_name_furigana'=>$request->input('first_name_furigana'),
        'address'=>$request->input('address')
      ]);
      Session::flash('user_updates_reg_details', 'Saved!');
      return back();
    }
    public function cancel_order($order_id)
    {
      Order::where('id','=',$order_id)->update(['bid_status' => 2]);
      return $this->client_orders_all();
    }
    public function choose_company(Request $request)
    {
      Order::where('id','=',$request->input('order'))->update(['bid_status' => 1]);
      $bid=Bid::where('id','=',$request->input('bid'))->get();
      BidCompany::where('id','=',$bid[0]['bid_company_id'])->update([
        'price_agreed'=>$bid[0]['price']
      ]);
      Session::flash('order_closed', 'Your order has been closed, check your email!');//need a jap message
      $client_email=Auth::user()->email;
      $company_id=BidCompany::where('id','=',$bid[0]['bid_company_id'])->value('user_id');
      $company_email=User::where('id','=',$company_id)->value('email');
      $client_message='Your order has been closed';
      $company_message='You have been chosen!';
      mail($client_email,'Order Confirmed',$client_message);
      mail($company_email,'Bid Accepted',$company_message);

      return back();
    }
    /*public function update_password(Request $request) //mig,ht be having a bug when hashing password
    {

      if ($request->input('password')=='' || $request->input('password_check')=='')
      {
        Session::flash('password_mismatch', 'Fill both password fields!');
      }
      elseif($request->input('password') !== $request->input('password_check'))
      {
        Session::flash('password_mismatch', 'Passwords did not match!');
      }
      elseif($request->input('password')==$request->input('password_check'))
      {
        $user_id=Auth::id();
        User::where('id','=',$user_id)->update([
          'password'=>bcrypt($request->input('company_name'))
        ]);
        Session::flash('password_mismatch', 'Passwords changed');
      }
      return back();
    }*/
    public function create(Request $request)
    {
        $new_user=new User;
        $email_token=str_random(10);
        $new_user->user_category=$request->input('cars');
        $new_user->company_name=$request->input('hire_comp');
        $new_user->company_name_furigana=$request->input('hire_comp_fu');
        $new_user->first_name=$request->input('hire_first_name');
        $new_user->first_name_furigana=$request->input('hire_first_name_fu');
        $new_user->last_name=$request->input('hire_last_name');
        $new_user->last_name_furigana=$request->input('hire_last_name_fu');
        $new_user->address=$request->input('hire_address');
        $new_user->tel=$request->input('hire_tel');
        $new_user->email=$request->input('hire_email');
        if($request->input('hire_email')===$request->input('hire_email_check'))//ensure emails MongoDeleteBatch
        {
          $new_user->email_token=$email_token;
          $email = new EmailVerification(new User(['email_token' => $email_token, 'hire_first_name' => $request->input('hire_first_name')]));
          Mail::to($request->input('hire_email'))->send($email);
          Session::flash('message', 'We have sent you a verification email!');
          $new_user->save();
          return back();
        }
        else
        {
           return back()->withInput();
        }
    }
}
