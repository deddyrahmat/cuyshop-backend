<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';
    protected static ?int $sort = 1;
    protected function getStats(): array
    {

        /*
        Penjelasan:
        -Hitung Jumlah Order:

         * totalOrdersThisMonth untuk bulan ini.
         * totalOrdersLastMonth untuk bulan lalu.
        
        - Hitung Persentase Perubahan Order:

         * Jika totalOrdersLastMonth lebih dari 0, hitung persentase perubahan.
         * Jika totalOrdersLastMonth adalah 0, tetapi totalOrdersThisMonth lebih dari 0, anggap kenaikan 100%.
         * Jika kedua bulan tidak memiliki order, anggap perubahan persentase adalah 0.
        
        - Pesan Perubahan Order:

        Menyediakan pesan yang jelas apakah jumlah order naik atau turun.
        
        - Menampilkan Hasil:

         * Tampilkan total order untuk bulan ini dan bulan lalu.
         * Tampilkan persentase perubahan dan pesan terkait.
        */
        // ===================================================================
        // users

        // Mendapatkan total pengguna bulan ini
        $totalUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Mendapatkan total pengguna bulan lalu
        $totalUsersLastMonth = User::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        // Menghitung perubahan persentase
        if ($totalUsersLastMonth > 0) {
            $percentageChangeUsers = (($totalUsersThisMonth - $totalUsersLastMonth) / $totalUsersLastMonth) * 100;
        } else {
            // Jika pengguna bulan lalu 0, tetapi bulan ini ada pengguna, anggap peningkatan 100%
            $percentageChangeUsers = $totalUsersThisMonth > 0 ? 100 : 0;
        }

        // Pesan perubahan pengguna
        $userChangeMessage = $percentageChangeUsers >= 0
            ? 'up ' . number_format($percentageChangeUsers, 2) . '% .'
            : 'down ' . number_format(abs($percentageChangeUsers), 2) . '% .';


        // ==================================================================
        // revnue

        // Mendapatkan total revenue bulan ini
        $totalRevenueThisMonth = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        // Mendapatkan total revenue bulan lalu
        $totalRevenueLastMonth = Order::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('total_price');

        // Menghitung perubahan persentase
        if ($totalRevenueLastMonth > 0) {
            $percentageChange = (($totalRevenueThisMonth - $totalRevenueLastMonth) / $totalRevenueLastMonth) * 100;
        } else {
            // Jika revenue bulan lalu 0, tetapi bulan ini ada revenue, anggap peningkatan 100%
            $percentageChange = $totalRevenueThisMonth > 0 ? 100 : 0;
        }

        // Pesan perubahan revenue
        $revenueChangeMessage = $percentageChange >= 0
            ? 'up ' . number_format($percentageChange, 2) . '%.'
            : 'down ' . number_format(abs($percentageChange), 2) . '% .';

        // =====================================================================

        // Menghitung total order bulan ini
        $totalOrdersThisMonth = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Menghitung total order bulan lalu
        $totalOrdersLastMonth = Order::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        // Menghitung perubahan persentase order
        if ($totalOrdersLastMonth > 0) {
            $percentageChangeOrders = (($totalOrdersThisMonth - $totalOrdersLastMonth) / $totalOrdersLastMonth) * 100;
        } else {
            $percentageChangeOrders = $totalOrdersThisMonth > 0 ? 100 : 0;
        }

        // Pesan perubahan order
        $orderChangeMessage = $percentageChangeOrders >= 0
            ? 'up ' . number_format($percentageChangeOrders, 2) . '% .'
            : 'down ' . number_format(abs($percentageChangeOrders), 2) . '% .';

        Carbon::setLocale('id');
        return [
            Stat::make('Total Customers   ' . Carbon::now()->translatedFormat('F'), fn() => User::where('role', 'USER')->count())
                ->description($userChangeMessage)
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionIcon('heroicon-o-users', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
            Stat::make('Revenue ' . Carbon::now()->translatedFormat('F'), fn() => Order::where('status', 'success') // Memfilter hanya order dengan status "success"
                ->whereMonth('created_at', Carbon::now()->month) // Memfilter berdasarkan bulan saat ini
                ->whereYear('created_at', Carbon::now()->year)   // Memfilter berdasarkan tahun saat ini
                ->sum('total_price'))
                ->description($revenueChangeMessage)
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionIcon('heroicon-o-currency-dollar', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
            Stat::make('New Orders  ' . Carbon::now()->translatedFormat('F'), fn() => Order::whereMonth('created_at', Carbon::now()->month)  // Memfilter bulan sekarang
                ->whereYear('created_at', Carbon::now()->year)  // Memfilter tahun sekarang
                ->count())
                ->description($orderChangeMessage)
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionIcon('heroicon-o-shopping-bag', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
        ];
    }
}
