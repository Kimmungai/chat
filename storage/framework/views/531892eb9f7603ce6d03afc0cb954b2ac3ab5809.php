<?php $__env->startSection('content'); ?>
<ol class="breadcrumb">
    <li><a href="/">トップ</a></li>
    <li class="current"><a href="#">新しいご注文</a></li>
</ol>
<form action="new_order" method="post">
  <?php echo e(csrf_field()); ?>

<div class="new-order">
    <div class="stripe">
        <label>注文名<span>必須</span>:</label>
        <input type="text" name="order_name" value="<?php echo e(old('order_name')); ?>" required>
    </div>
    <div class="stripe">
        <label>ご開始日時<span>必須</span>:</label>
        <input type="date" name="start_date" value="<?php echo e(old('start_date')); ?>" required><input type="time" name="start_time" value="<?php echo e(old('start_time')); ?>" required>
        <label>お迎えの場所<span>必須</span>:</label>
        <input type="text" name="pick_up_address" value="<?php echo e(old('pick_up_address')); ?>" required>
    </div>
    <div class="stripe">
        <label>終了予定日時<span>必須</span>:</label>
        <input type="date" name="end_date" value="<?php echo e(old('end_date')); ?>" required><input type="time" name="end_time" value="<?php echo e(old('end_time')); ?>" required>
        <label>お送り先の場所<span>必須</span>:</label>
        <input type="text" name="drop_off_address" value="<?php echo e(old('drop_off_address')); ?>" required>
    </div>
    <div class="stripe">
        <label>利用希望台数<span>必須</span>:</label>
        <input type="number" name="car_num" value="<?php echo e(old('car_num')); ?>" required>
        <label>利用人数<span>必須</span>:</label>
        <input type="number" name="people_num" value="<?php echo e(old('people_num')); ?>" required>
        <label>お荷物個数<span>必須</span>:</label>
        <input type="text" name="baggage" placeholder="例えば：スーツケース　大:　3個" value="<?php echo e(old('baggage')); ?>" required>
        <label>希望車種:</label>
        <input type="text" name="car_type" value="<?php echo e(old('car_type')); ?>" required>
        <label>備考:</label>
        <textarea name="details" cols="40" rows="6"><?php echo e(old('details')); ?></textarea>
    </div>
</div>

<input type="submit" value="内容確認" />

</form>


</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.hire', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>