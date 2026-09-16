<?php

namespace App\Infrastructure\Elasticsearch\Seeders;

use App\Application\DTO\PostData;
use Carbon\Carbon;
use Faker\Factory;

final class PostDataFactory
{
    private static array $titles = [
        'وضعیت آب و هوای تهران در روزهای آینده',
        'افزایش الودگی هوای کلانشهرها',
        'بارش باران در استان‌های شمالی',
        'کاهش دما در سراسر کشور',
        'وزش باد شدید در مناطق شرقی',
        'پیش‌بینی بارش برف در ارتفاعات',
        'افزایش دما تا پایان هفته',
        'الودگی هوای تهران در حد هشدار',
        'بارش پراکنده در برخی استان‌ها',
        'هوای ابری در روزهای پایانی هفته',
        'گرمای زودرس در بهار امسال',
        'سرمای شدید در شمال کشور',
        'باد شدید و گردوخاک در جنوب',
        'افزایش رطوبت هوا در سواحل خلیج فارس',
        'یخبندان شبانه در مناطق کوهستانی',
    ];

    private static array $agencies = [
        'ایرنا',
        'ایسنا',
        'مهر',
        'تسنیم',
        'فارس',
        '.semna',
        ' mend',
        'ایلنا',
        'اقتصاد آنلاین',
        'برنا',
    ];

    public function make(int $count, int $days): \Generator
    {
        $faker = Factory::create('fa_IR');
        $start = Carbon::now()->subDays($days);
        $range = $days * 86400;

        for ($i = 0; $i < $count; $i++) {
            $date = $start->copy()->addSeconds(random_int(0, $range));

            yield new PostData(
                id: '',
                title: $faker->randomElement(self::$titles),
                lead: $faker->sentence(20),
                content: $faker->paragraphs(3, true),
                publishedAt: $date->toIso8601String(),
                newsAgencyName: $faker->randomElement(self::$agencies),
                url: $faker->url(),
                categories: [],
                tags: [],
                mainImages: [],
                language: 'fa',
                newsAgencyId: null,
            );
        }
    }
}
