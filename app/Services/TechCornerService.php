<?php

namespace App\Services;

class TechCornerService
{
    /**
     * Danh sách chủ đề lọc cho trang Góc công nghệ.
     *
     * @return array<string, string>
     */
    public function topics(): array
    {
        return [
            'all' => 'Tất cả bài viết',
            'goc-lam-viec' => 'Góc làm việc',
            'tu-van-chon-mua' => 'Tư vấn chọn mua',
            'meo-hay-moi-ngay' => 'Mẹo hay mỗi ngày',
        ];
    }

    /**
     * Toàn bộ bài viết biên tập của Góc công nghệ D&D.
     *
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        return [
            [
                'slug' => 'goc-lam-viec-toi-gian',
                'topic_key' => 'goc-lam-viec',
                'category_label' => 'GÓC LÀM VIỆC',
                'title' => 'Gọn một góc bàn. Rộng một khoảng sáng tạo.',
                'headline_html' => 'Gọn một góc bàn.<br>Rộng một khoảng sáng tạo.',
                'excerpt' => 'Cách sắp xếp lại bàn làm việc tối giản với màn hình rời, bàn phím không dây và ánh sáng dịu giúp bạn tập trung hơn mỗi ngày.',
                'image' => 'images/blog-workspace.jpg',
                'read_time' => '5 phút đọc',
                'tag_label' => 'Cảm hứng mỗi ngày',
                'published_at' => '28/11/2026',
                'author' => 'Đội ngũ D&D Studio',
                'shop_route_params' => ['category' => 'chuot-ban-phim'],
                'shop_cta_label' => 'Xem phụ kiện gọn bàn làm việc',
                'recommended_filters' => [
                    'category' => 'chuot-ban-phim',
                    'sort' => 'bestseller',
                ],
                'recommended_heading' => 'Thiết bị gọn bàn được yêu thích',
                'highlights' => [
                    'Giữ tối đa 4 món thường xuyên dùng trên mặt bàn: máy tính, bàn phím, chuột không dây và một nguồn sáng dịu.',
                    'Ưu tiên kết nối USB-C một dây vừa xuất hình vừa sạc để triệt tiêu cảnh rối dây sau màn hình.',
                    'Nâng màn hình lên ngang tầm mắt giúp giảm mỏi cổ vai gáy rõ rệt sau 2–3 giờ làm việc liên tục.',
                ],
                'sections' => [
                    [
                        'heading' => '1. Bắt đầu từ quy tắc "mặt bàn thở được"',
                        'paragraphs' => [
                            'Một góc bàn làm việc đẹp không cần thật nhiều đồ đắt tiền mà nằm ở khoảng trống bạn giữ lại. Khi mặt bàn bớt dây sạc chằng chịt, sổ tay cũ hay hộp phụ kiện không dùng tới, mắt và não cũng bớt phân tán ngay khi vừa ngồi xuống.',
                            'Hãy thử dọn sạch toàn bộ mặt bàn trong 10 phút và chỉ đặt lại đúng những thiết bị bạn chạm vào hằng ngày: chiếc laptop hoặc màn hình chính, bộ bàn phím – chuột không dây và một cốc nước hoặc đèn bàn nhỏ.',
                        ],
                        'tips' => [
                            'Dùng kẹp giữ dây dưới gầm bàn hoặc khay giấu ổ điện để không còn dây thả lơ lửng.',
                            'Chọn chuột và bàn phím Bluetooth đa thiết bị để chuyển nhanh giữa máy tính và máy tính bảng chỉ bằng một phím bấm.',
                        ],
                    ],
                    [
                        'heading' => '2. Tối ưu tư thế và ánh sáng làm việc',
                        'paragraphs' => [
                            'Làm việc lâu trước màn hình laptop khiến nhiều người có thói quen cúi gập cổ. Chỉ cần kê thêm một giá đỡ nhôm gọn nhẹ hoặc kết nối màn hình ngoài đặt ngang tầm mắt, tư thế ngồi sẽ tự động thẳng hơn và giảm áp lực lên cột sống.',
                            'Về ánh sáng, hãy đặt bàn làm việc gần cửa sổ lấy sáng tự nhiên từ bên hông thay vì chiếu thẳng sau lưng hoặc đối diện gây chói màn hình. Vào buổi tối, ánh sáng vàng ấm dịu mắt kết hợp chế độ Night Shift giúp giấc ngủ không bị ảnh hưởng.',
                        ],
                        'tips' => [
                            'Cạnh trên màn hình nên ngang hoặc thấp hơn tầm mắt khoảng 2–3 cm, khoảng cách từ mắt tới màn hình từ 50–70 cm.',
                            'Bàn phím kích thước TKL hoặc 75% giúp thu hẹp khoảng di chuột, giữ vai ở trạng thái tự nhiên hơn.',
                        ],
                    ],
                    [
                        'heading' => '3. Thiết lập góc làm việc theo đúng nhịp sống của bạn',
                        'paragraphs' => [
                            'Nếu bạn thường xuyên di chuyển giữa nhà, văn phòng và quán cà phê, một chiếc laptop mỏng nhẹ đi cùng bàn phím không dây bỏ túi và tai nghe chống ồn sẽ linh hoạt hơn bộ máy bàn cồng kềnh. Ngược lại, nếu hay dựng thiết kế hay viết code nhiều cửa sổ, đầu tư thêm màn hình rời độ phân giải cao sẽ tăng hiệu suất thấy rõ.',
                        ],
                        'tips' => [
                            'Một hub USB-C gọn nhẹ giúp cắm rút toàn bộ màn hình, chuột, phím và sạc chỉ bằng một thao tác.',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'chon-may-tinh-dau-tien',
                'topic_key' => 'tu-van-chon-mua',
                'category_label' => 'TƯ VẤN CHỌN MUA',
                'title' => 'Chọn máy tính đầu tiên: Bắt đầu từ điều bạn cần.',
                'headline_html' => 'Chọn máy tính đầu tiên:<br>Bắt đầu từ điều bạn cần.',
                'excerpt' => 'Không cần phải thuộc hết tên chip hay thông số kỹ thuật phức tạp — chỉ cần xác định đúng nhu cầu học tập, di chuyển hay đồ họa để chọn đúng máy ngay từ đầu.',
                'image' => 'images/blog-first-laptop.jpg',
                'read_time' => '7 phút đọc',
                'tag_label' => 'Hướng dẫn chọn mua',
                'published_at' => '28/11/2026',
                'author' => 'Chuyên viên tư vấn D&D.',
                'shop_route_params' => ['category' => 'laptop'],
                'shop_cta_label' => 'Khám phá toàn bộ Laptop tại D&D.',
                'recommended_filters' => [
                    'category' => 'laptop',
                    'sort' => 'bestseller',
                ],
                'recommended_heading' => 'Gợi ý Laptop khởi đầu dễ chọn nhất',
                'highlights' => [
                    'Đừng mua máy quá nặng chỉ để phòng hờ những tác vụ đồ họa nặng mà cả năm bạn không dùng tới.',
                    'Mức 16GB RAM và SSD từ 512GB là điểm cân bằng bền vững nhất cho nhu cầu học tập & làm việc 3–5 năm.',
                    'Luôn kiểm tra 3 yếu tố thực tế trước khi chốt: trọng lượng mang vác, thời lượng pin thực tế và cổng kết nối hằng ngày.',
                ],
                'sections' => [
                    [
                        'heading' => '1. Bắt đầu từ câu hỏi: Bạn sẽ mang máy đi đâu?',
                        'paragraphs' => [
                            'Rất nhiều bạn mua chiếc laptop đầu tiên dựa trên cấu hình mạnh nhất trong tầm giá, rồi chỉ sau một học kỳ lại ngại mang theo vì máy kèm cục sạc nặng gần 3 kg. Nếu bạn là sinh viên hoặc nhân viên văn phòng mang máy ra ngoài mỗi ngày, trọng lượng dưới 1.5 kg và pin trụ được 7–10 tiếng quan trọng hơn nhiều so với card đồ họa rời.',
                            'Ngược lại, nếu bạn học kỹ thuật, lập trình AI cục bộ, chỉnh sửa video 4K hay dựng 3D, hãy ưu tiên hệ thống tản nhiệt tốt, màn hình chuẩn màu và CPU/GPU hiệu năng cao.',
                        ],
                        'tips' => [
                            'Học tập, văn phòng, lập trình web/mobile phổ thông: ưu tiên máy mỏng nhẹ 13–14 inch, pin trâu, bàn phím gõ êm.',
                            'Đồ họa, dựng phim, chơi game nặng: chọn dòng hiệu năng cao 15–16 inch, độ phủ màu 100% sRGB trở lên.',
                        ],
                    ],
                    [
                        'heading' => '2. Công thức cấu hình gọn nhẹ để dùng mượt trong 4 năm',
                        'paragraphs' => [
                            'Các trình duyệt, ứng dụng văn phòng và công cụ học tập ngày nay tiêu tốn bộ nhớ RAM nhiều hơn trước. Mức 8GB RAM chỉ còn phù hợp cho nhu cầu lướt web, soạn thảo rất cơ bản; mức 16GB RAM giúp bạn mở song song hàng chục tab, chạy họp trực tuyến và ứng dụng học tập mượt mà lâu dài.',
                            'Với ổ cứng, 512GB SSD NVMe vừa đủ thoải mái lưu tài liệu, bài giảng và ảnh cá nhân mà không phải dọn dẹp liên tục.',
                        ],
                        'tips' => [
                            'Kiểm tra xem máy có hỗ trợ nâng cấp RAM/SSD về sau không — đặc biệt hữu ích nếu bạn dùng Windows.',
                            'Nếu ngại đọc bảng thông số dài, bạn có thể dùng tính năng So sánh 2–3 máy trực tiếp trên D&D. hoặc hỏi trợ lý D&D AI.',
                        ],
                    ],
                    [
                        'heading' => '3. Đừng bỏ qua màn hình, bàn phím và bảo hành',
                        'paragraphs' => [
                            'Đây là 3 phần bạn tiếp xúc trực tiếp nhiều nhất suốt thời gian sử dụng. Một tấm nền IPS hoặc OLED sáng rõ (từ 300 nits trở lên) giúp bạn làm việc ngoài trời hoặc trong giảng đường sáng đèn mà không bị chói mờ.',
                            'Ngoài ra, chính sách bảo hành minh bạch và tra cứu nhanh theo mã đơn hàng/IMEI cũng giúp bạn yên tâm trọn vẹn nếu có sự cố phát sinh.',
                        ],
                        'tips' => [
                            'Ưu tiên máy có sạc USB-C Power Delivery để dùng chung củ sạc gọn nhẹ với điện thoại khi đi công tác hay về quê.',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'pin-ben-hon-moi-ngay',
                'topic_key' => 'meo-hay-moi-ngay',
                'category_label' => 'MẸO HAY MỖI NGÀY',
                'title' => 'Pin bền hơn từ những thói quen nhỏ mỗi ngày.',
                'headline_html' => 'Pin bền hơn từ những<br>thói quen nhỏ mỗi ngày.',
                'excerpt' => 'Pin laptop hay điện thoại không chai chỉ vì thời gian mà phần lớn do nhiệt độ và cách sạc hằng ngày. Vài điều chỉnh đơn giản giúp pin khỏe hơn nhiều năm.',
                'image' => 'images/blog-battery-habits.jpg',
                'read_time' => '4 phút đọc',
                'tag_label' => 'Sử dụng thông minh',
                'published_at' => '28/11/2026',
                'author' => 'Kỹ thuật viên D&D.',
                'shop_route_params' => ['battery' => 'high'],
                'shop_cta_label' => 'Xem các thiết bị pin lớn tại D&D.',
                'recommended_filters' => [
                    'battery' => 'high',
                    'sort' => 'battery_desc',
                ],
                'recommended_heading' => 'Thiết bị nổi bật về thời lượng pin',
                'highlights' => [
                    'Nhiệt độ cao mới là "kẻ thù" làm chai pin nhanh nhất — tránh vừa sạc công suất lớn vừa chơi game nặng hoặc để máy trên nệm dày.',
                    'Không cần phải xả cạn 0% rồi mới sạc; giữ pin loanh quanh mức 20%–80% giúp kéo dài tuổi thọ chu kỳ sạc.',
                    'Bật tính năng sạc tối ưu (Optimized Battery Charging / Conservation Mode) có sẵn trên macOS, Windows và smartphone.',
                ],
                'sections' => [
                    [
                        'heading' => '1. Kẻ thù số một của pin là nhiệt độ, không phải số lần cắm sạc',
                        'paragraphs' => [
                            'Pin Lithium-Ion hiện đại được quản lý bởi vi mạch thông minh, nhưng phản ứng hóa học bên trong cell pin sẽ xuống cấp rất nhanh khi nhiệt độ vượt ngưỡng 40–45°C trong thời gian dài.',
                            'Thói quen đặt laptop chạy tác vụ nặng trực tiếp trên chăn gối làm bít khe hút gió, hoặc vừa cắm sạc nhanh điện thoại vừa chơi game liên tục, sẽ khiến máy nóng lên đột biến và giảm dung lượng pin tối đa chỉ sau vài tháng.',
                        ],
                        'tips' => [
                            'Đặt laptop trên mặt bàn phẳng hoặc đế kê thoáng đáy khi làm việc nặng.',
                            'Tháo ốp lưng quá dày nếu thấy điện thoại nóng lên bất thường khi sạc nhanh.',
                        ],
                    ],
                    [
                        'heading' => '2. Bật giới hạn sạc thông minh khi cắm điện cả ngày',
                        'paragraphs' => [
                            'Nếu bạn làm việc cố định tại bàn và cắm sạc laptop liên tục 8 tiếng mỗi ngày, hãy kích hoạt chế độ giới hạn sạc 80% (macOS tự học theo thói quen, hoặc cài đặt trong ứng dụng hãng như Lenovo Vantage, Asus MyASUS, Dell Power Manager).',
                            'Trên điện thoại, bạn không cần canh rút sạc đúng phút thứ 100 — chỉ cần dùng củ sạc chính hãng chuẩn PD/PPS và hạn chế để máy tụt sập nguồn về 0% quá thường xuyên.',
                        ],
                        'tips' => [
                            'Nếu cất máy không dùng trên 2 tuần, hãy sạc mức pin khoảng 50% rồi tắt nguồn hoàn toàn thay vì sạc đầy 100% hoặc để kiệt 0%.',
                        ],
                    ],
                    [
                        'heading' => '3. Tối ưu thời lượng sử dụng trong một lần sạc',
                        'paragraphs' => [
                            'Độ sáng màn hình tối đa, hàng chục ứng dụng chạy nền không dùng tới và tần số quét cao liên tục có thể làm sụt pin nhanh hơn 25–35%. Giảm độ sáng xuống khoảng 65–75% trong phòng kín và tắt các app khởi động cùng hệ thống sẽ cho bạn thêm 1–2 tiếng làm việc mỗi ngày.',
                        ],
                        'tips' => [
                            'Kiểm tra mục Tình trạng Pin (Battery Health) định kỳ 3 tháng/lần để theo dõi độ ổn định của thiết bị.',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lọc bài viết theo chuyên mục.
     *
     * @return array<int, array<string, mixed>>
     */
    public function byTopic(?string $topic): array
    {
        $articles = $this->all();

        if ($topic === null || $topic === '' || $topic === 'all') {
            return $articles;
        }

        return array_values(array_filter(
            $articles,
            fn (array $article): bool => $article['topic_key'] === $topic
        ));
    }

    /**
     * Tìm bài viết theo slug.
     *
     * @return array<string, mixed>|null
     */
    public function findBySlug(string $slug): ?array
    {
        foreach ($this->all() as $article) {
            if ($article['slug'] === $slug) {
                return $article;
            }
        }

        return null;
    }

    /**
     * Lấy các bài viết còn lại để gợi ý đọc tiếp.
     *
     * @return array<int, array<string, mixed>>
     */
    public function related(string $currentSlug): array
    {
        return array_values(array_filter(
            $this->all(),
            fn (array $article): bool => $article['slug'] !== $currentSlug
        ));
    }
}
