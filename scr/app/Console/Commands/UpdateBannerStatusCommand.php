<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Banner;

class UpdateBannerStatusCommand extends Command
{
    protected $signature = 'banners:update-status';
    protected $description = 'Cập nhật trạng thái banner dựa trên ngày hiện tại';

    public function handle()
    {
        $banners = Banner::all();
        
        foreach ($banners as $banner) {
            $banner->updateStatus();
        }

        $this->info('Cập nhật trạng thái banner thành công.');
    }
}