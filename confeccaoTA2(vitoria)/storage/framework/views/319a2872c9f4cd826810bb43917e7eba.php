<div
    <?php echo e($attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)); ?>

>
    <?php echo e($getChildSchema()); ?>

</div>
<?php /**PATH C:\Users\43816567843\Documents\PBE-2026\confeccaoTA2(vitoria)\vendor\filament\schemas\resources\views/components/grid.blade.php ENDPATH**/ ?>