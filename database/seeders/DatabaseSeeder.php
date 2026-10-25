<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Comic;
use App\Models\Chapter;
use App\Models\ChapterImage;
use App\Models\CoinPackage;
use App\Models\Product;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users
        $admin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@mangastream.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'coin_balance' => 99999,
            'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Admin',
        ]);

        $uploader = User::create([
            'name' => 'Translator Team Lead',
            'email' => 'uploader@mangastream.com',
            'password' => Hash::make('password123'),
            'role' => 'uploader',
            'coin_balance' => 1500,
            'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Translator',
        ]);

        $vipUser = User::create([
            'name' => 'Otaku VIP Member',
            'email' => 'vip@mangastream.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'coin_balance' => 5000,
            'vip_expires_at' => now()->addDays(30),
            'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=VipUser',
        ]);

        $normalUser = User::create([
            'name' => 'Nguyen Van A',
            'email' => 'user@mangastream.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'coin_balance' => 100,
            'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=NormalUser',
        ]);

        // 2. Seed Categories
        $categoriesData = [
            ['name' => 'Action', 'slug' => 'action', 'description' => 'Thể loại hành động kịch tính, đánh đấm mãn nhãn'],
            ['name' => 'Romance', 'slug' => 'romance', 'description' => 'Tình cảm lãng mạn tuổi học trò và cuộc sống'],
            ['name' => 'Isekai', 'slug' => 'isekai', 'description' => 'Chuyển sinh sang dị giới, khởi đầu cuộc sống mới'],
            ['name' => 'Comedy', 'slug' => 'comedy', 'description' => 'Hài hước, giải trí sảng khoái'],
            ['name' => 'Fantasy', 'slug' => 'fantasy', 'description' => 'Thế giới phép thuật, rồng, ma vương và thần thoại'],
            ['name' => 'Martial Arts', 'slug' => 'martial-arts', 'description' => 'Võ thuật, tu tiên, kiếm hiệp cổ trang'],
            ['name' => 'Sci-Fi', 'slug' => 'sci-fi', 'description' => 'Khoa học viễn tưởng, công nghệ tương lai'],
            ['name' => 'Horror', 'slug' => 'horror', 'description' => 'Kinh dị, giật gân, rùng rợn tâm linh'],
        ];

        $catModels = [];
        foreach ($categoriesData as $c) {
            $catModels[$c['slug']] = Category::create($c);
        }

        // 3. Seed Coin Packages
        CoinPackage::create([
            'name' => 'Gói Khởi Động',
            'price_vnd' => 20000,
            'coins_awarded' => 200,
            'bonus_coins' => 0,
        ]);
        CoinPackage::create([
            'name' => 'Gói Tiết Kiệm',
            'price_vnd' => 50000,
            'coins_awarded' => 550,
            'bonus_coins' => 50,
        ]);
        CoinPackage::create([
            'name' => 'Gói VIP Pro',
            'price_vnd' => 100000,
            'coins_awarded' => 1200,
            'bonus_coins' => 200,
        ]);
        CoinPackage::create([
            'name' => 'Gói Đại Gia',
            'price_vnd' => 200000,
            'coins_awarded' => 2600,
            'bonus_coins' => 600,
        ]);

        // 4. Seed Products (Manga Merch - 100% Matching Images)
        $products = [
            [
                'name' => 'Combo Boxset Solo Leveling (Tập 1 - 8 Full Phụ Kiện)',
                'slug' => 'combo-boxset-solo-leveling-tap-1-8',
                'description' => 'Bộ truyện tranh in màu tráng gương cao cấp tập 1-8 bản giới hạn. Tặng kèm postcard hologram độc quyền, bookmark kim loại và huy hiệu Sung Jin-Woo.',
                'price' => 950000,
                'stock_quantity' => 25,
                'image' => '/uploads/products/solo-leveling-boxset.jpg',
                'category' => 'manga_book',
            ],
            [
                'name' => 'Mô Hình Monkey D. Luffy Gear 5 Sun God Nika (King of Artist 23cm)',
                'slug' => 'mo-hinh-luffy-gear-5-sun-god-nika',
                'description' => 'Mô hình chính hãng Bandai Spirits dòng King of Artist. Điêu khắc chuẩn trạng thái Thần Mặt Trời Nika với hiệu ứng mây bồng bềnh và biểu cảm cười tự do.',
                'price' => 1450000,
                'stock_quantity' => 15,
                'image' => '/uploads/products/luffy-gear-5.jpg',
                'category' => 'figure',
            ],
            [
                'name' => 'Áo Thun Unisex Chainsaw Man - Quỷ Cưa Pochita Signature',
                'slug' => 'ao-thun-unisex-chainsaw-man-pochita',
                'description' => 'Chất liệu 100% Cotton 2 chiều định lượng 260gsm dày dặn, thấm hút mồ hôi. Hình in chú cún Pochita công nghệ DTG sắc nét, giặt máy thoải mái không nứt vỡ.',
                'price' => 320000,
                'stock_quantity' => 50,
                'image' => '/uploads/products/chainsaw-man-pochita-shirt.jpg',
                'category' => 'apparel',
            ],
            [
                'name' => 'Bookmark Mạ Vàng Spy x Family - Anya & Chó Cưng Bond',
                'slug' => 'bookmark-ma-vang-spy-family-anya-bond',
                'description' => 'Kẹp sách kim loại mạ vàng 18K thiết kế đục lỗ tinh xảo biểu cảm nụ cười "Waku Waku" của Anya Forger và chú chó Bond, gắn charm chuông mini phát tiếng kêu leng keng.',
                'price' => 85000,
                'stock_quantity' => 100,
                'image' => '/uploads/products/spy-family-anya-bookmark.jpg',
                'category' => 'accessory',
            ],
            [
                'name' => 'Mô Hình Nendoroid #2354 Frieren Pháp Sư Ngàn Tuổi (Good Smile)',
                'slug' => 'nendoroid-frieren-good-smile-company',
                'description' => 'Nendoroid Frieren chính hãng Good Smile Company (Nhật Bản). Kèm theo 3 khuôn mặt thay thế, gậy phép thuật, vali hành lý và hoa sen xanh Grimoire.',
                'price' => 1250000,
                'stock_quantity' => 12,
                'image' => '/uploads/products/frieren-nendoroid.jpg',
                'category' => 'figure',
            ],
            [
                'name' => 'Mô Hình S.H.Figuarts Gojo Satoru - Vô Lượng Không Xứ',
                'slug' => 'mo-hinh-shfiguarts-gojo-satoru',
                'description' => 'Figure khớp động cao cấp Tamashii Nations Bandai tái hiện tuyệt kỹ Bành Trướng Lãnh Địa Vô Lượng Không Xứ. Có đầu thay thế lúc gỡ băng bịt mắt khoe Lục Nhãn huyền ảo.',
                'price' => 1850000,
                'stock_quantity' => 8,
                'image' => '/uploads/products/gojo-satoru-figure.jpg',
                'category' => 'figure',
            ]
        ];

        foreach ($products as $p) {
            Product::create($p);
        }

        // 5. Seed Real Comics with Authentic Covers and Pages
        $comics = [
            [
                'title' => 'Solo Leveling: Tôi Thăng Cấp Một Mình',
                'slug' => 'solo-leveling-toi-thang-cap-mot-minh',
                'alternative_names' => 'Na Honjaman Rebeleop, I Level Up Alone',
                'author' => 'Chugong',
                'artist' => 'DUBU (REDICE Studio)',
                'status' => 'completed',
                'description' => '10 năm trước, sau khi "Cánh cổng" kết nối thế giới thực với thế giới quái vật mở ra, một số người bình thường nhận được sức mạnh thức tỉnh để săn lùng quái vật bên trong cánh cổng. Họ được gọi là "Thợ săn". Sung Jin-Woo - một thợ săn hạng E yếu ớt nhất loài người bất ngờ nhận được hệ thống thăng cấp vô hạn sau một biến cố đền thờ thần bí.',
                'cover_image' => '/uploads/covers/solo-leveling.jpg',
                'views_count' => 152840,
                'rating_score' => 4.95,
                'rating_count' => 840,
                'is_featured' => true,
                'categories' => ['action', 'fantasy', 'isekai', 'martial-arts'],
                'chapters' => [
                    ['num' => 1, 'title' => 'Thợ săn hạng E yếu nhất thế giới', 'vip' => false, 'price' => 0],
                    ['num' => 2, 'title' => 'Cửa ngục kép và Đền Thần chết chóc', 'vip' => false, 'price' => 0],
                    ['num' => 3, 'title' => 'Lời phán quyết của Thần tượng đá', 'vip' => false, 'price' => 0],
                    ['num' => 4, 'title' => 'Thức tỉnh bảng nhiệm vụ hàng ngày', 'vip' => true, 'price' => 15],
                    ['num' => 5, 'title' => 'Hầm ngục ga tàu điện ngầm', 'vip' => true, 'price' => 20],
                ]
            ],
            [
                'title' => 'One Piece: Đảo Hải Tặc',
                'slug' => 'one-piece-dao-hai-tac',
                'alternative_names' => 'Vua Hải Tặc, Mũ Rơm',
                'author' => 'Eiichiro Oda',
                'artist' => 'Eiichiro Oda',
                'status' => 'ongoing',
                'description' => 'Câu chuyện kể về Monkey D. Luffy, một thiếu niên mang ước mơ cháy bỏng trở thành Vua Hải Tặc, kế thừa kho báu vĩ đại nhất thế giới One Piece được để lại bởi Gol D. Roger. Cùng với những người đồng đội trung thành trong băng Mũ Rơm, Luffy căng buồm tiến vào Đại Hải Trình.',
                'cover_image' => '/uploads/covers/one-piece.jpg',
                'views_count' => 324100,
                'rating_score' => 4.98,
                'rating_count' => 1250,
                'is_featured' => true,
                'categories' => ['action', 'comedy', 'fantasy'],
                'chapters' => [
                    ['num' => 1, 'title' => 'Bình minh của chuyến phiêu lưu', 'vip' => false, 'price' => 0],
                    ['num' => 2, 'title' => 'Thợ săn hải tặc Roronoa Zoro', 'vip' => false, 'price' => 0],
                    ['num' => 3, 'title' => 'Đối đầu Hải quân đại tá Morgan Tay Rìu', 'vip' => true, 'price' => 10],
                ]
            ],
            [
                'title' => 'Chainsaw Man: Người Cưa',
                'slug' => 'chainsaw-man-nguoi-cua',
                'alternative_names' => 'Thợ Săn Quỷ Cưa Denji',
                'author' => 'Tatsuki Fujimoto',
                'artist' => 'Tatsuki Fujimoto',
                'status' => 'ongoing',
                'description' => 'Denji sống một cuộc đời khốn cùng dưới đáy xã hội để gánh món nợ khổng lồ từ người cha đã khuất. Cậu sinh tồn bằng nghề thợ săn quỷ nghiệp dư cùng chú chó quỷ cưa Pochita. Sau khi bị bọn Yakuza mưu hại, Pochita đã hòa làm một với trái tim của Denji để hồi sinh cậu thành Người Cưa.',
                'cover_image' => '/uploads/covers/chainsaw-man.jpg',
                'views_count' => 98450,
                'rating_score' => 4.88,
                'rating_count' => 520,
                'is_featured' => true,
                'categories' => ['action', 'horror', 'comedy'],
                'chapters' => [
                    ['num' => 1, 'title' => 'Chó và Cưa máy', 'vip' => false, 'price' => 0],
                    ['num' => 2, 'title' => 'Lời mời đến Sở Trị An Tokyo', 'vip' => false, 'price' => 0],
                    ['num' => 3, 'title' => 'Cứu lấy bé mèo Meowy', 'vip' => true, 'price' => 15],
                ]
            ],
            [
                'title' => 'Chú Thuật Hồi Chiến: Jujutsu Kaisen',
                'slug' => 'jujutsu-kaisen-chu-thuat-hoi-chien',
                'alternative_names' => 'Sorcery Fight, Cuộc Chiến Chú Thuật',
                'author' => 'Gege Akutami',
                'artist' => 'Gege Akutami',
                'status' => 'ongoing',
                'description' => 'Yuji Itadori là một nam sinh trung học sở hữu thể chất phi phàm. Vào ngày nọ, để bảo vệ bạn học khỏi nguyền hồn nguy hiểm, Yuji đã nuốt ngón tay phong ấn của Ryomen Sukuna - Vua Lời Nguyền, tự đưa bản thân bước chân vào thế giới của các Chú thuật sư.',
                'cover_image' => '/uploads/covers/jujutsu-kaisen.jpg',
                'views_count' => 215400,
                'rating_score' => 4.93,
                'rating_count' => 910,
                'is_featured' => true,
                'categories' => ['action', 'horror', 'fantasy'],
                'chapters' => [
                    ['num' => 1, 'title' => 'Sukuna Ryomen và Ngón tay Nguyền hồn', 'vip' => false, 'price' => 0],
                    ['num' => 2, 'title' => 'Án tử hình bí mật', 'vip' => false, 'price' => 0],
                    ['num' => 3, 'title' => 'Thần khuyển và Búp bê Chú lực', 'vip' => true, 'price' => 15],
                ]
            ],
            [
                'title' => 'Spy x Family: Gia Đình Điệp Viên',
                'slug' => 'spy-x-family-gia-dinh-diep-vien',
                'alternative_names' => 'Gia Đình Điệp Viên Twilight',
                'author' => 'Tatsuya Endo',
                'artist' => 'Tatsuya Endo',
                'status' => 'ongoing',
                'description' => 'Để hoàn thành chiến dịch Strix bảo vệ nền hòa bình thế giới giữa hai miền Đông - Tây, chàng điệp viên siêu đẳng Twilight buộc phải xây dựng một "gia đình vỏ bọc" trong vòng 7 ngày. Nhưng anh không hề biết con gái nuôi Anya đọc được suy nghĩ, còn cô vợ Yor là đệ nhất sát thủ Hoàng Hôn.',
                'cover_image' => '/uploads/covers/spy-x-family.jpg',
                'views_count' => 189000,
                'rating_score' => 4.92,
                'rating_count' => 780,
                'is_featured' => true,
                'categories' => ['comedy', 'romance', 'action'],
                'chapters' => [
                    ['num' => 1, 'title' => 'Chiến dịch tối mật Strix', 'vip' => false, 'price' => 0],
                    ['num' => 2, 'title' => 'Tìm kiếm một người mẹ hoàn hảo', 'vip' => false, 'price' => 0],
                    ['num' => 3, 'title' => 'Buổi phỏng vấn danh giá học viện Eden', 'vip' => true, 'price' => 10],
                ]
            ],
            [
                'title' => 'Frieren: Pháp Sư Tiễn Táng',
                'slug' => 'frieren-phap-su-tien-tang',
                'alternative_names' => 'Sousou no Frieren, Beyond Journey\'s End',
                'author' => 'Kanehito Yamada',
                'artist' => 'Tsukasa Abe',
                'status' => 'ongoing',
                'description' => 'Hành trình tĩnh lặng nhưng đầy cảm xúc của Frieren - nữ pháp sư tộc elf sống hàng ngàn năm sau khi cùng tổ đội Dũng sĩ Himmel đánh bại Ma Vương. Khi nhìn những người bạn con người lần lượt qua đời vì tuổi già, Frieren bắt đầu chuyến phiêu lưu mới để thấu hiểu trái tim con người.',
                'cover_image' => '/uploads/covers/frieren.jpg',
                'views_count' => 142000,
                'rating_score' => 4.96,
                'rating_count' => 690,
                'is_featured' => false,
                'categories' => ['fantasy', 'romance'],
                'chapters' => [
                    ['num' => 1, 'title' => 'Hồi kết của chuyến hành trình 10 năm', 'vip' => false, 'price' => 0],
                    ['num' => 2, 'title' => 'Lời nói dối ấm áp của vị linh mục', 'vip' => false, 'price' => 0],
                ]
            ],
            [
                'title' => 'Thám Tử Lừng Danh Conan',
                'slug' => 'tham-tu-lung-danh-conan',
                'alternative_names' => 'Detective Conan, Case Closed',
                'author' => 'Gosho Aoyama',
                'artist' => 'Gosho Aoyama',
                'status' => 'ongoing',
                'description' => 'Kudo Shinichi - thám tử học sinh lừng danh trong lúc theo dõi vụ giao dịch mờ ám đã bị Tổ chức Áo Đen bắt quả tang và cho uống độc dược APTX 4869. Sống sót kỳ diệu nhưng bị teo nhỏ thành đứa trẻ tiểu học, cậu lấy tên Edogawa Conan để tiếp tục giải các vụ án hóc búa.',
                'cover_image' => '/uploads/covers/conan.jpg',
                'views_count' => 284000,
                'rating_score' => 4.94,
                'rating_count' => 1100,
                'is_featured' => false,
                'categories' => ['comedy', 'sci-fi', 'action'],
                'chapters' => [
                    ['num' => 1, 'title' => 'Thám tử học sinh Kudo Shinichi', 'vip' => false, 'price' => 0],
                    ['num' => 2, 'title' => 'Thám tử teo nhỏ Conan Edogawa', 'vip' => false, 'price' => 0],
                ]
            ],
            [
                'title' => 'Đại Chiến Titan: Attack on Titan',
                'slug' => 'dai-chien-titan-attack-on-titan',
                'alternative_names' => 'Shingeki no Kyojin',
                'author' => 'Hajime Isayama',
                'artist' => 'Hajime Isayama',
                'status' => 'completed',
                'description' => 'Hơn 100 năm trước, nhân loại đứng trước bờ vực diệt vọng bởi những người khổng lồ ăn thịt người gọi là Titan. Những người sống sót dựng lên ba bức tường thành kiên cố. Nhưng vào một ngày định mệnh, Titan Khổng Lồ xuất hiện phá vỡ bức tường thành ngoài cùng.',
                'cover_image' => '/uploads/covers/attack-on-titan.jpg',
                'views_count' => 310500,
                'rating_score' => 4.97,
                'rating_count' => 1400,
                'is_featured' => false,
                'categories' => ['action', 'horror', 'fantasy'],
                'chapters' => [
                    ['num' => 1, 'title' => 'Gửi bạn sau hai nghìn năm', 'vip' => false, 'price' => 0],
                    ['num' => 2, 'title' => 'Ngày hôm ấy tường thành sụp đổ', 'vip' => false, 'price' => 0],
                ]
            ],
        ];

        // Authentic local comic pages
        $authenticPagesCh1 = [
            '/uploads/chapters/ch1_page_01.jpg',
            '/uploads/chapters/ch1_page_02.jpg',
            '/uploads/chapters/ch1_page_03.jpg',
            '/uploads/chapters/ch1_page_04.jpg',
        ];

        $authenticPagesCh2 = [
            '/uploads/chapters/ch2_page_01.jpg',
            '/uploads/chapters/ch2_page_02.jpg',
            '/uploads/chapters/ch2_page_03.jpg',
            '/uploads/chapters/ch2_page_04.jpg',
        ];

        foreach ($comics as $cData) {
            $catSlugs = $cData['categories'];
            $chaptersList = $cData['chapters'];
            unset($cData['categories'], $cData['chapters']);

            $cData['uploader_id'] = $uploader->id;
            $comic = Comic::create($cData);

            // Attach categories
            $catIds = [];
            foreach ($catSlugs as $slug) {
                if (isset($catModels[$slug])) {
                    $catIds[] = $catModels[$slug]->id;
                }
            }
            $comic->categories()->sync($catIds);

            // Create chapters with authentic comic pages
            foreach ($chaptersList as $ch) {
                $chapter = Chapter::create([
                    'comic_id' => $comic->id,
                    'chapter_number' => $ch['num'],
                    'title' => $ch['title'],
                    'slug' => 'chapter-' . $ch['num'],
                    'is_vip' => $ch['vip'],
                    'price_coins' => $ch['price'],
                    'views_count' => rand(350, 4200),
                    'published_at' => now()->subDays(rand(1, 20)),
                ]);

                // Assign authentic comic reading page images
                $sourcePages = ($ch['num'] % 2 == 1) ? $authenticPagesCh1 : $authenticPagesCh2;
                
                foreach ($sourcePages as $idx => $pageUrl) {
                    ChapterImage::create([
                        'chapter_id' => $chapter->id,
                        'image_url' => $pageUrl,
                        'page_number' => $idx + 1,
                    ]);
                }
            }

            // Create sample comments
            Comment::create([
                'user_id' => $vipUser->id,
                'comic_id' => $comic->id,
                'content' => 'Nét vẽ và chất lượng truyện sắc nét tuyệt vời! Bản dịch tiếng Việt mượt mà, đọc cuốn hút không rời mắt.',
                'likes_count' => rand(15, 60),
            ]);
            Comment::create([
                'user_id' => $normalUser->id,
                'comic_id' => $comic->id,
                'content' => 'Trải nghiệm đọc truyện mượt, không bị lag, giao diện nhìn rất hiện đại chuẩn phong cách MangaDex!',
                'likes_count' => rand(5, 25),
            ]);
        }
    }
}
