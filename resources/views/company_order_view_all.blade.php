@extends('layouts.hire_company')

@section('content')
<ol class="breadcrumb">
    <li><a href="/">トップ</a></li>
    <li class="current"><a href="#">依頼履歴一覧</a></li>
</ol>

<ol class="filter">
    <li><a href="open-bids">未確定のみ</a></li>
    <li><a href="closed-bids">確定のみ</a></li>
    <li class="current"><a href="company_order_view_all">全部</a></li>
</ol>

@foreach (collect($orders)->reverse() as $order)
<div class="order-card"> <!-- start order card -->
<div class="row">
    <div class="half">
    <span class="date"><i class="fa fa-clock-o" aria-hidden="true"></i> {{$order['created_at']->format('m/d/Y')}}</span>@if((time()-strtotime($order['created_at'])) < 172800)<span class="new">新規</span>@endif
    <p><strong>注文名：</strong><span>{{$order['order_name']}}</span></p>
    <p><strong>利用希望台数：</strong><span>{{$order['num_of_cars']}}</span></p>
    <p><strong>希望車種：</strong><span>{{$order['car_type']}}</span></p>
    <p><strong>状態：<span>未確定</span></strong></p>
    </div>
    <div class="half">
            <p class="hrs">利用時間：{{number_format((time()-strtotime($order['created_at']))/3600)}}時間</p><!--number of hours since bidding-->
            <p><small>From:</small><br>
                <strong>お迎えの場所:</strong><br>
                {{$order['pick_up_address']}}
            </p>
            <p><small>To:</small><br>
                <strong>お送り先の場所:</strong><br>
                {{$order['drop_off_address']}}
            </p>
    </div>
</div>
<hr>
<div class="row">
    <div class="half">
        <form id="bid"  action="/bid" method="POST">
            {{ csrf_field() }}
            <p>御社提供の金額:</p>
            <input type="hidden" name="order-num" value="{{$order['id']}}" />
            <input type="number" name="bid-price" id="bid-price">
            <input type="hidden" name="bid-message" value="" /><button class="submit active">設定する</button>
        </form>
    </div>
    <div class="quarter">
        <div class="avg">
            <p><small>提供した会社数：</small></p>
            <p class="avg-num">{{count($order['BidCompany'])}}</p>
        </div>
    </div>
    <div class="quarter">
        <div class="avg">
            <p><small>現在平均金額：</small></p>
            <p class="avg-num">
              <?php  $summed_bid_price=0;
              foreach($order['bid'] as $bid_price){ $summed_bid_price += doubleval($bid_price['price']);}
              $number_of_bids=count($order['bid']);if($number_of_bids==0){$number_of_bids=1;}
              $average_bid_price=$summed_bid_price/$number_of_bids;?>
              ¥{{number_format($average_bid_price,2)}}
            </p>
        </div>
    </div>
</div>
<div class="more"><a href="/company_order_view/{{$order['id']}}"><i class="fa fa-info-circle" aria-hidden="true"></i> 詳しく見る ></a></div>
</div><!-- order card finish -->
@endforeach

<script>
$(document).ready(function(){
  $("#bid").submit(function(event){
    event.preventDefault();
    var price=$("#bid-price").val();
    window.open('/bid-with-comment/{{$order["id"]}}/'+price,'_self');
  });
});
</script>

</div>
@endsection
